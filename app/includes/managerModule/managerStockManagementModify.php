<?php
header("Content-Type: application/json");
include "../../config/dbConnection.php";
session_start();

$staff_id = $_SESSION['staff_id'] ?? null;
if (!$staff_id) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please log in again']);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Sanitize and extract
$item_id    = isset($data['item_id']) ? (int)$data['item_id'] : null;
$item_name  = sanitizeInput($data['item_name'] ?? '');
$quantity   = filter_var($data['quantity'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$unit       = sanitizeInput($data['unit'] ?? '');
$date_made  = sanitizeInput($data['date_made'] ?? '');
$date_expiry = sanitizeInput($data['date_expiry'] ?? '');
$remarks = sanitizeInput($data['remarks'] ?? '');
// Validation
if (!$item_id || !$item_name || !$unit || !$quantity || !$date_made || !$date_expiry) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}


if (!$remarks) {
    echo json_encode(['success' => false, 'message' => 'Remarks are required']);
    exit;
}


// Item name only letters, numbers, spaces
if (!preg_match("/^[a-zA-Z0-9 ]+$/", $item_name)) {
    echo json_encode(['success' => false, 'message' => 'Item name can only contain letters, numbers, and spaces']);
    exit;
}

// Unit only letters
if (!preg_match("/^[a-zA-Z ]+$/", $unit)) {
    echo json_encode(['success' => false, 'message' => 'Unit can only contain letters and spaces']);
    exit;
}

// Quantity positive
if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Quantity must be a positive number']);
    exit;
}

// Date validation
$datePattern = "/^\d{4}-\d{2}-\d{2}$/";
if (!preg_match($datePattern, $date_made) || !preg_match($datePattern, $date_expiry)) {
    echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

// Expiry cannot be before manufacturing
if (strtotime($date_expiry) < strtotime($date_made)) {
    echo json_encode(['success' => false, 'message' => 'Expiry date cannot be earlier than manufacturing date']);
    exit;
}

try {
    // 1️ Get current quantity
    $stmt = $conn->prepare("SELECT quantity FROM inventory_item WHERE item_id = :item_id");
    $stmt->execute([':item_id' => $item_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(['success' => false, 'message' => 'Item not found']);
        exit;
    }
    $quantity_before = (float)$row['quantity'];

    // 2️ Update inventory_item
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
        ':quantity' => $quantity,
        ':unit' => $unit,
        ':date_made' => $date_made,
        ':date_expiry' => $date_expiry,
        ':item_id' => $item_id
    ]);

    // 3️ Insert log
    $quantity_adjusted = $quantity - $quantity_before;
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
        ':total_after' => $quantity,
        ':remarks' => $remarks
    ]);
    echo json_encode(['success' => true, 'message' => 'Item updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
}
