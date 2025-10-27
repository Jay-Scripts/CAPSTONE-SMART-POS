<?php
include "../../app/config/dbConnection.php";
session_start();

$allProducts = [];

// ✅ Category loading
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

// ✅ POST handling for order_data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
  header('Content-Type: application/json');

  $order_data = json_decode($_POST['order_data'], true);
  $payment_type = $_POST['payment_type'] ?? 'CASH';
  $tendered = $_POST['amount_sent'] ?? 0;
  $change = $_POST['change_amount'] ?? 0;

  try {
    $conn->beginTransaction();

    // 🧮 Compute total and VAT
    $total = 0;
    foreach ($order_data as $item) {
      $total += $item['price'] * $item['quantity'];
    }
    $vat = $total * 0.12;

    $staff_id = $_SESSION['staff_id'] ?? 69;
    $kiosk_id = $_SESSION['kiosk_transaction_id'] ?? null; // ✅ check if kiosk ID is stored
    $ordered_by = $kiosk_id ? 'KIOSK' : 'POS'; // ✅ flag the source

    // 🧾 Insert REG_TRANSACTION (link kiosk_transaction if available)
    $stmt = $conn->prepare("
      INSERT INTO REG_TRANSACTION 
      (STAFF_ID, TOTAL_AMOUNT, VAT_AMOUNT, STATUS, kiosk_transaction_id, ORDERED_BY)
      VALUES (:staff_id, :total, :vat, 'PAID', :kiosk_id, :ordered_by)
    ");
    $stmt->execute([
      ':staff_id' => $staff_id,
      ':total' => $total,
      ':vat' => $vat,
      ':kiosk_id' => $kiosk_id,
      ':ordered_by' => $ordered_by
    ]);

    $transaction_id = $conn->lastInsertId();

    // 🧺 Insert each item
    $stmtItem = $conn->prepare("
      INSERT INTO TRANSACTION_ITEM 
      (REG_TRANSACTION_ID, PRODUCT_ID, SIZE_ID, QUANTITY, PRICE)
      VALUES (:transaction_id, :product_id, :size_id, :quantity, :price)
    ");

    foreach ($order_data as $item) {
      $stmtItem->execute([
        ':transaction_id' => $transaction_id,
        ':product_id' => $item['product_id'],
        ':size_id' => $item['size_id'],
        ':quantity' => $item['quantity'],
        ':price' => $item['price']
      ]);
      $item_id = $conn->lastInsertId();

      // Add-ons
      if (!empty($item['addons'])) {
        $stmtAdd = $conn->prepare("
          INSERT INTO item_add_ons (add_ons_id, item_id)
          VALUES (:addon_id, :item_id)
        ");
        foreach ($item['addons'] as $addon_id) {
          $stmtAdd->execute([
            ':addon_id' => $addon_id,
            ':item_id' => $item_id
          ]);
        }
      }

      // Modifications
      if (!empty($item['modifications'])) {
        $stmtMod = $conn->prepare("
          INSERT INTO item_modification (item_id, modification_id)
          VALUES (:item_id, :mod_id)
        ");
        foreach ($item['modifications'] as $mod_id) {
          $stmtMod->execute([
            ':item_id' => $item_id,
            ':mod_id' => $mod_id
          ]);
        }
      }
    }

    // 💳 Insert payment record
    $stmtPay = $conn->prepare("
      INSERT INTO PAYMENT_METHODS 
      (REG_TRANSACTION_ID, TYPE, AMOUNT_SENT, CHANGE_AMOUNT)
      VALUES (:transaction_id, :type, :amount_sent, :change_amount)
    ");
    $stmtPay->execute([
      ':transaction_id' => $transaction_id,
      ':type' => $payment_type,
      ':amount_sent' => $tendered,
      ':change_amount' => $change
    ]);

    // ✅ If this came from kiosk, update its status to PAID
    if ($kiosk_id) {
      $updateKiosk = $conn->prepare("
        UPDATE kiosk_transaction 
        SET status = 'PAID' 
        WHERE kiosk_transaction_id = ?
      ");
      $updateKiosk->execute([$kiosk_id]);

      // ✅ Clear kiosk transaction ID after completion
      unset($_SESSION['kiosk_transaction_id']);
    }

    $conn->commit();

    echo json_encode([
      'success' => true,
      'transaction_id' => $transaction_id,
      'vat_amount' => $vat,
      'message' => 'Transaction saved and marked as PAID'
    ]);
  } catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }

  exit;
}

// ===== Session check & HTML output =====
if (!isset($_SESSION['staff_name'])) {
  header("Location: ../auth/cashier/cashierLogin.php");
  exit;
}
header('Content-Type: text/html');
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
            class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-md shadow-lg hidden z-50">
            <div class="border-t border-gray-200"></div>
            <a
              href="../auth/cashier/cashierLogout.php"
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

          <button
            onclick="openKioskModal()"
            class="w-full aspect-[4/3] flex flex-col items-center justify-center border-2 border-[var(--container-border)] rounded-xl bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer shadow-sm transition-all peer-checked:bg-black peer-checked:text-white peer-checked:border-white peer-checked:shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg"
              class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 mb-1"
              viewBox="0 -960 960 960"
              fill="currentColor">
              <path d="M120-520v-320h320v320H120Zm80-80h160v-160H200v160Zm-80 480v-320h320v320H120Zm80-80h160v-160H200v160Zm320-320v-320h320v320H520Zm80-80h160v-160H600v160Zm160 480v-80h80v80h-80ZM520-360v-80h80v80h-80Zm80 80v-80h80v80h-80Zm-80 80v-80h80v80h-80Zm80 80v-80h80v80h-80Zm80-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm80 80v-80h80v80h-80Z" />
            </svg>
            <p class="font-semibold text-[9px] sm:text-[10px] md:text-xs lg:text-sm text-center">Recall Order</p>


          </button>
          <!-- 🧾 Kiosk Order Modal -->
          <div id="kioskModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-[var(--background-color)] text-[var(--text-color)] border-2 border-[var(--container-border)] p-6 rounded-2xl shadow-xl w-[300px] text-center">
              <h2 class="text-lg font-semibold mb-3">Load Kiosk Order</h2>
              <input id="kioskInput"
                type="text"
                placeholder="Scan or enter QR code"
                class="w-full border border-gray-300 rounded-lg p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-black/40">

              <div class="flex justify-between">
                <button onclick="closeKioskModal()" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg transition">Cancel</button>
                <button onclick="submitKioskOrder()" class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">Load</button>
              </div>
            </div>
          </div>

          <script>
            const kioskModal = document.getElementById("kioskModal");
            const kioskInput = document.getElementById("kioskInput");

            function openKioskModal() {
              kioskModal.classList.remove("hidden");
              kioskInput.focus();
            }

            function closeKioskModal() {
              kioskModal.classList.add("hidden");
              kioskInput.value = "";
            }

            function submitKioskOrder() {
              const value = kioskInput.value.trim();
              if (!value) return;

              // redirect or fetch the kiosk order here
              window.location.href = `../pos/loadKioskOrder.php?id=${value}`;
            }

            // ✅ Auto-submit when scanner sends "Enter"
            kioskInput.addEventListener("keydown", (e) => {
              if (e.key === "Enter") {
                e.preventDefault();
                submitKioskOrder();
              }
            });
          </script>

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
      <!-- ================================================
      =           Cart Calculator - Starts Here           =
      ================================================ -->

      <!-- Calculator Modal -->
      <div
        id="calculatorModal"
        class="fixed inset-0 bg-black/90 hidden flex items-center justify-center z-50 ">
        <div
          id="calculator"
          class="bg-[var(--background-color)] border rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-xl sm:max-h-xs p-4 sm:p-6 mx-2 animate-[fadeIn_0.3s_ease]">

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
              <span>Total:</span><span id="totalAmount" class="font-bold">0</span>
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
            <button onclick="finalizePayment()" class=" p-3 bg-green-500 hover:bg-green-600 text-white rounded-lg">
              Enter
            </button>
          </div>


          <!-- QR Popup -->
          <div
            id="qrPopup"
            class="fixed inset-0  hidden flex items-center justify-center z-50 text-[var(--text-color)] animate-[fadeIn_0.3s_ease]">
            <div
              class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-4 sm:p-6 mx-2"
              id="rewardingQR">
              <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold">Scan QR</h2>

                <button onclick="closeQrPopup()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
              </div>
              <div class="flex flex-col items-center">
                <form action="POST">
                  <input type="text" name="rewardingCustQR" placeholder="Scan QR" class="border bg-transparent">
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
            class="fixed inset-0  hidden flex items-center justify-center z-50 text-[var(--text-color)] animate-[fadeIn_0.3s_ease]">
            <div
              class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-4 sm:p-6 mx-2"
              id="EPayment">
              <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-lg font-bold">Insert reference number</h2>

                <button onclick="closeEPaymentPopup()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
              </div>
              <div class="flex flex-col items-center">
                <form action="POST">
                  <input type="text" name="refNumber" placeholder="Enter reference number" class="border bg-transparent">
                </form>
                <button onclick="closeEPaymentPopup()" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg w-full sm:w-auto">
                  Done
                </button>
              </div>
            </div>
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
  <!-- linked JS file below for Manager refund transaction -->



  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->

  <!-- <script src="../JS/shared/checkDBCon.js"></script> -->
  <script src="../JS/pos/POSCalculatorScript.js"></script>
  <!-- linked JS file below for Logoutt BTN -->
  <script src="../JS/shared//dropDownLogout.js"></script>
  <!-- 
      ========================
      =   JS Links Ends Here =
      ========================
    -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



</body>

</html>