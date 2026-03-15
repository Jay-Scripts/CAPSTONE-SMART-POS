<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

$stmt = $conn->query("
SELECT REG_TRANSACTION_ID
FROM REG_TRANSACTION
WHERE STATUS='NOW SERVING'
ORDER BY date_added DESC
");

$orders = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($orders);