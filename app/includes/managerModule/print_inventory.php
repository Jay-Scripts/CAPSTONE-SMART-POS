<?php
session_start();
include "../../config/dbConnection.php";

$inventoryData = json_decode($_POST['inventory_json'] ?? '[]', true);

// Staff info (moved above for use in logs)
$staffName = $_SESSION['staff_name'] ?? 'Unknown';
$staffID   = $_SESSION['staff_id'] ?? 'N/A';
$monthName = date('F Y'); // e.g. "November 2025"

if (!empty($inventoryData)) {
    // Prepare your statements
    $updateStmt = $conn->prepare("
    UPDATE inventory_item 
    SET quantity = :actual_count 
    WHERE item_id = :item_id
");

    $selectStmt = $conn->prepare("
    SELECT item_id, quantity FROM inventory_item WHERE item_id = :item_id
");


    $logStmt = $conn->prepare("
        INSERT INTO inventory_item_logs
        (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
        VALUES (:item_id, :staff_id, :action_type, :last_quantity, :quantity_adjusted, :total_after, :remarks)
    ");

    foreach ($inventoryData as $item) {
        // 1️ Fetch current inventory details
        $selectStmt->execute([':item_id' => $item['item_id']]);
        $row = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $itemID = $row['item_id'];
            $oldQty = (float)$row['quantity'];
            $newQty = (float)$item['actual_count'];
            $diff = $newQty - $oldQty;

            // 2️ Determine action type
            $actionType = 'INVENTORY';
            $remarks = "Inventory updated via Excel upload ($monthName)";

            // 3️ Update quantity
            $updateStmt->execute([
                ':actual_count' => $newQty,
                ':item_id' => $itemID
            ]);

            // 4️ Log to inventory_item_logs
            $logStmt->execute([
                ':item_id' => $itemID,
                ':staff_id' => $staffID,
                ':action_type' => $actionType,
                ':last_quantity' => $oldQty,
                ':quantity_adjusted' => abs($diff),
                ':total_after' => $newQty,
                ':remarks' => $remarks
            ]);
        }
    }
}

$categories = [];
foreach ($inventoryData as $item) {
    $categories[$item['sheet']][] = $item;
}

// Week range (example: could be passed via hidden input or JS)
$weekStart = $_POST['week_start'] ?? date('Y-m-d', strtotime('monday this week'));
$weekEnd   = $_POST['week_end'] ?? date('Y-m-d', strtotime('sunday this week'));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Inventory Report</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;

        }

        p {
            text-align: left;
            margin: 5px 0;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        th {
            background: #eee;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <h1>BIG BREW SMART POS</h1>
    <p>Inventory Report</p>
    <p>Month: <?= date('F Y') ?></p>
    <p>Prepared By: <?= htmlspecialchars($staffName) ?> (ID: <?= htmlspecialchars($staffID) ?>)</p>

    <!-- Inventory Tables -->
    <?php foreach ($categories as $sheetName => $items): ?>
        <h2><?= htmlspecialchars($sheetName) ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Unit</th>
                    <th>System Count</th>
                    <th>Actual Count</th>
                    <th>Variance</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['item_name']) ?></td>
                        <td><?= htmlspecialchars($item['unit']) ?></td>
                        <td><?= $item['system_count'] ?></td>
                        <td><?= $item['actual_count'] ?></td>
                        <td><?= $item['variance'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>

    <script>
        window.print();
    </script>
</body>

</html>