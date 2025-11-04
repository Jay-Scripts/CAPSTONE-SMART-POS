<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
include "../../config/dbConnection.php";


$cashier_id = $_GET['cashier_id'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

if (!$cashier_id || !$start_date || !$end_date) {
    echo json_encode(["success" => false, "message" => "Missing parameters."]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT IFNULL(SUM(total_amount), 0) AS total
        FROM reg_transaction
        WHERE staff_id = ?
          AND date_added BETWEEN ? AND ?
          AND status = 'PAID'
    ");
    $stmt->execute([$cashier_id, $start_date, $end_date]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "total" => $row['total']
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
