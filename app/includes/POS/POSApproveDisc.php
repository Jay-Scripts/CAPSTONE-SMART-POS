<?php
include "../../config/dbConnection.php";


$input = json_decode(file_get_contents("php://input"), true);
$staff_id = intval($input['staff_id'] ?? 0);

$response = ['success' => false, 'message' => 'Invalid staff ID'];

if ($staff_id) {
    $stmt = $conn->prepare("
        SELECT sr.role 
        FROM staff_roles sr 
        WHERE sr.staff_id = ? 
        AND sr.role = 'MANAGER'
        LIMIT 1
    ");
    $stmt->execute([$staff_id]);
    $manager = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($manager) {
        $response['success'] = true;
        unset($response['message']);
    } else {
        $response['message'] = "Staff is not a manager.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);
