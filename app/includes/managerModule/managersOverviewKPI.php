<?php
include "../../config/dbConnection.php";
header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$response = [
    "total_sales" => 0,
    "total_transactions" => 0,
    "total_items_sold" => 0,
    "error" => null
];

try {
    $today = date('Y-m-d');

    // Total Sales
    $stmt = $conn->prepare("
        SELECT SUM(TOTAL_AMOUNT)
        FROM reg_transaction
        WHERE STATUS = 'COMPLETED'
    ");
    $stmt->execute([':today' => $today]);
    $response["total_sales"] = $stmt->fetchColumn() ?: 0;

    // Total Transactions
    $stmt = $conn->prepare("
        SELECT COUNT(*)
        FROM reg_transaction
        WHERE STATUS = 'COMPLETED'
        AND DATE(date_added) = :today
    ");
    $stmt->execute([':today' => $today]);
    $response["total_transactions"] = $stmt->fetchColumn() ?: 0;

    // Total Items Sold
    $stmt = $conn->prepare("
        SELECT SUM(ti.QUANTITY)
        FROM transaction_item ti
        INNER JOIN reg_transaction rt
        ON ti.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE rt.STATUS = 'COMPLETED'
        AND DATE(rt.date_added) = :today
    ");
    $stmt->execute([':today' => $today]);
    $response["total_items_sold"] = $stmt->fetchColumn() ?: 0;
} catch (Throwable $e) {
    $response["error"] = $e->getMessage();
}

echo json_encode($response);
