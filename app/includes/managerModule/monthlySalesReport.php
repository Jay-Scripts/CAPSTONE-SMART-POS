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

    // ===== DISCOUNT TRANSACTION COUNT =====
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT CASE WHEN d.ID_TYPE='PWD' THEN d.REG_TRANSACTION_ID END) AS total_pwd,
            COUNT(DISTINCT CASE WHEN d.ID_TYPE='SC'  THEN d.REG_TRANSACTION_ID END) AS total_sc
        FROM reg_transaction rt
        LEFT JOIN disc_transaction d ON d.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
          AND d.ID_TYPE IN ('SC','PWD')
          AND DATE(d.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
        WHERE rt.STATUS='COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $discountCounts = $stmt->fetch(PDO::FETCH_ASSOC);

    // ===== REFUND & WASTE COUNT =====
    $stmt = $conn->prepare("
        SELECT 
            COUNT(DISTINCT r.REG_TRANSACTION_ID) AS total_refund,
            COUNT(DISTINCT w.REG_TRANSACTION_ID) AS total_waste
        FROM reg_transaction rt
        LEFT JOIN refund_transactions r ON r.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
          AND DATE(r.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
        LEFT JOIN waste_transactions w ON w.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
          AND DATE(w.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
        WHERE rt.STATUS='COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $start_date, ':end' => $end_date]);
    $refundWaste = $stmt->fetch(PDO::FETCH_ASSOC);

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
        <tr>
            <td>Total Transactions</td>
            <td class="right"><?= $sumItems ?></td>
        </tr>
        <tr>
            <td>Total PWD Transactions</td>
            <td class="right"><?= $discountCounts['total_pwd'] ?></td>
        </tr>
        <tr>
            <td>Total SC Transactions</td>
            <td class="right"><?= $discountCounts['total_sc'] ?></td>
        </tr>
        <tr>
            <td>Total Refund Transactions</td>
            <td class="right"><?= $refundWaste['total_refund'] ?></td>
        </tr>
        <tr>
            <td>Total Waste Transactions</td>
            <td class="right"><?= $refundWaste['total_waste'] ?></td>
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
    </table>

</body>

</html>