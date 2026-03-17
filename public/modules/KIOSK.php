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

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>KIOSK</title>
  <link href="../css/style.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="../assets/favcon/pos.ico" type="image/x-icon" />

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      overflow: hidden;
      background: var(--background-color);
      color: var(--text-color);
    }

    /* ── Sidebar category buttons ── */
    .cat-radio-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 10px 6px;
      border-radius: 12px;
      cursor: pointer;
      border: 1.5px solid transparent;
      transition: all .15s ease;
      min-height: 104px;
      width: 100%;
      background: transparent;
      user-select: none;
    }

    .cat-radio-btn svg {
      width: 22px;
      height: 22px;
      color: var(--text-color);
      opacity: .4;
      transition: opacity .15s;
    }

    .cat-radio-btn span {
      font-size: 9px;
      font-weight: 700;
      color: var(--text-color);
      opacity: .4;
      text-align: center;
      letter-spacing: .04em;
      line-height: 1.2;
    }

    .cat-radio-btn:hover {
      background: var(--glass-bg, rgba(128, 128, 128, .08));
      border-color: var(--container-border);
    }

    .cat-radio-btn:hover svg,
    .cat-radio-btn:hover span {
      opacity: .75;
    }

    /* active state driven by peer-checked on the hidden radio */
    .peer:checked~.cat-radio-btn {
      background: var(--text-color) !important;
      border-color: var(--text-color) !important;
    }

    .peer:checked~.cat-radio-btn svg {
      color: var(--background-color) !important;
      opacity: 1 !important;
    }

    .peer:checked~.cat-radio-btn span {
      color: var(--background-color) !important;
      opacity: 1 !important;
    }

    /* ── Menu content area scrollable ── */
    #menuContent {
      flex: 1;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: var(--container-border) transparent;
    }

    /* ── Cart panel ── */
    #cartPanel {
      width: 400px;
      flex-shrink: 0;
      background: var(--nav-bg);
      border-left: 1px solid var(--container-border);
      display: flex;
      flex-direction: column;
    }

    .cart-panel-header {
      padding: 14px 16px 12px;
      border-bottom: 1px solid var(--container-border);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cart-panel-header h3 {
      font-size: 14px;
      font-weight: 700;
      margin: 0;
      color: var(--text-color);
    }

    #cartItemsList {
      flex: 1;
      overflow-y: auto;
      padding: 10px 12px;
      scrollbar-width: thin;
      scrollbar-color: var(--container-border) transparent;
    }

    .cart-panel-footer {
      padding: 12px 14px 16px;
      border-top: 1px solid var(--container-border);
    }

    .cart-total-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      margin-bottom: 10px;
    }

    .cart-total-label {
      font-size: 12px;
      opacity: .5;
      color: var(--text-color);
    }

    .cart-total-val {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-color);
    }

    .kiosk-checkout-btn {
      width: 100%;
      padding: 12px;
      background: #16a34a;
      border: none;
      border-radius: 12px;
      color: #fff;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      transition: background .15s, transform .1s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .kiosk-checkout-btn:hover {
      background: #15803d;
    }

    .kiosk-checkout-btn:active {
      transform: scale(.97);
    }

    /* ── Tablet adaptations (768px and below) ── */
    @media (max-width: 768px) {

      /* Sidebar shrinks to icon-only */
      nav[style*="width:88px"] {
        width: 64px !important;
      }

      .cat-radio-btn {
        padding: 8px 4px;
        min-height: 100px;
        gap: 0;
      }



      /* Cart panel becomes a bottom drawer */
      #cartPanel {
        position: fixed !important;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100% !important;
        height: auto;
        max-height: 60vh;
        border-left: none !important;
        border-top: 1px solid var(--container-border);
        border-radius: 16px 16px 0 0;
        transform: translateY(100%);
        transition: transform .3s ease;
        z-index: 100;
      }

      #cartPanel.open {
        transform: translateY(0);
      }

      #cartItemsList {
        max-height: 35vh;
      }

      /* Floating cart toggle button — shows on tablet */
      #cartToggleBtn {
        display: flex !important;
      }

      /* Menu takes full width */
      #menuContent {
        width: 100%;
      }
    }

    /* ── Small tablet / large phone (480px–768px) ── */
    @media (max-width: 600px) {

      nav[style*="width:88px"],
      nav[style*="width:64px"] {
        width: 56px !important;
      }

      .cat-radio-btn {
        min-height: 52px;
      }

      .cat-radio-btn svg {
        width: 18px;
        height: 18px;
      }
    }
  </style>
</head>

<body style="display:flex; flex-direction:column; height:100vh; overflow:hidden;">

  <!-- ══ HEADER ══ -->
  <header class="w-full flex justify-between items-center gap-4 px-4 py-2
    bg-[var(--nav-bg)] text-[var(--nav-text)] border-b border-[var(--container-border)] shadow-sm z-50 flex-shrink-0">
    <h1 class="text-xl font-semibold text-[var(--text-color)]">
      <span class="flex items-center">
        <img src="../assets/SVG/LOGO/BLOGO.svg" class="h-10 theme-logo m-1" alt="Logo" />
        KIOSK
      </span>
    </h1>
    <div class="flex items-center gap-2">
      <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition"
        id="theme-toggle" title="Toggle theme" aria-label="auto" aria-live="polite">
        <svg class="sun-and-moon text-gray-600 dark:text-gray-200" aria-hidden="true" width="22" height="22" viewBox="0 0 24 24">
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
    </div>
  </header>


  <!-- ══ BODY: sidebar + content + cart ══ -->
  <div style="display:flex; flex:1; overflow:hidden;">

    <!-- ── LEFT SIDEBAR — category radio buttons ── -->
    <nav style="width:110px; flex-shrink:0; background:var(--nav-bg);
      border-right:1px solid var(--container-border);
      display:flex; flex-direction:column; gap:4px;
      padding:10px 6px; overflow-y:auto; scrollbar-width:none;">

      <!-- each item: hidden radio + visible label acting as the button -->
      <div>
        <input type="radio" id="milktea_module" name="module" class="hidden peer" checked onclick="showModule('milktea')" />
        <label for="milktea_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2l-4 2M12 2v3M5 7h14M6 7l1.2 11.2A2 2 0 009.19 20h5.62a2 2 0 001.99-1.8L18 7M7 12h10" />
            <circle cx="9" cy="16.5" r="1" fill="currentColor" stroke="none" />
            <circle cx="12" cy="17.5" r="1" fill="currentColor" stroke="none" />
            <circle cx="15" cy="16.5" r="1" fill="currentColor" stroke="none" />
          </svg>
          <span>MILK TEA</span>
        </label>
      </div>

      <div>
        <input type="radio" id="fruittea_module" name="module" class="hidden peer" onclick="showModule('fruittea')" />
        <label for="fruittea_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 7h12l-1 11a2 2 0 01-2 2H9a2 2 0 01-2-2L6 7zM5 7h14M12 2v5M7 12h10" />
            <circle cx="16.5" cy="15.5" r="2" />
            <path d="M16.5 13.5v4M14.5 15.5h4" />
          </svg>
          <span>FRUIT TEA</span>
        </label>
      </div>

      <div>
        <input type="radio" id="hotbrew_module" name="module" class="hidden peer" onclick="showModule('hotbrew')" />
        <label for="hotbrew_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 8h12v8a4 4 0 01-4 4H8a4 4 0 01-4-4V8zM16 10h1a3 3 0 010 6h-1M9 2v3M13 2v3" />
          </svg>
          <span>HOT BREW</span>
        </label>
      </div>

      <div>
        <input type="radio" id="icedcoffee_module" name="module" class="hidden peer" onclick="showModule('icedcoffee')" />
        <label for="icedcoffee_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 7h10l-1.2 11.2A2 2 0 0113.8 20H10.2a2 2 0 01-2-1.8L7 7zM6 7h12M12 2v5" />
            <rect x="9" y="11" width="2.5" height="2.5" />
            <rect x="12.5" y="14" width="2.5" height="2.5" />
          </svg>
          <span>ICED COFFEE</span>
        </label>
      </div>

      <div>
        <input type="radio" id="praf_module" name="module" class="hidden peer" onclick="showModule('praf')" />
        <label for="praf_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 9h12l-1.2 9.2A2 2 0 0114.8 20H9.2a2 2 0 01-2-1.8L6 9zM6 9c0-3 3-5 6-5s6 2 6 5M12 4V2M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5" />
          </svg>
          <span>PRAF</span>
        </label>
      </div>

      <div>
        <input type="radio" id="brosty_module" name="module" class="hidden peer" onclick="showModule('brosty')" />
        <label for="brosty_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z" />
            <path d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2" />
            <path d="M15 5l2 4" />
          </svg>
          <span>BROSTY</span>
        </label>
      </div>

      <div>
        <input type="radio" id="promos_module" name="module" class="hidden peer" onclick="showModule('promos')" />
        <label for="promos_module" class="cat-radio-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 7h10l-1.2 10.5A2 2 0 0113.8 20H10.2a2 2 0 01-2-1.8L7 7zM6 7h12M12 2v5" />
            <polygon points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10" />
          </svg>
          <span>PROMOS</span>
        </label>
      </div>

    </nav>
    <!-- ── Sidebar End ── -->


    <!-- ── MENU CONTENT ── -->
    <div id="menuContent" style="flex:1; overflow-y:auto; background:var(--background-color);">

      <?php include_once "../../app/includes/POS/POSPopUpModalOrdering.php"; ?>

      <!-- All sections — hidden/shown by POSmodules.js unchanged -->
      <section id="milktea" class="hidden p-4">
        <div id="milkteaMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 1;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="fruittea" class="hidden p-4">
        <div id="fruitTeaMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 2;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="hotbrew" class="hidden p-4">
        <div id="hotBrewMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 3;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="praf" class="hidden p-4">
        <div id="prafMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 4;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="icedcoffee" class="hidden p-4">
        <div id="icedCoffeeMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 6;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="promos" class="hidden p-4">
        <div id="promosMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 7;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="brosty" class="hidden p-4">
        <div id="brostyMenu" class="flex flex-wrap justify-center gap-3">
          <?php $category_id = 5;
          include "../../app/includes/POS/fetchProducts.php"; ?>
        </div>
      </section>

      <section id="modify" class="hidden p-4"></section>
      <section id="addOns" class="hidden p-4"></section>

    </div>
    <!-- ── Menu Content End ── -->


    <!-- ── CART PANEL ── -->
    <div id="cartPanel">
      <div class="cart-panel-header">
        <h3>Orders</h3>

      </div>

      <div id="cartItemsList">

        <div id="productList"></div>
      </div>

      <div class="cart-panel-footer">
        <div class="cart-total-row">
          <span class="cart-total-label">Total</span>
          <span class="cart-total-val" id="cartTotalDisplay">₱ 0.00</span>
        </div>
        <button class="kiosk-checkout-btn" onclick="kioskCheckout()">
          <svg style="width:18px;height:18px;" viewBox="0 -960 960 960" fill="currentColor">
            <path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
          </svg>
          Checkout
        </button>
      </div>
    </div>
    <!-- ── Cart Panel End ── -->

  </div>
  <!-- Floating cart button — tablet only -->
  <button id="cartToggleBtn"
    onclick="toggleCartDrawer()"
    style="display:none; position:fixed; bottom:20px; right:20px; z-index:99;
    width:56px; height:56px; border-radius:50%; background:#16a34a; border:none;
    color:#fff; cursor:pointer; align-items:center; justify-content:center;
    box-shadow:0 4px 12px rgba(0,0,0,.3); transition:transform .1s;"
    onmousedown="this.style.transform='scale(.93)'"
    onmouseup="this.style.transform='scale(1)'">
    <svg width="24" height="24" viewBox="0 -960 960 960" fill="currentColor">
      <path d="M280-80q-33 0-56.5-23.5T200-160q0-33 23.5-56.5T280-240q33 0 56.5 23.5T360-160q0 33-23.5 56.5T280-80Zm400 0q-33 0-56.5-23.5T600-160q0-33 23.5-56.5T680-240q33 0 56.5 23.5T760-160q0 33-23.5 56.5T680-80ZM246-720l96 200h280l110-200H246Zm-38-80h590q23 0 35 20.5t1 41.5L692-482q-11 20-29.5 31T622-440H324l-44 80h480v80H280q-45 0-68-39.5t-2-78.5l54-98-144-304H40v-80h130l38 80Zm134 280h280-280Z" />
    </svg>

  </button>

  <!-- Backdrop for cart drawer -->
  <div id="cartBackdrop"
    onclick="toggleCartDrawer()"
    style="display:none; position:fixed; inset:0; background:var(--background-color); z-index:99;"></div>

  <!-- ══ SCRIPTS — all unchanged ══ -->
  <?php include_once "../../app/includes/POS/POSOrderingScript.php"; ?>

  <script src="../JS/pos/POSmodules.js"></script>
  <script src="../JS/pos/POSCartResponsiveScriptsKioks.js"></script>
  <script src="../JS/pos/POSRealTimeProductCheckStatus.js"></script>
  <script src="../JS/kiosk/kioskOrdering.js"></script>
  <script src="../JS/shared/theme-toggle.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    function toggleCartDrawer() {
      const panel = document.getElementById('cartPanel');
      const backdrop = document.getElementById('cartBackdrop');
      const isOpen = panel.classList.contains('open');
      panel.classList.toggle('open');
      backdrop.style.display = isOpen ? 'none' : 'block';
    }

    const plEl = document.getElementById('productList');
    if (plEl) {
      new MutationObserver(() => {
        const count = typeof cart !== 'undefined' ? cart.length : 0;
        const total = typeof cart !== 'undefined' ?
          cart.reduce((s, i) => s + (i.price * i.quantity), 0) : 0;
        const floatBadge = document.getElementById('cartFloatBadge');
        const totalDisp = document.getElementById('cartTotalDisplay');

        if (floatBadge) {
          floatBadge.textContent = count;
          floatBadge.style.display = count > 0 ? 'block' : 'none';
        }
        if (totalDisp) totalDisp.textContent = '₱ ' + total.toFixed(2);

      }).observe(plEl, {
        childList: true,
        subtree: true
      });
    }
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