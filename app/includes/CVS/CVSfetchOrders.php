<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

$response = ['preparing' => [], 'serving' => []];

// Preparing Orders
$stmt = $conn->query("
  SELECT REG_TRANSACTION_ID 
  FROM REG_TRANSACTION 
  WHERE STATUS IN ('PAID', 'PENDING') 
  ORDER BY date_added DESC
");
$response['preparing'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Now Serving
$stmt2 = $conn->query("
  SELECT REG_TRANSACTION_ID 
  FROM REG_TRANSACTION 
  WHERE STATUS = 'NOW SERVING' 
  ORDER BY date_added DESC
");
$response['serving'] = $stmt2->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($response);
