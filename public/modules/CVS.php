<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BVS</title>
  <script src="app.js" defer></script>
  <!--  linked css below for animations purpose -->
  <link href="../css/input.css" rel="stylesheet" />
  <!--  linked css below for tailwind dependencies to work ofline -->
  <link href="../css/output.css" rel="stylesheet" />
  <!--  linked script below cdn of tailwind for online use -->
  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
  <link
    rel="shortcut icon"
    href="../assets/favcon/logo.ico"
    type="image/x-icon" />
</head>

<body
  class="bg-[var(--background-color)] text-white font-sans min-h-screen">
  <header
    class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
    <h1
      class="text-2xl flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
      <span class="flex items-center">
        <img
          src="../assets/SVG/LOGO/BLOGO.svg"
          class="h-[3rem] theme-logo m-1"
          alt="Module Logo" />
        Customer View System
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
      <div class="flex justify-end p-4 text-[var(--text-color)]">
        <span>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
            role="img" aria-label="Cashier" class="inline-block">
            <g fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="6" r="2.5" />
              <path d="M9.5 11h5c1.5 0 2.5 1 2.5 2.5V15H7v-1.5c0-1.5 1-2.5 2.5-2.5z" />
              <rect x="3" y="15" width="18" height="5" rx="1" />
              <rect x="16" y="12" width="4" height="2" rx="0.5" />
            </g>
          </svg>
        </span><select
          id="userMenu"
          class="bg-transparent border-0 border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm lg:text-base font-medium cursor-pointer max-w-32 sm:max-w-none truncate focus:outline-none focus:ring-2 focus:ring-blue-400"
          onchange="">
          <option selected disabled class="text">
            None
          </option>
          <option value="logout" class="text-red-600">
            Logout
          </option>
        </select>
      </div>
    </div>
  </header>

  <!-- 
    =======================
    Profile Dropdown Ends Here
    ======================= -->
  <!-- 
      =============================
      = Main Contents Starts Here =
      =============================
    -->

  <section
    id="ordersContainer"
    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <!-- Order Update Card Placeholder -->
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
      <p class="text-base text-gray-300 mb-4">Waiting for new orders...</p>
      <div class="w-full h-4 bg-gray-500 rounded"></div>
    </div>
    <div class="order-card m-5 bg-[var(--order-container)] border-2 border-[var(--border-color)] rounded-lg shadow p-6 flex flex-col items-center justify-center animate-pulse">
      <div class="w-16 h-16 bg-gray-400 rounded-full mb-4"></div>
      <h2 class="text-xl font-semibold text-[var(--text-color)] mb-2">Order Updates</h2>
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

  <footer
    class="fixed bottom-0 w-full bg-[transparent] px-3 p-5 z-50">
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
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
          </svg>
          <span
            id="footerDate"
            class="font-medium text-base text-[var(--text-color)]">Loading...</span>
        </span>

        <!-- Time -->
        <span
          class="flex items-center text-base gap-1 text-[var(--text-color)]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
            <path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
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
</body>

</html>