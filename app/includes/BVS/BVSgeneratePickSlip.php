<?php
include "../../config/dbConnection.php";
session_start();

// ✅ Fetch session user name
$staffName = $_SESSION['staff_name'] ?? 'Unknown User';

// ✅ Validate transaction ID
$regId = intval($_GET['id'] ?? 0);
if (!$regId) die("Invalid transaction ID");

// ✅ Fetch transaction details
$stmt = $conn->prepare("SELECT * FROM REG_TRANSACTION WHERE REG_TRANSACTION_ID = ?");
$stmt->execute([$regId]);
$trans = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$trans) die("Transaction not found");

// ✅ Fetch items
$itemStmt = $conn->prepare("
  SELECT ti.QUANTITY, pd.product_name, ps.SIZE
  FROM TRANSACTION_ITEM ti
  JOIN PRODUCT_DETAILS pd ON ti.PRODUCT_ID = pd.PRODUCT_ID
  JOIN PRODUCT_SIZES ps ON ti.SIZE_ID = ps.SIZE_ID
  WHERE ti.REG_TRANSACTION_ID = ?
");
$itemStmt->execute([$regId]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Pick Slip #<?= $regId ?></title>
    <style>
        @media print {
            @page {
                size: 58mm auto;
                /* ✅ Auto height */
                margin: 0;
            }

            body {
                margin: 10mm;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            font-size: 12px;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }

        td {
            padding: 2px 0;
        }

        .footer {
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <h2>PICK SLIP</h2>
    <div class="divider"></div>
    <p><strong>Transaction #<?= $regId ?></strong></p>
    <canvas id="qrCanvas" class="qr-code" style="margin:10px auto; display:block;"></canvas>
    <div class="divider"></div>

    <table>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['QUANTITY'] ?>x <?= htmlspecialchars($item['product_name']) ?></td>
                <td style="text-align:right"><?= htmlspecialchars($item['SIZE']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="divider"></div>
    <p><strong>Total:</strong> ₱<?= number_format($trans['TOTAL_AMOUNT'], 2) ?></p>
    <p><?= date('M d, Y h:i A', strtotime($trans['date_added'])) ?></p>

    <div class="footer">
        <div class="divider"></div>
        <p>Prepared by: <strong><?= htmlspecialchars($staffName) ?></strong></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
    <script>
        const qr = new QRious({
            element: document.getElementById('qrCanvas'),
            value: '<?= $regId ?>',
            size: 130,
            level: 'H'
        });
        window.onload = () => window.print();
    </script>
</body>

</html>