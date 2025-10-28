<?php
include "../../config/dbConnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];
    $updateQueryToSetInactiveProduct = "UPDATE product_details SET status = 'inactive' WHERE product_id = ?";
    $stmt = $conn->prepare($updateQueryToSetInactiveProduct);
    $stmt->execute([$id]);

    echo json_encode(['status' => 'success', 'message' => 'Product has been disabled.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
