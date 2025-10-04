<?php
session_start();
$sanitizedBaristaScannedID = $baristaScannedID = $BVSLoginMessage = $BVSPopupAlert = "";

if (isset($_POST['BVSLogin'])) {
    $baristaScannedID = trim($_POST['IDNumber']);
    $sanitizedBaristaScannedID = htmlspecialchars($baristaScannedID);

    if (empty($sanitizedBaristaScannedID)) {
        $BVSLoginMessage = "<p class='text-red-500 text-sm'>Staff ID is required.</p>";
    } elseif (!preg_match("/^[0-9]+$/", $sanitizedBaristaScannedID)) {
        $BVSLoginMessage = "<p class='text-red-500 text-sm'>Staff ID can only contain numbers.</p>";
    } else {
        $selectQueryToVerifyBarista = "SELECT si.staff_id, si.staff_name, sr.role
                FROM staff_info si
                JOIN staff_roles sr ON si.staff_id = sr.staff_id
                WHERE si.staff_id = :staff_id AND sr.role = 'BARISTA'";
        $stmt = $conn->prepare($selectQueryToVerifyBarista);
        $stmt->execute([':staff_id' => $sanitizedBaristaScannedID]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff) {
            $_SESSION['staff_id'] = $staff['staff_id'];
            $_SESSION['staff_name'] = $staff['staff_name'];
            $_SESSION['role'] = $staff['role'];

            $logStmt = $conn->prepare("INSERT INTO staff_logs (staff_id, login) VALUES (:staff_id, NOW())");
            $logStmt->execute([':staff_id' => $staff['staff_id']]);

            // Success alert with redirect
            $BVSPopupAlert = "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
            <script>
            setTimeout(function() {
                Swal.fire({
                    title: 'Login Success!',
                    icon: 'success',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(() => {
                    window.location.href = './baristaLoginSuccess.php';
                });
            }, 200);
            </script>";
        } else {
            $BVSLoginMessage = "<p class='text-red-500 text-sm'><b>Invalid ID or not a Barista.</b></p>";
            $BVSPopupAlert = "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed!',
                    text: 'Invalid ID or not a Barista!',
                });
            </script>";
        }
    }
}
