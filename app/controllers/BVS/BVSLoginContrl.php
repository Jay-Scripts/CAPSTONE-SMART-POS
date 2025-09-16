<?php
session_start();

include "../../app/config/dbConnection.php";

$sanitizedBaristaScannedID = $baristaScannedID = $BVSLoginMessage = "";

if (isset($_POST['BVSLogin'])) {
    $baristaScannedID = trim($_POST['IDNumber']);
    $sanitizedBaristaScannedID = htmlspecialchars($baristaScannedID);

    if (empty($sanitizedBaristaScannedID)) {
        $BVSLoginMessage = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Staff ID is required.</p>";
    } elseif (!preg_match("/^[0-9]+$/", $sanitizedBaristaScannedID)) {
        $BVSLoginMessage = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Staff ID can only contain numbers.</p>";
    } else {
        $sql = "SELECT si.staff_id, si.staff_name, sr.role
                FROM staff_info si
                JOIN staff_roles sr ON si.staff_id = sr.staff_id
                WHERE si.staff_id = :staff_id AND sr.role = 'BARISTA'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':staff_id' => $sanitizedBaristaScannedID]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff) {
            // store session
            $_SESSION['staff_id'] = $staff['staff_id'];
            $_SESSION['staff_name'] = $staff['staff_name'];
            $_SESSION['role'] = $staff['role'];

            //  logs login
            $logStmt = $conn->prepare("INSERT INTO staff_logs (staff_id, login) VALUES (:staff_id, NOW())");
            $logStmt->execute([':staff_id' => $staff['staff_id']]);

            //  redirect to same page to close modal
            header("Location: " . $_SERVER['PHP_SELF'] . "?login=success");
            exit;
        } else {
            $BVSLoginMessage = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Invalid ID or not a Barista.</p>";
        }
    }
}
