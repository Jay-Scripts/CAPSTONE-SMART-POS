<?php
include "../../app/config/dbConnection.php";
session_start();

$allProducts = [];

//  Category loading
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

// POST handling for order_data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
  header('Content-Type: application/json');

  $order_data = json_decode($_POST['order_data'], true);
  $payment_type = $_POST['payment_type'] ?? 'CASH';
  $tendered = $_POST['amount_sent'] ?? 0;
  $change = $_POST['change_amount'] ?? 0;

  try {
    $conn->beginTransaction();

    // 🧮 Compute total from cart
    $total = 0;
    foreach ($order_data as $item) {
      $total += $item['price'] * $item['quantity'];
    }

    // --- ADD THIS BLOCK BEFORE INSERTING REG_TRANSACTION ---
    $disc = !empty($_POST['discount_data']) ? json_decode($_POST['discount_data'], true) : null;
    $discountAmount = $disc['disc_total'] ?? 0;
    $total_after_discount = max(0, $total - $discountAmount);
    $vat = $total_after_discount * 0.12;
    // --- END ADD ---

    $staff_id = $_SESSION['staff_id'] ?? 69;
    $kiosk_id = $_SESSION['kiosk_transaction_id'] ?? null;
    $ordered_by = $kiosk_id ? 'KIOSK' : 'POS';

    // Insert REG_TRANSACTION
    $stmt = $conn->prepare("
  INSERT INTO REG_TRANSACTION 
  (STAFF_ID, TOTAL_AMOUNT, VAT_AMOUNT, vatable_sales, amount_tendered, change_amount, STATUS, kiosk_transaction_id, ORDERED_BY)
  VALUES (:staff_id, :total, :vat, :vatable, :tendered, :change, 'PAID', :kiosk_id, :ordered_by)
");
    $stmt->execute([
      ':staff_id' => $staff_id,
      ':total' => $total_after_discount,      // discounted total
      ':vat' => $vat,
      ':vatable' => $total_after_discount - $vat,  // vatable sales
      ':tendered' => $tendered,               // amount received
      ':change' => $change,                   // change to give back
      ':kiosk_id' => $kiosk_id,
      ':ordered_by' => $ordered_by
    ]);



    $transaction_id = $conn->lastInsertId();
    if (!empty($_POST['discount_data'])) {
      $disc = json_decode($_POST['discount_data'], true); // ✅ decode JSON
      if (!empty($disc['type']) && !empty($disc['id_num']) && !empty($disc['first_name']) && !empty($disc['last_name']) && isset($disc['disc_total'])) {
        $stmtDisc = $conn->prepare("
            INSERT INTO DISC_TRANSACTION 
            (REG_TRANSACTION_ID, ID_TYPE, ID_NUM, FIRST_NAME, LAST_NAME, DISC_TOTAL_AMOUNT) 
            VALUES (:reg_id, :id_type, :id_num, :first_name, :last_name, :disc_total)
        ");
        $stmtDisc->execute([
          ':reg_id' => $transaction_id,
          ':id_type' => $disc['type'],
          ':id_num' => $disc['id_num'],
          ':first_name' => $disc['first_name'],
          ':last_name' => $disc['last_name'],
          ':disc_total' => $disc['disc_total']
        ]);
      }
    }


    // --- Insert optional E-Payment record ---
    if (!empty($_POST['epay_amount']) && floatval($_POST['epay_amount']) > 0) {
      $epayAmount = floatval($_POST['epay_amount']);
      $refNumber = !empty($_POST['refNumber']) ? intval($_POST['refNumber']) : 0;


      $stmtEpay = $conn->prepare("
        INSERT INTO EPAYMENT_TRANSACTION 
        (REG_TRANSACTION_ID, AMOUNT, REFERENCES_NUM) 
        VALUES (:reg_id, :amount, :ref_num)
    ");
      $stmtEpay->execute([
        ':reg_id' => $transaction_id,
        ':amount' => $epayAmount,
        ':ref_num' => $refNumber
      ]);
    }




    //  Insert each item
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

    //  If this came from kiosk, update its status to PAID
    if ($kiosk_id) {
      $updateKiosk = $conn->prepare("
        UPDATE kiosk_transaction 
        SET status = 'PAID' 
        WHERE kiosk_transaction_id = ?
      ");
      $updateKiosk->execute([$kiosk_id]);

      //  Clear kiosk transaction ID after completion
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

    <section class="grid grid-cols-4 gap-2 w-full h-[85vh] m-2">

      <!-- ============================================================
         MENU CONTAINER
    ============================================================ -->
      <section
        id="menuContainer"
        class="col-span-4 landscape:col-span-3 flex flex-col gap-0 rounded-2xl border border-[var(--container-border)] overflow-hidden bg-[var(--background-color)] shadow-lg">

        <!-- ── TOP BAR: Category Nav ── -->
        <div class="px-3 pt-3 pb-2 border-b border-[var(--container-border)] bg-[var(--background-color)]">
          <fieldset
            id="orderCategory"
            class="flex flex-wrap gap-2"
            aria-label="Order Categories">

            <!-- MILK TEA -->
            <div class="categoryButtons">
              <input type="radio" id="milktea_module" name="module" class="hidden peer" checked onclick="showModule('milktea')" />
              <label for="milktea_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M14 2l-4 2" />
                  <path d="M12 2v3" />
                  <path d="M5 7h14" />
                  <path d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
                  <path d="M7 12h10" />
                  <circle cx="9" cy="16.5" r="1" fill="currentColor" stroke="none" />
                  <circle cx="12" cy="17.5" r="1" fill="currentColor" stroke="none" />
                  <circle cx="15" cy="16.5" r="1" fill="currentColor" stroke="none" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Milk Tea</span>
              </label>
            </div>

            <!-- FRUIT TEA -->
            <div class="categoryButtons">
              <input type="radio" id="fruittea_module" name="module" class="hidden peer" onclick="showModule('fruittea')" />
              <label for="fruittea_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z" />
                  <path d="M5 7h14" />
                  <path d="M12 2v5" />
                  <path d="M7 12h10" />
                  <circle cx="16.5" cy="15.5" r="2" />
                  <path d="M16.5 13.5v4" />
                  <path d="M14.5 15.5h4" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Fruit Tea</span>
              </label>
            </div>

            <!-- HOT BREW -->
            <div class="categoryButtons">
              <input type="radio" id="hotbrew_module" name="module" class="hidden peer" onclick="showModule('hotbrew')" />
              <label for="hotbrew_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z" />
                  <path d="M16 10h1a3 3 0 0 1 0 6h-1" />
                  <path d="M9 2v3" />
                  <path d="M13 2v3" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Hot Brew</span>
              </label>
            </div>

            <!-- ICED COFFEE -->
            <div class="categoryButtons">
              <input type="radio" id="icedcoffee_module" name="module" class="hidden peer" onclick="showModule('icedcoffee')" />
              <label for="icedcoffee_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                  <path d="M6 7h12" />
                  <path d="M12 2v5" />
                  <rect x="9" y="11" width="2.5" height="2.5" />
                  <rect x="12.5" y="14" width="2.5" height="2.5" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Iced Coffee</span>
              </label>
            </div>

            <!-- PRAF -->
            <div class="categoryButtons">
              <input type="radio" id="praf_module" name="module" class="hidden peer" onclick="showModule('praf')" />
              <label for="praf_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z" />
                  <path d="M6 9c0-3 3-5 6-5s6 2 6 5" />
                  <path d="M12 4V2" />
                  <path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Praf</span>
              </label>
            </div>

            <!-- PROMOS -->
            <div class="categoryButtons">
              <input type="radio" id="promos_module" name="module" class="hidden peer" onclick="showModule('promos')" />
              <label for="promos_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z" />
                  <path d="M6 7h12" />
                  <path d="M12 2v5" />
                  <polygon points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Promos</span>
              </label>
            </div>

            <!-- BROSTY -->
            <div class="categoryButtons">
              <input type="radio" id="brosty_module" name="module" class="hidden peer" onclick="showModule('brosty')" />
              <label for="brosty_module"
                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                     bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                     transition-all duration-200 select-none
                     peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                     peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                     hover:border-[var(--text-color)] active:scale-95">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
                  <path d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2" />
                  <path d="M15 5l2 4" />
                </svg>
                <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Brosty</span>
              </label>
            </div>

            <!-- RECALL ORDER -->
            <button
              onclick="openKioskModal()"
              class="flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                   bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer
                   transition-all duration-200 select-none
                   hover:border-[var(--text-color)] active:scale-95">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 -960 960 960" fill="currentColor">
                <path d="M120-520v-320h320v320H120Zm80-80h160v-160H200v160Zm-80 480v-320h320v320H120Zm80-80h160v-160H200v160Zm320-320v-320h320v320H520Zm80-80h160v-160H600v160Zm160 480v-80h80v80h-80ZM520-360v-80h80v80h-80Zm80 80v-80h80v80h-80Zm-80 80v-80h80v80h-80Zm80 80v-80h80v80h-80Zm80-80v-80h80v80h-80Zm0-160v-80h80v80h-80Zm80 80v-80h80v80h-80Z" />
              </svg>
              <span class="text-xs font-semibold tracking-wide whitespace-nowrap">Recall Order</span>
            </button>

          </fieldset>
        </div>
        <!-- ── END TOP BAR ── -->

        <!-- ── MENU CONTENT AREA ── -->
        <div class="flex-1 overflow-y-auto px-3 py-3">

          <!-- Kiosk Order Modal -->
          <div id="kioskModal"
            class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-[var(--background-color)] text-[var(--text-color)] border border-[var(--container-border)] p-6 rounded-2xl shadow-2xl w-[320px] text-center">
              <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--container-border)]">
                <h2 class="text-base font-bold tracking-tight">Recall Order</h2>
                <button onclick="closeKioskModal()" class="text-[var(--text-color)] opacity-50 hover:opacity-100 text-2xl leading-none">&times;</button>
              </div>
              <p class="text-xs opacity-60 mb-3">Scan QR code or enter order number</p>
              <input id="kioskInput"
                type="text"
                inputmode="numeric"
                pattern="[0-9]*"
                autofocus
                placeholder="e.g. 00123"
                class="w-full border border-[var(--container-border)] rounded-xl p-3 mb-5 text-center text-lg font-mono
                     placeholder-[var(--text-color)] placeholder-opacity-30
                     text-[var(--text-color)] bg-[var(--background-color)]
                     focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30">
              <div class="flex gap-2">
                <button onclick="closeKioskModal()"
                  class="flex-1 py-3 rounded-xl border border-[var(--container-border)] text-sm font-semibold
                       text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200">
                  Cancel
                </button>
                <button onclick="submitKioskOrder()"
                  class="flex-1 py-3 rounded-xl bg-[var(--text-color)] text-[var(--background-color)] text-sm font-semibold
                       hover:opacity-80 transition-all duration-200">
                  Load Order
                </button>
              </div>
            </div>
          </div>

          <script>
            let kioskModal = document.getElementById("kioskModal");
            let kioskInput = document.getElementById("kioskInput");

            function openKioskModal() {
              kioskModal.classList.remove("hidden");
              kioskInput.focus();
            }

            function closeKioskModal() {
              kioskModal.classList.add("hidden");
              kioskInput.value = "";
            }

            function sanitizeInput(input) {
              return input.replace(/[^0-9]/g, "");
            }

            function submitKioskOrder() {
              let value = kioskInput.value.trim();
              value = sanitizeInput(value);
              if (!value) {
                Swal.fire({
                  icon: "warning",
                  title: "Invalid Input",
                  text: "Please enter a valid numeric QR code."
                });
                return;
              }
              if (!/^\d+$/.test(value)) {
                Swal.fire({
                  icon: "error",
                  title: "Invalid Code",
                  text: "Only numbers are allowed in the QR input."
                });
                return;
              }
              let encodedValue = encodeURIComponent(value);
              window.location.href = `../pos/loadKioskOrder.php?id=${encodedValue}`;
            }
            kioskInput.addEventListener("input", () => {
              kioskInput.value = sanitizeInput(kioskInput.value);
            });
            kioskInput.addEventListener("keydown", (e) => {
              if (e.key === "Enter") {
                e.preventDefault();
                submitKioskOrder();
              }
            });
          </script>

          <!-- POP-UP MODAL FOR ORDERING -->
          <?php include_once "../../app/includes/POS/POSPopUpModalOrdering.php"; ?>

          <!-- ── CATEGORY SECTIONS ── -->

          <!-- Milk Tea -->
          <section id="milktea" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Milk Tea</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="milkteaMenu">
              <?php $category_id = 1;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Fruit Tea -->
          <section id="fruittea" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Fruit Tea</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="fruitTeaMenu">
              <?php $category_id = 2;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Hot Brew -->
          <section id="hotbrew" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Hot Brew</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="hotBrewMenu">
              <?php $category_id = 3;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Praf -->
          <section id="praf" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Praf</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="prafMenu">
              <?php $category_id = 4;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Iced Coffee -->
          <section id="icedcoffee" class="hidden" aria-labelledby="icedcoffeeTitle">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Iced Coffee</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="icedCoffeeMenu">
              <?php $category_id = 6;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Promos -->
          <section id="promos" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Promos</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="promosMenu">
              <?php $category_id = 7;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <!-- Brosty -->
          <section id="brosty" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Brosty</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
            <div class="gap-2 mt-1 justify-center items-center text-black" id="brostyMenu">
              <?php $category_id = 5;
              include "../../app/includes/POS/fetchProducts.php"; ?>
            </div>
          </section>

          <section id="modify" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Modify</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
          </section>

          <section id="addOns" class="hidden">
            <div class="flex items-center gap-3 mb-4">
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
              <h1 class="text-sm font-bold tracking-widest uppercase text-[var(--text-color)] opacity-60">Add-ons</h1>
              <div class="h-px flex-1 bg-[var(--container-border)]"></div>
            </div>
          </section>

        </div>
        <!-- ── END MENU CONTENT AREA ── -->

      </section>
      <!-- ── END MENU CONTAINER ── -->


      <!-- ============================================================
         CART PANEL — Desktop/landscape side panel + portrait overlay
    ============================================================ -->
      <section
        id="cart"
        class="animate-[fadeIn_0.3s_ease]
             hidden
             portrait:fixed portrait:inset-0 portrait:z-40 portrait:bg-black/50 portrait:backdrop-blur-sm
             portrait:flex portrait:items-end
             landscape:block landscape:relative landscape:col-span-1"
        aria-label="Order Summary">

        <div
          id="cartBox"
          class="bg-[var(--background-color)]
               portrait:rounded-t-3xl portrait:w-full portrait:max-h-[85vh]
               landscape:rounded-2xl landscape:h-[85vh] landscape:w-full
               border border-[var(--container-border)] shadow-2xl
               flex flex-col overflow-hidden
               portrait:animate-[slideUp_0.3s_ease]">

          <!-- Cart Header -->
          <div class="flex items-center justify-between px-5 py-4 border-b border-[var(--container-border)]">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-5 h-5 text-[var(--text-color)]">
                <path fill="currentColor" d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
              </svg>
              <h2 class="font-bold text-base text-[var(--text-color)] tracking-tight">Current Order</h2>
            </div>
            <button
              onclick="toggleCart()"
              class="portrait:flex landscape:hidden w-8 h-8 rounded-full border border-[var(--container-border)]
                   items-center justify-center text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500
                   transition-all duration-200 text-lg font-bold">
              &times;
            </button>
          </div>

          <!-- Scrollable order list -->
          <div class="flex-1 overflow-y-auto px-4 py-3 space-y-2 text-[var(--text-color)]">
            <div id="productList">
              <!-- items injected here -->
            </div>
          </div>

          <!-- Checkout Button -->
          <div class="px-4 py-3 border-t border-[var(--container-border)]">
            <button
              onclick="openCalculator()"
              class="w-full py-4 rounded-2xl bg-green-600 hover:bg-green-500 active:scale-[0.98]
                   text-white font-bold text-base tracking-wide
                   flex items-center justify-center gap-2 transition-all duration-200 shadow-lg">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-5 h-5">
                <path fill="currentColor" d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
              </svg>
              Proceed to Checkout
            </button>
          </div>
        </div>
      </section>

      <!-- FLOATING CART BUTTON (portrait only) -->
      <section class="landscape:hidden fixed bottom-5 right-5 z-30">
        <button
          onclick="toggleCart()"
          class="relative h-14 px-5 bg-green-600 hover:bg-green-500 active:scale-95
               text-white font-bold flex items-center gap-2 rounded-2xl shadow-xl
               transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-5 h-5">
            <path fill="currentColor" d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
          </svg>
          <span class="text-sm">Cart</span>
          <!-- Badge slot — populate via JS if needed -->
        </button>
      </section>


      <!-- ============================================================
         PAYMENT CALCULATOR MODAL
    ============================================================ -->
      <div id="calculatorModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm hidden flex items-center justify-center z-50">
        <div id="calculator"
          class="bg-[var(--background-color)] border border-[var(--container-border)] rounded-3xl shadow-2xl
               w-full max-w-sm mx-4 p-5 animate-[fadeIn_0.25s_ease]">

          <!-- Header -->
          <div class="flex items-center justify-between mb-5 pb-3 border-b border-[var(--container-border)]">
            <h2 class="text-lg font-bold text-[var(--text-color)] tracking-tight">Payment</h2>
            <button onclick="closeCalculator()"
              class="w-8 h-8 flex items-center justify-center rounded-full border border-[var(--container-border)]
                   text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200 text-xl">
              &times;
            </button>
          </div>

          <!-- Summary Card -->
          <div class="rounded-2xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)] p-4 mb-4 space-y-2 text-[var(--text-color)]">
            <div class="flex justify-between text-sm">
              <span class="opacity-60">Subtotal</span>
              <span id="totalAmount" class="font-bold">₱0</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="opacity-60">Discount</span>
              <span id="discountAmount" class="font-bold text-amber-500">₱0</span>
            </div>
            <div class="flex justify-between text-sm">
              <span class="opacity-60">E-Pay</span>
              <span id="epayAmount" class="font-bold text-blue-500">₱0</span>
            </div>
            <div class="border-t border-[var(--container-border)] pt-2 flex justify-between text-sm">
              <span class="opacity-60">Tendered</span>
              <span id="tenderedAmount" class="font-bold">₱0</span>
            </div>
            <div class="flex justify-between">
              <span class="font-semibold text-[var(--text-color)]">Change</span>
              <span id="changeAmount" class="font-bold text-green-500 text-lg">₱0</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="grid grid-cols-2 gap-2 mb-4">
            <!-- SC & PWD Discount -->
            <button id="pwdBtn" onclick="openManagerQrModal(0.2)"
              class="flex items-center gap-2 px-3 py-3 rounded-xl border border-[var(--container-border)]
                   bg-[var(--calc-bg-btn)] text-[var(--text-color)]
                   hover:border-[var(--text-color)] active:scale-95 transition-all duration-200">
              <span class="flex gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-5 h-5">
                  <path fill="currentColor" d="m320-40-64-48 104-139v-213q0-31 5-67.5t15-67.5l-60 33v142h-80v-188l176-100q25-14 43.5-21.5T494-717q25 0 45.5 21.5T587-628q32 54 58 81t56 41q11-8 19-11t19-3q25 0 43 18t18 42v420h-40v-420q0-8-6-14t-14-6q-8 0-14 6t-6 14v50h-40v-19q-54-23-84-51.5T543-557q-11 28-17.5 68.5T521-412l79 112v260h-80v-200l-71-102-9 142L320-40Zm220-700q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-5 h-5">
                  <path fill="currentColor" d="M480-720q-33 0-56.5-23.5T400-800q0-33 23.5-56.5T480-880q33 0 56.5 23.5T560-800q0 33-23.5 56.5T480-720ZM680-80v-200H480q-33 0-56.5-23.5T400-360v-240q0-33 23.5-56.5T480-680q24 0 41.5 10.5T559-636q55 66 99.5 90.5T760-520v80q-53 0-107-23t-93-55v138h120q33 0 56.5 23.5T760-300v220h-80Zm-280 0q-83 0-141.5-58.5T200-280q0-72 45.5-127T360-476v82q-35 14-57.5 44.5T280-280q0 50 35 85t85 35q39 0 69.5-22.5T514-240h82q-14 69-69 114.5T400-80Z" />
                </svg>
              </span>
              <span class="text-xs font-semibold">SC & PWD</span>
            </button>

            <!-- E-Payment -->
            <button onclick="openManagerEPaymentModal()"
              class="flex items-center gap-2 px-3 py-3 rounded-xl border border-[var(--container-border)]
                   bg-[var(--calc-bg-btn)] text-[var(--text-color)]
                   hover:border-[var(--text-color)] active:scale-95 transition-all duration-200">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 shrink-0">
                <rect x="2" y="6" width="20" height="12" rx="2" ry="2" fill="none" stroke="currentColor" stroke-width="2" />
                <line x1="2" y1="10" x2="22" y2="10" stroke="currentColor" stroke-width="2" />
                <rect x="4" y="12" width="3" height="3" rx="0.5" ry="0.5" fill="currentColor" />
              </svg>
              <span class="text-xs font-semibold">E-Payment</span>
            </button>
          </div>

          <!-- Quick Bills -->
          <div class="grid grid-cols-4 gap-2 mb-4">
            <?php foreach ([500, 200, 100, 50, 20, 10, 5] as $bill): ?>
              <button onclick="addCash(<?= $bill ?>)"
                class="py-2.5 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                   text-[var(--text-color)] text-sm font-semibold
                   hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                   active:scale-95 transition-all duration-150">
                ₱<?= $bill ?>
              </button>
            <?php endforeach; ?>
            <button onclick="addCash(0)"
              class="py-2.5 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                   text-[var(--text-color)] text-sm font-semibold
                   hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                   active:scale-95 transition-all duration-150">
              .00
            </button>
          </div>

          <!-- Numeric Keypad -->
          <div class="grid grid-cols-3 gap-2">
            <?php foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $n): ?>
              <button onclick="manualKey(<?= $n ?>)"
                class="py-4 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                   text-[var(--text-color)] text-lg font-semibold
                   hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                   active:scale-95 transition-all duration-150">
                <?= $n ?>
              </button>
            <?php endforeach; ?>
            <button onclick="clearCash()"
              class="py-4 rounded-xl bg-red-500 hover:bg-red-400 active:scale-95 text-white text-sm font-bold transition-all duration-150">
              Clear
            </button>
            <button onclick="manualKey(0)"
              class="py-4 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                   text-[var(--text-color)] text-lg font-semibold
                   hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                   active:scale-95 transition-all duration-150">
              0
            </button>
            <button onclick="finalizePayment()"
              class="py-4 rounded-xl bg-green-500 hover:bg-green-400 active:scale-95 text-white font-bold text-sm tracking-wide transition-all duration-150">
              Enter
            </button>
          </div>


          <!-- QR Popup -->
          <div id="qrPopup"
            class="fixed inset-0 hidden flex items-center justify-center z-50 text-[var(--text-color)] animate-[fadeIn_0.3s_ease]">
            <div class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-5 mx-4 border border-[var(--container-border)]">
              <div class="flex justify-between items-center mb-4 pb-3 border-b border-[var(--container-border)]">
                <h2 class="text-base font-bold">Scan QR</h2>
                <button onclick="closeQrPopup()"
                  class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--container-border)]
                       hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200 text-lg">
                  &times;
                </button>
              </div>
              <form action="POST" class="flex flex-col gap-3">
                <input type="text" name="rewardingCustQR" placeholder="Scan QR"
                  class="w-full border border-[var(--container-border)] rounded-xl p-3 bg-transparent text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30">
              </form>
              <button onclick="closeQrPopup()"
                class="mt-4 w-full py-3 bg-[var(--text-color)] text-[var(--background-color)] rounded-xl font-semibold hover:opacity-80 transition-all duration-200">
                Done
              </button>
            </div>
          </div>

          <!-- Manager Verification for E-Payment -->
          <div id="managerEPaymentModal"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 animate-[fadeIn_0.3s_ease]">
            <div class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl p-5 w-[300px] border border-[var(--container-border)]">
              <div id="managerEPayVerifySection">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--container-border)]">
                  <h2 class="text-base font-bold">Manager Verification</h2>
                  <button onclick="closeManagerEPaymentModal()"
                    class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--container-border)]
                         hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200 text-lg">
                    &times;
                  </button>
                </div>
                <p class="text-xs opacity-60 mb-3">Required to process e-payment</p>
                <input type="password" id="managerEPayInput" placeholder="Scan Manager ID"
                  autocomplete="off" inputmode="numeric" pattern="[0-9]*"
                  class="w-full p-3 border border-[var(--container-border)] rounded-xl
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30 mb-4" />
                <div class="flex gap-2">
                  <button onclick="closeManagerEPaymentModal()"
                    class="flex-1 py-3 rounded-xl border border-[var(--container-border)] text-sm font-semibold
                         hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200">
                    Cancel
                  </button>
                  <button onclick="verifyManagerEPayment()"
                    class="flex-1 py-3 rounded-xl bg-[var(--text-color)] text-[var(--background-color)] text-sm font-semibold hover:opacity-80 transition-all duration-200">
                    Verify
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- E-Payment Details Popup -->
          <div id="EPaymentPopup"
            class="fixed inset-0 hidden flex items-center justify-center z-50 text-[var(--text-color)] animate-[fadeIn_0.3s_ease]">
            <div id="EPayment" class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl w-full max-w-xs p-5 mx-4 border border-[var(--container-border)]">
              <div class="flex justify-between items-center mb-4 pb-3 border-b border-[var(--container-border)]">
                <h2 class="text-base font-bold">E-Payment Details</h2>
                <button onclick="closeEPaymentPopup()"
                  class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--container-border)]
                       hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200 text-lg">
                  &times;
                </button>
              </div>
              <form onsubmit="finalizeEPayment(); return false;" class="flex flex-col gap-3">
                <label class="text-xs font-semibold opacity-60 uppercase tracking-wide">Reference Number</label>
                <input type="text" id="refNumber" name="refNumber" placeholder="Enter reference number"
                  class="w-full border border-[var(--container-border)] rounded-xl p-3 bg-transparent
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30">
                <label class="text-xs font-semibold opacity-60 uppercase tracking-wide">Amount</label>
                <input type="number" name="epayAmountInput" placeholder="0.00"
                  class="w-full border border-[var(--container-border)] rounded-xl p-3 bg-transparent
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30" min="0" step="0.01">
                <input type="hidden" id="epayAmountHidden" name="epay_amount" value="0">
                <input type="hidden" id="refNumberHidden" name="refNumber" value="">
                <button type="submit"
                  class="mt-1 w-full py-3 bg-[var(--text-color)] text-[var(--background-color)] rounded-xl font-semibold hover:opacity-80 transition-all duration-200">
                  Confirm Payment
                </button>
              </form>
            </div>
          </div>

        </div>
      </div>
      <!-- ── END CALCULATOR MODAL ── -->


      <!-- ============================================================
         MANAGER DISCOUNT MODAL
    ============================================================ -->
      <div id="managerDiscountModal"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 animate-[fadeIn_0.3s_ease]">
        <div class="bg-[var(--background-color)] text-[var(--text-color)] rounded-2xl shadow-2xl p-5 w-[320px] border border-[var(--container-border)]">

          <!-- Manager Verify -->
          <div id="managerVerifySection">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--container-border)]">
              <h2 class="text-base font-bold">Manager Verification</h2>
              <button onclick="closeManagerDiscountModal()"
                class="w-7 h-7 flex items-center justify-center rounded-full border border-[var(--container-border)]
                     hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200 text-lg">
                &times;
              </button>
            </div>
            <p class="text-xs opacity-60 mb-3">Discount requires manager approval</p>
            <input type="password" id="managerInput" placeholder="Scan Manager ID"
              autocomplete="off" inputmode="numeric" pattern="[0-9]*"
              class="w-full p-3 border border-[var(--container-border)] rounded-xl
                   bg-[var(--background-color)] text-[var(--text-color)]
                   focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30 mb-4" />
            <div class="flex gap-2">
              <button onclick="closeManagerDiscountModal()"
                class="flex-1 py-3 rounded-xl border border-[var(--container-border)] text-sm font-semibold
                     hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200">
                Cancel
              </button>
              <button onclick="verifyManager()"
                class="flex-1 py-3 rounded-xl bg-[var(--text-color)] text-[var(--background-color)] text-sm font-semibold hover:opacity-80 transition-all duration-200">
                Verify
              </button>
            </div>
          </div>

          <!-- Discount Form -->
          <div id="discountFormSection" class="hidden">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-[var(--container-border)]">
              <h2 class="text-base font-bold">Discount Details</h2>
            </div>

            <input type="hidden" id="discountType" name="discountType" />

            <p class="text-xs font-semibold opacity-60 uppercase tracking-wide mb-2">Discount Type</p>
            <div class="flex gap-2 mb-4">
              <!-- PWD -->
              <label class="flex-1">
                <input type="radio" name="discountChoice" value="PWD" class="peer hidden" checked />
                <div class="flex flex-col items-center gap-1 py-3 rounded-xl border border-[var(--container-border)]
                          bg-[var(--calc-bg-btn)] cursor-pointer
                          peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                          peer-checked:border-[var(--text-color)] transition-all duration-200 hover:opacity-80">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
                    <path fill="currentColor" d="M480-720q-33 0-56.5-23.5T400-800q0-33 23.5-56.5T480-880q33 0 56.5 23.5T560-800q0 33-23.5 56.5T480-720ZM680-80v-200H480q-33 0-56.5-23.5T400-360v-240q0-33 23.5-56.5T480-680q24 0 41.5 10.5T559-636q55 66 99.5 90.5T760-520v80q-53 0-107-23t-93-55v138h120q33 0 56.5 23.5T760-300v220h-80Zm-280 0q-83 0-141.5-58.5T200-280q0-72 45.5-127T360-476v82q-35 14-57.5 44.5T280-280q0 50 35 85t85 35q39 0 69.5-22.5T514-240h82q-14 69-69 114.5T400-80Z" />
                  </svg>
                  <span class="text-xs font-bold">PWD</span>
                </div>
              </label>
              <!-- SC -->
              <label class="flex-1">
                <input type="radio" name="discountChoice" value="SC" class="peer hidden" />
                <div class="flex flex-col items-center gap-1 py-3 rounded-xl border border-[var(--container-border)]
                          bg-[var(--calc-bg-btn)] cursor-pointer
                          peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                          peer-checked:border-[var(--text-color)] transition-all duration-200 hover:opacity-80">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-6 h-6">
                    <path fill="currentColor" d="m320-40-64-48 104-139v-213q0-31 5-67.5t15-67.5l-60 33v142h-80v-188l176-100q25-14 43.5-21.5T494-717q25 0 45.5 21.5T587-628q32 54 58 81t56 41q11-8 19-11t19-3q25 0 43 18t18 42v420h-40v-420q0-8-6-14t-14-6q-8 0-14 6t-6 14v50h-40v-19q-54-23-84-51.5T543-557q-11 28-17.5 68.5T521-412l79 112v260h-80v-200l-71-102-9 142L320-40Zm220-700q-33 0-56.5-23.5T460-820q0-33 23.5-56.5T540-900q33 0 56.5 23.5T620-820q0 33-23.5 56.5T540-740Z" />
                  </svg>
                  <span class="text-xs font-bold">SC</span>
                </div>
              </label>
            </div>

            <script>
              document.querySelectorAll('input[name="discountChoice"]').forEach(radio => {
                radio.addEventListener("change", () => {
                  document.getElementById("discountType").value = radio.value;
                });
              });
              document.getElementById("discountType").value = document.querySelector('input[name="discountChoice"]:checked').value;
            </script>

            <div class="flex flex-col gap-3 mb-4">
              <div>
                <label class="text-xs font-semibold opacity-60 uppercase tracking-wide block mb-1">ID Number</label>
                <input type="number" id="discountId" placeholder="Enter ID Number"
                  class="w-full p-3 border border-[var(--container-border)] rounded-xl
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30" />
              </div>
              <div>
                <label class="text-xs font-semibold opacity-60 uppercase tracking-wide block mb-1">First Name</label>
                <input type="text" id="discountFirstName" placeholder="First Name"
                  class="w-full p-3 border border-[var(--container-border)] rounded-xl
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30" />
              </div>
              <div>
                <label class="text-xs font-semibold opacity-60 uppercase tracking-wide block mb-1">Last Name</label>
                <input type="text" id="discountLastName" placeholder="Last Name"
                  class="w-full p-3 border border-[var(--container-border)] rounded-xl
                       bg-[var(--background-color)] text-[var(--text-color)]
                       focus:outline-none focus:ring-2 focus:ring-[var(--text-color)]/30" />
              </div>
            </div>

            <div class="flex gap-2">
              <button onclick="closeManagerDiscountModal()"
                class="flex-1 py-3 rounded-xl border border-[var(--container-border)] text-sm font-semibold
                     hover:bg-red-500 hover:text-white hover:border-red-500 transition-all duration-200">
                Cancel
              </button>
              <button onclick="applyDiscount()"
                class="flex-1 py-3 rounded-xl bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition-all duration-200">
                Apply Discount
              </button>
            </div>
          </div>

        </div>
      </div>
      <!-- ── END MANAGER DISCOUNT MODAL ── -->

    </section>

  </main>

  <!-- Slide-up animation for portrait cart -->
  <style>
    @keyframes slideUp {
      from {
        transform: translateY(100%);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.97);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>
  <script>
    /* ================================
   STATE VARIABLES
================================ */
    let epayDiscountRateTemp = 0;
    let epayAmount = 0; // stores finalized E-Payment amount

    /* ================================
       MODAL FUNCTIONS
    ================================ */
    window.openManagerEPaymentModal = function() {
      document.getElementById("managerEPaymentModal").classList.remove("hidden");
      document.getElementById("managerEPayVerifySection").classList.remove("hidden");
      document.getElementById("managerEPayInput").value = "";
      document.getElementById("managerEPayInput").focus();
    }

    window.closeManagerEPaymentModal = function() {
      document.getElementById("managerEPaymentModal").classList.add("hidden");
      document.getElementById("managerEPayInput").value = "";
    }

    window.openEPaymentPopup = function() {
      document.getElementById("EPaymentPopup").classList.remove("hidden");
      document.getElementById("refNumber").value = "";
      document.querySelector('#EPayment input[name="epayAmountInput"]').value = "";
    }

    window.closeEPaymentPopup = function() {
      document.getElementById("EPaymentPopup").classList.add("hidden");
    }

    /* ================================
       MANAGER VERIFICATION
    ================================ */
    window.verifyManagerEPayment = async function() {
      let staffId = document.getElementById("managerEPayInput").value.trim();
      if (!staffId) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Please scan or enter a manager ID."
        });
        return;
      }

      try {
        let res = await fetch("../../app/includes/POS/POSApproveDisc.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            staff_id: staffId
          })
        });
        let data = await res.json();

        if (data.success) {
          closeManagerEPaymentModal();
          openEPaymentPopup();
          Swal.fire({
            icon: "success",
            title: "Verified",
            text: "Manager approved E-Payment!"
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Denied",
            text: data.message || "Not authorized."
          });
        }
      } catch (err) {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: err.message
        });
      }
    }

    /* ================================
       REFERENCE NUMBER INPUT RESTRICTION
    ================================ */
    let refInput = document.getElementById('refNumber');
    refInput.addEventListener('input', () => {
      refInput.value = refInput.value.replace(/[^a-zA-Z0-9]/g, '');
    });

    /* ================================
       FINALIZE E-PAYMENT
    ================================ */
    window.finalizeEPayment = function() {
      let refNumber = document.getElementById('refNumber').value.trim();
      let amountInput = parseFloat(document.querySelector('#EPayment input[name="epayAmountInput"]').value) || 0;

      if (!refNumber || amountInput <= 0) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Enter valid E-Payment details.'
        });
        return;
      }

      // Store in hidden fields
      document.getElementById('epayAmountHidden').value = amountInput;
      document.getElementById('refNumberHidden').value = refNumber;

      // Update global variable for display
      epayAmount = amountInput;

      // Update calculator summary
      updateDisplay();

      // Close popup
      closeEPaymentPopup();

      // SweetAlert confirmation
      Swal.fire({
        icon: 'success',
        title: 'E-Payment Added',
        html: `Reference: <b>${refNumber}</b><br>Amount: <b>₱${amountInput.toFixed(2)}</b>`,
        timer: 2000,
        timerProgressBar: true,
        showConfirmButton: false
      });
    }

    /* ================================
       RESET E-PAYMENT
    ================================ */
    window.resetEPayment = function() {
      epayAmount = 0;
      epayDiscountRateTemp = 0;

      document.getElementById('epayAmountHidden').value = '';
      document.getElementById('refNumberHidden').value = '';
      document.getElementById('refNumber').value = '';
      document.querySelector('#EPayment input[name="epayAmountInput"]').value = '';

      updateDisplay(); // refresh calculator
    }





    document.addEventListener("DOMContentLoaded", () => {
      let discountRateTemp = 0;

      // Restrict to numeric input
      let managerInput = document.getElementById("managerInput");
      managerInput.addEventListener("input", () => {
        managerInput.value = managerInput.value.replace(/\D/g, ""); // remove non-numbers
      });

      // ✅ Allow Enter key to trigger verification
      managerInput.addEventListener("keydown", (e) => {
        if (e.key === "Enter") {
          e.preventDefault();
          verifyManager(); // same function you already have
        }
      });

      // Open modal
      window.openManagerQrModal = function(rate) {
        discountRateTemp = rate;
        document.getElementById("managerDiscountModal").classList.remove("hidden");
        document.getElementById("managerVerifySection").classList.remove("hidden");
        document.getElementById("discountFormSection").classList.add("hidden");
        managerInput.focus();
      }

      // Close modal
      window.closeManagerDiscountModal = function() {
        document.getElementById("managerDiscountModal").classList.add("hidden");
        managerInput.value = "";
      }

      // Manager verification
      window.verifyManager = async function() {
        let staffId = managerInput.value.trim();
        if (!staffId) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Please scan or enter a manager ID."
          });
          return;
        }

        try {
          let res = await fetch("../../app/includes/POS/POSApproveDisc.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json"
            },
            body: JSON.stringify({
              staff_id: staffId
            })
          });
          let data = await res.json();

          if (data.success) {
            document.getElementById("managerVerifySection").classList.add("hidden");
            document.getElementById("discountFormSection").classList.remove("hidden");

            // Pre-fill discount type & amount
            document.getElementById("discountType").value = data.type || "PWD";
            let total = parseFloat(document.getElementById("totalAmount").innerText.replace(/[^0-9.-]+/g, "")) || 0;
            document.getElementById("discountAmount").value = (total * discountRateTemp).toFixed(2);

            Swal.fire({
              icon: "success",
              title: "Verified",
              text: "Manager approved discount!"
            });

          } else {
            Swal.fire({
              icon: "error",
              title: "Denied",
              text: data.message || "Not authorized."
            });
          }
        } catch (err) {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: err.message
          });
        }
      }

      // Apply discount
      window.applyDiscount = function() {
        discountRate = discountRateTemp;
        updateDisplay(); // your existing function
        closeManagerDiscountModal();

        Swal.fire({
          icon: "success",
          title: "Success",
          text: "Discount applied!"
        });
      }
    });
  </script>



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

  <script src="../JS/pos/POSKioskModal.js"></script>
  <!-- linked JS file below for Manager refund transaction -->



  <!-- linked JS file below for theme toggle interaction -->
  <script src="../JS/shared/theme-toggle.js"></script>
  <!-- linked JS file below for checking DB status -->


  <!-- linked JS file below for Logoutt BTN -->
  <script src="../JS/shared//dropDownLogout.js"></script>
  <!-- 
      ========================
      =   JS Links Ends Here =
      ========================
    -->

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- 
      =====================================
      =   API TO CHECK THE STOCKS STATUS  =
      =====================================
    -->
  <script>
    async function autoStockCheck() {
      try {
        const res = await fetch('../../app/includes/events/stockStatusChecker.php');
        const data = await res.json();
        // console.log("Auto Stock Status Checker :", data);
      } catch (err) {
        console.error("Auto Stock Error:", err);
      }
    }

    setInterval(autoStockCheck, 1000);
  </script>

  <!-- 
      ==================================================
      =   API TO MAP EACH TRANS THEN DEDUCT TO INV QTY =
      ==================================================
    -->

  <script>
    async function autoInventoryDeduct() {
      try {
        const res = await fetch('../../app/includes/events/inventoryDeduct.php');
        const data = await res.json();
        console.log("Inventory Deduction:", data);
      } catch (err) {
        console.error("Auto Inventory Deduction Error:", err);
      }
    }

    // Run every 1 second
    setInterval(autoInventoryDeduct, 1000);
  </script>


  <!-- <script>
    async function milkteaStockCheck() {
      try {
        const res = await fetch('../../app/includes/events/milkteaStatusChecker.php');
        const data = await res.json();
        console.log("Milktea Status Checker :", data);
      } catch (err) {
        console.error("Milktea Stock Error:", err);
      }
    }

    setInterval(milkteaStockCheck, 1000);
  </script> -->
</body>

</html>