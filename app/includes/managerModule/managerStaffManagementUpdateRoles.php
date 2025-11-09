<?php
include "../../config/dbConnection.php";


header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Invalid request"];

if (isset($_POST['action'])) {
    $staffId = $_POST['staffId'];
    $newRole = strtoupper($_POST['newRole']);
    $validRoles = ['BARISTA', 'CASHIER', 'MANAGER'];

    if (!in_array($newRole, $validRoles)) {
        $response['message'] = "Invalid role selected.";
        echo json_encode($response);
        exit;
    }

    if ($_POST['action'] === 'modifyRole') {
        $conn->beginTransaction();
        $conn->exec("DELETE FROM staff_roles WHERE staff_id = $staffId");
        $stmt = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:id, :role)");
        $stmt->execute([':id' => $staffId, ':role' => $newRole]);
        $conn->commit();
        $response = ["status" => "success", "message" => "Role modified successfully!"];
    }

    if ($_POST['action'] === 'addRole') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM staff_roles WHERE staff_id=:id AND role=:role");
        $stmt->execute([':id' => $staffId, ':role' => $newRole]);
        if ($stmt->fetchColumn() > 0) {
            $response = ["status" => "warning", "message" => "Staff already has this role."];
        } else {
            $stmt = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:id, :role)");
            $stmt->execute([':id' => $staffId, ':role' => $newRole]);
            $response = ["status" => "success", "message" => "Role added successfully!"];
        }
    }
}

echo json_encode($response);
