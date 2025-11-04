<?php
include "../../config/dbConnection.php";
session_start();

// Manager info
$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';
$start_date = $_GET['start_date'] ?? date('Y-m-d 00:00:00', strtotime('-6 days'));
$end_date   = $_GET['end_date'] ?? date('Y-m-d 23:59:59');

try {
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

    // ===== TOTAL DISCOUNT BY TYPE =====
    $stmt = $conn->prepare("
        SELECT 
            SUM(CASE WHEN ID_TYPE='PWD' THEN DISC_TOTAL_AMOUNT ELSE 0 END) AS total_pwd,
            SUM(CASE WHEN ID_TYPE='SC'  THEN DISC_TOTAL_AMOUNT ELSE 0 END) AS total_sc
        FROM disc_transaction d
        JOIN reg_transaction rt ON d.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE rt.STATUS='COMPLETED'
          AND DATE(d.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
          AND d.ID_TYPE IN ('SC','PWD')
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $discounts = $stmt->fetch(PDO::FETCH_ASSOC);

    // ===== ITEMS SOLD PER CATEGORY =====
    $stmt = $conn->prepare("
        SELECT c.category_name, SUM(ti.quantity) AS total_sold
        FROM transaction_item ti
        JOIN product_details pd ON ti.product_id = pd.product_id
        JOIN category c ON pd.category_id = c.category_id
        JOIN reg_transaction rt ON ti.reg_transaction_id = rt.reg_transaction_id
        WHERE rt.status='COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
        GROUP BY c.category_name
        ORDER BY total_sold DESC
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $itemsPerCategory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ===== TRANSACTION SUMMARY =====
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total_transactions,
               IFNULL(SUM(ti.quantity),0) AS total_items_sold
        FROM reg_transaction rt
        LEFT JOIN transaction_item ti ON rt.reg_transaction_id = ti.reg_transaction_id
        WHERE rt.status='COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $transactionSummary = $stmt->fetch(PDO::FETCH_ASSOC);
    // ===== TOTAL DISCOUNT, REFUND, WASTE TRANSACTION COUNT BY TYPE =====
    $stmt = $conn->prepare("
    SELECT 
        -- PWD & SC
        COUNT(DISTINCT CASE WHEN d.ID_TYPE='PWD' THEN d.REG_TRANSACTION_ID END) AS total_pwd,
        COUNT(DISTINCT CASE WHEN d.ID_TYPE='SC'  THEN d.REG_TRANSACTION_ID END) AS total_sc,
        -- Refunds
        COUNT(DISTINCT r.REG_TRANSACTION_ID) AS total_refund,
        -- Waste
        COUNT(DISTINCT w.REG_TRANSACTION_ID) AS total_waste
    FROM reg_transaction rt
    -- join discount table
    LEFT JOIN disc_transaction d ON d.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        AND d.ID_TYPE IN ('SC','PWD')
        AND DATE(d.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    -- join refund table
    LEFT JOIN refund_transactions r ON r.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        AND DATE(r.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    -- join waste table
    LEFT JOIN waste_transactions w ON w.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        AND DATE(w.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    WHERE rt.STATUS='COMPLETED'
      AND rt.date_added BETWEEN :start AND :end
");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $counts = $stmt->fetch(PDO::FETCH_ASSOC);

    // Update summary array
    $summary = [
        'total_transactions' => $transactionSummary['total_transactions'] ?? 0,
        'discounted_pwd'     => $counts['total_pwd'] ?? 0,
        'discounted_sc'      => $counts['total_sc'] ?? 0,
        'refund'             => $counts['total_refund'] ?? 0,
        'waste'              => $counts['total_waste'] ?? 0,
        'brew_rewards'       => 0
    ];
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Weekly Sales Summary</title>
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
    <h2>Weekly Sales Summary</h2>
    <p><strong>Week:</strong> <?= date('M d, Y', strtotime($start_date)) ?> - <?= date('M d, Y', strtotime($end_date)) ?></p>
    <p><strong>Prepared By:</strong> <?= htmlspecialchars($manager_name) ?> (ID: <?= htmlspecialchars($manager_id) ?>)</p>

    <!-- Items Sold -->
    <h2>Items Sold Per Category</h2>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Items Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sumItems = 0;
            foreach ($itemsPerCategory as $cat):
                $sumItems += $cat['total_sold'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>
                    <td><?= $cat['total_sold'] ?></td>
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
            <td>Total Transactions</td>
            <td class="right"><?= $summary['total_transactions'] ?></td>
        </tr>
        <tr>
            <td>Total PWD Transactions</td>
            <td class="right"><?= $summary['discounted_pwd'] ?></td>
        </tr>
        <tr>
            <td>Total SC Transactions</td>
            <td class="right"><?= $summary['discounted_sc'] ?></td>
        </tr>
        <tr>
            <td>Total Refund Transactions</td>
            <td class="right"><?= $summary['refund'] ?></td>
        </tr>
        <tr>
            <td>Total Waste Transactions</td>
            <td class="right"><?= $summary['waste'] ?></td>
        </tr>

        <tr>
            <td>Total Brew Rewards Claimed</td>
            <td class="right"><?= $summary['brew_rewards'] ?></td>
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
        <tr>
            <td>Total Less Discount (SC/PWD)</td>
            <td class="right">₱<?= number_format($summary['discounted_pwd'] + $summary['discounted_sc'], 2) ?></td>
        </tr>
    </table>

</body>

</html>