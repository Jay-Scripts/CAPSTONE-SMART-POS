<?php
header("Content-Type: application/json");
include "../../config/dbConnection.php";
session_start(); // to get staff_id

$data = json_decode(file_get_contents('php://input'), true);
$item_id = $data['item_id'] ?? null;
$action_type = $data['action_type'] ?? null;
$last_quantity = (float)($data['last_quantity'] ?? 0);
$staff_id = $_SESSION['staff_id'] ?? 1; // replace with actual logged-in staff id

if (!$item_id || !$action_type) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

try {
    // 1. Update inventory_item status and quantity
    $stmt = $conn->prepare("UPDATE inventory_item SET status='UNAVAILABLE', quantity=0 WHERE item_id=:item_id");
    $stmt->execute([':item_id' => $item_id]);

    // 2. Insert log
    $stmtLog = $conn->prepare("
        INSERT INTO inventory_item_logs
        (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
        VALUES
        (:item_id, :staff_id, :action_type, :last_quantity, :quantity_adjusted, :total_after, :remarks)
    ");
    $stmtLog->execute([
        ':item_id' => $item_id,
        ':staff_id' => $staff_id,
        ':action_type' => $action_type,
        ':last_quantity' => $last_quantity,
        ':quantity_adjusted' => -$last_quantity,
        ':total_after' => 0,
        ':remarks' => "$action_type removed by staff"
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
