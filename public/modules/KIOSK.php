<?php
include "../../app/config/dbConnection.php";
session_start();

$allProducts = [];

$categories = [
  1 => 'Milk Tea',
  2 => 'Fruit Tea',
  3 => 'Hot Brew',
  4 => 'Praf',
  5 => 'Brosty',
  6 => 'Iced Coffee',
  7 => 'Promos',
];

foreach ($categories as $id => $label) {
  $category_id = $id;
  ob_start();
  include "../../app/includes/POS/fetchProducts.php";
  ob_end_clean();
  if (isset($products)) {
    $allProducts[$id] = $products;
  }
}
header('Content-Type: text/html');
?>
<!DOCTYPE html>
<html>
<!-- Your HTML continues here -->

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KIOSK</title>
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
        KIOSK
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


    </div>
  </header>


  <main class="flex justify-center items-center h-full portrait:items-start">
    <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                  Main Menu Container - Starts Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
    <section
      class="grid grid-cols-4 gap-1 w-full h-[85vh] m-2">

      <section
        id="menuContainer"
        class="border-2 border-[var(--container-border)] p-4 rounded-lg col-span-4 landscape:col-span-3">
        <!-- Categories Section -->
        <fieldset
          id="orderCategory"
          class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 xl:grid-cols-7 gap-3"
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


        <!-- 
      ==========================================================================================================================================
      =                                                  Popup Modal for Ordering Starts Here                                                  =
      ==========================================================================================================================================
    -->
        <?php
        include_once "../../app/includes/POS/POSPopUpModalOrdering.php";
        ?>
        <!-- 
      ==========================================================================================================================================
      =                                                  Popup Modal for Ordering Ends Here                                                    =
      ==========================================================================================================================================
    -->


        <section id="milktea" class="hidden">
          <div class="titleContainer">
            <hr class="border border-[var(--border-color)] my-3" />

            <h1
              id="menuTitle"
              class="text-center text-[1rem] md:text-2xl lg:text-3xl font-bold text-[var(--text-color)]">
              Milk Tea Menu
            </h1>
            <hr class="border border-[var(--border-color)] my-3" />
          </div>
          <div
            class="gap-1 mt-2 justify-center items-center text-black"
            id="milkteaMenu">
            <?php
            $category_id = 1; // Milk Tea
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="fruitTeaMenu">
            <?php
            $category_id = 2; // Fruit Tea
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="hotBrewMenu">
            <?php
            $category_id = 3; // Hot Brew
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="prafMenu">
            <?php
            $category_id = 4; // Praf
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="icedCoffeeMenu">
            <?php
            $category_id = 6; // Iced Coffee
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="promosMenu">
            <?php
            $category_id = 7; // Promos
            include "../../app/includes/POS/fetchProducts.php";
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
            class="gap-1 mt-2 justify-center items-center text-black"
            id="brostyMenu">
            <?php
            $category_id = 5; // Brosty
            include "../../app/includes/POS/fetchProducts.php";
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
        class="animate-[fadeIn_0.3s_ease] hidden portrait:mt-[-10%] portrait:ml-[-2%] portrait:absolute portrait:items-center portrait:justify-center landscape:block landscape:relative landscape:col-span-1 rounded-lg portrait:p-0 portrait:w-screen portrait:h-screen portrait:m-0"
        aria-label="Order Summary">
        <!-- Cart Box -->
        <modal
          id="cartBox"
          class="animate-[animate-[fadeIn_0.3s_ease]_0.3s_ease] bg-[var(--background-color)] p-4 portrait:p-6 portrait:rounded-2xl portrait:w-[90%] portrait:h-[80vh] portrait:z-50 portrait:shadow-2xl landscape:h-[85vh] landscape:w-full border-2 border-[var(--container-border)] rounded-lg shadow-xl relative flex flex-col portrait:mx-auto portrait:my-auto portrait:flex portrait:items-center portrait:justify-center">
          <!-- Close button (only visible on portrait) -->
          <button
            onclick="toggleCart()"
            class="portrait:block landscape:hidden absolute top-3 right-3 text-red-600 font-bold text-2xl hover:scale-110 transition">
            &times;
          </button>

          <!-- Cart content -->
          <h2 class="text-center font-bold text-lg text-[var(--text-color)] m-4">
            Orders
          </h2>

          <!-- Scrollable list -->
          <div class="flex-1 overflow-y-auto mb-16  px-2 space-y-3 w-full text-[var(--text-color)]">
            <div id="productList">
              <!-- items -->
            </div>

          </div>

          <!-- Checkout button (fixed at bottom) -->
          <button
            class=" fixed right-0 bottom-0 w-full h-[50px] bg-green-600 hover:bg-green-500 text-white font-bold flex items-center justify-center rounded-lg border shadow-lg transition-all duration-200"
            onclick="kioskCheckout()">
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
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
            <path fill="currentColor" d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
          </svg>
          CART
        </button>
      </section>
      <!-- 
      ================================================
      =       Cart on Desktop View - Ends Here       =
      ================================================
    -->





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
      =                                                  Ordering Script for POS Starts Here                                                  =
      ==========================================================================================================================================
    -->
  <?php

  include_once "../../app/includes/POS/POSOrderingScript.php";

  ?>
  <!-- 
      ==========================================================================================================================================
      =                                                  Ordering Script for POS Ends Here                                                  =
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
  <!-- linked JS file below for Reltime product status check -->
  <script src="../JS/pos/POSRealTimeProductCheckStatus.js"></script>

  <script src="../JS/kiosk/kioskOrdering.js"></script>



  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->

  <!-- 
      ========================
      =   JS Links Ends Here =
      ========================
    -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>