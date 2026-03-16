<?php
ini_set('display_errors', 0);
error_reporting(0);
header('Content-Type: application/json');
include '../../config/dbConnection.php';
include "periodHelper.php";

$range = getDateRange();

if ($range['mode'] === 'week') {

    $weekSales = [
        'Mon' => 0,
        'Tue' => 0,
        'Wed' => 0,
        'Thu' => 0,
        'Fri' => 0,
        'Sat' => 0,
        'Sun' => 0
    ];

    $sql = "
        SELECT DATE(date_added) AS sale_date, SUM(TOTAL_AMOUNT) AS total
        FROM REG_TRANSACTION
        WHERE STATUS = 'COMPLETED'
          AND DATE(date_added) BETWEEN :start AND :end
        GROUP BY DATE(date_added)
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':start', $range['start']);
    $stmt->bindParam(':end', $range['end']);
    $stmt->execute();

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $day = date('D', strtotime($row['sale_date']));
        $weekSales[$day] = (float) $row['total'];
    }

    echo json_encode($weekSales);
} elseif ($range['mode'] === 'month') {

    $sql = "
        SELECT DAY(date_added) AS label, SUM(TOTAL_AMOUNT) AS total
        FROM REG_TRANSACTION
        WHERE STATUS = 'COMPLETED'
          AND DATE(date_added) BETWEEN :start AND :end
        GROUP BY DAY(date_added)
        ORDER BY label ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':start', $range['start']);
    $stmt->bindParam(':end', $range['end']);
    $stmt->execute();

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}
