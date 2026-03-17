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

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  $regId = $row['REG_TRANSACTION_ID'];
  if (!isset($transactions[$regId])) {
    $transactions[$regId] = [
      'status'          => $row['STATUS'],
      'total_amount'    => $row['TOTAL_AMOUNT'],
      'elapsed_seconds' => $row['elapsed_seconds'],
      'items'           => []
    ];
  }

  $itemId     = $row['ITEM_ID'];
  $addonsStmt = $conn->prepare("
    SELECT pa.add_ons_name FROM item_add_ons ia
    JOIN product_add_ons pa ON ia.add_ons_id = pa.add_ons_id
    WHERE ia.item_id = :itemId");
  $addonsStmt->execute(['itemId' => $itemId]);
  $addons = $addonsStmt->fetchAll(PDO::FETCH_COLUMN);

  $modsStmt = $conn->prepare("
    SELECT pm.modification_name FROM item_modification im
    JOIN product_modifications pm ON im.modification_id = pm.modification_id
    WHERE im.item_id = :itemId");
  $modsStmt->execute(['itemId' => $itemId]);
  $mods = $modsStmt->fetchAll(PDO::FETCH_COLUMN);

  $transactions[$regId]['items'][] = [
    'quantity'     => $row['QUANTITY'],
    'product_name' => $row['product_name'],
    'size'         => $row['size_name'],
    'price'        => $row['PRICE'],
    'addons'       => $addons,
    'mods'         => $mods,
  ];
}

session_start();
if (!isset($_SESSION['staff_name'])) {
  header("Location: ../auth/barista/baristaLogin.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BVS — Barista View</title>
  <link rel="shortcut icon" href="../assets/favcon/bvs.ico" type="image/x-icon" />
  <link href="../css/style.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
</head>

<body class="bg-[var(--background-color)] text-[var(--text-color)] font-sans min-h-screen flex flex-col">

  <!-- ── HEADER ── -->
  <header class="w-full flex justify-between items-center px-4 py-3 lg:px-6
                 bg-[var(--nav-bg)] border-b border-[var(--container-border)] shadow-sm z-50 shrink-0">
    <div class="flex items-center gap-3">
      <img src="../assets/SVG/LOGO/BLOGO.svg" class="h-10 theme-logo" alt="Logo" />
      <div>
        <h1 class="text-sm font-bold text-[var(--text-color)] leading-tight">Barista View System</h1>
        <p class="text-xs opacity-50 text-[var(--text-color)]">Incoming paid orders</p>
      </div>
    </div>

    <div class="flex items-center gap-2">
      <!-- Theme toggle -->
      <button id="theme-toggle" title="Toggle theme" aria-label="auto" aria-live="polite"
        class="w-9 h-9 flex items-center justify-center rounded-full border border-[var(--container-border)]
               hover:bg-[var(--container-border)] transition duration-200">
        <svg class="sun-and-moon text-[var(--text-color)]" aria-hidden="true" width="18" height="18" viewBox="0 0 24 24">
          <mask class="moon" id="moon-mask">
            <rect x="0" y="0" width="100%" height="100%" fill="white" />
            <circle cx="24" cy="10" r="6" fill="black" />
          </mask>
          <circle class="sun" cx="12" cy="12" r="6" mask="url(#moon-mask)" fill="currentColor" />
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

      <!-- Staff dropdown -->
      <div class="relative inline-block text-left">
        <button id="userMenuBtn"
          class="flex items-center gap-2 px-3 py-2 rounded-xl  
               text-sm font-semibold text-[var(--text-color)]
                 hover:bg-[var(--container-border)] transition-all duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-4 h-4 shrink-0" fill="currentColor">
            <path d="M480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM160-160v-112q0-34 17-62.5t47-43.5q60-30 124.5-46T480-440q67 0 131.5 16T736-378q30 15 47 43.5t17 62.5v112H160Z" />
          </svg>
          <span class="max-w-[120px] truncate">
            <?= htmlspecialchars($_SESSION['staff_name'] ?? 'Staff') ?>
          </span>
          <svg class="w-3 h-3 opacity-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 9l-7 7-7-7" />
          </svg>
        </button>
        <div id="userDropdown"
          class="absolute right-0 mt-2 w-40 bg-[var(--background-color)] border border-[var(--container-border)]
                 rounded-xl shadow-xl hidden z-50 overflow-hidden">
          <a href="../auth/barista/baristaLogout.php"
            class="flex items-center gap-2 px-4 py-3 text-sm text-red-500 hover:bg-red-500/10 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
            </svg>
            Logout
          </a>
        </div>
      </div>
    </div>
  </header>
  <!-- ── END HEADER ── -->

  <!-- ── ORDER CARDS ── -->
  <section id="ordersContainer"
    class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4 content-start">

    <?php if (empty($transactions)): ?>
      <!-- Empty state -->
      <div class="col-span-full flex flex-col items-center justify-center py-20 gap-3 opacity-30">
        <svg class="w-14 h-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2l-4 2M12 2v3M5 7h14M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
          <path d="M7 12h10" />
        </svg>
        <p class="text-base font-semibold text-[var(--text-color)]">No orders yet</p>
        <p class="text-sm text-[var(--text-color)]">Paid transactions will appear here</p>
      </div>

    <?php else: ?>
      <?php foreach ($transactions as $regId => $trans):
        $totalCount = array_sum(array_column($trans['items'], 'quantity'));
        $elapsed    = (int)$trans['elapsed_seconds'];
        $mins       = floor($elapsed / 60);
        $secs       = $elapsed % 60;
        $elapsed_str = $mins > 0 ? "{$mins}m {$secs}s" : "{$secs}s";
        $urgency    = $elapsed >= 300 ? 'border-red-500/50 bg-red-500/5'
          : ($elapsed >= 120 ? 'border-amber-500/50 bg-amber-500/5'
            : 'border-[var(--container-border)] bg-[var(--glass-bg)]');
      ?>
        <div class="order-card flex flex-col rounded-2xl border <?= $urgency ?> shadow-sm
                  transition-all duration-200 hover:shadow-md overflow-hidden">

          <!-- Card header -->
          <div class="flex items-center justify-between px-4 py-3 border-b border-[var(--container-border)]">
            <div class="flex items-center gap-2">
              <div class="w-2 h-8 rounded-full bg-amber-500 shrink-0"></div>
              <div>
                <p class="text-[10px] font-semibold uppercase tracking-widest opacity-50 text-[var(--text-color)]">Order</p>
                <p class="text-lg font-bold text-[var(--text-color)] leading-tight">#<?= $regId ?></p>
              </div>
            </div>
            <div class="text-right">
              <span class="inline-block text-[10px] font-bold px-2 py-1 rounded-lg bg-green-500/15 text-green-500">
                <?= htmlspecialchars($trans['status']) ?>
              </span>
              <p class="text-[10px] opacity-40 text-[var(--text-color)] mt-0.5"><?= $elapsed_str ?> ago</p>
            </div>
          </div>

          <!-- Items -->
          <div class="flex-1 px-4 py-3 space-y-3 overflow-y-auto"
            style="max-height:280px; scrollbar-width:thin; scrollbar-color:var(--container-border) transparent;">
            <?php foreach ($trans['items'] as $item): ?>
              <div class="rounded-xl border border-[var(--container-border)] bg-[var(--background-color)] px-3 py-2.5">

                <!-- Product name + qty badge -->
                <div class="flex items-start justify-between gap-2">
                  <p class="text-sm font-semibold text-[var(--text-color)] leading-tight">
                    <?= htmlspecialchars($item['product_name']) ?>
                  </p>
                  <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-lg bg-[var(--calc-bg-btn)] text-[var(--text-color)]">
                    ×<?= $item['quantity'] ?>
                  </span>
                </div>

                <!-- Size -->
                <p class="text-[11px] opacity-50 text-[var(--text-color)] mt-0.5 uppercase tracking-wide">
                  <?= htmlspecialchars($item['size']) ?>
                </p>

                <!-- Add-ons -->
                <?php if (!empty($item['addons'])): ?>
                  <div class="mt-1.5 flex flex-wrap gap-1">
                    <?php foreach ($item['addons'] as $addon): ?>
                      <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-blue-500/10 text-blue-400 font-medium">
                        + <?= htmlspecialchars($addon) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <!-- Mods -->
                <?php if (!empty($item['mods'])): ?>
                  <div class="mt-1 flex flex-wrap gap-1">
                    <?php foreach ($item['mods'] as $mod): ?>
                      <span class="text-[10px] px-1.5 py-0.5 rounded-md bg-amber-500/10 text-amber-400 font-medium">
                        <?= htmlspecialchars($mod) ?>
                      </span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

              </div>
            <?php endforeach; ?>
          </div>

          <!-- Card footer -->
          <div class="px-4 py-3 border-t border-[var(--container-border)] flex items-center justify-between gap-2">
            <div>
              <p class="text-[10px] opacity-50 text-[var(--text-color)] uppercase tracking-wide">Items</p>
              <p class="text-base font-bold text-[var(--text-color)]"><?= $totalCount ?></p>
            </div>
            <button
              class="serve-btn flex items-center gap-1.5 px-4 py-2 rounded-xl
                   bg-green-600 hover:bg-green-500 active:scale-95
                   text-white text-xs font-bold transition-all duration-150 shadow-sm"
              data-id="<?= $regId ?>">
              <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
              </svg>
              Serve
            </button>
          </div>

        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </section>
  <!-- ── END ORDER CARDS ── -->

  <!-- ── FOOTER ── -->
  <footer class="w-full px-4 py-3 border-t border-[var(--container-border)] bg-[var(--nav-bg)] shrink-0">
    <div class="flex items-center justify-center gap-4 text-xs text-[var(--text-color)]">
      <span class="onlineContainer flex items-center gap-1.5 font-medium">
        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Online
      </span>
      <span class="offlineContainer hidden items-center gap-1.5 font-medium">
        <span class="w-2 h-2 rounded-full bg-red-500"></span> Offline
      </span>
      <span class="opacity-30">|</span>
      <span class="flex items-center gap-1.5 opacity-60">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-3.5 h-3.5" fill="currentColor">
          <path d="M200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z" />
        </svg>
        <span id="footerDate" class="font-medium">Loading…</span>
      </span>
      <span class="flex items-center gap-1.5 opacity-60">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" class="w-3.5 h-3.5" fill="currentColor">
          <path d="M582-298 440-440v-200h80v167l118 118-56 57ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
        </svg>
        <span id="footerTime" class="font-medium">Loading…</span>
      </span>
    </div>
  </footer>
  <!-- ── END FOOTER ── -->

  <script src="../JS/shared/theme-toggle.js"></script>
  <script src="../JS/shared/checkDBCon.js"></script>
  <script src="../JS/shared/footer.js"></script>
  <script src="../JS/bvs/BVSServe.js"></script>
  <script src="../JS/shared/dropDownLogout.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <script>
    async function fetchTransactions() {
      try {
        const response = await fetch('../../app/includes/BVS/BVSRealtimeOrderSync.php');
        const data = await response.json();
        const container = document.getElementById('ordersContainer');
        container.innerHTML = '';

        if (!data || data.length === 0) {
          container.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-20 gap-3 opacity-30">
              <svg class="w-14 h-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2l-4 2M12 2v3M5 7h14M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7"/>
                <path d="M7 12h10"/>
              </svg>
              <p class="text-base font-semibold text-[var(--text-color)]">No orders yet</p>
              <p class="text-sm text-[var(--text-color)]">Paid transactions will appear here</p>
            </div>`;
          return;
        }

        data.forEach(trans => {
          let totalCount = 0;
          let itemsHTML = '';

          // Elapsed time
          const elapsed = parseInt(trans.elapsed_seconds) || 0;
          const mins = Math.floor(elapsed / 60);
          const secs = elapsed % 60;
          const elapsedStr = mins > 0 ? `${mins}m ${secs}s` : `${secs}s`;
          const urgency = elapsed >= 300 ? 'border-red-500/50 bg-red-500/5' :
            elapsed >= 120 ? 'border-amber-500/50 bg-amber-500/5' :
            'border-[var(--container-border)] bg-[var(--glass-bg)]';

          trans.items.forEach(item => {
            totalCount += item.quantity;

            const addonsHTML = item.addons?.length ?
              `<div class="mt-1.5 flex flex-wrap gap-1">
                  ${item.addons.map(a => `<span class="text-[10px] px-1.5 py-0.5 rounded-md bg-blue-500/10 text-blue-400 font-medium">+ ${a}</span>`).join('')}
                 </div>` : '';

            const modsHTML = item.mods?.length ?
              `<div class="mt-1 flex flex-wrap gap-1">
                  ${item.mods.map(m => `<span class="text-[10px] px-1.5 py-0.5 rounded-md bg-amber-500/10 text-amber-400 font-medium">${m}</span>`).join('')}
                 </div>` : '';

            itemsHTML += `
              <div class="rounded-xl border border-[var(--container-border)] bg-[var(--background-color)] px-3 py-2.5">
                <div class="flex items-start justify-between gap-2">
                  <p class="text-sm font-semibold text-[var(--text-color)] leading-tight">${item.product_name}</p>
                  <span class="shrink-0 text-xs font-bold px-2 py-0.5 rounded-lg bg-[var(--calc-bg-btn)] text-[var(--text-color)]">×${item.quantity}</span>
                </div>
                <p class="text-[11px] opacity-50 text-[var(--text-color)] mt-0.5 uppercase tracking-wide">${item.size}</p>
                ${addonsHTML}
                ${modsHTML}
              </div>`;
          });

          container.innerHTML += `
            <div class="order-card flex flex-col rounded-2xl border ${urgency} shadow-sm transition-all duration-200 hover:shadow-md overflow-hidden">
              <div class="flex items-center justify-between px-4 py-3 border-b border-[var(--container-border)]">
                <div class="flex items-center gap-2">
                  <div class="w-2 h-8 rounded-full bg-amber-500 shrink-0"></div>
                  <div>
                    <p class="text-[10px] font-semibold uppercase tracking-widest opacity-50 text-[var(--text-color)]">Order</p>
                    <p class="text-lg font-bold text-[var(--text-color)] leading-tight">#${trans.REG_TRANSACTION_ID}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="inline-block text-[10px] font-bold px-2 py-1 rounded-lg bg-green-500/15 text-green-500">${trans.status}</span>
                  <p class="text-[10px] opacity-40 text-[var(--text-color)] mt-0.5">${elapsedStr} ago</p>
                </div>
              </div>
              <div class="flex-1 px-4 py-3 space-y-3 overflow-y-auto"
                   style="max-height:280px; scrollbar-width:thin; scrollbar-color:var(--container-border) transparent;">
                ${itemsHTML}
              </div>
              <div class="px-4 py-3 border-t border-[var(--container-border)] flex items-center justify-between gap-2">
                <div>
                  <p class="text-[10px] opacity-50 text-[var(--text-color)] uppercase tracking-wide">Items</p>
                  <p class="text-base font-bold text-[var(--text-color)]">${totalCount}</p>
                </div>
                <button class="serve-btn flex items-center gap-1.5 px-4 py-2 rounded-xl
                               bg-green-600 hover:bg-green-500 active:scale-95
                               text-white text-xs font-bold transition-all duration-150 shadow-sm"
                        data-id="${trans.REG_TRANSACTION_ID}">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                  </svg>
                  Serve
                </button>
              </div>
            </div>`;
        });

      } catch (err) {
        console.error('Error fetching transactions:', err);
      }
    }

    setInterval(fetchTransactions, 1000);
    fetchTransactions();
  </script>


  <script>
    const checker = setInterval(() => {
      fetch("../../app/config/dbConnection.php?check=1")
        .then(res => res.text())
        .then(data => {
          if (!data.includes("Connected")) {
            window.location.href = "connectionLost.php";
          }
        })
        .catch(() => {
          window.location.href = "connectionLost.php";
        });
    }, 1000);
  </script>
</body>

</html>