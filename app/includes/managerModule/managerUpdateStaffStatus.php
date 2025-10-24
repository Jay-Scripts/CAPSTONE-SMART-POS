<?php
header('Content-Type: application/json');
include '../../config/dbConnection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffID = $_POST['staffID'] ?? null;
    $status = $_POST['staffStatus'] ?? null;

    if (!$staffID || !$status) {
        echo json_encode(['status' => 'error', 'message' => 'Staff ID and Status are required.']);
        exit;
    }

    try {
        // Convert to uppercase to match ENUM
        $status = strtoupper($status);

        $stmt = $conn->prepare("UPDATE staff_info SET status = :status WHERE staff_id = :staff_id");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':staff_id', $staffID);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Staff status updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No record updated. Check Staff ID.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
