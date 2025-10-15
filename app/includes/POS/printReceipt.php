<?php
include "../../config/dbConnection.php";

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


    // Payment type (optional, if needed)
    $stmtPay = $conn->prepare("
    SELECT type 
    FROM payment_methods 
    WHERE reg_transaction_id = :id 
    LIMIT 1
");
    $stmtPay->execute([':id' => $transaction_id]);
    $payment = $stmtPay->fetch(PDO::FETCH_ASSOC);
    $receipt['payment_type'] = $payment['type'] ?? 'CASH';

    if (!$receipt) {
        die("Receipt not found.");
    }

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        header,
        footer {
            text-align: center;

        }

        body {
            font-family: monospace;
            width: 100%;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            font-size: 13px;
            vertical-align: top;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        .addons,
        .mods {
            text-align: left;
            white-space: pre-line;
        }

        hr {
            border: 1px dashed black;
        }
    </style>
</head>

<body onload="window.print(); ">

    <header>
        <h1>BIG BREW POS</h1>
        <p>BIG BREW STA. MESA MANILA BRANCH</p>
        <p>smartposBBstamesa@gmail.com</p>
        <p>TEL (02) 0000 0000</p>
        <p>Transaction #: <?= str_pad($transaction_id, 6, '0', STR_PAD_LEFT) ?><br></p>
        <p>Date: <?= date('Y-m-d h:i A', strtotime($receipt['date_added'])) ?></p>
        <p>Cashier: <?= htmlspecialchars($receipt['cashier']) ?></p>
        <p> Payment: <?= htmlspecialchars($receipt['payment_type']) ?></p>
    </header>
    <hr>

    <table>
        <?php foreach ($items as $item): ?>
            <tr>
                <td colspan="2">
                    <?= $item['quantity'] ?>x <?= htmlspecialchars($item['product_name']) ?> (<?= htmlspecialchars($item['size']) ?>)
                </td>
                <td>₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>

            <?php if (!empty($item['addons'])): ?>
                <tr>
                    <td colspan="3" style="padding-left: 10px; font-size: 12px;">
                        *Add-ons:
                        <?php foreach ($item['addons'] as $addon): ?>
                            <div style="padding-left: 20px;">- <?= htmlspecialchars($addon['add_ons_name']) ?> (+₱<?= number_format($addon['price'], 2) ?>)</div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <?php if (!empty($item['modifications'])): ?>
                <tr>
                    <td colspan="3" style="padding-left: 10px; font-size: 12px;">
                        *Mods:
                        <?php foreach ($item['modifications'] as $mod): ?>
                            <div style="padding-left: 20px;">- <?= htmlspecialchars($mod['modification_name']) ?></div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endif; ?>

            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>


    <p class="total">Total Items: <?= $total_items ?></p>
    <p class="total">Subtotal: ₱<?= number_format($receipt['total_amount'] - $receipt['vat_amount'], 2) ?></p>
    <p class="total">VAT (12%): ₱<?= number_format($receipt['vat_amount'], 2) ?></p>
    <p class="total">Total: ₱<?= number_format($receipt['total_amount'], 2) ?></p>

    <hr>
    <footer>
        <p>Thank you for your purchase! ☕</p>
    </footer>

</body>

</html>