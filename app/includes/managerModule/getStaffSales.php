<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
include "../../config/dbConnection.php";

// 1️⃣ Sanitize input
$cashier_id  = isset($_GET['cashier_id']) ? trim($_GET['cashier_id']) : '';
$start_date  = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$end_date    = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';
$handed_cash = isset($_GET['handed_cash']) ? trim($_GET['handed_cash']) : '0';

$response = [
    "success" => false,
    "message" => "",
    "total"   => 0
];

// 2️⃣ Validate input
if (empty($cashier_id)) {
    $response["message"] = "Cashier ID is required.";
    echo json_encode($response);
    exit;
}

if (!filter_var($cashier_id, FILTER_VALIDATE_INT)) {
    $response["message"] = "Invalid Cashier ID format.";
    echo json_encode($response);
    exit;
}

if (empty($start_date) || empty($end_date)) {
    $response["message"] = "Start and end date are required.";
    echo json_encode($response);
    exit;
}

// 3️⃣ Database query
try {
    $stmt = $conn->prepare("
        SELECT IFNULL(SUM(total_amount), 0) AS total
        FROM reg_transaction
        WHERE staff_id = :id
          AND date_added BETWEEN :start AND :end
          AND status = 'COMPLETED'
    ");
    $stmt->execute([
        ':id' => $cashier_id,
        ':start' => $start_date,
        ':end' => $end_date
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $total = $result['total'] ?? 0;

    if ($total > 0) {
        $response = [
            "success" => true,
            "total" => $total
        ];
    } else {
        $response["message"] = "No sales found for this date.";
    }

    echo json_encode($response);
} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
    echo json_encode($response);
}
