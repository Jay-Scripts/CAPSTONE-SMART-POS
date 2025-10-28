<?php
include "../../config/dbConnection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $transaction_id = trim($_POST['REG_TRANSACTION_ID'] ?? '');
    $reason = trim($_POST['reason'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Escape HTML entities to prevent XSS
    $transaction_id = htmlspecialchars($transaction_id, ENT_QUOTES, 'UTF-8');
    $reason = htmlspecialchars($reason, ENT_QUOTES, 'UTF-8');
    $notes = htmlspecialchars($notes, ENT_QUOTES, 'UTF-8');

    header('Content-Type: application/json');

    // Validate required fields
    if (empty($transaction_id) || empty($reason)) {
        echo json_encode(['status' => 'error', 'message' => 'Transaction ID and Reason are required.']);
        exit;
    }

    //  Validate transaction_id: numbers only
    if (!preg_match("/^[0-9]+$/", $transaction_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Transaction ID can only contain numbers.']);
        exit;
    }

    //  Validate reason: letters, numbers, spaces only
    if (!preg_match("/^[a-zA-Z0-9 ]+$/", $reason)) {
        echo json_encode(['status' => 'error', 'message' => 'Reason can only contain letters, numbers, and spaces.']);
        exit;
    }

    //  Validate notes: optional, but restrict special characters
    if (!empty($notes) && !preg_match("/^[a-zA-Z0-9 ,.()'-]*$/", $notes)) {
        echo json_encode(['status' => 'error', 'message' => 'Notes contain invalid characters.']);
        exit;
    }

    try {
        // Check if transaction exists
        $check = $conn->prepare("SELECT REG_TRANSACTION_ID FROM reg_transaction WHERE REG_TRANSACTION_ID = :id");
        $check->execute([':id' => $transaction_id]);
        $transaction = $check->fetch(PDO::FETCH_ASSOC);

        if (!$transaction) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid transaction number.']);
            exit;
        }

        // Begin refund process
        $conn->beginTransaction();

        // Insert refund record
        $insertRefund = $conn->prepare("
            INSERT INTO refund_transactions (REG_TRANSACTION_ID, reason, notes)
            VALUES (:transaction_id, :reason, :notes)
        ");
        $insertRefund->execute([
            ':transaction_id' => $transaction_id,
            ':reason' => $reason,
            ':notes' => $notes
        ]);

        // Update main transaction
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
