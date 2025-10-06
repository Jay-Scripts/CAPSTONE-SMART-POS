<?php
include "../../app/config/dbConnection.php";

session_start();
$userId = $_SESSION['staff_id'] ?? null;


if (!isset($_SESSION['staff_name'])) {
  header("Location: ../auth/barista/baristaLogin.php");
  exit;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BVS</title>
  <link
    rel="shortcut icon"
    href="../assets/favcon/bvs.ico"
    type="image/x-icon" />

  <!--  linked css below for animations purpose -->
  <link href="../css/style.css" rel="stylesheet" />
  <!--  linked css below for tailwind dependencies to work ofline -->
  <link href="../css/output.css" rel="stylesheet" />
  <!--  linked script below cdn of tailwind for online use -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->


  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">




</head>

<body class="bg-[var(--background-color)] text-white font-sans min-h-screen">

  <header
    class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
    <h1
      class="text-2xl flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
      <span class="flex items-center">
        <img
          src="../assets/SVG/LOGO/BLOGO.svg"
          class="h-[3rem] theme-logo m-1"
          alt="Module Logo" />
        Barista View System
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
      <!-- Profile Dropdown Example -->
      <div class="flex justify-end p-4 text-[var(--text-color)]">
        <span>
          <!-- Example SVG Button -->
          <button class="flex flex-col items-center justify-center gap-2 bg-[var(--calc-bg-btn)] rounded-lg p-2">
            <!-- SVG Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
              <path fill="currentColor" d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17-62.5t47-43.5q60-30 124.5-46T480-440q67 0 131.5 16T736-378q30 15 47 43.5t17 62.5v112H160Zm320-400q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm160 228v92h80v-32q0-11-5-20t-15-14q-14-8-29.5-14.5T640-332Zm-240-21v53h160v-53q-20-4-40-5.5t-40-1.5q-20 0-40 1.5t-40 5.5ZM240-240h80v-92q-15 5-30.5 11.5T260-306q-10 5-15 14t-5 20v32Zm400 0H320h320ZM480-640Z" />
            </svg>
            <!-- Label -->
          </button>

        </span>


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
                href="../auth/barista/baristaLogout.php"
                class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</a>
            </div>


          </div>

        </div>
  </header>
  <!-- 
      =============================
      = Main Contents Starts Here =
      =============================
    -->

  <section
    id="ordersContainer"
    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Order Update Card Placeholder -->
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div
      class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">
        Order Updates
      </h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <!-- Orders will be injected here -->
  </section>

  <!-- 
      ===========================
      = Main Contents Ends Here =
      ===========================
    -->

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Starts Here                                                                  =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <footer class="fixed bottom-0 w-full bg-[transparent] px-3 p-5 z-50">
    <div class="flex items-center gap-1">
      <!-- Centered Info -->
      <div
        class="absolute left-1/2 -translate-x-1/2 flex flex-wrap justify-center items-center gap-3 text-[11px]">
        <!-- Online/Offline -->
        <span
          class="onlineContainer flex items-center gap-1 text-base font-medium text-[var(--text-color)]">
          <span class="text-[14px] text-green-600">●</span> Online
        </span>
        <span
          class="offlineContainer hidden items-center gap-1 text-base font-medium text-[var(--text-color)]">
          <span class="text-[14px] text-red-600">●</span> Offline
        </span>

        <!-- Date -->
        <span class="flex items-center gap-1 text-[var(--text-color)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 -960 960 960"
            class="h-[1vw]"
            fill="var(--text-color)">
            <path
              d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
          </svg>
          <span
            id="footerDate"
            class="font-medium text-base text-[var(--text-color)]">Loading...</span>
        </span>

        <!-- Time -->
        <span
          class="flex items-center text-base gap-1 text-[var(--text-color)]">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 -960 960 960"
            class="h-[1vw]"
            fill="var(--text-color)">
            <path
              d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
          </svg>
          <span
            id="footerTime"
            class="text-base font-medium text-[var(--text-color)]">Loading...</span>
        </span>
      </div>
    </div>
  </footer>

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Footer - Ends Here                                                                    =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

  <!-- 
      ========================
      = JS Links Starts Here =
      ========================
    -->

  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->
  <script src="../JS/shared/checkDBCon.js"></script>
  <!-- linked JS file below for footer scrpts -->
  <script src="../JS/shared/footer.js"></script>

  <!-- 
      ======================
      = JS Links Ends Here =
      ======================
    -->
  <!-- linked JS file below for account Dropdown to logOut -->
  <script src="../JS/shared/dropDownLogout.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</body>

</html>