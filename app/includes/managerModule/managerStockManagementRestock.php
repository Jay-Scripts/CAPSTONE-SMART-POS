<?php
include "../../config/dbConnection.php";
session_start();

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents("php://input"), true);

    $inv_category_id = $data['inv_category_id'];
    $item_name = $data['item_name'];
    $unit = $data['unit'];
    $quantity = $data['quantity'];
    $date_made = $data['date_made'];
    $date_expiry = $data['date_expiry'];
    $added_by = $_SESSION['staff_id']; // manager/staff adding it

    // ✅ Force proper NULL binding for optional foreign keys
    $product_id = isset($data['product_id']) && $data['product_id'] !== '' && $data['product_id'] !== 'null'
        ? (int)$data['product_id']
        : null;

    $category_id = isset($data['category_id']) && $data['category_id'] !== '' && $data['category_id'] !== 'null'
        ? (int)$data['category_id']
        : null;

    $stmt = $conn->prepare("
        INSERT INTO inventory_item (
            inv_category_id, item_name, added_by, product_id, category_id,
            unit, quantity, status, date_made, date_expiry
        )
        VALUES (
            :inv_category_id, :item_name, :added_by, :product_id, :category_id,
            :unit, :quantity, 'IN STOCK', :date_made, :date_expiry
        )
    ");

    $stmt->bindParam(':inv_category_id', $inv_category_id, PDO::PARAM_INT);
    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':added_by', $added_by, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, $product_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':category_id', $category_id, $category_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindParam(':unit', $unit);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':date_made', $date_made);
    $stmt->bindParam(':date_expiry', $date_expiry);

    $stmt->execute();

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => "SQL Error: " . $e->getMessage()]);
}
