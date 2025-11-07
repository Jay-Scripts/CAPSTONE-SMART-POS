<?php
include "../../config/dbConnection.php";


$staffId = $_GET['staff_id'] ?? 0;
$stmt = $conn->prepare("SELECT role FROM staff_roles WHERE staff_id = :staff_id");
$stmt->execute([':staff_id' => $staffId]);
$roles = $stmt->fetchAll(PDO::FETCH_COLUMN);

header('Content-Type: application/json');
echo json_encode($roles);
