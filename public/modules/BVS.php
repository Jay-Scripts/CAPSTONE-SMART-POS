<?php
include "../../app/config/dbConnection.php";

$selectQueryToGetPaidOrders = "
SELECT 
    rt.REG_TRANSACTION_ID,
    rt.STATUS,
    rt.TOTAL_AMOUNT,
    UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(rt.date_added) AS elapsed_seconds,
    ti.ITEM_ID,
    ti.QUANTITY,
    ti.PRICE,
    pd.product_name,
    ps.SIZE AS size_name
FROM REG_TRANSACTION rt
JOIN TRANSACTION_ITEM ti ON rt.REG_TRANSACTION_ID = ti.REG_TRANSACTION_ID
JOIN PRODUCT_DETAILS pd ON ti.PRODUCT_ID = pd.PRODUCT_ID
JOIN PRODUCT_SIZES ps ON ti.SIZE_ID = ps.SIZE_ID
WHERE rt.STATUS = 'PAID'
ORDER BY rt.date_added DESC, ti.ITEM_ID ASC
";

$stmt = $conn->query($selectQueryToGetPaidOrders);

$transactions = [];

// Organize data by transaction
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $regId = $row['REG_TRANSACTION_ID'];

  if (!isset($transactions[$regId])) {
    $transactions[$regId] = [
      'status' => $row['STATUS'],
      'total_amount' => $row['TOTAL_AMOUNT'],
      'elapsed_seconds' => $row['elapsed_seconds'],
      'items' => []
    ];
  }

  $itemId = $row['ITEM_ID'];
  // Fetch add-ons for this item
  $addonsStmt = $conn->prepare("
    SELECT pa.add_ons_name
    FROM item_add_ons ia
    JOIN product_add_ons pa ON ia.add_ons_id = pa.add_ons_id
    WHERE ia.item_id = :itemId
");
  $addonsStmt->execute(['itemId' => $itemId]);
  $addons = [];
  while ($a = $addonsStmt->fetch(PDO::FETCH_ASSOC)) {
    $addons[] = $a['add_ons_name']; // no price column
  }

  // Fetch modifications for this item
  $modsStmt = $conn->prepare("
    SELECT pm.modification_name
    FROM item_modification im
    JOIN product_modifications pm ON im.modification_id = pm.modification_id
    WHERE im.item_id = :itemId
");
  $modsStmt->execute(['itemId' => $itemId]);
  $mods = [];
  while ($m = $modsStmt->fetch(PDO::FETCH_ASSOC)) {
    $mods[] = $m['modification_name'];
  }


  $transactions[$regId]['items'][] = [

    'quantity' => $row['QUANTITY'],
    'product_name' => $row['product_name'],
    'size' => $row['size_name'],
    'price' => $row['PRICE'],
    'addons' => $addons,
    'mods' => $mods
  ];
}

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
  <!-- <link href="../css/output.css" rel="stylesheet" /> -->
  <!--  linked script below cdn of tailwind for online use -->
  <script src="https://cdn.tailwindcss.com"></script>


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
  <section id="ordersContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
    <?php if (empty($transactions)): ?>
      <!-- Placeholder Card -->
      <div class="order-card flex flex-col items-center justify-center m-4 p-6 rounded-xl border-2 border-dashed text-center transition transform hover:scale-105 duration-200">
        <p class="text-gray-400 text-lg font-semibold">No transactions yet</p>
        <p class="text-gray-500 text-sm mt-2">Paid transactions will appear here</p>
        <svg class="w-16 h-16 mt-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
        </svg>
      </div>
    <?php else: ?>
      <?php foreach ($transactions as $regId => $trans): ?>
        <div class="order-card flex flex-col justify-between m-4 p-5 rounded-xl border-2 border-[var(--border-color)] bg-[var(--order-container)] shadow-lg transition transform hover:scale-105">
          <div>
            <h6 class="text-xl text-[var(--text-color)] font-semibold mb-2 truncate text-center border-b">
              Transaction #<?= $regId ?>
              <span class="font-bold text-green-400"><?= $trans['status'] ?></span>
            </h6>

            <div class="mt-4 space-y-3">
              <?php $totalCount = 0; ?>
              <?php foreach ($trans['items'] as $item): ?>
                <?php $totalCount += $item['quantity']; ?>
                <div class="rounded-lg">
                  <!-- Main item -->
                  <p class="text-sm text-[var(--text-color)] font-medium"><?= $item['quantity'] ?>x <?= $item['product_name'] ?> (<?= $item['size'] ?>)</p>

                  <!-- Add-ons -->
                  <?php if ($item['addons']): ?>
                    <div class="ml-4 mt-1 text-xs text-[var(--text-color)]">
                      <p>*Add-ons:</p>
                      <ul class="list-disc list-inside ml-4">
                        <?php foreach ($item['addons'] as $addon): ?>
                          <li><?= $addon ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>

                  <!-- Mods -->
                  <?php if ($item['mods']): ?>
                    <div class="ml-4 mt-1 text-xs text-[var(--text-color)]">
                      <p>*Mods:</p>
                      <ul class="list-disc list-inside ml-4">
                        <?php foreach ($item['mods'] as $mod): ?>
                          <li><?= $mod ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="mt-4 pt-2 border-t text-[var(--text-color)] flex justify-between items-center">
            <p class="font-bold text-lg">Total Items: <?= $totalCount ?></p>
            <button
              class="px-3 py-1 bg-green-500 text-[var(--text-color)] rounded-lg text-sm hover:bg-green-600 transition serve-btn"
              data-id="${trans.REG_TRANSACTION_ID}">
              Serve
            </button>

          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
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
  <!-- linked JS file below for printing pick slip and update order status to serve -->
  <script src="../JS/bvs/BVSServe.js"></script>

  <!-- 
      ======================
      = JS Links Ends Here =
      ======================
    -->
  <!-- linked JS file below for account Dropdown to logOut -->
  <script src="../JS/shared/dropDownLogout.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <script>
    async function fetchTransactions() {
      try {
        const response = await fetch('../../app/includes/BVS/BVSRealtimeOrderSync.php');
        const data = await response.json();
        const container = document.getElementById('ordersContainer');
        container.innerHTML = ''; // Clear old transactions

        if (!data || data.length === 0) {
          container.innerHTML = `
        <div class="order-card flex flex-col items-center justify-center m-4 p-6 rounded-xl border-2 border-dashed  text-center transition transform hover:scale-105 duration-200">
          <p class="text-gray-400 text-lg font-semibold">No transactions yet</p>
          <p class="text-gray-500 text-sm mt-2">Paid transactions will appear here</p>
        </div>`;
          return;
        }

        data.forEach(trans => {
          let totalCount = 0;
          let itemsHTML = '';

          trans.items.forEach(item => {
            totalCount += item.quantity;

            let addonsHTML = '';
            if (item.addons && item.addons.length > 0) {
              addonsHTML = `
            <div class="ml-4 mt-1 text-xs text-[var(--text-color)]">
              <p>*Add-ons:</p>
              <ul class="list-disc list-inside ml-4">
                ${item.addons.map(a => `<li>${a}</li>`).join('')}
              </ul>
            </div>`;
            }

            let modsHTML = '';
            if (item.mods && item.mods.length > 0) {
              modsHTML = `
            <div class="ml-4 mt-1 text-xs text-[var(--text-color)]">
              <p>*Mods:</p>
              <ul class="list-disc list-inside ml-4">
                ${item.mods.map(m => `<li>${m}</li>`).join('')}
              </ul>
            </div>`;
            }

            itemsHTML += `
          <div class="rounded-lg">
            <p class="text-sm text-[var(--text-color)] font-medium">
              ${item.quantity}x ${item.product_name} (${item.size})
            </p>
            ${addonsHTML}
            ${modsHTML}
          </div>`;
          });

          container.innerHTML += `
        <div class="order-card flex flex-col justify-between m-4 p-5 rounded-xl border-2 border-[var(--border-color)] bg-[var(--order-container)] shadow-lg transition transform hover:scale-105">
          <div>
            <h6 class="text-xl text-[var(--text-color)] font-semibold mb-2 truncate text-center border-b">
              Transaction #${trans.REG_TRANSACTION_ID}
              <span class="font-bold text-green-400">${trans.status}</span>
            </h6>
            <div class="mt-4 space-y-3">
              ${itemsHTML}
            </div>
          </div>
          <div class="mt-4 pt-2 border-t text-[var(--text-color)] flex justify-between items-center">
            <p class="font-bold text-lg">Total Items: ${totalCount}</p>
            <button 
              class="px-3 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition serve-btn" 
              data-id="${trans.REG_TRANSACTION_ID}">
              Serve
            </button>
          </div>
        </div>`;
        });
      } catch (error) {
        console.error('Error fetching transactions:', error);
      }
    }

    // Refresh every 1 second
    setInterval(fetchTransactions, 1000);
    fetchTransactions(); // initial load
  </script>
</body>

</html>