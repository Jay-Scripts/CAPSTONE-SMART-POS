<?php
include "../../config/dbConnection.php";

try {
    $stmt = $conn->query("
    SELECT 
        sl.logs_id, 
        si.staff_id, 
        si.staff_name, 
        GROUP_CONCAT(sr.role ORDER BY sr.role SEPARATOR ', ') AS role, 
        sl.log_type, 
        sl.log_time
    FROM staff_logs sl
    LEFT JOIN staff_info si ON sl.staff_id = si.staff_id
    LEFT JOIN staff_roles sr ON si.staff_id = sr.staff_id
    GROUP BY sl.logs_id
    ORDER BY sl.log_time DESC
");

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($logs);
} catch (PDOException $e) {
    echo json_encode([]);
}
