<?php
include "../../config/dbConnection.php";
header('Content-Type: application/json');

$response = ['preparing' => [], 'serving' => []];

// Preparing Orders [ left side of the CVS Preparing Colum] 
$selectQueryForPaidOrders = "
  SELECT REG_TRANSACTION_ID 
  FROM REG_TRANSACTION 
  WHERE STATUS IN ('PAID') 
  ORDER BY date_added DESC
";

$stmt = $conn->query($selectQueryForPaidOrder);
$response['preparing'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Now Serving [ Right side of the CVS Now Serving Column]
$selectQueryForNowServingOrders = " 
  SELECT REG_TRANSACTION_ID 
  FROM REG_TRANSACTION 
  WHERE STATUS = 'NOW SERVING' 
  ORDER BY date_added DESC
";
$stmt2 = $conn->query($selectQueryForNowServingOrders);
$response['serving'] = $stmt2->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($response);
