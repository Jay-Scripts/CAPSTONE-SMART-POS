<?php
include "../../config/dbConnection.php";
header("Content-Type: application/json");

//  Ensure POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

//  Sanitize & validate input
$regIdRaw = trim($_POST['regId'] ?? '');
if (!preg_match('/^\d+$/', $regIdRaw)) {
    echo json_encode(["status" => "error", "message" => "Invalid transaction ID."]);
    exit;
}

$regId = (int)$regIdRaw;

try {
    //  Check if transaction exists
    $checkStmt = $conn->prepare("SELECT STATUS FROM REG_TRANSACTION WHERE REG_TRANSACTION_ID = ?");
    $checkStmt->execute([$regId]);
    $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(["status" => "error", "message" => "Transaction not found."]);
        exit;
    }

    //  Only allow update if status = NOW SERVING
    if (strtoupper(trim($row['STATUS'])) !== 'NOW SERVING') {
        echo json_encode([
            "status" => "info",
            "message" => "Only transactions marked as 'NOW SERVING' can be completed."
        ]);
        exit;
    }

    //  Update to 'COMPLETED'
    $updateStmt = $conn->prepare("
        UPDATE REG_TRANSACTION 
        SET STATUS = 'COMPLETED' 
        WHERE REG_TRANSACTION_ID = ?
    ");
    $updateStmt->execute([$regId]);

    echo json_encode(["status" => "success", "message" => "Transaction #$regId marked as COMPLETED."]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Database error: " . htmlspecialchars($e->getMessage())
    ]);
}
