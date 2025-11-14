<?php
include "../../app/config/dbConnection.php";

session_start();
$userId = $_SESSION['staff_id'] ?? null;


if (!isset($_SESSION['staff_name'])) {
  header("Location: ../auth/manager/managerLogin.php");
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manager's Module</title>

  <link
    rel="shortcut icon"
    href="../assets/favcon/manager.ico"
    type="image/x-icon" />
  <!-- cdn for chartJs -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- cdn for sweet alert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!--  linked css below for animations purpose -->
  <link href="../css/style.css" rel="stylesheet" />
  <!--  linked css below for tailwind dependencies to work ofline -->
  <!-- <link href="../css/output.css" rel="stylesheet" /> -->
  <!--  linked script below cdn of tailwind for online use -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--background-color)]">

  <?php
  include "../../app/includes/managerModule/asideNavBTNS.php";
  ?>

  <!-- Page Content -->
  <div class="sm:ml-64">
    <header
      class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
      <h1
        class="text-xs flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
        <span class="flex items-center">
          <img
            data-drawer-target="logo-sidebar"
            data-drawer-toggle="logo-sidebar"
            aria-controls="logo-sidebar"
            type="button"
            src="../assets/SVG/LOGO/BLOGO.svg"
            class="h-[3rem] theme-logo m-1"
            alt="Module Logo" />
          Manager's Module
        </span>

      </h1>

      <!-- 
      ==================================
      =   Theme toggle Btn Starts Here =
      ==================================
    -->
      <div class="flex items-center gap-2 sm:gap-3 lg:gap-4">
        <button
          class="p-2 sm:p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-200"
          id="theme-toggle"
          title="Toggle theme"
          aria-label="auto"
          aria-live="polite">
          <svg
            class="sun-and-moon text-gray-600 dark:text-gray-200"
            aria-hidden="true"
            width="24"
            height="24"
            viewBox="0 0 24 24">
            <mask class="moon" id="moon-mask">
              <rect x="0" y="0" width="100%" height="100%" fill="white" />
              <circle cx="24" cy="10" r="6" fill="black" />
            </mask>
            <circle
              class="sun"
              cx="12"
              cy="12"
              r="6"
              mask="url(#moon-mask)"
              fill="currentColor" />
            <g class="sun-beams" stroke="currentColor">
              <line x1="12" y1="1" x2="12" y2="3" />
              <line x1="12" y1="21" x2="12" y2="23" />
              <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
              <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
              <line x1="1" y1="12" x2="3" y2="12" />
              <line x1="21" y1="12" x2="23" y2="12" />
              <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
              <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
            </g>
          </svg>
        </button>

        <!-- 
      ================================
      =   Theme toggle Btn Ends Here =
      ================================
    -->

        <!-- 
    =======================
    Profile Dropdown
    ======================= -->
        <div class="flex items-center space-x-2">

          <div class="relative inline-block text-left">
            <button
              id="userMenuBtn"
              class="flex items-center gap-2 px-3 py-2 text-sm font-medium  rounded-md">
              <div class="text-left">
                <p class="font-medium text-[var(--text-color)]">
                  <?php
                  echo isset($_SESSION['staff_name']) ? $_SESSION['staff_name'] . "   " : "No Staff";
                  echo isset($_SESSION['role'])  ? $_SESSION['role'] . " " : "No Role"; ?>
                </p>

              </div>
              <svg
                class="w-4 h-4 text-gray-500"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <div
              id="userDropdown"
              class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg hidden">
              <div class="border-t border-gray-200"></div>
              <a
                href="../auth/manager/managerLogout.php"
                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
            </div>


          </div>

        </div>
      </div>
    </header>

    <!-- 
    =======================
    Profile Dropdown Ends Here
    ======================= -->

    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Header - Ends Here                                                                    =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
    <main
      class="bg-[var(--background-color)] transition-all duration-200 ease-out">
      <section class="bg-[var(--background-color)]">
        <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                     Overview module Starts Here                                                          =F
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
        <section
          id="overview"
          class="bg-[var(--background-color)] rounded-lg shadow portrait:px-2 portrait:py-2 hidden">
          <header class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="bg-[var(--background-color)] flex flex-col items-center justify-center">

              <div>
                <h2 class="text-2xl font-bold text-[var(--text-color)]">
                  Analytics Dashboard
                </h2>

                <?php
                include "../../app/config/dbConnection.php";

                // Unique variables for this form
                $ADDSTOCK_prodName = $ADDSTOCK_prodCategory = $ADDSTOCK_prodPrice_medio = $ADDSTOCK_prodPrice_grande = "";
                $ADDSTOCK_SwalMessage = "";
                $ADDSTOCK_SwalType = "";

                // Handle form submission
                if (isset($_POST['ADDSTOCK_submit'])) {
                  $ADDSTOCK_prodName = trim($_POST['ADDSTOCK_productName']);
                  $ADDSTOCK_prodCategory = $_POST['ADDSTOCK_category'] ?? null;
                  $ADDSTOCK_prodPrice_medio = $_POST['ADDSTOCK_price_medio'] ?? 0;
                  $ADDSTOCK_prodPrice_grande = $_POST['ADDSTOCK_price_grande'] ?? 0;

                  // Map categories to folders
                  $categoryFolders = [
                    1 => "MILKTEA_MENU",
                    2 => "FRUITTEA_MENU",
                    3 => "HOT_BREW",
                    4 => "PRAF_MENU",
                    5 => "BROSTY",
                    6 => "ICEDCOFFEE_MENU",
                    7 => "PROMOS_MENU",
                    8 => "ADDONS_MENU"
                  ];

                  // File upload
                  $ADDSTOCK_thumbnailPath = "";
                  if (isset($_FILES['ADDSTOCK_thumbnail']) && $_FILES['ADDSTOCK_thumbnail']['error'] === UPLOAD_ERR_OK) {
                    $folderName = $categoryFolders[$ADDSTOCK_prodCategory] ?? "PRODUCTS"; // fallback
                    $uploadDir = "../assets/IMAGES/MENU IMAGES/" . $folderName . "/";

                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                    $fileName = basename($_FILES['ADDSTOCK_thumbnail']['name']);
                    $targetFile = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['ADDSTOCK_thumbnail']['tmp_name'], $targetFile)) {
                      $ADDSTOCK_thumbnailPath = "../assets/IMAGES/MENU IMAGES/" . $folderName . "/" . $fileName;
                    } else {
                      $ADDSTOCK_SwalMessage = "Failed to upload thumbnail.";
                      $ADDSTOCK_SwalType = "error";
                    }
                  }

                  // Validation
                  if (empty($ADDSTOCK_prodName) || empty($ADDSTOCK_prodCategory) || empty($ADDSTOCK_prodPrice_medio) || empty($ADDSTOCK_prodPrice_grande) || empty($ADDSTOCK_thumbnailPath)) {
                    $ADDSTOCK_SwalMessage = "Please fill all required fields and upload an image.";
                    $ADDSTOCK_SwalType = "error";
                  } else {
                    try {
                      $conn->beginTransaction();

                      // Insert product
                      $stmt = $conn->prepare("INSERT INTO product_details (product_name, category_id, thumbnail_path) VALUES (:name, :category, :thumbnail)");
                      $stmt->execute([
                        ':name' => htmlspecialchars($ADDSTOCK_prodName),
                        ':category' => $ADDSTOCK_prodCategory,
                        ':thumbnail' => $ADDSTOCK_thumbnailPath
                      ]);
                      $ADDSTOCK_productId = $conn->lastInsertId();

                      // Insert sizes
                      $stmtSize = $conn->prepare("INSERT INTO product_sizes (product_id, size, regular_price) VALUES (:product_id, :size, :price)");

                      // Medio
                      $stmtSize->execute([
                        ':product_id' => $ADDSTOCK_productId,
                        ':size' => 'medio',
                        ':price' => $ADDSTOCK_prodPrice_medio
                      ]);

                      // Grande
                      $stmtSize->execute([
                        ':product_id' => $ADDSTOCK_productId,
                        ':size' => 'grande',
                        ':price' => $ADDSTOCK_prodPrice_grande
                      ]);

                      $conn->commit();
                      $ADDSTOCK_SwalMessage = "Product added successfully!";
                      $ADDSTOCK_SwalType = "success";
                    } catch (Exception $e) {
                      $conn->rollBack();
                      $ADDSTOCK_SwalMessage = "Error: " . $e->getMessage();
                      $ADDSTOCK_SwalType = "error";
                    }
                  }
                }

                // Fetch categories
                $ADDSTOCK_categories = $conn->query("SELECT * FROM category WHERE status='ACTIVE'")->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="flex justify-center items-center p-6  bg-[var(--bg-color)]">
                  <form method="POST" enctype="multipart/form-data"
                    class="glass-card w-full  border border-[var(--glass-border)] rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10 transition-all">

                    <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center">
                      <img src="../assets/SVG/LOGO/BLOGO.svg" alt="Logo" class="h-16 w-auto theme-logo" />
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-bold text-center text-[var(--text-color)] mb-6">
                      Add Product
                    </h2>

                    <!-- Product Name -->
                    <label class="block mb-4">
                      <span class="block text-sm font-medium">Product Name <span class="text-red-500">*</span></span>
                      <input type="text" name="ADDSTOCK_productName" required
                        class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        placeholder="Enter product name" value="<?= htmlspecialchars($ADDSTOCK_prodName) ?>">
                    </label>

                    <!-- Category -->
                    <label class="block mb-4">
                      <span class="block text-sm font-medium">Category <span class="text-red-500">*</span></span>
                      <select name="ADDSTOCK_category" required
                        class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">Select Category</option>
                        <?php foreach ($ADDSTOCK_categories as $cat): ?>
                          <option value="<?= $cat['category_id'] ?>" <?= ($ADDSTOCK_prodCategory == $cat['category_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </label>

                    <!-- Prices side by side -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                      <label>
                        <span class="block text-sm font-medium">Price (Medio) <span class="text-red-500">*</span></span>
                        <input type="number" step="0.01" min="0" name="ADDSTOCK_price_medio" required
                          class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                          placeholder="Enter price for Medio" value="<?= htmlspecialchars($ADDSTOCK_prodPrice_medio) ?>">
                      </label>

                      <label>
                        <span class="block text-sm font-medium">Price (Grande) <span class="text-red-500">*</span></span>
                        <input type="number" step="0.01" min="0" name="ADDSTOCK_price_grande" required
                          class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                          placeholder="Enter price for Grande" value="<?= htmlspecialchars($ADDSTOCK_prodPrice_grande) ?>">
                      </label>
                    </div>

                    <!-- Thumbnail -->
                    <label class="block mb-6">
                      <span class="block text-sm font-medium">Thumbnail <span class="text-red-500">*</span></span>
                      <input type="file" name="ADDSTOCK_thumbnail" accept="image/*" required
                        class="w-full mt-1 border bg-[var(--background-color)] rounded-lg border-[var(--glass-border)] px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </label>

                    <!-- Submit -->
                    <div class="flex">
                      <button type="submit" name="ADDSTOCK_submit"
                        class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow hover:bg-indigo-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-indigo-500">
                        Add Product
                      </button>
                    </div>
                  </form>
                </div>

                <?php if (!empty($ADDSTOCK_SwalMessage)): ?>
                  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                  <script>
                    Swal.fire({
                      icon: '<?= $ADDSTOCK_SwalType ?>',
                      title: '<?= addslashes($ADDSTOCK_SwalMessage) ?>',
                      timer: 2500,
                      showConfirmButton: false
                    });
                  </script>
                <?php endif; ?>

              </div>
            </div>
          </header>
        </section>



        <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                     Overview module Ends Here                                                          =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

        <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                     Sales Management Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

        <!-- 
      ==========================================================================================================================================
      =                                                    Sales Report Starts Here                                                            =
      ==========================================================================================================================================
    -->
        <section id="salesReports" class="bg-[var(--background-color)] rounded-2xl  overflow-hidden hidden">
          <header class="border-b border-[var(--border-color)] px-6 py-5">
            <h2 class="text-2xl font-bold text-[var(--text-color)] tracking-tight">Sales Reports</h2>
            <p class="text-sm text-gray-500 mt-1">Generate and print your store’s summarized reports.</p>
          </header>

          <div class="p-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">




            <?php
            // ==========================================================================================================================================
            // =                                                    Daily Cashier Sales Report                                                         =
            // ==========================================================================================================================================
            include "../../app/includes/managerModule/manageSalesManagementDailySalesReport.php";
            // ==========================================================================================================================================
            // =                                                    Weekly Report                                                                     =
            // ==========================================================================================================================================
            include "../../app/includes/managerModule/manageSalesManagementWeeklyReport.php";
            // ==========================================================================================================================================
            // =                                                    Monthly Report                                                                     =
            // ==========================================================================================================================================
            include "../../app/includes/managerModule/manageSalesManagementMonthlyReport.php";
            // ==========================================================================================================================================
            // =                                                    Reprint Receipt                                                                     =
            // ==========================================================================================================================================
            include "../../app/includes/managerModule/managerSalesManagementReprintReceiptUI.php";
            ?>






          </div>
        </section>




        <!-- 
      ==========================================================================================================================================
      =                                                    Sales Report Ends Here                                                              =
      ==========================================================================================================================================
    -->

        <!-- 
      ==========================================================================================================================================
      =                                                    Performance Trends Starts Here                                                      =
      ==========================================================================================================================================
    -->
        <section id="performanceTrend" class="bg-white rounded-lg shadow hidden">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Overview</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.

                </p>
              </div>
            </div>
          </header>

          <?php
          include "../../app/includes/managerModule/manageSalesManagementSalesDashboard.php";
          ?>



        </section>


        <!-- 
      ==========================================================================================================================================
      =                                                     Visual Analytics Ends Here                                                          =
      ==========================================================================================================================================
    -->
  </div>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                    Performance Trends Ends Here                                                        =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                    Refund Starts Here                                                                  =
      ==========================================================================================================================================
    -->
  <section id="refund" class="rounded-lg shadow hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Refund</h2>
          <p class="text-sm text-gray-600">
            Welcome back, here's what's happening with your store today.
          </p>
        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/manageSalesManagementRefund.php";
    ?>



  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                    Refund Ends Here                                                                    =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                    Sales Management Ends Here                                                          =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                      Stock Management Starts Here                                                      =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                     Stock Reports Starts Here                                                            =
      ==========================================================================================================================================
    -->
  <section id="stockEntry" class="bg-[var(--background-color)] text-[var(--text-color)] rounded-lg shadow p-6 hidden">
    <!-- Header -->
    <header class="shadow-sm border-b border-[var(--border-color)] pb-4 mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Stock Reports</h2>
          <p class="text-sm text-gray-500 mt-2">
            Upload your staff-counted Excel file to automatically compare system stock vs. actual count and generate a variance report for review.
            Used for monthly inventory reports.
          </p>

        </div>
      </div>
    </header>


    <?php
    include "../../app/includes/managerModule/managerStockManagementStockReport.php";
    ?>


  </section>









  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                     Stock Reports Ends Here                                                              =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                     Stock Control Starts Here                                                            =
      ==========================================================================================================================================
    -->


  <section id="stockLevel" class="bg-[var(--background-color)] text-[var(--text-color)] rounded-lg shadow hidden">
    <header class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Stock Control</h2>
          <p class="text-sm text-gray-600">Welcome back, here's what's happening with your store today.</p>
        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/managerStockMaangementStockControl.php";
    ?>

  </section>



  <!-- 
      ==========================================================================================================================================
      =                                                     Stock Control Ends Here                                                              =
      ==========================================================================================================================================
    -->




  <!-- 
      ==========================================================================================================================================
      =                                                      Stock Alert Starts Here                                                        =
      ==========================================================================================================================================
    -->
  <section id="lowStockAlerts" class="bg-[var(--background-color)] rounded-lg shadow  text-[var(--text-color)] hidden">
    <header class="shadow-sm border-b border-[var(--border-color)] pb-4 mb-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Stock Alerts</h2>
          <p class="text-sm ">
            Welcome back, here’s what's happening with your store today.
          </p>
        </div>
      </div>
    </header>
    <div class="p-4"> <?php
                      include "../../app/includes/managerModule/managerStockManagementStockALerts.php";
                      ?></div>

  </section>






  <!-- 
      ==========================================================================================================================================
      =                                                      Stock Alert Ends Here                                                          =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                    Stock Movement History Starts Here                                                  =
      ==========================================================================================================================================
    -->
  <section id="stocksMovementHistory" class="rounded-lg shadow bg-[var(--background-color)] text-[var(--text-color)] hidden">
    <header class="shadow-sm border-b border-[var(--border-color)] px-6 py-4 mb-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Stock History</h2>
          <p class="text-sm ">
            Welcome back, here's what's happening with your store today.
          </p>
        </div>
      </div>
    </header>
    <div class="p-4">
      <?php
      include "../../app/includes/managerModule/managerStockManagementStockHistory.php";
      ?></div>

  </section>


  <!-- 
      ==========================================================================================================================================
      =                                                    Stock Movement History Ends Here                                                    =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                   Stock Management Ends Here                                                           =
      =                                                                                                                                        =
      ==========================================================================================================================================

    -->


  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                    Staff Management Starts Here                                                        =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                     Register Staff Starts Here                                                         =
      ==========================================================================================================================================
    -->
  <section id="registerStaff" class="bg-[var(--background-color)] text-[var(--text-color)] hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Staff Registration</h2>
          <p class="text-sm">
            Add new staff members and manage their account details.
          </p>

        </div>
      </div>
    </header>
    <?php
    include "../../app/views/managerModule/managerStaffRegistrationView.php";
    ?>

  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                     Register Staff Ends Here                                                           =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                     Modify Position Starts Here                                                        =
      ==========================================================================================================================================
    -->
  <?php
  include "../../app/config/dbConnection.php";

  // Fetch staff with all roles
  $staffList = $conn->query("
    SELECT si.staff_id, si.staff_name, GROUP_CONCAT(sr.role ORDER BY sr.role SEPARATOR ', ') AS roles
    FROM staff_info si
    LEFT JOIN staff_roles sr ON si.staff_id = sr.staff_id
    GROUP BY si.staff_id
    ORDER BY si.staff_name
")->fetchAll(PDO::FETCH_ASSOC);

  $swalMessage = "";
  $swalType = "";

  // Handle role modification
  if (isset($_POST['modifyRole'])) {
    $staffId = $_POST['staffId'];
    $newRole = strtoupper($_POST['newRole']);
    $validRoles = ['BARISTA', 'CASHIER', 'MANAGER'];

    if (!in_array($newRole, $validRoles)) {
      $swalMessage = "Invalid role selected.";
      $swalType = "error";
    } else {
      // Replace all roles with the selected one
      $conn->beginTransaction();
      $conn->exec("DELETE FROM staff_roles WHERE staff_id = $staffId");
      $stmt = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:staff_id, :role)");
      $stmt->execute([':staff_id' => $staffId, ':role' => $newRole]);
      $conn->commit();

      $swalMessage = "Role modified successfully!";
      $swalType = "success";
    }
  }

  // Handle adding role
  if (isset($_POST['addRole'])) {
    $staffId = $_POST['staffId'];
    $newRole = strtoupper($_POST['newRole']);
    $validRoles = ['BARISTA', 'CASHIER', 'MANAGER'];

    if (!in_array($newRole, $validRoles)) {
      $swalMessage = "Invalid role selected.";
      $swalType = "error";
    } else {
      // Add role only if not already assigned
      $stmt = $conn->prepare("SELECT COUNT(*) FROM staff_roles WHERE staff_id=:staff_id AND role=:role");
      $stmt->execute([':staff_id' => $staffId, ':role' => $newRole]);
      if ($stmt->fetchColumn() > 0) {
        $swalMessage = "Staff already has this role.";
        $swalType = "warning";
      } else {
        $stmt = $conn->prepare("INSERT INTO staff_roles (staff_id, role) VALUES (:staff_id, :role)");
        $stmt->execute([':staff_id' => $staffId, ':role' => $newRole]);
        $swalMessage = "Role added successfully!";
        $swalType = "success";
      }
    }
  }
  ?>

  <section id="modifyPosition" class="bg-[var(--background-color)] text-[var(--text-color)]">
    <header class="shadow-sm border-b border-[var(--border-color)] px-6 py-4 mb-4">
      <h2 class="text-2xl font-bold text-[var(--text-color)]">Modify Staff</h2>
      <p class="text-sm text-[var(--text-color)]">Update or assign a staff member's role.</p>
    </header>

    <div class="overflow-x-auto p-5">
      <table class="min-w-full bg-[var(--glass-bg)] border border-[var(--border-color)] rounded-xl">
        <thead class="bg-gray-200 text-black">
          <tr>
            <th class="py-3 px-4  border border-[var(--border-color)] text-left">Staff Name</th>
            <th class="py-3 px-4  border border-[var(--border-color)] text-left">Roles</th>
            <th class="py-3 px-4  border border-[var(--border-color)] text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($staffList as $staff): ?>
            <tr class="border-b">
              <td class="py-3 px-4  border border-[var(--border-color)]"><?= htmlspecialchars($staff['staff_name']) ?></td>
              <td class="py-3 px-4  border border-[var(--border-color)]"><?= $staff['roles'] ?? 'NONE' ?></td>
              <td class="py-3 px-4 text-center flex justify-center gap-2  border border-[var(--border-color)]">
                <button onclick="modifyRole(<?= $staff['staff_id'] ?>,'<?= addslashes($staff['staff_name']) ?>')"
                  class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">Modify Role</button>
                <button onclick="addRole(<?= $staff['staff_id'] ?>,'<?= addslashes($staff['staff_name']) ?>')"
                  class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">Add Role</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <script>
      let lastStaffData = null;

      // 🔹 Refresh staff table if data changes (roles or new staff)
      const staffTbody = document.querySelector("#staffTable tbody");



      // 🔹 Send async request to update roles
      async function sendRoleUpdate(action, staffId, role) {
        const formData = new FormData();
        formData.append("action", action);
        formData.append("staffId", staffId);
        formData.append("newRole", role);

        try {
          const res = await fetch("../../app/includes/managerModule/managerStaffManagementUpdateRoles.php", {
            method: "POST",
            body: formData
          });
          const data = await res.json();

          Swal.fire({
            icon: data.status,
            title: data.message,
            timer: 2000,
            showConfirmButton: false
          });

          // Immediately refresh table after successful update
          if (data.status === "success") await refreshStaffTable();
        } catch (err) {
          console.error("Failed to update role:", err);
        }
      }

      // 🔹 Modify role popup
      function modifyRole(staffId, staffName) {
        Swal.fire({
          title: `Modify Role for ${staffName}`,
          input: 'select',
          inputOptions: {
            'BARISTA': 'Barista',
            'CASHIER': 'Cashier',
            'MANAGER': 'Manager'
          },
          inputPlaceholder: 'Select role',
          showCancelButton: true,
          confirmButtonText: 'Update Role'
        }).then(result => {
          if (result.isConfirmed) sendRoleUpdate("modifyRole", staffId, result.value);
        });
      }

      // 🔹 Add role popup
      function addRole(staffId, staffName) {
        Swal.fire({
          title: `Add Role for ${staffName}`,
          input: 'select',
          inputOptions: {
            'BARISTA': 'Barista',
            'CASHIER': 'Cashier',
            'MANAGER': 'Manager'
          },
          inputPlaceholder: 'Select role',
          showCancelButton: true,
          confirmButtonText: 'Add Role'
        }).then(result => {
          if (result.isConfirmed) sendRoleUpdate("addRole", staffId, result.value);
        });
      }
    </script>
  </section>







  <!-- 
      ==========================================================================================================================================
      =                                                     Modify Position Ends Here                                                           =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                      Update Status Starts Here                                                         =
      ==========================================================================================================================================
    -->
  <section id="modifyStatus" class="bg-[var(--background-color)] hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div
        class="flex items-center justify-between text-[var(--text-color)]">
        <div>
          <h2 class="text-2xl font-bold">Modify Staff Status</h2>
          <p class="text-sm text[var(--text-color)]">
            Update a staff member's status.
          </p>
        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/managerStaffManagementModifyStaffStatus.php";
    ?>


  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                      Update Status Ends Here                                                           =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                      Staff Management Ends Here                                                        =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->



  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                   Product Management Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================

    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Log Waste Starts Here                                                                =
      ==========================================================================================================================================
    -->
  <section id="logWaste" class="rounded-lg shadow hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl text-[var(--text-color)] font-bold">
            Log Waste
          </h2>
          <p class="text-sm text-[var(--text-color)]">
            Please log details of any wasted items, including reason and
            notes.
          </p>
        </div>
      </div>
    </header>

    <?php
    include "../../app/includes/managerModule/manageSalesManagementWaste.php";
    ?>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                   Log Waste Ends Here                                                                  =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Disable Product Starts Here                                                          =
      ==========================================================================================================================================
    -->
  <section id="disableProduct" class="bg-[var(--background-color)] rounded-lg shadow hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="text-[var(--text-color)]">
          <h2 class="text-2xl font-bold">Disable Product</h2>
          <p class="text-sm">
            Manage your store Product by temporarily disabling products that are out of stock or unavailable.
          </p>
        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/managerProductManagementDisableProduct.php";
    ?>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                   Disable Product Ends Here                                                            =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Enable Product Starts Here                                                           =
      ==========================================================================================================================================
    -->
  <section id="enableProduct" class="bg-[var(background-color)] rounded-lg shadow hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div class="text-[var(--text-color)]">
          <h2 class="text-2xl font-bold">Enable Product</h2>
          <p class="text-sm">
            Reactivate previously disabled products to make them available for sale in your store again.
          </p>

        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/managerProductManagementEnableProduct.php";
    ?>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                   Enable Product Ends Here                                                             =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Product Movement History Stars Here                                                  =
      ==========================================================================================================================================
    -->
  <section
    id="productMovementHistory"
    class="bg-[var(--background-color)] rounded-lg shadow text-[var(--text-color)] hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Product Sold per Category</h2>
          <p class="text-sm">
            Track all product movements made within your store.
          </p>

        </div>
      </div>
    </header>
    <?php
    include "../../app/includes/managerModule/managerProductManagementAnalytics.php";

    ?>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                   Product Movement History Ends Here                                                   =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                 Product Management Ends Here                                                           =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                 Customers Management Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                   Satisfaction Dashboard Starts Here                                                   =
      ==========================================================================================================================================
    -->
  <section id="satisfactionDashboard" class="bg-white rounded-lg shadow p-4 md:p-6 hidden">
    <header class="shadow-sm border-b border-gray-200 px-4 py-3 mb-4 md:flex md:justify-between md:items-center">
      <h2 class="text-xl md:text-2xl font-bold">Satisfaction Dashboard</h2>
      <p class="text-sm text-gray-600 mt-2 md:mt-0">Overview of today's feedback</p>
    </header>

    <?php
    include "../../app/includes/managerModule/managerCRMSatisfactionDashboard.php";
    ?>
  </section>



  <!-- 
      ==========================================================================================================================================
      =                                                   Satisfaction Dashboard Ends Here                                                     =
      ==========================================================================================================================================
    -->
  <!-- 
      ==========================================================================================================================================
      =                                                   Complaints Management Starts Here                                                    =
      ==========================================================================================================================================
    -->
  <section id="complaintsManagement" class="bg-white rounded-lg shadow hidden">
    <header class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Staff Logs History</h2>
          <p class="text-sm text-gray-600">
            Welcome back, here's what's happening with your store today.
          </p>
        </div>
      </div>
    </header>
    <div class="p-6">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
        <input type="text" id="StaffLogsSearch" placeholder="Search staff name..."
          class="p-2 border border-[var(--border-color)] rounded w-full sm:w-1/2 bg-[var(--background-color)] text-[var(--text-color)]">
      </div>

      <div class="overflow-x-auto border border-[var(--border-color)] rounded-lg">
        <table id="StaffLogsTable" class="min-w-full border-collapse bg-[var(--glass-bg)]">
          <thead class="sticky top-0 z-10 bg-gray-200 text-black">
            <tr>
              <th class="py-2 px-4 border border-[var(--border-color)]">Staff ID</th>
              <th class="py-2 px-4 border border-[var(--border-color)]">Staff Name</th>
              <th class="py-2 px-4 border border-[var(--border-color)]">Role</th>
              <th class="py-2 px-4 border border-[var(--border-color)]">Log Type</th>
              <th class="py-2 px-4 border border-[var(--border-color)]">Time</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <div class="mt-4 flex justify-center gap-2" id="StaffLogsPagination"></div>
    </div>

    <script>
      function initStaffLogsModule() {
        const tableBody = document.querySelector('#StaffLogsTable tbody');
        const searchInput = document.getElementById('StaffLogsSearch');
        const pagination = document.getElementById('StaffLogsPagination');
        const rowsPerPage = 10;
        let currentPage = 1;
        let allRows = [];

        async function fetchStaffLogs() {
          try {
            const response = await fetch('../../app/includes/managerModule/fetchStaffLogs.php');
            const data = await response.json();
            tableBody.innerHTML = '';

            data.forEach(log => {
              const tr = document.createElement('tr');
              tr.className = 'hover:bg-blue-400 hover:text-white transition';
              tr.innerHTML = `
              <td class="py-2 px-4 border border-[var(--border-color)]">${log.staff_id}</td>
              <td class="py-2 px-4 border border-[var(--border-color)]">${log.staff_name}</td>
              <td class="py-2 px-4 border border-[var(--border-color)]">${log.role}</td>
              <td class="py-2 px-4 border border-[var(--border-color)] font-bold" style="color:${log.log_type === 'IN' ? 'green' : 'red'}">${log.log_type}</td>
              <td class="py-2 px-4 border border-[var(--border-color)]">${new Date(log.log_time).toLocaleString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'numeric', minute:'2-digit', hour12:true })}</td>
            `;
              tableBody.appendChild(tr);
            });

            allRows = Array.from(tableBody.querySelectorAll('tr'));
            renderStaffLogsTable();
          } catch (err) {
            console.error('Error fetching logs:', err);
          }
        }

        function renderStaffLogsTable() {
          const filterText = searchInput.value.toLowerCase();
          const filteredRows = allRows.filter(row => row.textContent.toLowerCase().includes(filterText));
          const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
          currentPage = Math.min(currentPage, totalPages) || 1;

          allRows.forEach(r => r.style.display = 'none');
          const start = (currentPage - 1) * rowsPerPage;
          filteredRows.slice(start, start + rowsPerPage).forEach(r => r.style.display = '');

          pagination.innerHTML = '';

          const prevBtn = document.createElement('button');
          prevBtn.textContent = '<';
          prevBtn.disabled = currentPage === 1;
          prevBtn.className = `px-3 py-1 rounded ${prevBtn.disabled ? 'bg-gray-300' : 'bg-gray-200'}`;
          prevBtn.onclick = () => {
            currentPage--;
            renderStaffLogsTable();
          };
          pagination.appendChild(prevBtn);

          let startPage = Math.max(1, currentPage - 2);
          let endPage = Math.min(totalPages, startPage + 4);
          startPage = Math.max(1, endPage - 4);
          for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200'}`;
            btn.onclick = () => {
              currentPage = i;
              renderStaffLogsTable();
            };
            pagination.appendChild(btn);
          }

          const nextBtn = document.createElement('button');
          nextBtn.textContent = '>';
          nextBtn.disabled = currentPage === totalPages || totalPages === 0;
          nextBtn.className = `px-3 py-1 rounded ${nextBtn.disabled ? 'bg-gray-300' : 'bg-gray-200'}`;
          nextBtn.onclick = () => {
            currentPage++;
            renderStaffLogsTable();
          };
          pagination.appendChild(nextBtn);
        }

        searchInput.addEventListener('input', () => {
          currentPage = 1;
          renderStaffLogsTable();
        });

        fetchStaffLogs();
        setInterval(fetchStaffLogs, 1000);
      }

      // ✅ Initialize immediately
      initStaffLogsModule();
    </script>
  </section>


  <!-- 
      ==========================================================================================================================================
      =                                                   Complaints Management Ends Here                                                      =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Rewards Loyalty Program Starts Here                                                  =
      ==========================================================================================================================================
    -->
  <section
    id="rewards&LoyaltyProgram"
    class="bg-white rounded-lg shadow hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Rewards & Loyalty Program</h2>
          <p class="text-sm text-gray-600">
            Welcome back, here's what's happening with your store today.
          </p>
        </div>
      </div>
    </header>
    <h3 class="text-xl font-semibold mb-2">Refund</h3>
    <p>
      // rewards & loyalty program dito naman yung analytics view ng mga
      registered customer na may rewarding card or app
    </p>
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                   Rewards Loyalty Program Ends Here                                                    =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                   Discount Dashboard Starts Here                                                       =
      ==========================================================================================================================================
    -->



  <section id="discountDashboard" class="rounded-lg shadow hidden text-[var(--text-color)]">


    <?php
    include "../../app/includes/managerModule/manageSalesManagementDiscountDashboard.php";
    ?>
  </section>


  <!-- 
      ==========================================================================================================================================
      =                                                   Discount Dashboard Ends Here                                                         =
      ==========================================================================================================================================
    -->
  </section>
  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Customer Management  Ends Here                                                        =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  </main>
  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main - Ends Here                                                                      =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Starts Here                                                                  =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->


  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Ends Here                                                                    =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  </div>
  <!-- 
      ========================
      = JS Links Starts Here =
      ========================
    -->
  <!-- linked JS file below for Enable Product Module  -->
  <script src="../JS/manager/managerEnableProduct.js"></script>
  <!-- linked JS file below for Disable Product Module  -->
  <script src="../JS/manager/managerDisableProduct.js"></script>
  <!-- linked JS file below for changing module content -->
  <script src="../JS/manager/managerModules.js"></script>
  <!-- linked JS file below for Staff Register Module -->
  <script src="../JS/manager/managerStaffRegisterSuccessNotif.js"></script>
  <!-- linked JS file below for Staff Register Module -->
  <script src="../JS/manager/managerStaffRegister.js"></script>
  <!-- linked JS file below for Staff Modify Module -->
  <script src="../JS/manager/managerStaffModify.js"></script>
  <!-- linked JS file below for refund -->
  <script src="../JS/manager/managerRefundTrans.js"></script>
  <!-- linked JS file below for waste -->
  <script src="../JS/manager/managerLogwaste.js"></script>
  <!-- linked JS file below for updating staff status -->
  <script src="../JS/manager/managerUpdateStaffStatus.js"></script>
  <!-- linked JS file below for in Overview -->
  <script src="../JS/manager/managerOverview.js"></script>
  <!-- linked JS file below for Product Analytics Sold Items per Category -->
  <script src="../JS/manager/managerProductAnalyticsSoldPerCategory.js"></script>
  <!-- linked JS file below for Product Analytics Sold Addons -->
  <script src="../JS/manager/managerProductAnalyticsSoldAddons.js"></script>
  <!-- linked JS file below for Product Analytics Bar Charts per Category -->
  <script src="../JS/manager/managerProductAnalyticsBarCharts.js"></script>
  <!-- linked JS file below for Stocks Management Add Stocks -->
  <script src="../JS/manager/managerStockManagementAddStock.js"></script>
  <!-- linked JS file below for Stocks Management Fetch Inv List  -->
  <script src="../JS/manager/managerStockManagementFetchInvItems.js"></script>
  <!-- linked JS file below for account Dropdown to logOut -->
  <script src="../JS/shared/dropDownLogout.js"></script>


  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->
  <!-- <script src="../JS/shared/checkDBCon.js"></script> -->

  <!-- 
      ======================
      = JS Links Ends Here =
      ======================
    -->
  <!-- Flowbite JS -->
  <script src="../../node_modules/flowbite/dist/flowbite.min.js"></script>


</body>

</html>