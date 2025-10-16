<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $id = $_POST['product_id'];

    $stmt = $conn->prepare("UPDATE product_details SET status = 'active' WHERE product_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['status' => 'success', 'message' => 'Product has been disabled.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
