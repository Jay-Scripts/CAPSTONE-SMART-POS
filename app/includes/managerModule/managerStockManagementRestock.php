<?php
include "../../config/dbConnection.php";
session_start();
header('Content-Type: application/json');

$staff_id = $_SESSION['staff_id'] ?? null;
if (!$staff_id) {
    echo json_encode(["success" => false, "message" => "Session expired. Please log in again."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Sanitize inputs
$item_id         = isset($data['item_id']) ? (int)$data['item_id'] : null;
$inv_category_id = isset($data['inv_category_id']) ? (int)$data['inv_category_id'] : null;
$product_id      = isset($data['product_id']) && $data['product_id'] !== 'null' ? (int)$data['product_id'] : null;
$category_id     = isset($data['category_id']) && $data['category_id'] !== 'null' ? (int)$data['category_id'] : null;
$item_name       = sanitizeInput($data['item_name'] ?? '');
$unit            = sanitizeInput($data['unit'] ?? '');
$quantity        = filter_var($data['quantity'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$date_made       = sanitizeInput($data['date_made'] ?? '');
$date_expiry     = sanitizeInput($data['date_expiry'] ?? '');

// Validation
if (!$item_id || !$item_name || !$unit || !$quantity || !$date_made || !$date_expiry || !$inv_category_id) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

// Item name only letters, numbers, spaces
if (!preg_match("/^[a-zA-Z0-9 ]+$/", $item_name)) {
    echo json_encode(["success" => false, "message" => "Item name can only contain letters, numbers, and spaces."]);
    exit;
}

// Unit only letters
if (!preg_match("/^[a-zA-Z ]+$/", $unit)) {
    echo json_encode(["success" => false, "message" => "Unit can only contain letters and spaces."]);
    exit;
}

// Quantity must be positive
if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(["success" => false, "message" => "Quantity must be a valid positive number."]);
    exit;
}

// Date validation YYYY-MM-DD
$datePattern = "/^\d{4}-\d{2}-\d{2}$/";
if (!preg_match($datePattern, $date_made) || !preg_match($datePattern, $date_expiry)) {
    echo json_encode(["success" => false, "message" => "Invalid date format. Use YYYY-MM-DD."]);
    exit;
}

// Expiry cannot be before manufacturing
if (strtotime($date_expiry) < strtotime($date_made)) {
    echo json_encode(["success" => false, "message" => "Expiry date cannot be earlier than manufacturing date."]);
    exit;
}

try {
    // Validate foreign keys if provided
    if ($product_id !== null) {
        $stmt = $conn->prepare("SELECT 1 FROM product_details WHERE product_id = ?");
        $stmt->execute([$product_id]);
        if ($stmt->rowCount() === 0) $product_id = null;
    }
    if ($category_id !== null) {
        $stmt = $conn->prepare("SELECT 1 FROM category WHERE category_id = ?");
        $stmt->execute([$category_id]);
        if ($stmt->rowCount() === 0) $category_id = null;
    }

    // Insert restock record
    $stmt = $conn->prepare("
        INSERT INTO inventory_item (
            inv_category_id, item_name, added_by, product_id, category_id,
            unit, quantity, status, date_made, date_expiry
        ) VALUES (
            :inv_category_id, :item_name, :added_by, :product_id, :category_id,
            :unit, :quantity, 'IN STOCK', :date_made, :date_expiry
        )
    ");

    $stmt->execute([
        ":inv_category_id" => $inv_category_id,
        ":item_name"       => $item_name,
        ":added_by"        => $staff_id,
        ":product_id"      => $product_id,
        ":category_id"     => $category_id,
        ":unit"            => $unit,
        ":quantity"        => $quantity,
        ":date_made"       => $date_made,
        ":date_expiry"     => $date_expiry
    ]);

    echo json_encode(['success' => true, 'message' => 'Item restocked successfully!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "DB Error: " . $e->getMessage()]);
}
