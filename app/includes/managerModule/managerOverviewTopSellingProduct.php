<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
include "../../config/dbConnection.php";
include "periodHelper.php";

$range = getDateRange();

$sql = "
    SELECT pd.product_name, SUM(ti.QUANTITY) AS total_sold
    FROM TRANSACTION_ITEM ti
    JOIN REG_TRANSACTION rt ON ti.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
    JOIN PRODUCT_DETAILS pd ON ti.PRODUCT_ID = pd.PRODUCT_ID
    WHERE rt.STATUS = 'COMPLETED'
      AND DATE(rt.date_added) BETWEEN :start AND :end
    GROUP BY ti.PRODUCT_ID, pd.product_name
    ORDER BY total_sold DESC
    LIMIT 5
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':start', $range['start']);
$stmt->bindParam(':end', $range['end']);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
