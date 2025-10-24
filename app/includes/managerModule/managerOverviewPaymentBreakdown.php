<?php
include "../../config/dbConnection.php";


$today = date('Y-m-d');

$sql = "
SELECT pm.TYPE, SUM(pm.AMOUNT_SENT - pm.CHANGE_AMOUNT) AS total_amount
FROM PAYMENT_METHODS pm
JOIN REG_TRANSACTION rt ON pm.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
WHERE rt.STATUS = 'COMPLETED'
  AND DATE(rt.date_added) = :today
GROUP BY pm.TYPE
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':today', $today);
$stmt->execute();
$paymentBreakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($paymentBreakdown);
