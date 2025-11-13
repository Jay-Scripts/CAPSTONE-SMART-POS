<?php
include "../../app/config/dbConnection.php";

// ────────────── Module-specific Variables ──────────────
$regStaffName = $regStaffRoles = $regStaffManager = "";
$regStaffSwalMessage = "";
$regStaffSwalType = "";

// ────────────── Form Submission ──────────────
if (isset($_POST['submit'])) {
    $regStaffName = trim($_POST['staffName']);
    $regStaffRoles = $_POST['roles'] ?? []; // array of selected roles
    $regStaffManager = $_POST['manager_account'] ?? null;

    // ────────────── Validation ──────────────
    if (empty($regStaffName) || empty($regStaffRoles)) {
        $regStaffSwalMessage = "Please enter staff name and select at least one role.";
        $regStaffSwalType = "error";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $regStaffName)) {
        $regStaffSwalMessage = "Staff name can only contain letters and spaces.";
        $regStaffSwalType = "error";
    } else {
        try {
            $conn->beginTransaction();

            // Insert staff
            $stmt = $conn->prepare("INSERT INTO staff_info (staff_name, added_by) VALUES (:staff_name, :added_by)");
            $stmt->execute([
                ':staff_name' => htmlspecialchars($regStaffName),
                ':added_by' => $regStaffManager
            ]);
            $staffId = $conn->lastInsertId();

            // Insert roles
            $stmtRole = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:staff_id, :role)");
            foreach ($regStaffRoles as $role) {
                $stmtRole->execute([
                    ':staff_id' => $staffId,
                    ':role' => strtoupper($role)
                ]);
            }

            $conn->commit();
            $regStaffSwalMessage = "Staff registered successfully!";
            $regStaffSwalType = "success";
        } catch (Exception $e) {
            $conn->rollBack();
            $regStaffSwalMessage = "Error: " . $e->getMessage();
            $regStaffSwalType = "error";
        }
    }
}
?>

<div class="flex justify-center p-4 sm:p-6 lg:p-10 bg-[var(--bg-color)]">
    <form method="POST" class="glass-card w-full sm:w-[90%] md:w-[70%] lg:w-[50%] border border-[var(--glass-border)] rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10 transition-all">
        <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
            <img src="../assets/SVG/LOGO/BLOGO.svg" alt="Logo" class="h-16 w-auto theme-logo" />
        </div>

        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
            Register Staff
        </h2>

        <!-- Staff Name -->
        <label class="block mt-3">
            <span class="block text-sm font-medium">Staff Name <span class="text-red-500">*</span></span>
            <input type="text" name="staffName" required
                class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                placeholder="Enter staff name">
        </label>

        <!-- Roles -->
        <fieldset class="mt-4 space-y-2 w-full">
            <legend class="text-base sm:text-lg font-semibold">
                Select Roles <span class="text-red-500">*</span>
            </legend>
            <div class="flex flex-wrap gap-3 mt-2 w-full">
                <?php $allRoles = ['BARISTA', 'CASHIER', 'MANAGER']; ?>
                <?php foreach ($allRoles as $role): ?>
                    <label class="cursor-pointer flex-1 min-w-[120px] flex items-center justify-center">
                        <input type="checkbox" name="roles[]" value="<?= $role ?>" class="peer hidden" />
                        <div class="w-full text-center rounded-lg border border-gray-300 px-3 py-2  
                            peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 
                            peer-checked:bg-[var(--background-color)] transition font-semibold">
                            <?= ucfirst(strtolower($role)) ?>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <!-- Hidden Manager -->
        <input type="hidden" name="manager_account" value="1" />

        <!-- Submit -->
        <div class="flex pt-6">
            <button type="submit" name="submit" class="w-full rounded-lg bg-indigo-600 px-6 py-2.5 text-sm sm:text-base font-semibold text-white shadow hover:bg-indigo-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-indigo-500">
                Register Staff
            </button>
        </div>
    </form>
</div>

<!-- SweetAlert -->
<?php if (!empty($regStaffSwalMessage)): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: '<?= $regStaffSwalType ?>',
            title: '<?= addslashes($regStaffSwalMessage) ?>',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>