<?php
header("Content-Type: application/json; charset=UTF-8");
include "../../config/dbConnection.php";

// 1️ Sanitize input
$transaction_id = isset($_GET['reg_trans_id']) ? trim($_GET['reg_trans_id']) : '';

$response = [
    "success" => false,
    "message" => "",
    "url" => ""
];

// 2️ Validate input
if (empty($transaction_id)) {
    $response["message"] = "Transaction ID is required.";
    echo json_encode($response);
    exit;
}

if (!filter_var($transaction_id, FILTER_VALIDATE_INT)) {
    $response["message"] = "Invalid Transaction ID format.";
    echo json_encode($response);
    exit;
}

// 3️ Query database
try {
    $stmt = $conn->prepare("SELECT reg_transaction_id FROM reg_transaction WHERE reg_transaction_id = :id");
    $stmt->execute([':id' => $transaction_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $response["message"] = "Transaction not found.";
        echo json_encode($response);
        exit;
    }

    // 4️ Success response
    $response = [
        "success" => true,
        "url" => "../../app/includes/managerModule/reprintReceiptView.php?id=" . urlencode($transaction_id)
    ];
    echo json_encode($response);
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
    echo json_encode($response);
}
