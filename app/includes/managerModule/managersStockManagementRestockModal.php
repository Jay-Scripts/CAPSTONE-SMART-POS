<?php
header('Content-Type: application/json');
session_start();
include "../../config/dbConnection.php";

$data = json_decode(file_get_contents('php://input'), true);

// Required inputs
$quantity   = $data['quantity'];
$date_made  = $data['date_made'];
$date_expiry = $data['date_expiry'];
$staff_id   = $_SESSION['staff_id'];

// Fetch the last inserted inventory_item to get reference fields
$stmt = $conn->prepare("SELECT item_name, unit, inv_category_id, category_id, product_id 
                        FROM inventory_item 
                        ORDER BY item_id DESC LIMIT 1");
$stmt->execute();
$last = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$last) {
    echo json_encode(['success' => false, 'msg' => 'No inventory reference found.']);
    exit;
}

// Insert new inventory row using reference + new user input
$insert = $conn->prepare("
    INSERT INTO inventory_item
    (item_name, unit, product_id, inv_category_id, category_id, quantity, date_made, date_expiry, added_by)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$insert->execute([
    $last['item_name'],
    $last['unit'],
    $last['product_id'],        // can be NULL if not linked
    $last['inv_category_id'],   // must exist in inventory_category
    $last['category_id'],       // can be NULL
    $quantity,
    $date_made,
    $date_expiry,
    $staff_id
]);

// Log the action
$item_id = $conn->lastInsertId();
$log = $conn->prepare("
    INSERT INTO inventory_item_logs
    (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after)
    VALUES (?, ?, 'RESTOCK', 0, ?, ?)
");
$log->execute([$item_id, $staff_id, $quantity, $quantity]);

echo json_encode(['success' => true]);
