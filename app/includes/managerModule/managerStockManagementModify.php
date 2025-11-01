<?php
header("Content-Type: application/json");
include "../../config/dbConnection.php";

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['item_id'], $data['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Item ID or Quantity missing']);
    exit;
}

$item_id = (int)$data['item_id'];
$item_name = $data['item_name'] ?? null;
$quantity_new = (float)($data['quantity'] ?? 0);
$unit = $data['unit'] ?? 'pcs';
$date_made = $data['date_made'] ?? null;
$date_expiry = $data['date_expiry'] ?? null;

// Set staff_id, assuming you store logged-in staff in session
session_start();
$staff_id = $_SESSION['staff_id'] ?? 1; // fallback if not set

try {
    // 1️⃣ Get current quantity
    $stmt = $conn->prepare("SELECT quantity FROM inventory_item WHERE item_id = :item_id");
    $stmt->execute([':item_id' => $item_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
        exit;
    }
    $quantity_before = (float)$row['quantity'];

    // 2️⃣ Update inventory_item
    $stmt = $conn->prepare("
        UPDATE inventory_item SET
            item_name = :item_name,
            quantity = :quantity,
            unit = :unit,
            date_made = :date_made,
            date_expiry = :date_expiry
        WHERE item_id = :item_id
    ");
    $stmt->execute([
        ':item_name' => $item_name,
        ':quantity' => $quantity_new,
        ':unit' => $unit,
        ':date_made' => $date_made,
        ':date_expiry' => $date_expiry,
        ':item_id' => $item_id
    ]);

    // 3️⃣ Insert log
    $quantity_adjusted = $quantity_new - $quantity_before;
    $stmt = $conn->prepare("
        INSERT INTO inventory_item_logs
        (item_id, staff_id, action_type, last_quantity, quantity_adjusted, total_after, remarks)
        VALUES
        (:item_id, :staff_id, 'ADJUSTMENT', :last_quantity, :quantity_adjusted, :total_after, :remarks)
    ");
    $stmt->execute([
        ':item_id' => $item_id,
        ':staff_id' => $staff_id,
        ':last_quantity' => $quantity_before,
        ':quantity_adjusted' => $quantity_adjusted,
        ':total_after' => $quantity_new,
        ':remarks' => 'Modified via manager UI'
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
