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
          class="bg-[var(--background-color)] rounded-lg shadow portrait:px2 portrait:py-2 hidden">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div
              class="bg-[var(--background-color)] flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-[var(--text-color)]">
                  Analytics Dashboard
                </h2>
                <p class="text-sm text-[var(--text-color)]">
                  Welcome back, here's what's happening with your store today.
                </p>
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
                <h2 class="text-2xl font-bold">Sales Dashboard</h2>
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
  <section id="modifyPosition" class="bg-[var(--background-color)] hidden">
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div
        class="flex items-center justify-between text-[var(--text-color)]">
        <div>
          <h2 class="text-2xl font-bold text[var(--text-color)]">
            Modify Staff
          </h2>
          <p class="text-sm text[var(--text-color)]">
            Update a staff member's position or promote them to a new
            role.
          </p>
        </div>
      </div>
    </header>


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
    <header
      class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold">Complaints Management</h2>
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



  <section id="discountDashboard" class="bg-white rounded-lg shadow p-4 sm:p-6 hidden">


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