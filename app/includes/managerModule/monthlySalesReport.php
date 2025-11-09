<?php
include "../../config/dbConnection.php";
session_start();

// Manager info
$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';

// Get selected month (YYYY-MM)
$month = $_GET['month'] ?? date('Y-m');
$start_date = date('Y-m-01 00:00:00', strtotime($month));
$end_date   = date('Y-m-t 23:59:59', strtotime($month));

try {
    // ===== ITEMS SOLD PER CATEGORY =====
    $stmt = $conn->prepare("
        SELECT c.category_name, SUM(ti.quantity) AS total_items, SUM(ti.price * ti.quantity) AS total_amount
        FROM transaction_item ti
        JOIN product_details pd ON ti.product_id = pd.product_id
        JOIN category c ON pd.category_id = c.category_id
        JOIN reg_transaction rt ON ti.reg_transaction_id = rt.reg_transaction_id
        WHERE rt.status='COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
        GROUP BY c.category_name
        ORDER BY c.category_name ASC
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ===== TRANSACTION SUMMARY =====
    $stmt = $conn->prepare("
        SELECT 
            -- Total E-Payment Transactions
            (SELECT COUNT(*) FROM EPAYMENT_TRANSACTION et
                JOIN REG_TRANSACTION rt2 ON et.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
                WHERE rt2.status='COMPLETED' AND rt2.date_added BETWEEN :start AND :end
            ) AS total_epayment,
            -- Total Discounted Transactions
            (SELECT COUNT(*) FROM disc_transaction d
                JOIN reg_transaction rt2 ON d.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
                WHERE rt2.status='COMPLETED' AND rt2.date_added BETWEEN :start AND :end
            ) AS discounted,
            -- Total Refunds
            (SELECT COUNT(*) FROM refund_transactions r
                JOIN reg_transaction rt2 ON r.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
                WHERE rt2.status='REFUNDED' AND r.TRANSACTION_TIME BETWEEN :start AND :end
            ) AS refund,
            -- Total Waste
            (SELECT COUNT(*) FROM waste_transactions w
                JOIN reg_transaction rt2 ON w.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
                WHERE rt2.status='WASTE' AND w.TRANSACTION_TIME BETWEEN :start AND :end
            ) AS waste,
            -- Pure Regular Transactions
            (SELECT COUNT(*) FROM reg_transaction rt3
                WHERE rt3.status='COMPLETED' 
                AND rt3.date_added BETWEEN :start AND :end
                AND rt3.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM EPAYMENT_TRANSACTION)
                AND rt3.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM disc_transaction)
                AND rt3.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM refund_transactions)
                AND rt3.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM waste_transactions)
            ) AS pure_regular,
            -- Total Regular Transactions (Completed + Refunded + Waste)
            (SELECT COUNT(*) FROM reg_transaction rt4
                WHERE rt4.status IN ('COMPLETED','REFUNDED','WASTE') 
                AND rt4.date_added BETWEEN :start AND :end
            ) AS total_regular_transactions
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);

    // ===== SALES SUMMARY =====
    $stmt = $conn->prepare("
        SELECT IFNULL(SUM(vatable_sales),0) AS vatable,
               IFNULL(SUM(total_amount),0) AS total
        FROM reg_transaction
        WHERE STATUS='COMPLETED'
          AND date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $sales = $stmt->fetch(PDO::FETCH_ASSOC);
    $vatableSales = $sales['vatable'];
    $totalSales   = $sales['total'];
    $vat          = $vatableSales * 0.12;

    // ===== TOTAL E-PAYMENT AMOUNT =====
    $stmt = $conn->prepare("
        SELECT IFNULL(SUM(et.amount),0) AS total_epayment_amount
        FROM EPAYMENT_TRANSACTION et
        JOIN REG_TRANSACTION rt ON et.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE rt.status='COMPLETED'
          AND et.TRANSACTION_TIME BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $epay = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalEpayment = $epay['total_epayment_amount'];
    $cashSales = $totalSales - $totalEpayment;
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Sales Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
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

        tfoot td {
            font-weight: bold;
        }

        .right {
            text-align: right;
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
    <h2>Monthly Sales Summary</h2>
    <p><strong>Month:</strong> <?= date('F Y', strtotime($month)) ?></p>
    <p><strong>Prepared By:</strong> <?= htmlspecialchars($manager_name) ?> (ID: <?= htmlspecialchars($manager_id) ?>)</p>

    <!-- Items Sold Per Category -->
    <h2>Items Sold Per Category</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Items Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php $sumItems = 0;
            foreach ($categories as $cat): $sumItems += $cat['total_items']; ?>
                <tr>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>
                    <td><?= $cat['total_items'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td><strong>Total</strong></td>
                <td><strong><?= $sumItems ?></strong></td>
            </tr>
        </tfoot>
    </table>

    <!-- Transaction Summary -->
    <h2>Transaction Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Transactions</th>
                <th>Total</th>
            </tr>
        </thead>
        <tr>
            <td>Total Regular Transactions</td>
            <td class="right"><?= $summary['pure_regular'] ?></td>
        </tr>
        <tr>
            <td>Total E-Payment Transactions</td>
            <td class="right"><?= $summary['total_epayment'] ?></td>
        </tr>
        <tr>
            <td>Total Discounted</td>
            <td class="right"><?= $summary['discounted'] ?></td>
        </tr>
        <tr>
            <td>Total Refunds</td>
            <td class="right"><?= $summary['refund'] ?></td>
        </tr>
        <tr>
            <td>Total Waste</td>
            <td class="right"><?= $summary['waste'] ?></td>
        </tr>
        <tr>
            <td>Total Transactions</td>
            <td class="right"><?= $summary['total_regular_transactions'] ?></td>
        </tr>
    </table>

    <!-- Sales Summary -->
    <h2>Sales Summary</h2>
    <table>
        <tr>
            <th>Description</th>
            <th class="right">Amount</th>
        </tr>
        <tr>
            <td>Total E-Payment Sales</td>
            <td class="right">₱<?= number_format($totalEpayment, 2) ?></td>
        </tr>
        <tr>
            <td>Cash Sales</td>
            <td class="right">₱<?= number_format($cashSales, 2) ?></td>
        </tr>
        <tr>
            <td>Vatable Sales</td>
            <td class="right">₱<?= number_format($vatableSales, 2) ?></td>
        </tr>
        <tr>
            <td>VAT (12%)</td>
            <td class="right">₱<?= number_format($vat, 2) ?></td>
        </tr>
        <tr>
            <td>Total Sales</td>
            <td class="right">₱<?= number_format($totalSales, 2) ?></td>
        </tr>
    </table>

</body>

</html>