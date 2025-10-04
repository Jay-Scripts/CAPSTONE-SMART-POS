<?php
include_once "../../app/config/dbConnection.php"; // including the Database Handler
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POS</title>
  <!--  linked css below for animations purpose -->
  <link href="../css/style.css" rel="stylesheet" />
  <!--  linked css below for tailwind dependencies to work ofline -->
  <!-- <link href="../css/output.css" rel="stylesheet" /> -->
  <script src="https://cdn.tailwindcss.com"></script>

  <link
    rel="shortcut icon"
    href="../assets/favcon/pos.ico"
    type="image/x-icon" />
</head>

<body class="bg-[var(--background-color)] min-h-screen">


  <header
    class="w-full flex justify-between items-center gap-4 px-3 py-2 lg:px-6 lg:py-3 md:static sm:px-4 sm:py-2 bg-[var(--nav-bg)] text-[var(--nav-text)] border-b shadow-md z-50">
    <h1
      class="text-2xl flex-1 lg:text-left lg:flex-none sm:text-lg md:text-xl font-semibold text-[var(--text-color)]">
      <span class="flex items-center">
        <img
          src="../assets/SVG/LOGO/BLOGO.svg"
          class="h-[3rem] theme-logo m-1"
          alt="Module Logo" />
        POS
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
  <main class="flex justify-center items-center h-full portrait:items-start">
    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main Menu Container - Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
    <section
      class="grid grid-cols-4 landscape:grid-cols-5 gap-1 w-full h-[70vh]  p-4">
      <!-- 
      ========================================
      =      Action Btns - Starts Here       =
      ========================================
    -->
      <aside
        class="text-white col-span-1 landscape:col-span-1 flex justify-center gap-2 items-center flex-col h-full"
        id="actionBtnContainer">

        <button
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow-lg transition-all duration-200 overflow-hidden group">
          <span
            class="text-[.rem] md:text-[1rem] lg:text-[1.2rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            SCAN
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/QR.svg"
              alt="SCAN ICON"
              class="w-[70%] h-[70%]" />
          </span>
        </button>
        <button
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow-lg transition-all duration-200 overflow-hidden group">
          <span
            class="text-[.rem] md:text-[1rem] lg:text-[1.2rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            SIZE
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/SIZE.svg"
              alt="SIZE ICON"
              class="w-[70%] h-[70%]" />
          </span>
        </button>

        <button
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow transition-all duration-200 overflow-hidden group"
          onclick="showModule('addOns')">
          <span
            class="text-[.6rem] md:text-[.7rem] lg:text-[1rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            ADD-ONS
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/ADD_ONS.svg"
              alt="ADD-ONS ICON"
              class="w-[70%] h-[70%]" />
          </span>
        </button>
        <button
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow transition-all duration-200 overflow-hidden group"
          onclick="showModule('modify')">
          <span
            class="text-[.9rem] md:text-[1rem] lg:text-[1.2rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            MODIFY
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/MODIFY.svg"
              alt="MODIFY ICON"
              class="w-[70%] h-[70%]" />
          </span>
        </button>

      </aside>
      <!-- 
      ========================================
      =      Action Btns - Ends  Here        =
      ========================================
    -->
      <section
        id="menuContainer"
        class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-3">
        <!-- Categories Section -->
        <fieldset
          id="orderCategory"
          class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-3 p-2"
          aria-label="Order Categories">
          <div class="categoryButtons ">
            <input type="radio" id="milktea_module" name="module" class="hidden peer" checked onclick="showModule('milktea')" />
            <label for="milktea_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">

              <!-- Icon -->
              <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2l-4 2" />
                <path d="M12 2v3" />
                <path d="M5 7h14" />
                <path d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
                <path d="M7 12h10" />
                <circle cx="9" cy="16.5" r="1" fill="currentColor" stroke="none" />
                <circle cx="12" cy="17.5" r="1" fill="currentColor" stroke="none" />
                <circle cx="15" cy="16.5" r="1" fill="currentColor" stroke="none" />
              </svg>

              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">MILK TEA</p>
            </label>
          </div>

          <div class="categoryButtons ">
            <input type="radio" id="fruittea_module" name="module" class="hidden peer" onclick="showModule('fruittea')" />
            <label for="fruittea_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">

              <!-- Icon -->
              <!-- Fruit Tea SVG -->
              <svg
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Cup -->
                <path d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                <!-- Lid -->
                <path d="M5 7h14" />
                <!-- Straw -->
                <path d="M12 2v5" />
                <!-- Liquid line -->
                <path d="M7 12h10" />
                <!-- Fruit slice (circle + wedge) -->
                <circle cx="16.5" cy="15.5" r="2" />
                <path d="M16.5 13.5v4" />
                <path d="M14.5 15.5h4" />
              </svg>


              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">FRUIT TEA</p>
            </label>
          </div>

          <div class="categoryButtons ">
            <input type="radio" id="hotbrew_module" name="module" class="hidden peer" onclick="showModule('hotbrew')" />
            <label for="hotbrew_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">

              <!-- Hot Brew SVG -->
              <svg
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Mug -->
                <path d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z" />
                <!-- Handle -->
                <path d="M16 10h1a3 3 0 0 1 0 6h-1" />
                <!-- Steam lines -->
                <path d="M9 2v3" />
                <path d="M13 2v3" />
              </svg>



              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">HOT BREW</p>
            </label>
          </div>


          <div class="categoryButtons ">
            <input type="radio" id="icedcoffee_module" name="module" class="hidden peer" onclick="showModule('icedcoffee')" />
            <label for="icedcoffee_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">

              <!-- Iced Coffee SVG -->
              <svg
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Glass -->
                <path d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                <!-- Lid -->
                <path d="M6 7h12" />
                <!-- Straw -->
                <path d="M12 2v5" />
                <!-- Ice cubes -->
                <rect x="9" y="11" width="2.5" height="2.5" />
                <rect x="12.5" y="14" width="2.5" height="2.5" />
              </svg>


              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">ICED COFFEE</p>
            </label>
          </div>

          <div class="categoryButtons ">
            <input type="radio" id="praf_module" name="module" class="hidden peer" onclick="showModule('praf')" />
            <label for="praf_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">


              <!-- Praf SVG -->
              <svg
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Cup -->
                <path d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z" />
                <!-- Dome lid -->
                <path d="M6 9c0-3 3-5 6-5s6 2 6 5" />
                <!-- Straw -->
                <path d="M12 4V2" />
                <!-- Topping detail (whipped cream swirl) -->
                <path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
              </svg>



              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">PRAF</p>
            </label>
          </div>


          <div class="categoryButtons ">
            <input type="radio" id="promos_module" name="module" class="hidden peer" onclick="showModule('promos')" />
            <label for="promos_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">

              <!-- Promos (Drink Special) SVG -->
              <svg
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Cup -->
                <path d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                <!-- Lid -->
                <path d="M6 7h12" />
                <!-- Straw -->
                <path d="M12 2v5" />
                <!-- Star badge (promo highlight) -->
                <polygon points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
              </svg>

              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">PROMOS</p>
            </label>
          </div>

          <div class="categoryButtons ">
            <input type="radio" id="brosty_module" name="module" class="hidden peer" onclick="showModule('brosty')" />
            <label for="brosty_module"
              class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">


              <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round">
                <!-- Cup/Bowl -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
                <!-- Shaved Ice (cloudy top) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2" />
                <!-- Straw/Stick -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 5l2 4" />
              </svg>


              <!-- Label -->
              <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">BROSTY</p>
            </label>
          </div>
        </fieldset>

        <section id="milktea" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Milk Tea Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="milkteaMenu">
            <?php
            include_once "../../app/controllers/POS/milkTeaProducts.php"; // Including the milktea fetching logic  

            ?>
          </div>
        </section>
        <section id="fruittea" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Fruit Tea Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="fruitTeaMenu">
            <?php
            include_once "../../app/controllers/POS/fruitTeaProducts.php"; // Including the fruit tea fetching logic  

            ?>
          </div>
        </section>
        <section id="hotbrew" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Hot Brew Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="hotBrewMenu">
            <?php
            include_once "../../app/controllers/POS/hotBrewProducts.php"; // Including the fruit tea fetching logic  

            ?>
          </div>
        </section>
        <section id="praf" class="hidden">
          <hr class="border-2 border-[var(--border-color)] my-5" />

          <h1
            id="menuTitle"
            class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
            Praf Menu
          </h1>
          <hr class="border-2 border-[var(--border-color)] my-5" />
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="prafMenu">
            <?php
            include_once "../../app/controllers/POS/prafProducts.php"; // Including the praf fetching logic  

            ?>
          </div>
        </section>
        <!-- 
      ================================================
      =      Iced Coffee Section - Starts Here       =
      ================================================
    -->
        <section
          id="icedcoffee"
          class="hidden"
          aria-labelledby="icedcoffeeTitle">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Iced Coffee Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="icedCoffeeMenu">
            <?php
            include_once "../../app/controllers/POS/icedCoffeeProducts.php"; // Including the iced coffee fetching logic  

            ?>
          </div>
        </section>
        <!-- 
      ==============================================
      =      Iced Coffee Section - Ends Here       =
      ==============================================
    -->

        <section id="promos" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Promo Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="icedCoffeeMenu">
            <?php
            include_once "../../app/controllers/POS/promoProducts.php"; // Including the promo fetching logic  

            ?>
          </div>
        </section>

        <section id="brosty" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Brosty Menu
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black overflow-y-auto hide-scrollbar max-h-[calc(55vh-50px)]"
            id="brostyMenu">
            <?php
            include_once "../../app/controllers/POS/brostyProducts.php"; // Including the fruit tea fetching logic  

            ?>
          </div>
        </section>

        <section id="modify" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Modify
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
        </section>

        <section id="addOns" class="hidden">
          <div class="titleContainer">
            <hr class="border-2 border-[var(--border-color)] my-5" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Add-ons
            </h1>
            <hr class="border-2 border-[var(--border-color)] my-5" />
          </div>
        </section>

      </section>
      <!-- 
      ================================================
      =     Cart on Desktop View - Starts Here       =
      ================================================
    -->
      <section
        id="cart"
        class="hidden portrait:mt-[-10%] portrait:ml-[-3%] portrait:absolute portrait:items-center portrait:justify-center landscape:block landscape:relative landscape:col-span-1 p-4 rounded-lg portrait:p-0 portrait:w-screen portrait:h-screen portrait:m-0"
        aria-label="Order Summary">
        <!-- Cart Box -->
        <modal
          id="cartBox"
          class="bg-white portrait:p-6 portrait:rounded-2xl portrait:w-[90%] portrait:h-[80vh] portrait:z-50 portrait:shadow-2xl landscape:h-[70vh] landscape:w-full border-2 border-[var(--container-border)] rounded-lg shadow-xl relative flex flex-col portrait:mx-auto portrait:my-auto portrait:flex portrait:items-center portrait:justify-center">
          <!-- Close button (only visible on portrait) -->
          <button
            onclick="toggleCart()"
            class="portrait:block landscape:hidden absolute top-3 right-3 text-red-600 font-bold text-2xl hover:scale-110 transition">
            &times;
          </button>

          <!-- Cart content -->
          <h2 class="text-center font-bold text-lg text-[var(--cart-text)] mb-4">
            🧾 Orders
          </h2>

          <!-- Scrollable list -->
          <div id="productList" class="flex-1 overflow-y-auto  px-2 space-y-3 w-full">
            <!-- Example items -->
            <div
              class="flex justify-between items-center p-2 bg-white rounded shadow-sm over">
              <span>Milk Tea - asdasdasdasd</span>
              <span class="font-semibold">₱120</span>
            </div>
            <div
              class="flex justify-between items-center p-2 bg-white rounded shadow-sm over">
              <span>Milk Tea - asdasdasdasd</span>
              <span class="font-semibold">₱120</span>
            </div>


            <!-- Checkout button (fixed at bottom) -->
            <button
              class=" fixed right-0 bottom-0 w-full h-[50px] bg-green-600 hover:bg-green-500 text-white font-bold flex items-center justify-center rounded-xl shadow-lg transition-all duration-200"
              onclick="openCalculator()">
              <img
                src="../assets/SVG/ACTION BTN/CART.svg"
                alt="CART ICON"
                class="w-5 h-5" />
              Checkout
            </button>
        </modal>
      </section>

      <!-- CART BUTTON (Only on Portrait) -->
      <section class="landscape:hidden fixed bottom-5 right-5">
        <button
          onclick="toggleCart()"
          class="actionBtn relative w-[150px] h-[50px] bg-green-600 hover:bg-green-500 text-white font-bold flex items-center justify-center rounded-xl shadow-xl transition-all duration-200 overflow-hidden group">
          <span class="mr-2">🛒</span> CART
        </button>
      </section>
      <!-- 
      ================================================
      =       Cart on Desktop View - Ends Here       =
      ================================================
    -->
      <!-- ================================================
      =           Cart Calculator - Starts Here           =
      ================================================ -->

      <!-- Calculator Modal -->
      <div
        id="calculatorModal"
        class="fixed inset-0 bg-black/80 hidden flex items-center justify-center z-50">
        <div
          id="calculator"
          class="bg-[var(--background-color)] border rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-sm p-4 sm:p-6 mx-2">

          <!-- Header -->
          <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h2 class="text-lg sm:text-xl font-bold text-[var(--text-color)]">
              Payment
            </h2>
            <button onclick="closeCalculator()"
              class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
          </div>

          <!-- Summary -->
          <div class="space-y-2 mb-5 text-base sm:text-lg text-[var(--text-color)]">
            <div class="flex justify-between">
              <span>Total:</span><span id="totalAmount" class="font-bold">₱350</span>
            </div>
            <div class="flex justify-between">
              <span>Tendered:</span><span id="tenderedAmount" class="font-bold">₱0</span>
            </div>
            <div class="flex justify-between">
              <span>Change:</span><span id="changeAmount" class="font-bold text-green-600">₱0</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-4 gap-2 mb-5 text-xs sm:text-xs text-[var(--text-color)]">

            <!-- Brew Rewards -->
            <button onclick="openQrPopup()"
              class="w-full aspect-square flex flex-col items-center justify-center gap-1 bg-[var(--calc-bg-btn)]  hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg ">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                <path fill="currentColor" d="M3 3h8v8H3V3zm2 2v4h4V5H5zm10-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z" />
              </svg>
              <p class="text-[10px]">Brew Rewards</p>
            </button>

            <!-- PWD -->
            <button onclick="applyDiscount('PWD')"
              class="w-full aspect-square flex flex-col items-center justify-center gap-1 bg-[var(--calc-bg-btn)]  hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg ">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
                <path fill="currentColor" d="M480-720q-33 0-56.5-23.5T400-800q0-33 23.5-56.5T480-880q33 0 56.5 23.5T560-800q0 33-23.5 56.5T480-720ZM680-80v-200H480q-33 0-56.5-23.5T400-360v-240q0-33 23.5-56.5T480-680q24 0 41.5 10.5T559-636q55 66 99.5 90.5T760-520v80q-53 0-107-23t-93-55v138h120q33 0 56.5 23.5T760-300v220h-80Zm-280 0q-83 0-141.5-58.5T200-280q0-72 45.5-127T360-476v82q-35 14-57.5 44.5T280-280q0 50 35 85t85 35q39 0 69.5-22.5T514-240h82q-14 69-69 114.5T400-80Z" />
              </svg>
              <p class="text-[10px]">PWD</p>
            </button>

            <!-- SC -->
            <button onclick="applyDiscount('SC')"
              class="w-full aspect-square flex flex-col items-center justify-center gap-1 bg-[var(--calc-bg-btn)] rounded-lg  hover:bg-gray-300 dark:hover:bg-gray-700  relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
                <path fill="currentColor" d="m320-40-64-48 104-139v-213q0-31 5-67.5t15-67.5l-60 33v142h-80v-188l176-100q25-14 43.5-21.5T494-717q25 0 45.5 21.5T587-628q32 54 58 81t56 41q11-8 19-11t19-3q25 0 43 18t18 42v420h-40v-420q0-8-6-14t-14-6q-8 0-14 6t-6 14v50h-40v-19q-54-23-84-51.5T543-557q-11 28-17.5 68.5T521-412l79 112v260h-80v-200l-71-102-9 142L320-40Zm220-700q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z" />
              </svg>
              <p class="text-[10px]">SC</p>
            </button>

            <!-- E Payment -->
            <button onclick="openEPaymentPopup()"
              class="w-full aspect-square flex flex-col items-center justify-center gap-1 bg-[var(--calc-bg-btn)] rounded-lg  hover:bg-gray-300 dark:hover:bg-gray-700  relative">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6">
                <rect x="2" y="6" width="20" height="12" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                <line x1="2" y1="10" x2="22" y2="10" stroke="currentColor" stroke-width="2" />
                <rect x="4" y="12" width="3" height="3" rx="0.5" ry="0.5" fill="currentColor" />
              </svg>

              <p class="text-[10px]">E-Payment</p>
            </button>

          </div>



          <!-- Quick Bills -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-5 text-[var(--text-color)]">
            <button onclick="addCash(500)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱500</button>
            <button onclick="addCash(200)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱200</button>
            <button onclick="addCash(100)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱100</button>
            <button onclick="addCash(50)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱50</button>
            <button onclick="addCash(20)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱20</button>
            <button onclick="addCash(10)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱10</button>
            <button onclick="addCash(5)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">₱5</button>
            <button onclick="addCash(0)"
              class="p-2 sm:p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg">.00</button>
          </div>

          <!-- Numeric Keypad -->
          <div class="grid grid-cols-3 gap-2 text-base sm:text-lg text-[var(--text-color)]">
            <button onclick="manualKey(1)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">1</button>
            <button onclick="manualKey(2)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">2</button>
            <button onclick="manualKey(3)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">3</button>
            <button onclick="manualKey(4)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">4</button>
            <button onclick="manualKey(5)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">5</button>
            <button onclick="manualKey(6)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">6</button>
            <button onclick="manualKey(7)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">7</button>
            <button onclick="manualKey(8)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">8</button>
            <button onclick="manualKey(9)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">9</button>
            <button onclick="clearCash()" class="p-3 bg-red-500 hover:bg-red-600 text-white rounded-lg">Clear</button>
            <button onclick="manualKey(0)" class="p-3 bg-[var(--calc-bg-btn)] hover:bg-gray-400 dark:hover:bg-gray-600 rounded-lg">0</button>
            <button onclick="finalizePayment()" class="p-3 bg-green-500 hover:bg-green-600 text-white rounded-lg">Cashout</button>
          </div>


          <!-- QR Popup -->
          <div
            id="qrPopup"
            class="fixed inset-0  hidden flex items-center justify-center z-50 text-[var(--text-color)]">
            <div
              class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-4 sm:p-6 mx-2"
              id="rewardingQR">
              <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold">Scan QR</h2>

                <button onclick="closeQrPopup()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
              </div>
              <div class="flex flex-col items-center">
                <form action="POST">
                  <input type="text" name="rewardingCustQR" placeholder="Scan QR" class="border-0 bg-transparent">
                </form>
                <button onclick="closeQrPopup()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg w-full sm:w-auto">
                  Done
                </button>
              </div>
            </div>
          </div>

          <!-- E-payment Popup -->
          <div
            id="EPaymentPopup"
            class="fixed inset-0  hidden flex items-center justify-center z-50 text-[var(--text-color)]">
            <div
              class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-4 sm:p-6 mx-2"
              id="EPayment">
              <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold">Insert reference number</h2>

                <button onclick="closeEPaymentPopup()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
              </div>
              <div class="flex flex-col items-center">
                <form action="POST">
                  <input type="text" name="refNumber" placeholder="Enter reference number" class="border-0 bg-transparent">
                </form>
                <button onclick="closeEPaymentPopup()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg w-full sm:w-auto">
                  Done
                </button>
              </div>
            </div>
          </div>

          <!-- ================================================
          =           Cart Calculator - Ends Here             =
            ================================================ -->




  </main>

  <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main Menu Container - Ends Here                                                       =
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
  <!-- linked JS file below for changing category module content -->
  <script src="../JS/pos/POSmodules.js"></script>
  <!-- linked JS file below for cart button in tablet version -->
  <script src="../JS/pos/POSCartResponsiveScripts.js"></script>
  <!-- linked JS file below for ordering -->
  <script src="../JS/pos/POSCartResponsiveScripts"></script>
  <script src="../JS/pos/POSCalculatorScript.js"></script>


  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for footer scrpts -->
  <script src="../JS/shared/footer.js"></script>
  <!-- linked JS file below for checking DB status -->
  <!-- <script src="../JS/shared/checkDBCon.js"></script> -->

  <!-- 
      ========================
      =   JS Links Ends Here =
      ========================
    -->
</body>

</html>