<?php
include_once "../config/dbConnection.php"; // including the Database Handler
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POS</title>
  <!--  linked css below for animations purpose -->
  <link href="../css/input.css" rel="stylesheet" />
  <!--  linked css below for tailwind dependencies to work ofline -->
  <link href="../css/output.css" rel="stylesheet" />
</head>

<body class="bg-[var(--background-color)] min-h-screen">
  <header
    class="fixed w-full flex justify-between items-center gap-4 px-4 py-2 bg-[var(--managers-nav-bg)] border-b shadow-sm shadow-black dark:shadow-white duration-200">
    <p class="text-xl font-semibold text-[var(--managers-nav-text)]">Smart POS</p>

    <div>

      <!-- 
      ==================================
      =   Theme toggle Btn Starts Here =
      ==================================
    -->
      <button
        class="p-2 rounded-full cursor-pointer"
        id="theme-toggle"
        title="Toggle theme"
        aria-label="auto"
        aria-live="polite">
        <svg
          class="sun-and-moon text-gray-600 dark:text-gray-200 transform transition-transform duration-300 "
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
            fill="white" />
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

      <!-- Profile Dropdown -->
      <div class="relative inline-block text-left">
        <button
          id="userMenuButton"
          class="flex items-center gap-2 px-3 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="w-6 h-6 "
            fill="var(--managers-nav-text)"
            viewBox="0 0 24 24"
            stroke="currentColor">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 14c-3.866 0-7 1.343-7 3v2h14v-2c0-1.657-3.134-3-7-3zM12 12a4 4 0 100-8 4 4 0 000 8z" />
          </svg>
          <span class="font-medium text-[var(--managers-nav-text)]" ]>Arwyn T.</span>
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
        </button>

        <div
          id="userDropdown"
          class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 border rounded shadow-md hidden z-50">
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
          <a
            href="#"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
        </div>
      </div>
    </div>
  </header>
  <main class="flex justify-center items-center h-screen">
    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main Menu Container - Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
    <section
      class="grid grid-cols-4 landscape:grid-cols-5 gap-1 w-full h-[70vh] p-4">
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
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-[#e62222] hover:bg-[#ff3636] text-white font-bold flex items-center justify-start px-4 rounded-lg shadow transition-all duration-200 overflow-hidden group">
          <span
            class="text-[.9rem] md:text-[1rem] lg:text-[1.2rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            VOID
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/VOID.svg"
              alt="VOID ICON"
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
        <button
          onclick="history.back()"
          class="actionBtn border-2 border-[var(--border-color)] m-2 relative w-[80px] h-[50px] md:w-[120px] md:h-[50px] lg:w-[150px] lg:h-[55px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow transition-all duration-200 overflow-hidden group">
          <span
            class="text-[.9rem] md:text-[1rem] lg:text-[1.2rem] transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            BACK
          </span>
          <span
            class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-white transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/RETURN.svg"
              alt="RETURN ICON"
              class="w-[70%] h-[70%]" />
          </span>
        </button>
      </aside>
      <!-- 
      ========================================
      =      Action Btns - Starts Here       =
      ========================================
    -->
      <section
        id="menuContainer"
        class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-3 landscape:col-span- portrait:active:cursor-grabbing">
        <fieldset
          id="orderCategory"
          class="flex flex-wrap gap-3 portrait:grid portrait:grid-cols-2"
          aria-label="Order Categories">
          <legend class="sr-only">Choose a Category</legend>

          <div class="categoryButtons">
            <input
              type="radio"
              id="milktea_module"
              name="module"
              class="hidden peer"
              checked
              onclick="showModule('milktea')" />
            <label
              for="milktea_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>MILK TEA</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="fruittea_module"
              name="module"
              class="hidden peer"
              onclick="showModule('fruittea')" />
            <label
              for="fruittea_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>FRUIT TEA</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="hotbrew_module"
              name="module"
              class="hidden peer"
              onclick="showModule('hotbrew')" />
            <label
              for="hotbrew_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>HOT BREW</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="praf_module"
              name="module"
              class="hidden peer"
              onclick="showModule('praf')" />
            <label
              for="praf_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>PRAF</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="icedcoffee_module"
              name="module"
              class="hidden peer"
              onclick="showModule('icedcoffee')" />
            <label
              for="icedcoffee_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>ICED COFFEE</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="promos_module"
              name="module"
              class="hidden peer"
              onclick="showModule('promos')" />
            <label
              for="promos_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>PROMOS</span>
            </label>
          </div>

          <div class="categoryButtons">
            <input
              type="radio"
              id="brosty_module"
              name="module"
              class="hidden peer"
              onclick="showModule('brosty')" />
            <label
              for="brosty_module"
              class="border-[var(--border-color)] border-2 text-[.6rem] flex items-center justify-center cursor-pointer px-4 py-2 bg-green-500 hover:bg-green-400 text-white rounded-lg peer-checked:bg-black peer-checked:text-white transition-all">
              <span>BROSTY</span>
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
            include_once "../controllers/POS/milkTeaProducts.php"; // Including the milktea fetching logic  

            ?>
          </div>
        </section>
        <section id="fruittea" class="hidden">
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
            id="fruitTeaMenu">
            <?php
            include_once "../controllers/POS/fruitTeaProducts.php"; // Including the fruit tea fetching logic  

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
            include_once "../controllers/POS/hotBrewProducts.php"; // Including the fruit tea fetching logic  

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
            id="milkteaMenu">
            <?php
            include_once "../controllers/POS/prafProducts.php"; // Including the praf fetching logic  

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
            include_once "../controllers/POS/icedCoffeeProducts.php"; // Including the iced coffee fetching logic  

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
            include_once "../controllers/POS/promoProducts.php"; // Including the promo fetching logic  

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
            id="hotBrewMenu">
            <?php
            include_once "../controllers/POS/brostyProducts.php"; // Including the fruit tea fetching logic  

            ?>
          </div>
        </section>

        <div id="addOns" class="hidden">
          <hr class="border-2 border-[var(--border-color)] my-5" />

          <h1
            id="menuTitle"
            class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
            Add-ons
          </h1>
          <hr class="border-2 border-[var(--border-color)] my-5" />
          Add-ons Content
        </div>
        <div id="modify" class="hidden">
          <hr class="border-2 border-[var(--border-color)] my-5" />

          <h1
            id="menuTitle"
            class="text-center text-[1.5rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
            Modify
          </h1>
          <hr class="border-2 border-[var(--border-color)] my-5" />
          Modify Content
        </div>
      </section>
      <!-- 
      ================================================
      =     Cart on Desktop View - Starts Here       =
      ================================================
    -->
      <!-- CART SECTRION HIDDEN ON PORTRAIT/TABLET MODE -->
      <aside
        id="col3"
        class="portrait:hidden portrait:col-span-0 landscape:block landscape:col-span-1 p-4 rounded-lg col-span-2 md:col-span-1 relative"
        aria-label="Order Summary">
        <section
          id="cartContainer"
          class="h-[70vh] w-full bg-[var(--cart-color)] border-2 border-[var(--container-border)] rounded-md shadow-2xl relative">
          <h2 class="text-center font-bold mt-[5%] text-[var(--cart-text)]">
            Orders
          </h2>

          <div id="productList"></div>

          <button
            class="portrait:hidden absolute bottom-[0px] left-0 right-0 w-full h-[50px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-md shadow transition-all duration-200 group"
            aria-label="Proceed to Checkout">
            <span
              class="transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
              Checkout
            </span>
            <span
              class="icon absolute right-0 h-full w-[20%] group-hover:w-full flex items-center justify-center border-l border-green-600 transition-all duration-200">
              <img
                src="../assets/SVG/ACTION BTN/CART.svg"
                alt="Cart Icon"
                class="color-white"
                id="checkoutBtn" />
            </span>
          </button>
        </section>
      </aside>
      <!-- 
      ================================================
      =       Cart on Desktop View - Ends Here       =
      ================================================
    -->
    </section>
    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main Menu Container - Ends Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
  </main>

  <!-- 
      =======================================================
      =      Cart Button on Tablet View - Starts Here       =
      =======================================================
    -->
  <!-- CART BUTTON WILL ONLY APPEAR IN PORTRAIT OR TABLET VER -->
  <section
    class="landscape:hidden mt-4 flex justify-center gap-4 fixed bottom-5 right-5">
    <button
      onclick="toggleModal('cart')"
      class="actionBtn border-2 relative w-[150px] h-[50px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded-lg shadow transition-all duration-200 overflow-hidden group">
      <span
        class="text transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
        CART
      </span>
      <span
        class="icon absolute right-0 h-full w-[40px] group-hover:w-full flex items-center justify-center border-l border-green-600 transition-all duration-200">
        <img
          src="../assets/SVG/ACTION BTN/CART.svg"
          alt="VOID ICON"
          class="color-white" />
      </span>
    </button>
  </section>
  <!-- 
      =======================================================
      =        Cart Button on Tablet View - Ends Here       =
      =======================================================
    -->

  <!-- 
      ================================================
      =       Cart on Tablet View - Starts Here      =
      ================================================
    -->
  <!-- CART MODAL VERSION WILL ONLY APPEAR IN PORTRAIT OR TABLET VER -->

  <modal
    id="cart"
    class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
    <div class="bg-white text-black p-6 rounded w-[50vh] h-[70vh] relative">
      <!-- Close button for the modal -->
      <button
        onclick="toggleModal('cart')"
        class="absolute top-2 right-2 text-red-600 font-bold">
        &times;
      </button>

      <!-- Cart content here (previous cartContainer content) -->
      <div
        id="cartContainer"
        class="h-full w-full bg-[var(--cart-color)] rounded-lg overflow-y-auto shadow-2xl relative">
        <h2 class="text-center font-bold mt-[5%] text-[var(--cart-text)]">
          Orders
        </h2>
        <!-- You can add other content above the checkout button as needed -->
        <div id="productList"></div>
        <button
          class="absolute bottom-4 left-4 right-4 w-[calc(100%-2rem)] h-[50px] bg-green-500 hover:bg-green-400 text-white font-bold flex items-center justify-start px-4 rounded shadow transition-all duration-200 overflow-hidden group">
          <span
            class="transition-all duration-200 transform group-hover:translate-x-[200%] group-hover:opacity-0">
            Checkout
          </span>
          <span
            class="icon absolute right-0 h-full w-[40px] group-hover:w-full flex items-center justify-center border-l border-green-600 transition-all duration-200">
            <img
              src="../assets/SVG/ACTION BTN/CART.svg"
              alt="CART ICON"
              class="color-white" />
          </span>
        </button>
      </div>
    </div>
  </modal>
  <!-- 
      ================================================
      =         Cart on Tablet View - Ends Here      =
      ================================================
    -->

  <footer
    class="fixed bottom-0 w-full shadow-sm  text-xs z-50">
    <div class="relative p-5 flex items-center justify-between w-full">
      <!-- Centered Info -->
      <div
        class="absolute left-1/2 -translate-x-1/2 flex flex-wrap justify-center items-center gap-3 text-[11px]">
        <!-- Online/Offline -->
        <span
          class="onlineContainer flex items-center gap-1 font-medium text-[var(--text-color)]">
          <span class="text-[14px] text-green-500 ">●</span> Online
        </span>
        <span
          class="offlineContainer hidden items-center gap-1 font-medium text-red-600">
          <span class="text-[14px]">●</span> Offline
        </span>

        <!-- Date -->
        <span class="flex items-center gap-1 text-[var(--text-color)]">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
            <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
          </svg>
          <time>
            <span
              id="day"
              class="portrait:text-[.7rem] text-[var(--text-color)]">day</span><span class="text-[var(--text-color)]">,</span>
            <span
              id="daynum"
              class="portrait:text-[.7rem] text-[var(--text-color)]">00</span>
            <span
              id="month"
              class="portrait:text-[.7rem] text-[var(--text-color)]">month</span>
            <span
              id="year"
              class="portrait:text-[.7rem] text-[var(--text-color)]">0000</span>
          </time>
        </span>

        <!-- Time -->
        <span class="flex items-center gap-1 ">

          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class=" h-[1vw] " fill="var(--text-color)">
            <path d="M582-298 440-440v-200h80v167l118 118-56 57ZM440-720v-80h80v80h-80Zm280 280v-80h80v80h-80ZM440-160v-80h80v80h-80ZM160-440v-80h80v80h-80ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
          </svg>
          <span class=" display-time text-[var(--text-color)]">Loading...</span>
        </span>
      </div>
    </div>
  </footer>

  <!-- 
    
      ========================
      = JS Links Starts Here =
      ========================
    -->
  <!-- linked JS file below for changing category module content -->
  <script src="../JS/modules.js"></script>
  <!-- linked JS file below for cart button in tablet version -->
  <script src="../JS/toggleModal.js"></script>
  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/theme-toggle.js"></script>
  <!-- linked JS file below for clock near the action Buttons -->
  <script src="../JS/time.js"></script>
  <!-- 
      ========================
      =   JS Links Ends Here =
      ========================
    -->
</body>

</html>