<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer View System</title>
  <script src="app.js" defer></script>
  <link href="../css/style.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

  <link rel="shortcut icon" href="../assets/favcon/cvs.ico" type="image/x-icon" />
</head>

<body class="bg-[var(--background-color)] text-[var(--text-color)] font-sans min-h-screen flex flex-col">

  <!-- ── HEADER ── -->
  <header class="w-full flex justify-between items-center px-4 py-3 lg:px-6
                 bg-[var(--nav-bg)] border-b border-[var(--container-border)] shadow-sm z-50">
    <div class="flex items-center gap-3">
      <img src="../assets/SVG/LOGO/BLOGO.svg" class="h-10 theme-logo" alt="Logo" />
      <div>
        <h1 class="text-sm font-bold text-[var(--text-color)] leading-tight">Customer View System</h1>
        <p class="text-xs opacity-50 text-[var(--text-color)]">Order Status Board</p>
      </div>
    </div>
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
  </header>
  <!-- ── END HEADER ── -->

  <!-- ── MAIN ── -->
  <main class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 p-4 lg:p-6">

    <!-- PREPARING ORDERS -->
    <section class="rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)]
                    shadow-sm flex flex-col overflow-hidden">

      <!-- Panel header -->
      <div class="flex items-center gap-3 px-5 py-4 border-b border-[var(--container-border)] shrink-0">
        <div class="w-9 h-9 rounded-xl bg-amber-500/15 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2l-4 2M12 2v3M5 7h14M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7" />
            <path d="M7 12h10" />
          </svg>
        </div>
        <div>
          <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Preparing</h2>
          <p class="text-xs opacity-50 text-[var(--text-color)]">Orders being prepared</p>
        </div>
        <!-- Count badge -->
        <span id="preparingCount"
          class="ml-auto text-xs font-bold px-2.5 py-1 rounded-xl
                 bg-amber-500/15 text-amber-500">0</span>
      </div>

      <!-- Order grid -->
      <div class="flex-1 overflow-y-auto p-4"
        style="scrollbar-width: thin; scrollbar-color: var(--container-border) transparent;">
        <div id="preparingOrders" class="flex flex-col gap-2">

          <?php
          include "../../app/config/dbConnection.php";
          $stmt = $conn->query("
            SELECT REG_TRANSACTION_ID
            FROM REG_TRANSACTION
            WHERE STATUS IN ('PAID', 'PENDING')
            ORDER BY date_added DESC
          ");
          $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($rows) > 0):
            foreach ($rows as $row): ?>
              <div class="flex items-center gap-3 px-4 py-3 rounded-2xl
                          border border-amber-500/30 bg-amber-500/10
                          animate-[fadeIn_0.2s_ease]">
                <div class="w-2 h-10 rounded-full bg-amber-500 shrink-0"></div>
                <div>
                  <p class="text-[10px] font-semibold uppercase tracking-widest opacity-50 text-[var(--text-color)]">Order</p>
                  <p class="text-2xl font-bold text-[var(--text-color)]">#<?= htmlspecialchars($row['REG_TRANSACTION_ID']) ?></p>
                </div>
              </div>
            <?php endforeach;
          else: ?>
            <div class="flex flex-col items-center justify-center py-12 gap-2 opacity-30">
              <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M12 8v4l3 3" />
              </svg>
              <p class="text-sm text-[var(--text-color)]">No preparing orders</p>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </section>

    <!-- NOW SERVING -->
    <section class="rounded-2xl border border-[var(--container-border)] bg-[var(--glass-bg)]
                    shadow-sm flex flex-col overflow-hidden">

      <!-- Panel header -->
      <div class="flex items-center gap-3 px-5 py-4 border-b border-[var(--container-border)] shrink-0">
        <div class="w-9 h-9 rounded-xl bg-green-500/15 flex items-center justify-center shrink-0">
          <svg class="w-4 h-4 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" />
          </svg>
        </div>
        <div>
          <h2 class="text-sm font-bold text-[var(--text-color)] leading-tight">Now Serving</h2>
          <p class="text-xs opacity-50 text-[var(--text-color)]">Ready for pickup</p>
        </div>
        <!-- Count badge -->

      </div>

      <!-- Order grid -->
      <div class="flex-1 overflow-y-auto p-4"
        style="scrollbar-width: thin; scrollbar-color: var(--container-border) transparent;">
        <div id="nowServing" class="flex flex-col gap-2">

          <?php
          $stmt2 = $conn->query("
            SELECT REG_TRANSACTION_ID
            FROM REG_TRANSACTION
            WHERE STATUS = 'NOW SERVING'
            ORDER BY date_added DESC
          ");
          $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
          if (count($rows2) > 0):
            foreach ($rows2 as $row2): ?>
              <div class="flex items-center gap-3 px-4 py-3 rounded-2xl
                          border border-green-500/30 bg-green-500/15
                          animate-[fadeIn_0.2s_ease]">
                <div class="w-2 h-10 rounded-full bg-green-500/30 shrink-0"></div>
                <div>
                  <p class="text-[10px] font-semibold uppercase tracking-widest text-green-500/70">Order</p>
                  <p class="text-2xl font-bold text-green-500">#<?= htmlspecialchars($row2['REG_TRANSACTION_ID']) ?></p>
                </div>
              </div>
            <?php endforeach;
          else: ?>
            <div class="flex flex-col items-center justify-center py-12 gap-2 opacity-30">
              <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 15s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01" />
              </svg>
              <p class="text-sm text-[var(--text-color)]">No orders serving now</p>
            </div>
          <?php endif; ?>

        </div>
      </div>

    </section>

  </main>
  <!-- ── END MAIN ── -->

  <!-- ── FOOTER ── -->
  <footer class="w-full px-4 py-3 border-t border-[var(--container-border)] bg-[var(--nav-bg)]">
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
  <script src="../JS/cvs/CVSRealTimeDBSync.js"></script>
  <script src="../JS/shared/dropDownLogout.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <style>
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }

      to {
        opacity: 1;
        transform: scale(1);
      }
    }
  </style>

</body>

</html>