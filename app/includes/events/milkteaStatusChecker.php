<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

$categoryId = 1; // Milk Tea category

// Get all products in this category
$stmt = $conn->prepare("SELECT product_id FROM product_details WHERE category_id = :categoryId");
$stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as $product) {
    $productId = $product['product_id'];

    // Sum all inventory linked to this product
    $stmt2 = $conn->prepare("SELECT SUM(quantity) AS total_stock FROM inventory_item WHERE product_id = :productId");
    $stmt2->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt2->execute();
    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
    $total_stock = floatval($row['total_stock'] ?? 0);

    // Default: set all sizes inactive
    $conn->prepare("UPDATE product_sizes SET status = 'inactive' WHERE product_id = :productId AND size IN ('medio','grande')")
        ->execute([':productId' => $productId]);

    // Logic based on stock
    if ($total_stock >= 60) {
        $conn->prepare("UPDATE product_sizes SET status = 'active' WHERE product_id = :productId AND size IN ('medio','grande')")
            ->execute([':productId' => $productId]);
        $status = 'active';
    } elseif ($total_stock > 40) {
        $conn->prepare("UPDATE product_sizes SET status = 'active' WHERE product_id = :productId AND size = 'medio'")
            ->execute([':productId' => $productId]);
        $status = 'active'; // keep it active
    } else {
        $status = 'inactive';
    }


    // Update product details status
    $conn->prepare("UPDATE product_details SET status = :status WHERE product_id = :productId")
        ->execute([':status' => $status, ':productId' => $productId]);
}

echo json_encode([
    "status" => "success",
]);
