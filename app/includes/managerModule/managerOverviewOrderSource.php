<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');

include "../../config/dbConnection.php";
include "periodHelper.php";

$range = getDateRange();
$start = $range['start'] . ' 00:00:00';
$end   = $range['end']   . ' 23:59:59';

$sql = "
    SELECT ORDERED_BY, COUNT(REG_TRANSACTION_ID) AS total
    FROM REG_TRANSACTION
    WHERE STATUS = 'COMPLETED'
      AND date_added BETWEEN :start AND :end
    GROUP BY ORDERED_BY
";

$stmt = $conn->prepare($sql);
$stmt->execute([':start' => $start, ':end' => $end]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = ['POS' => 0, 'KIOSK' => 0, 'REWARDS APP' => 0];
foreach ($rows as $row) {
    $result[$row['ORDERED_BY']] = (int) $row['total'];
}

echo json_encode($result);
