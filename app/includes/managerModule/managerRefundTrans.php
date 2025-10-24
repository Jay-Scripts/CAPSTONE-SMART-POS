<?php
include "../../config/dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $transaction_id = $_POST['REG_TRANSACTION_ID'] ?? null;
    $reason = $_POST['reason'] ?? null;
    $notes = $_POST['notes'] ?? '';

    header('Content-Type: application/json');

    if (empty($transaction_id) || empty($reason)) {
        echo json_encode(['status' => 'error', 'message' => 'Transaction ID and Reason are required.']);
        exit;
    }

    try {
        // Check if transaction exists
        $check = $conn->prepare("SELECT REG_TRANSACTION_ID FROM reg_transaction WHERE REG_TRANSACTION_ID = :id");
        $check->execute([':id' => $transaction_id]);
        $transaction = $check->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            // No match found
            echo json_encode(['status' => 'error', 'message' => 'Invalid transaction number.']);
            exit;
        }

        // Begin the refund process
        $conn->beginTransaction();

        $insertRefund = $conn->prepare("
            INSERT INTO refund_transactions (REG_TRANSACTION_ID, reason, notes)
            VALUES (:transaction_id, :reason, :notes)
        ");
        $insertRefund->execute([
            ':transaction_id' => $transaction_id,
            ':reason' => $reason,
            ':notes' => $notes
        ]);

        $updateStatus = $conn->prepare("
            UPDATE reg_transaction
            SET STATUS = 'REFUNDED'
            WHERE REG_TRANSACTION_ID = :transaction_id
        ");
        $updateStatus->execute([':transaction_id' => $transaction_id]);

        $conn->commit();

        echo json_encode(['status' => 'success', 'message' => 'Transaction successfully marked as REFUNDED.']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error processing refund.']);
    }
}
