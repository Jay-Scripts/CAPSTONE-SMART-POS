<?php
include "../../config/dbConnection.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg_id'])) {
    $regId = $_POST['reg_id'];

    $stmt = $conn->prepare("UPDATE REG_TRANSACTION SET STATUS = 'NOW SERVING' WHERE REG_TRANSACTION_ID = ?");
    $success = $stmt->execute([$regId]);

    echo json_encode(['success' => $success]);
}
