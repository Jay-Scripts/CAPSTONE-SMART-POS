<?php
include "../../config/dbConnection.php";
session_start();

$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';
if (!isset($_GET['cashier_id'])) die("No cashier ID provided.");
$cashier_id = $_GET['cashier_id'];

// 🗓 Optional date range
$start_date = $_GET['start_date'] ?? date('Y-m-d 00:00:00');
$end_date   = $_GET['end_date'] ?? date('Y-m-d 23:59:59');

try {
    // Cashier info
    $stmt = $conn->prepare("
        SELECT staff_name 
        FROM staff_info 
        WHERE staff_id = :id
    ");
    $stmt->execute([':id' => $cashier_id]);
    $cashier = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sold items per category (fixed)
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


    $stmt->execute([
        ':id'    => $cashier_id,
        ':start' => $start_date,
        ':end'   => $end_date
    ]);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //  Transaction summary 
    $stmt = $conn->prepare("
SELECT 
    COUNT(*) AS total_transactions,

    -- Total Discounted (count disc_transaction linked to completed reg_transaction in range)
    (SELECT COUNT(*)
     FROM disc_transaction d
     JOIN reg_transaction rt2 ON d.reg_transaction_id = rt2.reg_transaction_id
     WHERE rt2.staff_id = :id
       AND rt2.status = 'COMPLETED'
       AND DATE(d.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    ) AS discounted,

    -- Total Refunds (reg_transaction.status = 'REFUNDED', use refund_transactions.TRANSACTION_TIME)
    (SELECT COUNT(*)
     FROM reg_transaction rt2
     JOIN refund_transactions r ON r.reg_transaction_id = rt2.reg_transaction_id
     WHERE rt2.staff_id = :id
       AND rt2.status = 'REFUNDED'
       AND DATE(r.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    ) AS refund,

    -- Total Waste (reg_transaction.status = 'WASTE', use waste_transactions.TRANSACTION_TIME)
    (SELECT COUNT(*)
     FROM reg_transaction rt3
     JOIN waste_transactions w ON w.reg_transaction_id = rt3.reg_transaction_id
     WHERE rt3.staff_id = :id
       AND rt3.status = 'WASTE'
       AND DATE(w.TRANSACTION_TIME) BETWEEN DATE(:start) AND DATE(:end)
    ) AS waste,

    -- Brew rewards redeemed (customer_points_history.change_date)
    (SELECT COUNT(*)
     FROM customer_points_history cph
     JOIN customer_account ca ON cph.cust_account_id = ca.cust_account_id
     JOIN reg_transaction rt4 ON rt4.cust_account_id = ca.cust_account_id
     WHERE rt4.staff_id = :id
       AND cph.change_type = 'REDEEM'
       AND DATE(cph.change_date) BETWEEN DATE(:start) AND DATE(:end)
    ) AS brew_rewards

FROM reg_transaction rt
WHERE rt.staff_id = :id
  AND rt.status = 'COMPLETED'
  AND DATE(rt.date_added) BETWEEN DATE(:start) AND DATE(:end)
");
    $stmt->execute([
        ':id'    => $cashier_id,
        ':start' => $start_date,
        ':end'   => $end_date
    ]);
    $summary = $stmt->fetch(PDO::FETCH_ASSOC);




    //  Cash handling summary
    $stmt = $conn->prepare("
    SELECT 
        SUM(total_amount) AS total_sales,
        SUM(pm.amount_sent) AS total_cash
    FROM reg_transaction rt
    JOIN payment_methods pm ON rt.reg_transaction_id = pm.reg_transaction_id
    WHERE rt.staff_id = :id 
      AND rt.status = 'COMPLETED'
      AND rt.date_added BETWEEN :start AND :end
");

    $stmt->execute([
        ':id'    => $cashier_id,
        ':start' => $start_date,
        ':end'   => $end_date
    ]);
    $sales = $stmt->fetch(PDO::FETCH_ASSOC);

    $difference = $sales['total_cash'] - $sales['total_sales'];
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
    <p><strong>Report Range:</strong>
        <?= date('M d, Y h:i A', strtotime($start_date)) ?> -
        <?= date('M d, Y h:i A', strtotime($end_date)) ?>
    </p>

    <!-- `Items Sold -->
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
            $sumItems = $sumAmount = 0;
            foreach ($categories as $cat):
                $sumItems += $cat['total_items'];
                $sumAmount += $cat['total_amount'];
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

    <!--  Transaction Summary -->
    <h2>Transaction Summary</h2>
    <table class="summary">
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
            <td>Total Brew Rewards Claimed</td>
            <td class="right"><?= $summary['brew_rewards'] ?></td>
        </tr>
    </table>

    <!-- Cash Handling -->
    <h2>Cash Handling Summary</h2>
    <table class="summary">
        <tr>
            <td>Actual System Count (Sales)</td>
            <td class="right">₱<?= number_format($sales['total_sales'], 2) ?></td>
        </tr>
        <tr>
            <td>Total Handed Cash</td>
            <td class="right">₱<?= number_format($sales['total_cash'], 2) ?></td>
        </tr>
        <tr>
            <td>Result</td>
            <td class="right" style="
        color: <?= $difference == 0 ? 'green' : ($difference > 0 ? 'blue' : 'red') ?>;
        font-weight: bold;
    ">
                <?php
                if ($difference == 0)
                    echo "Balanced";
                elseif ($difference < 0)
                    echo "Short by ₱" . number_format(abs($difference), 2);
                else
                    echo "Over by ₱" . number_format($difference, 2);
                ?>
            </td>
        </tr>

    </table>

</body>

</html>