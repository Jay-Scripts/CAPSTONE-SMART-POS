<?php
include "../../config/dbConnection.php";


header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$productId = $data['product_id'] ?? null;
$customerId = $data['customer_id'] ?? null;

if (!$productId || !$customerId) {
    echo json_encode(["success" => false, "message" => "Missing data"]);
    exit;
}


$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(["success" => false, "message" => "No valid size found for this product"]);
    exit;
}


// Check customer points
$stmt = $conn->prepare("SELECT points FROM customer_account WHERE cust_account_id = ?");
$stmt->execute([$customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer || $customer['points'] < $pointsNeeded) {
    echo json_encode(["success" => false, "message" => "Not enough points"]);
    exit;
}

// Deduct points & record claim
$conn->beginTransaction();

$conn->prepare("UPDATE customer_account SET points = points - ? WHERE cust_account_id = ?")
    ->execute([$pointsNeeded, $customerId]);

$qrValue = uniqid("QR_");

$conn->prepare("
  INSERT INTO customer_rewards_claims (cust_account_id, product_id, qr_code_value, points_used)
  VALUES (?, ?, ?, ?)
")->execute([$customerId, $productId, $qrValue, $pointsNeeded]);

$conn->commit();

echo json_encode(["success" => true, "qrValue" => $qrValue, "message" => "Show this QR at the counter"]);
