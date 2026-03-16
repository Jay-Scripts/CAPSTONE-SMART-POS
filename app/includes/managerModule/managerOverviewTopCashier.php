<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

include "../../config/dbConnection.php";
include "periodHelper.php";

$range = getDateRange();

$sql = "
    SELECT si.staff_name, SUM(rt.TOTAL_AMOUNT) AS total_sales
    FROM REG_TRANSACTION rt
    JOIN STAFF_INFO si ON rt.STAFF_ID = si.STAFF_ID
    WHERE rt.STATUS = 'COMPLETED'
      AND DATE(rt.date_added) BETWEEN :start AND :end
    GROUP BY rt.STAFF_ID, si.staff_name
    ORDER BY total_sales DESC
    LIMIT 5
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':start', $range['start']);
$stmt->bindParam(':end', $range['end']);
$stmt->execute();

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
