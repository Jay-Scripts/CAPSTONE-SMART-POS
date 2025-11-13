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
    <title>Kiosk Order Receipt #<?= $transactionId ?></title>
    <style>
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }

            body {
                margin: 10mm;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            margin: 0 auto;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td {
            padding: 2px 0;
        }

        .footer {
            margin-top: 10px;
        }

        .qr-code {
            display: block;
            margin: 10px auto;
        }
    </style>
</head>

<body>
    <h2 class="center">ORDER RECEIPT</h2>
    <div class="divider"></div>

    <p class="center"><strong>Kiosk Order - Pending Payment</strong></p>

    <div class="divider"></div>

    <p><strong>Transaction #<?= str_pad($transactionId, 6, '0', STR_PAD_LEFT) ?></strong></p>

    <!-- QR Code -->
    <canvas id="qrCanvas" class="qr-code"></canvas>

    <!-- Barcode -->
    <svg id="barcode" class="qr-code"></svg>

    <div class="divider"></div>

    <table>
        <tr>
            <td><strong>Total Amount:</strong></td>
            <td style="text-align:right">₱<?= number_format($transaction['total_amount'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>Date:</strong></td>
            <td style="text-align:right"><?= date('M d, Y h:i A', strtotime($transaction['date_added'])) ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="footer center">
        <p style="font-size: 10px;">
            Please proceed to the counter<br>
            to complete your payment
        </p>
    </div>

    <!-- QR + Barcode Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode/dist/JsBarcode.all.min.js"></script>

    <script>
        // QR
        const qr = new QRious({
            element: document.getElementById("qrCanvas"),
            value: "<?= $transactionId ?>",
            size: 120,
            level: "H"
        });

        // Barcode
        JsBarcode("#barcode", "<?= $transactionId ?>", {
            format: "CODE128",
            width: 2,
            height: 45,
            displayValue: true,
            fontSize: 12,
            textMargin: 2
        });

        // Delay printing so QR + barcode finish drawing
        setTimeout(() => {
            window.print();
        }, 300);
    </script>

</body>

</html>