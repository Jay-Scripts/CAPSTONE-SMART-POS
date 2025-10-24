<?php
include '../../config/dbConnection.php';

// Initialize array with 0 for each day
$weekSales = [
    'Mon' => 0,
    'Tue' => 0,
    'Wed' => 0,
    'Thu' => 0,
    'Fri' => 0,
    'Sat' => 0,
    'Sun' => 0
];

// Get the current week's Monday and Sunday
$startOfWeek = date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime('sunday this week'));

$sql = "SELECT DATE(date_added) AS sale_date, SUM(TOTAL_AMOUNT) AS total
        FROM REG_TRANSACTION
        WHERE STATUS = 'COMPLETED'
          AND DATE(date_added) BETWEEN :start AND :end
        GROUP BY DATE(date_added)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':start', $startOfWeek);
$stmt->bindParam(':end', $endOfWeek);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fill weekly sales
foreach ($results as $row) {
    $dayName = date('D', strtotime($row['sale_date'])); // Mon, Tue, ...
    $weekSales[$dayName] = (float)$row['total'];
}

// Return JSON for JavaScript
echo json_encode($weekSales);
