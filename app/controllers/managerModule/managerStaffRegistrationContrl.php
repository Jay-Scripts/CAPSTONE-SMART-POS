<?php
include "../../app/config/dbConnection.php";

$sanitizedStaffName = $staffName = $role = $registerStaffSuccess = "";
$staffRegistrationMessage = [
    "staffName" => "",
    "role" => ""
];

if (isset($_POST['registerStaff'])) {
    $staffName = $_POST['staffName'];
    $role = $_POST['role'] ?? '';

    // 1 Sanitize input
    $staffName = trim($_POST['staffName']);  // remove extra spaces
    $sanitizedStaffName = htmlspecialchars($staffName); // prevent XSS

    // 2 Validation input
    if (empty($sanitizedStaffName)) {
        $staffRegistrationMessage['staffName'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Staff name is required.</p>";
    } elseif (strlen($sanitizedStaffName) < 3) {
        $staffRegistrationMessage['staffName'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Staff name must be at least 3 characters long.</p>";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $sanitizedStaffName)) {
        $staffRegistrationMessage['staffName'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Staff name can only contain letters and spaces.</p>";
    } else {
        // 2.1 Check for duplicate staff name (case-insensitive)
        $stmt = $conn->prepare("SELECT COUNT(*) FROM staff_info WHERE LOWER(staff_name) = LOWER(:staff_name)");
        $stmt->execute([':staff_name' => $sanitizedStaffName]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            $staffRegistrationMessage['staffName'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>This staff name already exists.</p>";
        }
    }

    // validate role
    $validRoles = ['barista', 'cashier', 'manager'];
    if (empty($role)) {
        $staffRegistrationMessage['role'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Role is required.</p>";
    } elseif (!in_array($role, $validRoles)) {
        $staffRegistrationMessage['role'] = "<p class='text-red-500 text-sm bg-red-300 w-full rounded-xl p-3'>Invalid role selected.</p>";
    }

    // 3️ Checking
    $hasErrors = false;
    foreach ($staffRegistrationMessage as $msg) {
        if (!empty($msg)) {
            $hasErrors = true;
        }
    }

    // 4️ insertion into DB if no errors
    if (!$hasErrors) {
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("INSERT INTO staff_info (staff_name) VALUES (:staff_name)");
            $stmt->execute([':staff_name' => $sanitizedStaffName]);
            $staffId = $conn->lastInsertId();

            $stmt = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:staff_id, :role)");
            $stmt->execute([':staff_id' => $staffId, ':role' => $role]);

            $conn->commit();

            $registerStaffSuccess = "<div
                id='successAlert'
                class='fixed top-4 left-1/2 -translate-x-1/2 bg-green-100 border border-green-300 text-green-700 px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 transform transition-all duration-500 opacity-0 -translate-y-10'>
                <svg class='w-6 h-6 flex-shrink-0' fill='none' stroke='currentColor' stroke-width='2' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7' />
                </svg>
                <p class='text-sm font-medium'>Staff registered successfully!</p>
            </div>";
        } catch (PDOException $e) {
            $conn->rollBack();
            echo "❌ Database error: " . $e->getMessage();
        }
    }
}
