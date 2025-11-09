<?php
include "../../config/dbConnection.php";
session_start();

$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';

if (!isset($_GET['cashier_id'])) die("No cashier ID provided.");
$cashier_id = $_GET['cashier_id'];
$handed_cash = $_GET['handed_cash'] ?? 0;

// Date range
$start_date = $_GET['start_date'] ?? date('Y-m-d 00:00:00');
$end_date   = $_GET['end_date'] ?? date('Y-m-d 23:59:59');

try {
    // Cashier info
    $stmt = $conn->prepare("SELECT staff_name FROM staff_info WHERE staff_id = :id");
    $stmt->execute([':id' => $cashier_id]);
    $cashier = $stmt->fetch(PDO::FETCH_ASSOC);

    // Items Sold per Category
    $stmt = $conn->prepare("
        SELECT 
            c.category_name, 
            SUM(ti.quantity) AS total_items,
            SUM(ti.price * ti.quantity) AS total_amount
        FROM transaction_item ti
        INNER JOIN product_details pd ON ti.product_id = pd.product_id
        INNER JOIN category c ON pd.category_id = c.category_id
        INNER JOIN reg_transaction rt ON ti.reg_transaction_id = rt.reg_transaction_id
        WHERE rt.staff_id = :id
          AND rt.status = 'COMPLETED'
          AND DATE(rt.date_added) BETWEEN DATE(:start) AND DATE(:end)
        GROUP BY c.category_name
        ORDER BY c.category_name ASC
    ");
    $stmt->execute([':id' => $cashier_id, ':start' => $start_date, ':end' => $end_date]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Transaction Summary
    $stmt = $conn->prepare("
    SELECT 
        -- Total Regular Transactions = all completed + refunded + waste
        COUNT(*) AS total_regular_transactions,

        -- Total E-Payment Transactions
        (SELECT COUNT(*)
         FROM EPAYMENT_TRANSACTION et
         JOIN REG_TRANSACTION rt2 ON et.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
         WHERE rt2.staff_id = :id
           AND rt2.status = 'COMPLETED'
           AND et.TRANSACTION_TIME BETWEEN :start AND :end
        ) AS total_epayment,

        -- Total Discounted
        (SELECT COUNT(*)
         FROM disc_transaction d
         JOIN reg_transaction rt2 ON d.reg_transaction_id = rt2.reg_transaction_id
         WHERE rt2.staff_id = :id
           AND rt2.status = 'COMPLETED'
           AND d.TRANSACTION_TIME BETWEEN :start AND :end
        ) AS discounted,

        -- Total Refunds
        (SELECT COUNT(*)
         FROM reg_transaction rt2
         JOIN refund_transactions r ON r.reg_transaction_id = rt2.reg_transaction_id
         WHERE rt2.staff_id = :id
           AND rt2.status = 'REFUNDED'
           AND r.TRANSACTION_TIME BETWEEN :start AND :end
        ) AS refund,

        -- Total Waste
        (SELECT COUNT(*)
         FROM reg_transaction rt3
         JOIN waste_transactions w ON w.reg_transaction_id = rt3.reg_transaction_id
         WHERE rt3.staff_id = :id
           AND rt3.status = 'WASTE'
           AND w.TRANSACTION_TIME BETWEEN :start AND :end
        ) AS waste,

        -- Pure Regular Transactions (COMPLETED and no FK in other tables)
        (SELECT COUNT(*)
         FROM reg_transaction rt4
         WHERE rt4.staff_id = :id
           AND rt4.status = 'COMPLETED'
           AND rt4.date_added BETWEEN :start AND :end
           AND rt4.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM EPAYMENT_TRANSACTION)
           AND rt4.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM disc_transaction)
           AND rt4.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM refund_transactions)
           AND rt4.reg_transaction_id NOT IN (SELECT reg_transaction_id FROM waste_transactions)
        ) AS pure_regular
    FROM reg_transaction rt
    WHERE rt.staff_id = :id
      AND rt.status IN ('COMPLETED','REFUNDED','WASTE')
      AND rt.date_added BETWEEN :start AND :end
");
    $stmt->execute([':id' => $cashier_id, ':start' => $start_date, ':end' => $end_date]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);


    // Cash Handling Summary
    $stmt = $conn->prepare("
        SELECT 
            SUM(rt.total_amount) AS total_sales,
            SUM(pm.amount_sent) AS total_cash,
            (SELECT SUM(et.AMOUNT)
             FROM EPAYMENT_TRANSACTION et
             JOIN REG_TRANSACTION rt2 ON et.REG_TRANSACTION_ID = rt2.REG_TRANSACTION_ID
             WHERE rt2.staff_id = :id
               AND rt2.status = 'COMPLETED'
               AND et.TRANSACTION_TIME BETWEEN :start AND :end
            ) AS total_epayment_amount
        FROM reg_transaction rt
        JOIN payment_methods pm ON rt.reg_transaction_id = pm.reg_transaction_id
        WHERE rt.staff_id = :id
          AND rt.status = 'COMPLETED'
          AND rt.date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':id' => $cashier_id, ':start' => $start_date, ':end' => $end_date]);
    $sales = $stmt->fetch(PDO::FETCH_ASSOC);

    $actual_system_count = $sales['total_sales'] - ($sales['total_epayment_amount'] ?? 0);
    $difference = $handed_cash - $actual_system_count;
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sales Report Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 70px;
        }

        h1,
        h2 {
            text-align: center;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
    <h2>Sales Report Summary</h2>

    <p><strong>Cashier Name:</strong> <?= htmlspecialchars($cashier['staff_name']) ?></p>
    <p><strong>Cashier ID:</strong> <?= $cashier_id ?></p>
    <p><strong>Witnessed By:</strong> <?= htmlspecialchars($manager_name) ?> (ID: <?= htmlspecialchars($manager_id) ?>)</p>
    <p><strong>Date of Report:</strong> <?= date('F d, Y h:i A') ?></p>
    <p><strong>Report Range:</strong> <?= date('M d, Y h:i A', strtotime($start_date)) ?> - <?= date('M d, Y h:i A', strtotime($end_date)) ?></p>

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
            foreach ($categories as $cat):
                $sumItems += $cat['total_items'];
            ?>
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
    <table class="summary">
        <thead>
            <tr>
                <th>Transactions</th>
                <th>Total</th>
            </tr>
        </thead>
        <tr>
            <td>Regular Transactions </td>
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

    <!-- Cash Handling Summary -->
    <h2>Cash Handling Summary</h2>
    <table class="summary">

        <tr>
            <td>Total E-Payment Transaction Sales</td>
            <td class="right">₱<?= number_format($sales['total_epayment_amount'] ?? 0, 2) ?></td>
        </tr>
        <tr>
            <td>Total Cash Transaction Sales</td>
            <td class="right">₱<?= number_format($actual_system_count, 2) ?></td>
        </tr>
        <tr>
            <td>Total Handed Cash</td>
            <td class="right">₱<?= number_format($handed_cash, 2) ?></td>
        </tr>
        <tr>
            <td>Total Sales</td>
            <td class="right">₱<?= number_format($sales['total_sales'], 2) ?></td>
        </tr>
        <tr>
            <td>Result</td>
            <td class="right" style="color:<?= $difference == 0 ? 'green' : ($difference > 0 ? 'blue' : 'red') ?>; font-weight:bold;">
                <?php
                if ($difference == 0) echo "Balanced";
                elseif ($difference < 0) echo "Short : ₱" . number_format(abs($difference), 2);
                else echo "Over : ₱" . number_format($difference, 2);
                ?>
            </td>
        </tr>
    </table>

</body>

</html>