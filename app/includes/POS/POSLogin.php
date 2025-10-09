<?php
session_start();
$sanitizedcashierScannedID = $cashierScannedID = $POSModuleLoginMessage = $POSModulePopupAlert = "";

if (isset($_POST['cashierModuleLogin'])) {
    $cashierScannedID = trim($_POST['IDNumber']);
    $sanitizedCashierScannedID = htmlspecialchars($cashierScannedID);

    if (empty($sanitizedCashierScannedID)) {
        $cashierModuleLoginMessage = "<p class='text-red-500 text-sm'>Staff ID is required.</p>";
    } elseif (!preg_match("/^[0-9]+$/", $sanitizedCashierScannedID)) {
        $cashierModuleLoginMessage = "<p class='text-red-500 text-sm'>Staff ID can only contain numbers.</p>";
    } else {
        $selectQueryToVerifyCashier = "SELECT si.staff_id, si.staff_name, sr.role
                FROM staff_info si
                JOIN staff_roles sr ON si.staff_id = sr.staff_id
                WHERE si.staff_id = :staff_id AND sr.role = 'cashier'";
        $stmt = $conn->prepare($selectQueryToVerifyCashier);
        $stmt->execute([':staff_id' => $sanitizedCashierScannedID]);
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff) {
            $_SESSION['staff_id'] = $staff['staff_id'];
            $_SESSION['staff_name'] = $staff['staff_name'];
            $_SESSION['role'] = $staff['role'];

            $logStmt = $conn->prepare("INSERT INTO staff_logs (staff_id, login) VALUES (:staff_id, NOW())");
            $logStmt->execute([':staff_id' => $staff['staff_id']]);

            // Success alert with redirect
            $POSModulePopupAlert = "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
                                <script>
                    setTimeout(function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Login Succesful!',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = './cashierLoginSuccess.php';
                        });
                    },);
                    </script>";
        } else {
            $POSModuleLoginMessage = "<p class='text-red-500 text-sm'><b>Invalid ID</b></p>";
            $POSModulePopupAlert = "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed!',
                    text: 'Invalid ID!',
                });
            </script>";
        }
    }
}
