<?php
header('Content-Type: application/json');
include "../../config/dbConnection.php";


try {
    $todayStart = date('Y-m-d 00:00:00');
    $todayEnd = date('Y-m-d 23:59:59');

    $stmt = $conn->prepare("
        SELECT IFNULL(SUM(TOTAL_AMOUNT),0) AS total_sales,
               COUNT(REG_TRANSACTION_ID) AS total_transactions
        FROM REG_TRANSACTION
        WHERE STATUS = 'COMPLETED' AND date_added BETWEEN :start AND :end
    ");
    $stmt->execute([':start' => $todayStart, ':end' => $todayEnd]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt2 = $conn->prepare("
        SELECT IFNULL(SUM(TI.QUANTITY),0) AS total_products_sold
        FROM TRANSACTION_ITEM TI
        INNER JOIN REG_TRANSACTION RT ON TI.REG_TRANSACTION_ID = RT.REG_TRANSACTION_ID
        WHERE RT.STATUS = 'COMPLETED' AND RT.date_added BETWEEN :start AND :end
    ");
    $stmt2->execute([':start' => $todayStart, ':end' => $todayEnd]);
    $products = $stmt2->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'total_sales' => floatval($result['total_sales']),
        'total_transactions' => intval($result['total_transactions']),
        'total_products_sold' => intval($products['total_products_sold'])
    ]);
    exit; // <- important
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
