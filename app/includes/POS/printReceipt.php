<?php
include "../../config/dbConnection.php";

function getLocalIPv4()
{
    $ips = [];
    if (PHP_OS_FAMILY === 'Windows') {
        // Windows
        exec('ipconfig', $output);
        foreach ($output as $line) {
            if (preg_match('/IPv4 Address[. ]*: ([0-9.]+)/', $line, $matches)) {
                $ips[] = $matches[1];
            }
        }
    } else {
        // Linux / macOS
        exec('hostname -I', $output);
        if (!empty($output[0])) {
            $ips = explode(' ', trim($output[0]));
        }
    }
    // Return first private IPv4
    foreach ($ips as $ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE) === false) {
            // It's a private IPv4
            return $ip;
        }
    }
    return '127.0.0.1'; // fallback
}

$serverIP = getLocalIPv4();


$posPath = "/SmartPOS1/public/modules/SATISFACTIONRating.php";
$posLink = "http://$serverIP$posPath";

if (!isset($_GET['id'])) {
    die("No transaction ID provided.");
}

$transaction_id = $_GET['id'];

try {
    // 🧾 Main transaction info
    $stmt = $conn->prepare("
        SELECT 
            si.staff_name AS cashier,
            rt.total_amount,
            rt.vat_amount,
            rt.date_added
        FROM reg_transaction rt
        JOIN staff_info si ON rt.staff_id = si.staff_id
        WHERE rt.reg_transaction_id = :id
    ");
    $stmt->execute([':id' => $transaction_id]);
    $receipt = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$receipt) die("Receipt not found.");

    // 🏷 Payment info
    $stmtPay = $conn->prepare("
        SELECT type, amount_sent, change_amount 
        FROM payment_methods 
        WHERE reg_transaction_id = :id 
        LIMIT 1
    ");
    $stmtPay->execute([':id' => $transaction_id]);
    $payment = $stmtPay->fetch(PDO::FETCH_ASSOC);

    $receipt['payment_type'] = $payment['type'] ?? 'CASH';
    $tendered = $payment['amount_sent'] ?? 0;
    $change = $payment['change_amount'] ?? 0;

    // 🏷 Discount info
    $stmtDisc = $conn->prepare("
        SELECT ID_TYPE, FIRST_NAME, LAST_NAME, DISC_TOTAL_AMOUNT 
        FROM disc_transaction 
        WHERE REG_TRANSACTION_ID = :id 
        LIMIT 1
    ");
    $stmtDisc->execute([':id' => $transaction_id]);
    $discount = $stmtDisc->fetch(PDO::FETCH_ASSOC);
    $discount_amount = $discount['DISC_TOTAL_AMOUNT'] ?? 0;

    // 💳 E-Payment details
    $stmtEpay = $conn->prepare("
        SELECT REFERENCES_NUM, AMOUNT 
        FROM EPAYMENT_TRANSACTION 
        WHERE REG_TRANSACTION_ID = :id 
        LIMIT 1
    ");
    $stmtEpay->execute([':id' => $transaction_id]);
    $epay = $stmtEpay->fetch(PDO::FETCH_ASSOC);
    $epay_ref = $epay['REFERENCES_NUM'] ?? null;
    $epay_amount = $epay['AMOUNT'] ?? null;

    // 🧺 Fetch items
    $stmtItems = $conn->prepare("
        SELECT 
            ti.item_id,
            pd.product_name,
            ps.size,
            ti.quantity,
            ti.price
        FROM transaction_item ti
        JOIN product_details pd ON ti.product_id = pd.product_id
        JOIN product_sizes ps ON ti.size_id = ps.size_id
        WHERE ti.reg_transaction_id = :id
    ");
    $stmtItems->execute([':id' => $transaction_id]);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

    // 🧩 Add-ons and Mods
    foreach ($items as $key => $item) {
        // fetch addons
        $stmtAdd = $conn->prepare("
            SELECT ao.add_ons_name, ao.price
            FROM item_add_ons ia
            JOIN product_add_ons ao ON ia.add_ons_id = ao.add_ons_id
            WHERE ia.item_id = :item_id
        ");
        $stmtAdd->execute([':item_id' => $item['item_id']]);
        $items[$key]['addons'] = $stmtAdd->fetchAll(PDO::FETCH_ASSOC);

        // fetch mods
        $stmtMod = $conn->prepare("
            SELECT pm.modification_name
            FROM item_modification im
            JOIN product_modifications pm ON im.modification_id = pm.modification_id
            WHERE im.item_id = :item_id
        ");
        $stmtMod->execute([':item_id' => $item['item_id']]);
        $items[$key]['modifications'] = $stmtMod->fetchAll(PDO::FETCH_ASSOC);
    }

    $stmtTotalItems = $conn->prepare("
        SELECT SUM(quantity) AS total_items
        FROM transaction_item
        WHERE reg_transaction_id = :id
    ");
    $stmtTotalItems->execute([':id' => $transaction_id]);
    $totalItemsResult = $stmtTotalItems->fetch(PDO::FETCH_ASSOC);
    $total_items = $totalItemsResult['total_items'] ?? 0;
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Receipt</title>
    <style>
        /* ======== PRINT SETUP ======== */
        @media print {
            @page {
                size: 58mm auto;
                /* thermal printer width */
                margin: 0;
                /* no default browser margin */
            }

            body {
                margin: 0;
                padding: 10mm;
                /* spacing around content */
            }
        }

        /* ======== BODY ======== */
        body {
            font-family: "Courier New", monospace;
            width: 58mm;
            /* match printer width */
            margin: 0 auto;
            padding: 10px;
            /* space from edges */
            font-size: 12px;
            /* base font */
            line-height: 1.5;
            /* space between lines */
            color: #000;
            font-weight: bold;
        }

        /* ======== HEADER ======== */
        header {
            text-align: center;
            margin-bottom: 10px;
            /* space after header */
        }

        header h1 {
            margin: 2px 0;
            font-size: 14px;
        }

        header p {
            margin: 2px 0;
            font-size: 12px;
        }

        /* ======== TABLE ======== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            /* space after table */
        }

        td {
            font-size: 13px;
            vertical-align: top;
            padding: 4px 0;
            /* spacing for rows */
        }

        /* Total / Bold text */
        .total {
            font-weight: bold;
            text-align: right;
            margin: 4px 0;
        }

        /* Add-ons and modifications */
        .addons,
        .mods {
            text-align: left;
            white-space: pre-line;
            /* line breaks */
            padding-left: 10px;
            font-size: 12px;
        }

        /* ======== HR / DIVIDERS ======== */
        hr {
            border: 1px dashed black;
            margin: 6px 0;
        }

        /* ======== FOOTER ======== */
        footer {
            text-align: center;
            margin-top: 10px;
            /* spacing from content above */
            font-size: 11px;
            line-height: 1.4;
        }

        .qr-code-container {
            display: flex;
            justify-content: center;
            /* horizontal center */
            align-items: center;
            /* vertical center if needed */
            margin: 10px 0;
        }

        /* ======== QR / Barcode (if needed) ======== */
        .qr-code {
            width: 100px;
            height: 100px;
        }

        /* Optional: extra space at the bottom to feed paper */
        .print-buffer {
            height: 20-30mm;
        }
    </style>
</head>

<body id="receiptBody" onload="window.print();">
    <header>
        <h1>BIG BREW POS</h1>
        <p>Big Brew Franchising Corporation</p>
        <p>BIG BREW STA. MESA MANILA BRANCH</p>
        <p>smartposBBstamesa@gmail.com</p>
        <p>TEL (02) 0000 0000</p>
        <p>
            Transaction #: <?= str_pad($transaction_id, 6, '0', STR_PAD_LEFT) ?><br />
            Date: <?= date('Y-m-d h:i A', strtotime($receipt['date_added'])) ?><br />
            Cashier: <?= htmlspecialchars($receipt['cashier']) ?><br />
            Payment:
            <?php
            if (!empty($epay_amount) && $tendered > 0) {
                echo 'CASH + E-PAY';
            } elseif (!empty($epay_amount)) {
                echo 'E-PAY';
            } else {
                echo 'CASH';
            }
            ?>

        </p>
        <?php if ($epay_ref): ?>
            <p>E-pay Reference No: <?= htmlspecialchars($epay_ref) ?></p>
        <?php endif; ?>
    </header>
    <hr />

    <table>
        <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="2"><?= $item['quantity'] ?>x <?= htmlspecialchars($item['product_name']) ?> (<?= htmlspecialchars($item['size']) ?>)</td>
                <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>

            <?php if (!empty($item['addons'])): ?>
                <tr>
                    <td colspan="3" style="padding-left: 10px; font-size: 12px">
                        *Add-ons:
                        <?php foreach ($item['addons'] as $addon): ?>
                            <div style="padding-left: 20px">- <?= htmlspecialchars($addon['add_ons_name']) ?> (+₱<?= number_format($addon['price'], 2) ?>)</div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($item['modifications'])): ?>
                <tr>
                    <td colspan="3" style="padding-left: 10px; font-size: 12px">
                        *Mods:
                        <?php foreach ($item['modifications'] as $mod): ?>
                            <div style="padding-left: 20px">- <?= htmlspecialchars($mod['modification_name']) ?></div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td colspan="3">
                    <hr />
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p class="total">Total Items: <?= $total_items ?></p>
    <p class="total">Vatable Sales: ₱<?= number_format($receipt['total_amount'] - $receipt['vat_amount'], 2) ?></p>
    <p class="total">VAT (12%): ₱<?= number_format($receipt['vat_amount'], 2) ?></p>
    <p class="total">Total: ₱<?= number_format($receipt['total_amount'], 2) ?></p>

    <?php if ($discount_amount > 0): ?>
        <p class="total">Less Discount: ₱<?= number_format($discount_amount, 2) ?></p>
    <?php endif; ?>

    <?php if ($epay_amount): ?>
        <p class="total">E-Payment Amount: ₱<?= number_format($epay_amount, 2) ?></p>
    <?php endif; ?>

    <p class="total">Cash Received: ₱<?= number_format($tendered, 2) ?></p>
    <p class="total">Change: ₱<?= number_format($change, 2) ?></p>

    <hr />
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <footer>
        <?php if (!empty($discount)): ?>
            <p class="total">Discount Applied (<?= htmlspecialchars($discount['ID_TYPE']) ?>): <?= htmlspecialchars($discount['FIRST_NAME'] . ' ' . $discount['LAST_NAME']) ?></p>
        <?php endif; ?>
        <p>Customer: ______________________</p>
        <p>Address: _______________________</p>
        <p>TIN: ___________________________</p>
        <p>Thank you for your purchase!</p>
        <p>We value your feedback!</p>
        <p>Rate us on Facebook: fb.com/BigBrewStaMesaManila</p>
        <p>Or scan to rate us next visit!</p>
        <p>Scan here:</p>
        <div class="qr-code-container">
            <div id="qrCode" class="qr-code"></div>
        </div>


    </footer>


</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    window.onload = function() {
        // --- Generate QR code ---
        const posPath = "/SmartPOS1/public/modules/SATISFACTIONRating.php";

        const serverIP = '<?= $serverIP ?>';
        const posLink = `http://${serverIP}/SmartPOS1/public/modules/SATISFACTIONRating.php`;
        new QRCode(document.getElementById("qrCode"), {
            text: posLink,
            width: 100,
            height: 100
        });


        // --- Print logic ---
        const epayAmount = <?= json_encode($epay_amount) ?>;
        const tendered = <?= json_encode($tendered) ?>;

        if (epayAmount && epayAmount > 0) {
            window.print();
            setTimeout(() => window.print(), 1500);
        } else {
            window.print();
        }
    };
</script>