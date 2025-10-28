<?php
include "../../config/dbConnection.php";
// to inform the browser the incoming data is JSON not as text
header("Content-Type: application/json");

$regId = intval($_POST['regId'] ?? 0);
if (!$regId) {
  echo json_encode(["status" => "error", "message" => "Invalid transaction ID."]);
  exit;
}

try {
  //  Check if the transaction exists and is in 'NOW SERVING'
   $selectQueryToGrabTheScannedQR = "SELECT STATUS FROM REG_TRANSACTION WHERE REG_TRANSACTION_ID = ?";
  $checkStmt = $conn->prepare($selectQueryToGrabTheScannedQR);
  $checkStmt->execute([$regId]);
  $row = $checkStmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    echo json_encode(["status" => "error", "message" => "Transaction not found."]);
    exit;
  }

  //  Validation to only update the orders where the status = Now Serving

  if (strtoupper($row['STATUS']) !== 'NOW SERVING') {
    echo json_encode(["status" => "info", "message" => "Only transactions marked as 'NOW SERVING' can be completed."]);
    exit;
  }

  //  Update to 'COMPLETED'
  $updateStmt = $conn->prepare("UPDATE REG_TRANSACTION SET STATUS = 'COMPLETED' WHERE REG_TRANSACTION_ID = ?");
  $updateStmt->execute([$regId]);

  echo json_encode(["status" => "success", "message" => "Transaction #$regId marked as COMPLETED."]);
} catch (PDOException $e) {
  echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
