<?php
include "../../config/dbConnection.php";
session_start();

// Get manager info
$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';

// Get month filter
$month = $_GET['month'] ?? null;
$whereMonth = '';
$params = [];

if ($month) {
    $whereMonth = " AND DATE_FORMAT(dt.TRANSACTION_TIME, '%Y-%m') = :month";
    $params[':month'] = $month;
}

try {
    $stmt = $conn->prepare("
        SELECT 
            dt.FIRST_NAME,
            dt.LAST_NAME,
            dt.ID_TYPE,
            dt.DISC_TOTAL_AMOUNT,
            rt.TOTAL_AMOUNT AS amount_paid,
            (rt.TOTAL_AMOUNT + dt.DISC_TOTAL_AMOUNT) AS total_before_disc,
            dt.TRANSACTION_TIME
        FROM DISC_TRANSACTION dt
        INNER JOIN REG_TRANSACTION rt 
            ON dt.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE 1=1 $whereMonth
        ORDER BY dt.TRANSACTION_TIME DESC
    ");
    $stmt->execute($params);
    $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

$displayMonth = $month ?? 'All';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Discount Records - <?= htmlspecialchars($displayMonth) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <h1>BIG BREW SMART POS</h1>
    <h2>Discount Records - <?= htmlspecialchars($displayMonth) ?></h2>
    <p><strong>Prepared By:</strong> <?= htmlspecialchars($manager_name) ?> (ID: <?= htmlspecialchars($manager_id) ?>)</p>

    <?php if (!empty($discounts)): ?>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Discount Type</th>
                    <th>Discount Amount</th>
                    <th>Amount Paid</th>
                    <th>Total Before Discount</th>
                    <th>Transaction Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($discounts as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['FIRST_NAME'] . ' ' . $d['LAST_NAME']) ?></td>
                        <td><?= $d['ID_TYPE'] ?></td>
                        <td><?= number_format($d['DISC_TOTAL_AMOUNT'], 2) ?></td>
                        <td><?= number_format($d['amount_paid'], 2) ?></td>
                        <td><?= number_format($d['total_before_disc'], 2) ?></td>
                        <td><?= $d['TRANSACTION_TIME'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No discount records found for <?= htmlspecialchars($displayMonth) ?>.</p>
    <?php endif; ?>
</body>

</html>