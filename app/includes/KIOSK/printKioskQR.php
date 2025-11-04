<?php
include "../../config/dbConnection.php";
session_start();

$transactionId = intval($_GET['id']);

// Fetch transaction details
$stmt = $conn->prepare("SELECT * FROM KIOSK_TRANSACTION WHERE kiosk_transaction_id = ?");
$stmt->execute([$transactionId]);
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die("Transaction not found in KIOSK_TRANSACTION");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kiosk Order Receipt</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 58mm auto;
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

        .center {
            text-align: center;
        }

        .qr-code {
            margin: 15px auto;
            display: block;
        }

        .transaction-number {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="center">
        <h2>ORDER RECEIPT</h2>
        <p>Kiosk Order - Pending Payment</p>
        <div class="divider"></div>

        <div class="transaction-number">
            #<?= str_pad($transactionId, 6, '0', STR_PAD_LEFT) ?>
        </div>

        <!-- Canvas for JS QR -->
        <canvas id="qrCanvas" class="qr-code"></canvas>

        <p>Scan at POS to complete payment</p>

        <div class="divider"></div>

        <p><strong>Total Amount:</strong> ₱<?= number_format($transaction['total_amount'], 2) ?></p>
        <p><strong>Date:</strong> <?= date('M d, Y h:i A', strtotime($transaction['date_added'])) ?></p>

        <div class="divider"></div>
        <p style="font-size: 10px;">
            Please proceed to the counter<br>
            to complete your payment
        </p>
    </div>

    <!-- ✅ Qrious QR generator -->
    <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
    <script>
        // Generate QR dynamically
        const qr = new QRious({
            element: document.getElementById('qrCanvas'),
            value: '<?= $transactionId ?>',
            size: 150, // adjust size for 80mm paper
            level: 'H' // high error correction for better scanning
        });
    </script>
</body>

</html>