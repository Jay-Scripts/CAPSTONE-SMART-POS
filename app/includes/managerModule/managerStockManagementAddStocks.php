<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");
include "../../config/dbConnection.php";
session_start();

$staff_id = $_SESSION['staff_id'] ?? null;
if (!$staff_id) {
    echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

$item_name       = sanitizeInput($_POST['item_name'] ?? '');
$inv_category_id = isset($_POST['inv_category']) ? (int)$_POST['inv_category'] : null;
$category_id     = (!empty($_POST['category_id'])) ? (int)$_POST['category_id'] : null;
$product_id      = (!empty($_POST['product_id'])) ? (int)$_POST['product_id'] : null;
$quantity        = filter_var($_POST['quantity'] ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$unit            = sanitizeInput($_POST['unit'] ?? '');
$date_made       = sanitizeInput($_POST['date_made'] ?? '');
$date_expiry     = sanitizeInput($_POST['date_expiry'] ?? '');

if (!$item_name || !$inv_category_id || !$quantity || !$unit || !$date_made || !$date_expiry) {
    echo json_encode(["status" => "error", "message" => "Missing required fields."]);
    exit;
}

try {

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

    $stmt = $conn->prepare("
    INSERT INTO inventory_item 
    (inv_category_id, item_name, quantity, added_by, product_id, category_id, unit, date_made, date_expiry)
    VALUES (:inv_category_id, :item_name, :quantity, :added_by, :product_id, :category_id, :unit, :date_made, :date_expiry)
  ");

    $stmt->execute([
        ":inv_category_id" => $inv_category_id,
        ":item_name"       => $item_name,
        ":quantity"        => $quantity,
        ":added_by"        => $staff_id,
        ":product_id"      => $product_id,
        ":category_id"     => $category_id,
        ":unit"            => $unit,
        ":date_made"       => $date_made,
        ":date_expiry"     => $date_expiry
    ]);

    echo json_encode(["status" => "success", "message" => "Inventory item added successfully!"]);
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "DB Error: " . $e->getMessage()]);
}
