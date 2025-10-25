<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");
include "../../config/dbConnection.php";

session_start();

// ✅ Check session
$staff_id = $_SESSION['staff_id'] ?? null;
if (!$staff_id) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
    exit;
}

// ✅ Only allow POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// ✅ Sanitize inputs
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

$item_name = sanitizeInput($_POST["item_name"] ?? '');
$category_name = sanitizeInput($_POST["category"] ?? '');
$quantity = filter_var($_POST["quantity"] ?? '', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$unit = sanitizeInput($_POST["unit"] ?? '');
$product_id = $_POST["product_id"] ?? null;

// ✅ Convert empty product_id to NULL
$product_id = ($product_id === '' || $product_id === null) ? null : (int)$product_id;

// ✅ Input validation rules
if (empty($item_name) || empty($category_name) || empty($quantity) || empty($unit)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if (!preg_match("/^[a-zA-Z0-9\s\-\_\(\)]+$/", $item_name)) {
    echo json_encode(["status" => "error", "message" => "Item name contains invalid characters."]);
    exit;
}

if (!in_array($unit, ['pcs', 'kg', 'L', 'ml', 'g'])) {
    echo json_encode(["status" => "error", "message" => "Invalid unit type."]);
    exit;
}

if ($quantity <= 0) {
    echo json_encode(["status" => "error", "message" => "Quantity must be greater than 0."]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT inv_category_id FROM inventory_category WHERE category_name = ?");
    $stmt->execute([$category_name]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        echo json_encode(["status" => "error", "message" => "Invalid category."]);
        exit;
    }

    $inv_category_id = $category["inv_category_id"];

    $addInvItem = "INSERT INTO inventory_item 
          (inv_category_id, item_name, quantity, added_by, product_id, unit)
        VALUES 
          (:inv_category_id, :item_name, :quantity, :added_by, :product_id, :unit)";
    $stmt = $conn->prepare($addInvItem);
    $stmt->execute([
        ":inv_category_id" => $inv_category_id,
        ":item_name" => $item_name,
        ":quantity" => $quantity,
        ":added_by" => $staff_id,
        ":product_id" => $product_id,
        ":unit" => $unit
    ]);


    echo json_encode(["status" => "success", "message" => "Inventory item added successfully!"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
