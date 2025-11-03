<?php
include "../../config/dbConnection.php";
header("Content-Type: application/json");

if (!isset($_GET['reg_trans_id'])) {
    echo json_encode(['success' => false, 'message' => '⚠️ No transaction ID provided']);
    exit;
}

$transaction_id = intval($_GET['reg_trans_id']);

try {
    $stmt = $conn->prepare("SELECT reg_transaction_id FROM reg_transaction WHERE reg_transaction_id = ?");
    $stmt->execute([$transaction_id]);

    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        exit;
    }

    // ✅ If found, return receipt view URL
    $url = "../../app/includes/managerModule/reprintReceiptView.php?id=" . $transaction_id;
    echo json_encode(['success' => true, 'url' => $url]);
    exit; // 🚨 Stop here to prevent HTML output
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
