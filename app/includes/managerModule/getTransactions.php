<?php
include "../../config/dbConnection.php";

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("
        SELECT r.REG_TRANSACTION_ID, r.date_added, r.TOTAL_AMOUNT, s.staff_name
        FROM REG_TRANSACTION r
        LEFT JOIN STAFF_INFO s ON r.STAFF_ID = s.STAFF_ID
        ORDER BY r.date_added DESC
        LIMIT 50
    ");
    $stmt->execute();
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$transactions) {
        echo json_encode([]);
        exit;
    }

    echo json_encode($transactions);
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
