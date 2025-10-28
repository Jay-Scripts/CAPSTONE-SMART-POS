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
  <aside
    id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[var(--managers-nav-bg)] overflow-y-auto"
    aria-label="Sidebar">
    <section class="h-full overflow-y">
      <div class="flex flex-col items-center justify-center mb-2">
        <img
          src="../assets/SVG/LOGO/WLOGO.svg"
          class="h-[10vh] theme-logo"
          alt="Module Logo" />
        <p
          class="self-center text-xl font-semibold whitespace-nowrap text-[var(--managers-nav-text)]">
          SMART POS
        </p>
      </div>
      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                         Navigation Bar - Starts Here                                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      <div class="text-[var(--managers-nav-text)]" id="sideBar">
        <!-- 
      ==========================================================================================================================================
      =                                                    Navigation Menu Starts Here                                                         =
      ==========================================================================================================================================
    -->
        <nav class="mt-6 px-3 mb-3 flex justify-around flex-col">
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Overview Start Here                                                                  -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-4 py-3 mt-6">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider flex items-center">
              Dashboard
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              href=""
              data-module="overview"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
              </svg>
              Overview
            </a>
          </section>
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Overview Ends Here                                                                   -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Staff Management Start Here                                                          -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-4 py-3 mt-2">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
              Staff Management
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              data-module="registerStaff"
              href="#"
              class="navItem flex font-medium items-center px-4 py-3 text-sm rounded-lg transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-cyan-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a3 3 0 11-6 0 3 3 0 016 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
              </svg>

              Register Staff
            </a>
            <a
              data-module="modifyPosition"
              href="#"
              class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-green-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5l7 7-9 9H2v-7l9-9z" />
              </svg>

              Modify Position
            </a>
            <a
              data-module="modifyStatus"
              href="#"
              class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-yellow-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M11 5l7 7-9 9H2v-7l9-9z" />
              </svg>
              Modify Status
            </a>
          </section>
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Staff Management Ends Here                                                           -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Product Management Starts Here                                                       -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-6 py-2 mt-2">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
              Product Management
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              data-module="logWaste"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-red-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
              Log Waste
            </a>
            <a
              data-module="enableProduct"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-green-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7" />
              </svg>

              Enable Product
            </a>
            <a
              data-module="disableProduct"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-red-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Disable Product
            </a>
            <a
              data-module="productMovementHistory"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-blue-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6v14h16M8 16v-4m4 4V8m4 8v-2" />
              </svg>

              Product Analytics
            </a>
          </section>

          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Product Management Ends Here                                                         -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Inventory Management Starts Here                                                     -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-6 py-2 mt-2">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
              Inventory Management
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              data-module="stockEntry"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-emerald-400"
                fill="currentColor"
                viewBox="0 -960 960 960"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M640-640h120-120Zm-440 0h338-18 14-334Zm16-80h528l-34-40H250l-34 40Zm184 270 80-40 80 40v-190H400v190Zm182 330H200q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v196q-19-7-39-11t-41-4v-122H640v153q-35 20-61 49.5T538-371l-58-29-160 80v-320H200v440h334q8 23 20 43t28 37Zm138 0v-120H600v-80h120v-120h80v120h120v80H800v120h-80Z" />
              </svg>
              Add New Stock
            </a>


            <a
              data-module="stockLevel"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-emerald-400"
                fill="currentColor"
                viewBox="0 -960 960 960"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M640-640h120-120Zm-440 0h338-18 14-334Zm16-80h528l-34-40H250l-34 40Zm184 270 80-40 80 40v-190H400v190Zm182 330H200q-33 0-56.5-23.5T120-200v-499q0-14 4.5-27t13.5-24l50-61q11-14 27.5-21.5T250-840h460q18 0 34.5 7.5T772-811l50 61q9 11 13.5 24t4.5 27v196q-19-7-39-11t-41-4v-122H640v153q-35 20-61 49.5T538-371l-58-29-160 80v-320H200v440h334q8 23 20 43t28 37Z" />
                <path d="M720-120v-160h-80l120-120 120 120h-80v160h-80Z" />
              </svg>

              Restock
            </a>
            <a
              data-module="lowStockAlerts"
              href="#"
              class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-yellow-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
              Low Stock Alerts
            </a>
            <a
              data-module="stocksMovementHistory"
              href="#"
              class="navItem flex font-medium items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-red-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
              </svg>
              Expiry Alerts
            </a>
          </section>
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Inventory Management Ends Here                                                       -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->

          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Sales Management Starts Here                                                         -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-4 py-3 mt-2">
            <h3
              class="text-xs font-semibold text-[var(managers-nav-text)] uppercase tracking-wider">
              Sales Management
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              data-module="salesReports"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-purple-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>

              Sales Reports
            </a>
            <a
              data-module="performanceTrend"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-orange-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>

              Performance Trends
            </a>
            <a
              data-module="refund"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-red-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
              </svg>
              Refund
            </a>
          </section>
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Sales Management Ends Here                                                           -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->


          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Customer Management Starts Here                                                        -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
          <div class="px-6 py-2 mt-2">
            <h3
              class="text-xs font-semibold text-[var(--managers-nav-text)] uppercase tracking-wider">
              Customer Management
            </h3>
          </div>
          <section class="space-y-1 px-3 group">
            <a
              data-module="satisfactionDashboard"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-green-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
              </svg>
              Satisfaction Dashboard
            </a>
            <a
              data-module="complaintsManagement"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-amber-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
              </svg>
              Complaint Management
            </a>
            <a
              data-module="rewards&LoyaltyProgram"
              href="#"
              class="navItem font-medium flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-opacity duration-200 group-hover:opacity-20 hover:!opacity-100">
              <svg
                class="w-5 h-5 mr-3 text-violet-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
              </svg>
              Rewards & Loyalty Program
            </a>
            <a
              data-module="discountDashboard"
              href="#"
              class="navItem flex items-center px-3 py-2 text-sm rounded-lg cursor-pointer transition-colors duration-200 group-hover:opacity-10 hover:!opacity-150 hover:bg-yellow-800 hover:text-white">
              <svg
                class="w-5 h-5 mr-3 text-rose-400"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>

              Discount Dashboard
            </a>
          </section>
          <!-- 
      -----------------------------------------------------------------------------------------------------------------------------------------
      -                                                  Customer Management Ends Here                                                        -
      -----------------------------------------------------------------------------------------------------------------------------------------
    -->
        </nav>
      </div>

      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                         Navigation Bar - Ends Here                                                                     =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
    </section>
  </aside>

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
          class="bg-[var(--background-color)] rounded-lg shadow portrait:px2 portrait:py-2">
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


          <div
            class="p-6 space-y-8 bg-[var(--background-color)] min-h-screen">
            <!-- 
      ==========================================================================================================================================
      =                                                     KPI Cards Starts Here                                                          =
      ==========================================================================================================================================
    -->
            <section
              aria-label="Store Performance Summary"
              class="grid portrait:grid-cols-1 sm:grid-cols-3 md:grid-cols-1 landscape:grid-cols-3 gap-6">
              <article class="glass-card bg-[var(--background-color)] p-4 rounded-lg shadow flex items-center space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg"
                  class="h-10 w-10 text-[var(--text-color)]"
                  viewBox="0 -960 960 960"
                  fill="currentColor">
                  <path d="M320-414v-306h120v306l-60-56-60 56Zm200 60v-526h120v406L520-354ZM120-216v-344h120v224L120-216Zm0 98 258-258 142 122 224-224h-64v-80h200v200h-80v-64L524-146 382-268 232-118H120Z" />
                </svg>
                <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Sales</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="salesAmount">
                    ₱ 0
                  </h3>
                </div>
              </article>


              <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-10 h-10 text-[var(--text-color)]"
                  viewBox="0 -960 960 960"
                  fill="currentColor">
                  <path d="m691-150 139-138-42-42-97 95-39-39-42 43 81 81ZM240-600h480v-80H240v80ZM720-40q-83 0-141.5-58.5T520-240q0-83 58.5-141.5T720-440q83 0 141.5 58.5T920-240q0 83-58.5 141.5T720-40ZM120-80v-680q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v267q-19-9-39-15t-41-9v-243H200v562h243q5 31 15.5 59T486-86l-6 6-60-60-60 60-60-60-60 60-60-60-60 60Zm120-200h203q3-21 9-41t15-39H240v80Zm0-160h284q38-37 88.5-58.5T720-520H240v80Zm-40 242v-562 562Z" />
                </svg>
                <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Transactions</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="transactions">
                    0
                  </h3>
                </div>
              </article>


              <article class="glass-card bg-[var(--background-color)] rounded-lg shadow flex items-center space-x-4 p-4">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-10 h-10 text-[var(--text-color)]"
                  viewBox="0 0 24 24"
                  fill="currentColor">
                  <path d="M8 2a1 1 0 0 0-1 1v1H5a1 1 0 0 0-.99 1.14l1.75 14A2 2 0 0 0 7.74 21h8.52a2 2 0 0 0 1.98-1.86l1.75-14A1 1 0 0 0 19 4h-2V3a1 1 0 0 0-1-1H8Zm1 2h6v1H9V4Zm-2 3h10l-1.5 12h-7L7 7Zm2.5 3a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1Zm5 0a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1ZM9 14a.75.75 0 1 1 0 1.5A.75.75 0 0 1 9 14Zm6 0a.75.75 0 1 1 0 1.5A.75.75 0 0 1 15 14Z" />
                </svg>
                <div>
                  <p class="text-sm text-[var(--text-color)]">Daily Product Sold</p>
                  <h3 class="text-3xl font-bold text-[var(--text-color)] mt-1" id="itemsSold">
                    0
                  </h3>
                </div>
              </article>

            </section>
            <!-- 
      ==========================================================================================================================================
      =                                                     KPI Cards Ends Here                                                          =
      ==========================================================================================================================================
    -->



            <!-- 
      ==========================================================================================================================================
      =                                                     Visual Analytics Starts Here                                                          =
      ==========================================================================================================================================
    -->

            <section
              class="bg-[var(--background-color)] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[2fr_1fr] gap-4 mb-8">
              <!--Sales Overview Starts here-->
              <section
                class="bg-[var(--background-color)] space-y-6"
                aria-label="Charts and Graphs">
                <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-[var(--text-color)]"
                        viewBox="0 -960 960 960"
                        fill="currentColor">
                        <path d="m136-240-56-56 296-298 160 160 208-206H640v-80h240v240h-80v-104L536-320 376-480 136-240Z" />
                      </svg>
                      Weekly Sales Overview
                    </h3>


                  </div>

                  <canvas id="ovSalesChart" height="100"></canvas>
                </article>
                <!--Sales Overview Ends here-->


                <!-- Top selling Products charts Starts HERE -->
                <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6">
                  <div class="flex justify-between items-center mb-4">
                    <h3 class="flex items-center gap-2 text-lg font-semibold text-[var(--text-color)]">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-[var(--text-color)]"
                        viewBox="0 -960 960 960"
                        fill="currentColor">
                        <path d="M160-160v-320h160v320H160Zm240 0v-640h160v640H400Zm240 0v-440h160v440H640Z" />
                      </svg>
                      Daily Top-Selling Products
                    </h3>

                  </div>

                  <canvas id="topProductsChart" height="100"></canvas>
                </article>

              </section>
              <!-- Top-Seling ends Here -->


              <!-- Payment Method Starts here -->
              <div class="space-y-6">
                <article class="glass-card bg-[var(--glass-bg)] rounded-xl shadow p-6 ">
                  <div class="flex items-center justify-between mb-4">
                    <h3 class="flex items-center gap-2 text-base font-semibold text-[var(--text-color)]">
                      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[var(--text-color)]" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="M880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720Zm-720 80h640v-80H160v80Zm0 160v240h640v-240H160Zm0 240v-480 480Z" />
                      </svg>
                      Daily Payment Method Breakdown
                    </h3>
                  </div>

                  <canvas id="paymentChart" height="100"></canvas>
                </article>

                <!-- Payment method ends here -->

              </div>


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
        <section
          id="salesReports"
          class=" bg-[var(--background-color)] rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] sm:px-6 py-4 rounded-t-lg">
            <div
              class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-600">
                  Sales Reports
                </h2>
                <p class="text-sm sm:text-base text-gray-600 mt-1">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>


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
        <section id="performanceTrend" class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Performance Trends</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>
          <h3 class="text-xl font-semibold mb-2">Performace Trends</h3>
          <p>
            // performance trend dito naman yung graph or charts ng sales per
            day, week, month, and year
          </p>
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
        <section id="refund" class="rounded-lg shadow">
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
          <section class="mx-auto max-w-3xl px-4 py-10">
            <form id="refundForm" class="rounded-2xl bg-white border border-gray-200 p-8 shadow-lg space-y-8 transition-all hover:shadow-xl">

              <!-- ============================== Transaction Reference ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Transaction Reference
                </legend>
                <p class="text-sm text-gray-500">Link this refund record to an existing transaction.</p>

                <label class="block mt-1">
                  <span class="block text-sm font-medium text-gray-700">
                    Transaction ID <span class="text-red-500">*</span>
                  </span>
                  <input
                    type="number"
                    name="REG_TRANSACTION_ID"
                    required
                    placeholder="Enter Transaction ID"
                    class="w-full mt-1 border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                  <small id="txnError" class="hidden text-red-500 text-sm mt-1">Invalid transaction number.</small>
                </label>
              </fieldset>

              <!-- ============================== Refund Reason ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Reason for Refund
                </legend>
                <p class="text-sm text-gray-500">Select the main reason for this refund.</p>

                <div class="grid gap-3 sm:grid-cols-2">
                  <!-- Peer-based radio options -->
                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Customer Cancelled" class="peer hidden" required />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Customer Cancelled
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Wrong Order" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Wrong Order
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Product Defect" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Product Defect
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Overcharge" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Overcharge
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Staff Error" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Staff Error
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Expired Item" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Expired Item
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Others" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Others
                    </div>
                  </label>
                </div>
              </fieldset>

              <!-- ============================== Additional Notes ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Additional Notes
                </legend>
                <p class="text-sm text-gray-500">Optional: clarify refund details.</p>
                <textarea
                  name="notes"
                  rows="3"
                  placeholder="e.g., Wrong item delivered, customer requested refund..."
                  class="w-full border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
              </fieldset>

              <!-- ============================== Submit Button ============================== -->
              <div class="flex items-center justify-end pt-6">
                <button
                  type="submit"
                  class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-red-500">
                  Log as Refund
                </button>
              </div>
            </form>
          </section>



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
      =                                                     Stock Entry Starts Here                                                            =
      ==========================================================================================================================================
    -->
        <section id="stockEntry" class="bg-[var(--background-color)] text-[var(--text-color)] rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">New Inventory Entry</h2>
                <p class="text-sm text-gray-500">
                  Add newly received items to your inventory and set their initial stock levels.
                </p>

              </div>
            </div>
          </header>
          <?php
          try {
            // Inventory categories
            $invCatStmt = $conn->query("SELECT inv_category_id, category_name FROM inventory_category ORDER BY category_name ASC");
            $invCategories = $invCatStmt->fetchAll(PDO::FETCH_ASSOC);

            // Product categories
            $prodCatStmt = $conn->query("SELECT category_id, category_name FROM category ORDER BY category_name ASC");
            $prodCategories = $prodCatStmt->fetchAll(PDO::FETCH_ASSOC);

            // Products
            $productStmt = $conn->query("SELECT product_id, product_name FROM product_details ORDER BY product_name ASC");
            $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
          } catch (PDOException $e) {
            $categories = [];
          }
          ?>

          <section class="flex justify-center p-4 sm:p-6">
            <div class="w-full max-w-6xl bg-white shadow-md rounded-2xl p-4 sm:p-6 border border-gray-200">
              <!-- Header -->
              <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-5">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800">Inventory Products</h2>
                <button
                  id="openModalBtn"
                  class="rounded-lg bg-green-600 text-white px-5 py-2 text-sm font-semibold shadow hover:bg-green-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-green-400">
                  Add Inventory Items
                </button>
              </div>


              <!-- Table -->
              <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-left">
                  <thead class="bg-green-700 text-white">
                    <tr>
                      <th class="px-4 py-3 font-medium">Product</th>
                      <th class="px-4 py-3 font-medium">Category</th>
                      <th class="px-4 py-3 font-medium">Stock</th>
                      <th class="px-4 py-3 font-medium">Status</th>
                      <th class="px-4 py-3 font-medium">Date Made</th>
                      <th class="px-4 py-3 font-medium">Expiry Date</th>
                      <th class="px-4 py-3 font-medium">Added By</th>
                    </tr>
                  </thead>
                  <tbody id="inventoryTableBody" class="divide-y divide-gray-200 text-gray-700">
                    <!-- Data dynamically loaded via JS -->
                  </tbody>
                </table>
              </div>

            </div>


            <!-- Modal -->
            <div id="inventoryModal"
              class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
              <div
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md p-6 animate-[fadeIn_0.3s_ease]">
                <h2 class="text-lg sm:text-xl font-bold mb-4 text-center text-gray-800">Receive Inventory</h2>

                <form id="inventoryForm" class="space-y-4">
                  <!-- Item Name -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="item_name">Item Name</label>
                    <input type="text" id="item_name" required class="w-full p-2 border rounded-lg" />
                  </div>

                  <!-- Inventory Category -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="inv_category">Inventory Category</label>
                    <select id="inv_category" required class="w-full p-2 border rounded-lg">
                      <option value="" disabled selected>Select inventory category</option>
                      <?php foreach ($invCategories as $cat): ?>
                        <option value="<?= $cat['inv_category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Product Category -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="prod_category">Product Category</label>
                    <select id="prod_category" class="w-full p-2 border rounded-lg">
                      <option value="" disabled selected>Select product category</option>
                      <?php foreach ($prodCategories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Product -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="product">Product</label>
                    <select id="product" class="w-full p-2 border rounded-lg">
                      <option value="" disabled selected>Select product</option>
                      <?php foreach ($products as $prod): ?>
                        <option value="<?= $prod['product_id'] ?>"><?= htmlspecialchars($prod['product_name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <!-- Quantity & Unit -->
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1" for="quantity">Quantity</label>
                      <input type="number" id="quantity" required min="1" class="w-full p-2 border rounded-lg" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1" for="unit">Unit</label>
                      <select id="unit" class="w-full p-2 border rounded-lg">
                        <option value="pcs">Pieces (pcs)</option>
                        <option value="kg">Kilograms (kg)</option>
                        <option value="g">Grams (g)</option>
                        <option value="l">Liters (L)</option>
                        <option value="ml">Milliliters (ml)</option>
                      </select>
                    </div>
                  </div>
                  <!-- Date Made & Expiry -->
                  <div class="grid grid-cols-2 gap-3">
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1" for="date_made">Date Made</label>
                      <input type="date" id="date_made" required class="w-full p-2 border rounded-lg" />
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-1" for="date_expiry">Expiry Date</label>
                      <input type="date" id="date_expiry" required class="w-full p-2 border rounded-lg" />
                    </div>
                  </div>


                  <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModalBtn" class="bg-gray-200 px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Save</button>
                  </div>
                </form>

              </div>
            </div>
          </section>







        </section>
        <!-- 
      ==========================================================================================================================================
      =                                                     Stock Entry Ends Here                                                              =
      ==========================================================================================================================================
    -->
        <!-- 
      ==========================================================================================================================================
      =                                                     Restock Starts Here                                                            =
      ==========================================================================================================================================
    -->

        <section id="stockLevel" class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Restock</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>

          <div id="inventoryContainer" class="p-6 space-y-6"></div>
          <script>
            async function loadInventory() {
              const container = document.getElementById("inventoryContainer");
              container.innerHTML = "<p>Loading...</p>";

              try {
                const res = await fetch("../../app/includes/managerModule/managerStockManagementRestock.php");
                const data = await res.json();

                if (!data.success) throw new Error(data.error);

                container.innerHTML = "";

                data.data.forEach(cat => {
                  const categoryBlock = document.createElement("div");
                  categoryBlock.className = "border rounded-lg shadow-sm p-4";

                  const itemsList = cat.items.map(item => `
        <div class="flex justify-between items-center border-b py-2">
          <div>
            <p class="font-medium">${item.item_name}</p>
            <p class="text-sm text-gray-500">
              Qty: ${item.quantity} ${item.unit} | ${item.status}
            </p>
            <p class="text-xs text-gray-400">
              Made: ${item.date_made} | Exp: ${item.date_expiry}
            </p>
          </div>
          <button 
            onclick="openRestockForm(${item.item_id}, '${item.item_name}')"
            class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
            + Add Stock
          </button>
        </div>
      `).join("");

                  categoryBlock.innerHTML = `
        <h3 class="text-lg font-semibold mb-2 border-b pb-2">${cat.category_name}</h3>
        <div class="space-y-2">${itemsList || "<p class='text-sm text-gray-500'>No items yet.</p>"}</div>
      `;

                  container.appendChild(categoryBlock);
                });
              } catch (err) {
                container.innerHTML = `<p class='text-red-500'>${err.message}</p>`;
              }
            }

            function openRestockForm(itemId, itemName) {
              Swal.fire({
                title: `Restock ${itemName}`,
                input: 'number',
                inputLabel: 'Enter quantity to add',
                inputAttributes: {
                  min: 1,
                  step: 'any'
                },
                showCancelButton: true,
                confirmButtonText: 'Add Stock',
                preConfirm: async (qty) => {
                  if (!qty || qty <= 0) {
                    Swal.showValidationMessage("Please enter a valid quantity");
                    return false;
                  }

                  const res = await fetch("../../app/includes/managerModule/managerStockManagementRestock.php", {
                    method: "POST",
                    headers: {
                      "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                      item_id: itemId,
                      quantity: qty
                    })
                  });

                  const result = await res.json();
                  if (!result.success) throw new Error(result.message);
                  return result;
                }
              }).then(result => {
                if (result.isConfirmed) {
                  Swal.fire("Success", "Stock updated successfully!", "success");
                  loadInventory();
                }
              });
            }

            loadInventory();
          </script>

        </section>

        <!-- 
      ==========================================================================================================================================
      =                                                     Restock Ends Here                                                              =
      ==========================================================================================================================================
    -->




        <!-- 
      ==========================================================================================================================================
      =                                                     Low Stock Alert Starts Here                                                        =
      ==========================================================================================================================================
    -->
        <section id="lowStockAlerts" class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Low Stock Alert</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>
          <h3 class="text-xl font-semibold mb-2">Refund</h3>
          <p>
            // low stocks history lalabas lang rito charts for analytics views
            ng mga critical stocks
          </p>
        </section>
        <!-- 
      ==========================================================================================================================================
      =                                                     Low Stock Alert Ends Here                                                          =
      ==========================================================================================================================================
    -->

        <!-- 
      ==========================================================================================================================================
      =                                                    Stock Movement History Starts Here                                                  =
      ==========================================================================================================================================
    -->
        <section
          id="stocksMovementHistory"
          class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Soon to Expired Products</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>
          <h3 class="text-xl font-semibold mb-2">Refund</h3>
          <p>
            // movement hist. dito naman analytics view lang ng galaw ng
            stocks
          </p>
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
        <section id="registerStaff" class="bg-[var(--background-color)] text-[var(--text-color)]">
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
        <section id="modifyPosition" class="bg-[var(--background-color)]">
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
          <div class="flex items-center justify-center p-3 ">
            <div class="glass-card rounded-3xl shadow-2xl p-5">
              <div
                class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <img
                  src="../assets/SVG/LOGO/BLOGO.svg"
                  alt="Logo Icon"
                  class="h-20 w-35 theme-logo" />
              </div>
              <h2
                class="text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
                Add Staff Role
              </h2>
              <p
                class="text-sm lg:text-base text-center text-[var(--text-color)] mb-4">
                Select a Staff member and update their position
              </p>

              <form class="space-y-6 sm:space-x-8" action="#" method="POST">
                <div class="space-y-2">
                  <label
                    for="staffSelect"
                    class="flex items-center gap-2 text-sm lg:text-base font-semibold text-[var(--text-color)]">
                    <svg
                      class="w-4 h-4 lg:w-5 lg:h-5 text-[var(--text-color)]"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Select Staff Member
                  </label>
                  <div class="relative">
                    <select
                      id="staffSelect"
                      name="staff_id"
                      required
                      class="w-full mt-1 px-4 py-4 border rounded-2xl glass-card text-sm lg:text-base focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition-all duration-300 hover:shadow-md appearance-none pr-12">
                      <option value="">Choose Staff Member</option>
                      <!-- Example: populate with staff_info  iintegrate kopa to sa db -->
                      <option value="1">Zenatnom HsoJ</option>
                      <option value="2">JDM Bakery</option>
                    </select>
                    <div
                      class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                      <svg
                        class="w-4 h-4 text-[var(--text-color)]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                      </svg>
                    </div>
                  </div>
                </div>

                <div class="space-y-2">
                  <label
                    for="currentRole"
                    class="flex items-center gap-2 text-sm lg:text-base font-medium text-[var(--text-color)]">
                    <svg
                      class="w-4 h-4 lg:w-5 lg:h-5 text-[var(--text-color)]"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0H8m8 0v2a2 2 0 002 2M8 6a2 2 0 002 2m0 0h4m-4 0a2 2 0 00-2-2V4" />
                    </svg>Current Role</label>
                  <input
                    type="text"
                    id="currentRole"
                    name="current_role"
                    class="w-full px-4 py-4 mt-1 lg:py-5 border rounded-2xl glass-card text-[var(--text-color)] text-sm lg:text-base focus:outline-none cursor-not-allowed"
                    readonly
                    placeholder="Auto-filled when staff selected" />
                </div>

                <div class="space-y-2">
                  <label
                    for="addRoleBarista"
                    class="flex items-center gap-2 text-sm lg:text-base font-semibold text-[var(--text-color)]">
                    <svg
                      class="w-4 h-4 lg:w-5 lg:h-5 text-green-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 11l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                    New Role
                  </label>

                  <section
                    class="grid grid-cols-1 sm:grid-cols-3 lg:gap-4 gap-3">
                    <!-- 
      ==========================================================================================================================================
      =                                                     Radio Barista - Starts Here                                                        =
      ==========================================================================================================================================
    -->
                    <div class="rolePositionBarista">
                      <input
                        type="radio"
                        id="addRoleBarista"
                        name="addRole"
                        value="barista"
                        class="hidden peer" />
                      <label
                        for="addRoleBarista"
                        class="block p-4 glass-card border-gray-200 rounded-2xl cursor-pointer hover:border-orange-400 hover:shadow-lg peer-checked:!border-orange-500 peer-checked:!bg-orange-500/20 peer-checked:!shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <div
                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                          <svg
                            class="w-6 h-6 text-[var(--text-color)]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 8h12a2 2 0 012 2v3a6 6 0 01-6 6H9a6 6 0 01-6-6v-3a2 2 0 012-2z" />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M19 11h1a2 2 0 010 4h-1" />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 4c0 .5.5 1 1 1s1-.5 1-1-.5-1-1-1-1 .5-1 1zm4 0c0 .5.5 1 1 1s1-.5 1-1-.5-1-1-1-1 .5-1 1z" />
                          </svg>
                        </div>
                        <h3
                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                          Barista
                        </h3>
                        <p
                          class="text-xs text-center text-[var(--text-color)] mt-1">
                          Prepares Drinks.
                        </p>
                      </label>
                    </div>

                    <!-- 
      ==========================================================================================================================================
      =                                                     Radio Barista - Ends Here                                                          =
      ==========================================================================================================================================
    -->

                    <!-- 
      ==========================================================================================================================================
      =                                                     Radio Cashier - Starts Here                                                        =
      ==========================================================================================================================================
    -->
                    <div class="rolePositionCashier">
                      <input
                        type="radio"
                        id="addRoleCashier"
                        name="addRole"
                        value="cashier"
                        class="hidden peer" />
                      <label
                        for="addRoleCashier"
                        class="block p-4 glass-card border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-green-400 hover:shadow-lg peer-checked:!border-green-500 peer-checked:!bg-green-500/20 peer-checked:!shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div
                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                          <svg
                            class="w-6 h-6 text-[var(--text-color)]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle
                              cx="12"
                              cy="6"
                              r="3"
                              stroke="currentColor"
                              stroke-width="1.5" />
                            <rect
                              x="4"
                              y="14"
                              width="16"
                              height="6"
                              rx="1"
                              stroke="currentColor"
                              stroke-width="1.5" />
                            <rect
                              x="16"
                              y="11"
                              width="3"
                              height="3"
                              rx="0.5"
                              stroke="currentColor"
                              stroke-width="1.5" />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 14v-2a3 3 0 016 0v2" />
                          </svg>
                        </div>
                        <h3
                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                          Cashier
                        </h3>
                        <p
                          class="text-xs text-center text-[var(--text-color)] mt-1">
                          Sales and Transactions
                        </p>
                      </label>
                    </div>
                    <!-- 
      ==========================================================================================================================================
      =                                                     Radio Cashier - Ends Here                                                          =
      ==========================================================================================================================================
    -->

                    <!-- 
      ==========================================================================================================================================
      =                                                     Manager  Position Starts Here                                                        =
      ==========================================================================================================================================
    -->
                    <div class="addRoleManager">
                      <input
                        type="radio"
                        id="addRoleManager"
                        name="addRole"
                        value="manager"
                        class="hidden peer" />
                      <label
                        for="addRoleManager"
                        class="block p-4 glass-card border-2 border-gray-200 rounded-2xl cursor-pointer hover:border-purple-400 hover:shadow-lg peer-checked:!border-purple-500 peer-checked:!bg-purple-500/20 peer-checked:!shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div
                          class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl mx-auto mb-3 flex items-center justify-center shadow-md">
                          <svg
                            class="w-6 h-6 text-[var(--text-color)]"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="6" r="3" />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M6 20v-2a6 6 0 0112 0v2H6z" />
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v2l-1 1 1 1 1-1-1-1V9z" />
                          </svg>
                        </div>
                        <h3
                          class="font-bold text-center text-[var(--text-color)] text-sm lg:text-base">
                          Manager
                        </h3>
                        <p
                          class="text-xs text-center text-[var(--text-color)] mt-1">
                          Supervises Staff
                        </p>
                      </label>
                    </div>
                  </section>
                </div>
                <div class="space-y-3">
                  <label
                    for="reason"
                    class="flex items-center gap-2 text-sm lg:text-base font-medium text-[var(--text-color)]">
                    <svg
                      class="w-4 h-4 lg:w-5 lg:h-5 text-yellow-600"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>

                    Reason for Change
                    <span class="text-[var(--text-color)] text-xs">(optional)</span>
                  </label>
                  <textarea
                    id="reason"
                    name="reason"
                    rows="2"
                    class="w-full px-4 py-4 lg:py-5 border glass-card placeholder-[var(--text-color)] border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition-all duration-200 hover:shadow-md resize-none text-sm lg:text-base"
                    placeholder="Add a note or reason for adding new role"></textarea>
                </div>

                <button
                  id="modifySubmitBtn"
                  name="submit"
                  type="submit"
                  class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-500/50 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl focus:outline-none 
                  f:translate-y-0 relative overflow-hidden group animate-fade-in delay-300 animation-fill-both text-sm sm:text-base lg:text-lg">
                  <div
                    class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                  Update Role
                  <svg
                    id="modifyLoadingSpinner"
                    class="hidden absolute right-4 top-1/2 -translate-y-1/2 animate-spin size-5 text-white"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle
                      class="opacity-25"
                      cx="12"
                      cy="12"
                      r="10"
                      stroke="currentColor"
                      stroke-width="4"></circle>
                    <path
                      class="opacity-75"
                      fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </button>
              </form>
            </div>
          </div>
          <script>
            document.getElementById('staffStatusForm').addEventListener('submit', async function(e) {
              e.preventDefault(); // prevent page reload

              const form = e.target;
              const formData = new FormData(form);
              const statusMessage = document.getElementById('statusMessage');

              statusMessage.textContent = 'Updating...';

              try {
                const response = await fetch('../../app/includes/managerModule/managerUpdateStaffStatus.php', {
                  method: 'POST',
                  body: formData
                });

                const result = await response.json();

                if (result.status === 'success') {
                  statusMessage.textContent = result.message;
                  statusMessage.className = 'text-green-500 text-center mt-3';
                } else {
                  statusMessage.textContent = result.message;
                  statusMessage.className = 'text-red-500 text-center mt-3';
                }
              } catch (error) {
                statusMessage.textContent = 'Error: ' + error.message;
                statusMessage.className = 'text-red-500 text-center mt-3';
              }
            });
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
        <section id="modifyStatus" class="bg-[var(--background-color)]">
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

          <div class="flex justify-center p-4 sm:p-6 lg:p-10 bg-[var(--bg-color)]">
            <form id="staffStatusForm" class="glass-card w-full sm:w-[90%] md:w-[70%] lg:w-[50%] rounded-2xl shadow-lg p-6 sm:p-8 lg:p-10 transition-all">
              <!-- Logo -->
              <div class="w-16 h-16 mx-auto mb-4 flex items-center justify-center">
                <img src="../assets/SVG/LOGO/BLOGO.svg" alt="Logo Icon" class="h-16 w-auto theme-logo" />
              </div>

              <!-- Header -->
              <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center text-[var(--text-color)] mb-2">
                Modify Staff Status
              </h2>
              <p class="text-[var(--text-color)] text-xs sm:text-sm lg:text-base font-medium text-center mb-4">
                Update or deactivate an employee account.
              </p>

              <!-- Staff ID -->
              <fieldset class="space-y-3">
                <legend class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Staff Identification
                </legend>
                <p class="text-xs sm:text-sm text-gray-500">Provide the staff ID to locate their record.</p>

                <label class="block mt-1">
                  <span class="block text-sm font-medium text-gray-700">
                    Staff ID <span class="text-red-500">*</span>
                  </span>
                  <input
                    type="text"
                    name="staffID"
                    id="staffID"
                    maxlength="30"
                    required
                    placeholder="Enter Staff ID Number"
                    class="w-full mt-1 border rounded-lg border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-gray-800 text-sm sm:text-base focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                </label>
                <p id="staffIDFeedback" class="text-sm text-gray-500"></p>
              </fieldset>

              <!-- Status -->
              <fieldset class="space-y-3 mt-6">
                <legend class="text-base sm:text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Update Staff Status
                </legend>
                <p class="text-xs sm:text-sm text-gray-500">Choose the new status for this staff account.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                  <!-- Active -->
                  <label class="cursor-pointer">
                    <input type="radio" name="staffStatus" value="active" class="peer hidden" required />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 sm:py-4 text-gray-700 text-sm sm:text-base peer-checked:border-green-500 peer-checked:ring-2 peer-checked:ring-green-400 peer-checked:bg-green-50 transition">
                      <div class="flex items-center gap-2 font-semibold">
                        Active
                      </div>
                    </div>
                  </label>

                  <!-- Inactive -->
                  <label class="cursor-pointer">
                    <input type="radio" name="staffStatus" value="inactive" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 sm:py-4 text-gray-700 text-sm sm:text-base peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-400 peer-checked:bg-red-50 transition">
                      <div class="flex items-center gap-2 font-semibold">
                        Inactive
                      </div>
                    </div>
                  </label>
                </div>
              </fieldset>

              <!-- Hidden Manager -->
              <input type="hidden" name="manager_account" />

              <!-- Submit -->
              <div class="flex pt-6">
                <button type="submit" name="submit" id="submitBtn" class="w-full rounded-lg bg-indigo-600 px-6 py-2.5 text-sm sm:text-base font-semibold text-white shadow hover:bg-indigo-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-indigo-500">
                  Update Status
                </button>
              </div>

              <!-- Message -->
              <p id="statusMessage" class="text-center text-sm mt-3"></p>
            </form>
          </div>


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
        <section id="logWaste" class="rounded-lg shadow">
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
          <section class="mx-auto max-w-3xl px-4 py-10">
            <form
              id="wasteForm"
              method="POST"
              class="rounded-2xl bg-white border border-gray-200 p-8 shadow-lg space-y-8 transition-all hover:shadow-xl">

              <!-- ============================== Transaction Reference ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Transaction Reference
                </legend>
                <p class="text-sm text-gray-500">
                  Link this waste record to an existing transaction.
                </p>

                <label class="block mt-1">
                  <span class="block text-sm font-medium text-gray-700">
                    Transaction ID <span class="text-red-500">*</span>
                  </span>
                  <input
                    type="number"
                    name="REG_TRANSACTION_ID"
                    required
                    placeholder="Enter Transaction ID"
                    class="w-full mt-1 border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition" />
                </label>
              </fieldset>

              <!-- ============================== Reason for Waste ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Reason for Waste
                </legend>
                <p class="text-sm text-gray-500">Select the main reason for this waste.</p>

                <div class="grid gap-3 sm:grid-cols-2">
                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Wrong Order" class="peer hidden" required />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Wrong Order
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Customer Cancelled" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Customer Cancelled
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Staff Error" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Staff Error
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Expired" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Expired
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Free Drink / Complimentary" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Free Drink / Complimentary
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Product Test" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Product Test
                    </div>
                  </label>

                  <label class="cursor-pointer">
                    <input type="radio" name="reason" value="Others" class="peer hidden" />
                    <div class="rounded-lg border border-gray-300 px-3 py-3 text-gray-700 peer-checked:border-indigo-500 peer-checked:ring-2 peer-checked:ring-indigo-400 peer-checked:bg-indigo-50 transition">
                      Others
                    </div>
                  </label>
                </div>
              </fieldset>

              <!-- ============================== Additional Notes ============================== -->
              <fieldset class="space-y-3">
                <legend class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                  <span class="h-4 w-1 bg-indigo-500 rounded"></span>
                  Additional Notes
                </legend>
                <p class="text-sm text-gray-500">Optional: clarify the waste transaction.</p>
                <textarea
                  name="notes"
                  rows="3"
                  placeholder="e.g., Customer changed order after payment, item already prepared..."
                  class="w-full border rounded-lg border-gray-300 px-4 py-2.5 text-gray-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
              </fieldset>

              <!-- ============================== Submit Button ============================== -->
              <div class="flex items-center justify-end pt-6">
                <button
                  type="submit"
                  name="logWaste"
                  class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-red-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-red-500">
                  Log as Waste
                </button>
              </div>

              <!-- Message Container -->
              <p id="wasteMessage" class="text-center text-sm mt-3"></p>
            </form>
          </section>

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
        <section id="disableProduct" class="bg-[var(--background-color)] rounded-lg shadow">
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
          <section class="flex justify-center items-center h-screen">
            <div class="w-full h-screen p-4">
              <div
                id="menuContainer"
                class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-3 landscape:col-span- portrait:active:cursor-grabbing">
                <fieldset
                  id="orderCategory"
                  class="flex flex-wrap justify-around items-center"
                  aria-label="Order Categories">
                  <legend class="sr-only">Choose a Category</legend>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="milktea_module"
                      name="module"
                      class="hidden peer"
                      checked
                      onclick="showModuleDisableProduct('milktea')" />
                    <label
                      for="milktea_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 2l-4 2" />
                        <path d="M12 2v3" />
                        <path d="M5 7h14" />
                        <path
                          d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
                        <path d="M7 12h10" />
                        <circle
                          cx="9"
                          cy="16.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                        <circle
                          cx="12"
                          cy="17.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                        <circle
                          cx="15"
                          cy="16.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">MILK TEA</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="fruittea_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('fruittea')" />
                    <label
                      for="fruittea_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                        <path d="M5 7h14" />
                        <path d="M12 2v5" />
                        <path d="M7 12h10" />
                        <circle cx="16.5" cy="15.5" r="2" />
                        <path d="M16.5 13.5v4" />
                        <path d="M14.5 15.5h4" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">
                        FRUIT TEA
                      </p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="hotbrew_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('hotbrew')" />
                    <label
                      for="hotbrew_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z" />
                        <path d="M16 10h1a3 3 0 0 1 0 6h-1" />
                        <path d="M9 2v3" />
                        <path d="M13 2v3" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">HOT BREW</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="icedcoffee_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('icedcoffee')" />
                    <label
                      for="icedcoffee_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                        <path d="M6 7h12" />
                        <path d="M12 2v5" />
                        <rect x="9" y="11" width="2.5" height="2.5" />
                        <rect x="12.5" y="14" width="2.5" height="2.5" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">
                        ICED COFFEE
                      </p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="praf_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('praf')" />
                    <label
                      for="praf_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z" />
                        <path d="M6 9c0-3 3-5 6-5s6 2 6 5" />
                        <path d="M12 4V2" />
                        <path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">PRAF</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="promos_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('promos')" />
                    <label
                      for="promos_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                        <path d="M6 7h12" />
                        <path d="M12 2v5" />
                        <polygon
                          points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">PROMOS</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="brosty_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleDisableProduct('brosty')" />
                    <label
                      for="brosty_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2" />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 5l2 4" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">BROSTY</p>
                    </label>
                  </div>
                </fieldset>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milktea Section - Starts Here                                                        -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="milktea" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Milk Tea Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="milkteaMenu">
                    <?php
                    $category_id = 1; // MilkTea
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milktea Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruity Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="fruittea" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Fruit Tea Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="fruitTeaMenu">
                    <?php
                    $category_id = 2; // Fruit Tea
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruity Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Starts Here                                                       -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="hotbrew" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Hot Brew Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="hotBrewMenu">
                    <?php
                    $category_id = 3; // Hotbrew
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Ends Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Starts Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="praf" class="hidden">
                  <hr class="border border-[var(--border-color)] my-5" />

                  <h1
                    id="menuTitle"
                    class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                    Praf Menu
                  </h1>
                  <hr class="border border-[var(--border-color)] my-5" />
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="prafMenu">
                    <?php
                    $category_id = 4; // Praf
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Ends Here                                                             -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Starts Here                                                    -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section
                  id="icedcoffee"
                  class="hidden"
                  aria-labelledby="icedcoffeeTitle">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Iced Coffee Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="icedCoffeeMenu">
                    <?php
                    $category_id = 6; // Iced Coffee
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Ends Here                                                      -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="promos" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Promo Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="promosMenu">
                    <?php
                    $category_id = 7; // Promos
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="brosty" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Brosty Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="brostyMenu">
                    <?php
                    $category_id = 5; // Brosty
                    include "../../app/includes/managerModule/managersFetchEnabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="modify" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Modify
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-ons Section - Starts Here                                                        -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="addOns" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Add-ons
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                </section>
              </div>
            </div>
          </section>
          <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-ons Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
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
        <section id="enableProduct" class="bg-[var(background-color)] rounded-lg shadow">
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
          <section class="flex justify-center h-screen">
            <div class="w-full h-auto p-4">
              <div
                id="menuContainer"
                class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-3 landscape:col-span- portrait:active:cursor-grabbing">
                <fieldset
                  id="orderCategory"
                  class="flex flex-wrap justify-around items-center"
                  aria-label="Order Categories">
                  <legend class="sr-only">Choose a Category</legend>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enableMilktea_module"
                      name="module"
                      class="hidden peer"
                      checked
                      onclick="showModuleEnableProduct('enableMilktea')" />
                    <label
                      for="enableMilktea_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M14 2l-4 2" />
                        <path d="M12 2v3" />
                        <path d="M5 7h14" />
                        <path
                          d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
                        <path d="M7 12h10" />
                        <circle
                          cx="9"
                          cy="16.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                        <circle
                          cx="12"
                          cy="17.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                        <circle
                          cx="15"
                          cy="16.5"
                          r="1"
                          fill="currentColor"
                          stroke="none" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">MILK TEA</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enableFruittea_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enableFruittea')" />
                    <label
                      for="enableFruittea_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                        <path d="M5 7h14" />
                        <path d="M12 2v5" />
                        <path d="M7 12h10" />
                        <circle cx="16.5" cy="15.5" r="2" />
                        <path d="M16.5 13.5v4" />
                        <path d="M14.5 15.5h4" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">
                        FRUIT TEA
                      </p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enableHotbrew_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enableHotbrew')" />
                    <label
                      for="enableHotbrew_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z" />
                        <path d="M16 10h1a3 3 0 0 1 0 6h-1" />
                        <path d="M9 2v3" />
                        <path d="M13 2v3" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">HOT BREW</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enableIcedCoffee_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enableIcedCoffee')" />
                    <label
                      for="enableIcedCoffee_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                        <path d="M6 7h12" />
                        <path d="M12 2v5" />
                        <rect x="9" y="11" width="2.5" height="2.5" />
                        <rect x="12.5" y="14" width="2.5" height="2.5" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">
                        ICED COFFEE
                      </p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enablePraf_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enablePraf')" />
                    <label
                      for="enablePraf_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z" />
                        <path d="M6 9c0-3 3-5 6-5s6 2 6 5" />
                        <path d="M12 4V2" />
                        <path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">PRAF</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enablePromos_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enablePromos')" />
                    <label
                      for="enablePromos_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                        <path d="M6 7h12" />
                        <path d="M12 2v5" />
                        <polygon
                          points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">PROMOS</p>
                    </label>
                  </div>

                  <div class="categoryButtons">
                    <input
                      type="radio"
                      id="enableBrosty_module"
                      name="module"
                      class="hidden peer"
                      onclick="showModuleEnableProduct('enableBrosty')" />
                    <label
                      for="enableBrosty_module"
                      class="w-[120px] h-[90px] m-1 border-2 border-[var(--container-border)] flex flex-col items-center justify-center cursor-pointer p-4 rounded-2xl bg-[vavr(--background-color)] text-[var(--text-color)] shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 mb-2"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2" />
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 5l2 4" />
                      </svg>

                      <p class="font-semibold text-xs sm:text-sm">BROSTY</p>
                    </label>
                  </div>
                </fieldset>

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milk Tea Section - Starts Here                                                       -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enableMilktea" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Milk Tea Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enableMilkteaMenu">
                    <?php
                    $category_id = 1; // MilkTea
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Milk Tea Section - Ends Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruit Tea Section - Starts Here                                                      -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enableFruittea" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Fruit Tea Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enableFruitTeaMenu">
                    <?php
                    $category_id = 2; // Fruit Tea
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Fruit Tea Section - Ends Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Starts Here                                                       -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enableHotbrew" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Hot Brew Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enableHotBrewMenu">
                    <?php
                    $category_id = 3; // Hot Brew
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Hot Brew Section - Ends Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Starts Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enablePraf" class="hidden">
                  <hr class="border border-[var(--border-color)] my-5" />

                  <h1
                    id="menuTitle"
                    class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                    Praf Menu
                  </h1>
                  <hr class="border border-[var(--border-color)] my-5" />
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enablePrafMenu">
                    <?php
                    $category_id = 4; // Praf
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Praf Section - Ends Here                                                             -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Starts Here                                                    -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section
                  id="enableIcedCoffee"
                  class="hidden"
                  aria-labelledby="icedcoffeeTitle">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Iced Coffee Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enableIcedCoffeeMenu">
                    <?php
                    $category_id = 6; // Iced Coffee
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Iced Coffee Section - Ends Here                                                      -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section
                  id="enablePromos"
                  class="hidden"
                  aria-labelledby="PromosTitle">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Promos Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enablePromosMenu">
                    <?php
                    $category_id = 7; // Promos
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Promos Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enableBrosty" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Brosty Menu
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                  <div
                    class="gap-1 mt-2 justify-center items-center text-black "
                    id="enableBrostyMenu">
                    <?php
                    $category_id = 5; // Brosty
                    include "../../app/includes/managerModule/managersFetchDisabledProducts.php";
                    ?>
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Brosty Section - Ends Here                                                           -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Starts Here                                                         -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
                <section id="enableModify" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Modify
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                </section>
                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Modify Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-Ons Section - Starts Here                                                        -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->

                <section id="enableAddOns" class="hidden">
                  <div class="titleContainer">
                    <hr class="border border-[var(--border-color)] my-5" />

                    <h1
                      id="menuTitle"
                      class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
                      Add-ons
                    </h1>
                    <hr class="border border-[var(--border-color)] my-5" />
                  </div>
                </section>
              </div>
            </div>
          </section>
          <!-- 
      ------------------------------------------------------------------------------------------------------------------------------------------
      =                                                   Add-Ons Section - Ends Here                                                          -
      ------------------------------------------------------------------------------------------------------------------------------------------
    -->
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
          class="bg-[var(--background-color)] rounded-lg shadow text-[var(--text-color)]">
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
          <!-- =============================================
                 DASHBOARD CONTENT CONTAINER STARTS HERE
                 ================================================= -->
          <div class="p-4 sm:p-6 sm:space-y-8 bg-[var(--background-color)]">
            <!-- ====================
           Cards starts Here
           ======================= -->
            <section
              class="bg-[var(--background-color)] grid grid-cols-1 xl:grid-cols-2 sm:gap-6"
              aria-label="overview charts">
              <!-- =============================================
               Category's Percentage per sales charts Starts here
               ================================================= -->
              <article
                class="glass-card rounded-xl shadow-lg p-4 sm:p-6 bg-white ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                    Sold Product by Category
                  </h2>
                  <div class="flex items-center gap-2 text-sm text-gray-500">

                  </div>
                </div>
                <div class="relative h-64 sm:h-80">
                  <canvas
                    id="soldProductByCategoryChart"
                    class="max-h-full"></canvas>
                </div>
              </article>

              <!-- =============================================
               Category's Percentage per sales charts Ends here
               ================================================= -->
              <!-- =============================================
               Promos charts Starts here
               ================================================= -->

              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
                    Sold Addons
                  </h2>
                </div>
                <div class="relative h-64 sm:h-80">
                  <canvas id="soldAddOnsChart" class="max-h-full"></canvas>
                </div>
              </article>

              <!-- =============================================
               Promos charts Ends here
               ================================================= -->
            </section>
            <!-- =============================================
               Category charts Starts here
               ================================================= -->
            <section
              class="bg-[var(--background-color)] grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3  sm:gap-6"
              aria-label="Category charts">
              <!-- =============================================
               Iced Coffee charts Starts here
               ================================================= -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Iced Coffee
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srIcedCoffeeChart"></canvas>
                </div>

              </article>

              <!-- =============================================
               Iced Coffee charts Ends here
               ================================================= -->

              <!-- =================================
                      Fruit Tea charts Starts here
               ======================================= -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg sm:p-6 ease-in-out">
                <div
                  class="bg-[var(--background-color)] flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Fruit Tea
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srFruitteaChart"></canvas>
                </div>

              </article>
              <!-- =============================================
               Fruit Tea charts ends here
               ================================================= -->

              <!-- =================================
               Hot Brew charts Starts here
               ======================================= -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Hot Brew
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srHotbrewChart"></canvas>
                </div>

              </article>
              <!-- =============================================
               Hot Brew charts ends here
               ================================================= -->

              <!-- =============================================
               Category charts Starts here
               ================================================= -->

              <!-- =================================
               Praf charts Starts here
               ======================================= -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-amber-600 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Praf
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srPrafChart"></canvas>
                </div>

              </article>
              <!-- =============================================
               Praf charts ends here
               ================================================= -->

              <!-- =========================================
             Milktea charts Starts Here
             ============================================== -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 fade-in">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Milk Tea
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srMilkteaChart"></canvas>
                </div>

              </article>

              <!-- =================================
                       Milktea charts ends here
               ======================================= -->

              <!-- =============================================
               Brosty charts Starts here
               ================================================= -->
              <article
                class="glass-card bg-[var(--glass-bg)] rounded-xl shadow-lg p-4 sm:p-6 ease-in-out">
                <div
                  class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-blue-300 rounded-full"></div>
                    <h3
                      class="text-base sm:text-lg font-semibold text-gray-800">
                      Iced Coffee
                    </h3>
                  </div>

                </div>
                <div class="relative h-[300px] w-full sm:h-64">
                  <canvas id="srBrostyChart"></canvas>
                </div>

              </article>

              <!-- =============================================
               Brosty charts Ends here
               ================================================= -->
            </section>

            <!-- =============================================
               Praf/Iced Coffee charts Ends here
               ================================================= -->
          </div>
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
        <section
          id="satisfactionDashboard"
          class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Satisfaction Dashboard</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>
          <h3 class="text-xl font-semibold mb-2">Refund</h3>
          <p>
            // satisfaction dashboard dito naman more on overview ng feedbacks
            like chat box graphs pie charts etc.
          </p>
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
        <section id="complaintsManagement" class="bg-white rounded-lg shadow">
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
          class="bg-white rounded-lg shadow">
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
        <section id="discountDashboard" class="bg-white rounded-lg shadow">
          <header
            class="shadow-sm border-b border-[var(--border-color)] px-6 py-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold">Discount Dashboard</h2>
                <p class="text-sm text-gray-600">
                  Welcome back, here's what's happening with your store today.
                </p>
              </div>
            </div>
          </header>
          <h3 class="text-xl font-semibold mb-2">Refund</h3>
          <p>
            // discount dashboard dito naman lalabas yung mga senoirs and PWD
            transactions
          </p>
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