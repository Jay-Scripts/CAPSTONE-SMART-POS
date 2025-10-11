<?php
$host = "localhost";
$port = 3307;
$dbName = "smart_pos";
$username = "root";
$password = "";

try {
  $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'DB failed: ' . $e->getMessage()]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
  $order_data = json_decode($_POST['order_data'], true);
  $payment_type = $_POST['payment_type'] ?? 'CASH';
  $tendered = $_POST['amount_sent'] ?? 0;
  $change = $_POST['change_amount'] ?? 0;


  try {
    $conn->beginTransaction();
    $total = 0;
    foreach ($order_data as $item) {
      $total += $item['price'] * $item['quantity'];
    }

    $staff_id = 69; // from session
    $stmt = $conn->prepare("INSERT INTO REG_TRANSACTION (STAFF_ID, TOTAL_AMOUNT) VALUES (?, ?)");
    $stmt->execute([$staff_id, $total]);
    $transaction_id = $conn->lastInsertId();

    foreach ($order_data as $item) {
      $stmt = $conn->prepare("INSERT INTO TRANSACTION_ITEM (REG_TRANSACTION_ID, PRODUCT_ID, SIZE_ID, QUANTITY, PRICE) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([$transaction_id, $item['product_id'], $item['size_id'], $item['quantity'], $item['price']]);
      $item_id = $conn->lastInsertId();

      if (!empty($item['addons'])) {
        $stmt = $conn->prepare("INSERT INTO item_add_ons (add_ons_id, item_id) VALUES (?, ?)");
        foreach ($item['addons'] as $addon_id) $stmt->execute([$addon_id, $item_id]);
      }

      if (!empty($item['modifications'])) {
        $stmt = $conn->prepare("INSERT INTO item_modification (item_id, modification_id) VALUES (?, ?)");
        foreach ($item['modifications'] as $mod_id) $stmt->execute([$item_id, $mod_id]);
      }
    }

    // 🟩 INSERT PAYMENT RECORD
    $stmt = $conn->prepare("INSERT INTO PAYMENT_METHODS (REG_TRANSACTION_ID, TYPE, AMOUNT_SENT, CHANGE_AMOUNT) VALUES (?, ?, ?, ?)");
    $stmt->execute([$transaction_id, $payment_type, $tendered, $change]);

    $conn->commit();
    echo json_encode(['success' => true, 'transaction_id' => $transaction_id]);
  } catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
  }
  exit;
}

// =================== FETCH PRODUCTS ===================
$stmt = $conn->query("
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size_id, ps.size, ps.regular_price
FROM product_details pd
JOIN product_sizes ps ON pd.product_id = ps.product_id
WHERE pd.category_id = 1
AND pd.status = 'active'
ORDER BY pd.product_name ASC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];
foreach ($rows as $row) {
  $id = $row['product_id'];
  if (!isset($products[$id])) {
    $products[$id] = [
      'product_id' => $id,
      'product_name' => $row['product_name'],
      'thumbnail_path' => $row['thumbnail_path'],
      'sizes' => []
    ];
  }
  $products[$id]['sizes'][] = [
    'size_id' => $row['size_id'],
    'size' => $row['size'],
    'price' => $row['regular_price']
  ];
}


$addons_stmt = $conn->query("SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active'");
$addons = $addons_stmt->fetchAll(PDO::FETCH_ASSOC);

$mods_stmt = $conn->query("SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active'");
$modifications = $mods_stmt->fetchAll(PDO::FETCH_ASSOC);

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="bg-gray-100 p-5">

  <h2 class="text-xl font-bold mb-4">Products</h2>
  <section class="flex flex-wrap justify-center gap-2">

    <?php foreach ($products as $product): ?>
      <div class="optionChoice cursor-pointer aspect-square w-[47%] sm:w-[15%] bg-transparent rounded-lg border border-gray-400 p-2"
        onclick='openModal(<?= json_encode($product) ?>)'>
        <img src="<?= $product['thumbnail_path'] ?>" class="object-cover">
        <h3 class="text-center font-semibold"><?= htmlspecialchars($product['product_name']) ?></h3>
      </div>
    <?php endforeach; ?>

  </section>
  <!-- Cart Preview -->
  <h2 class="text-xl font-bold mt-8 mb-2">Cart</h2>
  <div id="productList" class="bg-white rounded-lg shadow p-3"></div>

  <!-- Modal -->
  <div id="productModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-5 relative">
      <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>
      <div class="flex flex-col items-center">
        <img id="modalThumb" src="" alt="Product" class="w-32 h-32 rounded-lg object-cover mb-3">
        <h2 class="text-lg font-semibold text-gray-800" id="productName"></h2>
      </div>

      <!-- Size -->
      <div id="sizeOptions" class="mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Select Size</h3>
        <div id="sizesContainer" class="flex flex-col gap-2"></div>
      </div>

      <!-- Add-ons -->
      <div class="mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Add-ons</h3>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($addons as $addon): ?>
            <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
              <input type="checkbox" class="addon-checkbox accent-green-500"
                data-id="<?= $addon['ADD_ONS_ID'] ?>"
                data-price="<?= $addon['PRICE'] ?>">
              <?= htmlspecialchars($addon['ADD_ONS_NAME']) ?> (+₱<?= number_format($addon['PRICE'], 2) ?>)
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Modifications -->
      <!-- Ice Level -->
      <div class="mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Ice Level</h3>
        <div class="flex flex-wrap gap-2" id="iceLevel">
          <?php foreach ($modifications as $mod): ?>
            <?php if ($mod['MODIFICATION_ID'] >= 1 && $mod['MODIFICATION_ID'] <= 2): ?>
              <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                <input type="checkbox" class="mod-checkbox accent-blue-500"
                  value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Sugar Level -->
      <div class="mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Sugar Level</h3>
        <div class="flex flex-wrap gap-2" id="sugarLevel">
          <?php foreach ($modifications as $mod): ?>
            <?php if ($mod['MODIFICATION_ID'] >= 3 && $mod['MODIFICATION_ID'] <= 6): ?>
              <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                <input type="checkbox" class="mod-checkbox accent-red-500"
                  value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
              </label>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      </div>
      <script>
        // Make checkboxes behave like radio buttons
        function singleCheck(containerId) {
          const container = document.getElementById(containerId);
          container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', () => {
              if (cb.checked) {
                container.querySelectorAll('input[type="checkbox"]').forEach(other => {
                  if (other !== cb) other.checked = false;
                });
              }
            });
          });
        }

        singleCheck('iceLevel');
        singleCheck('sugarLevel');
      </script>

      <!-- Quantity -->
      <div class="flex items-center justify-between mt-4">
        <span class="text-sm font-medium text-gray-700">Quantity:</span>
        <div class="flex items-center space-x-2">
          <button id="decreaseQty" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 rounded">−</button>
          <input id="quantity" type="number" value="1" min="1" class="w-12 text-center border rounded" readonly>
          <button id="increaseQty" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 rounded">+</button>
        </div>
      </div>

      <!-- Total -->
      <div class="mt-4 text-right text-sm font-semibold text-gray-800">
        Total: ₱<span id="subtotal">0</span>
      </div>

      <!-- Add to Order -->
      <div class="mt-5 flex justify-end">
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow" onclick="addToOrder()">Add to Order</button>
      </div>
    </div>
  </div>

  <div
    id="calculatorModal"
    class="fixed inset-0 bg-black/90 hidden flex items-center justify-center z-50">
    <div
      id="calculator"
      class="bg-[var(--background-color)] border rounded-2xl shadow-2xl w-full max-w-sm sm:max-w-xl sm:max-h-xs p-4 sm:p-6 mx-2">

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
          <span>Total:</span><span id="totalAmount" class="font-bold"></span>
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
    </div>
  </div>
  <button
    class=" fixed right-0 bottom-0 w-full h-[50px] bg-green-600 hover:bg-green-500 text-white font-bold flex items-center justify-center rounded-xl shadow-lg transition-all duration-200"
    onclick="openCalculator()">

    Checkout
  </button>
  <script>
    const totalDisplay = document.querySelector('#totalAmount');
    let products = <?= json_encode($products) ?>;
    let selectedProduct = null;
    let cart = [];
    let quantityInput = document.getElementById('quantity');
    let subtotalEl = document.getElementById('subtotal');

    function openModal(product) {
      selectedProduct = product;
      document.getElementById('modalThumb').src = product.thumbnail_path;
      document.getElementById('productName').textContent = product.product_name;

      // build size options dynamically
      const sizesContainer = document.getElementById('sizesContainer');
      sizesContainer.innerHTML = '';
      product.sizes.forEach((s, i) => {
        const price = Number(s.price) || 0;
        sizesContainer.innerHTML += `
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="size" data-id="${s.size_id}" data-price="${price}" 
               ${i === 0 ? 'checked' : ''} onchange="updateTotal()">
        <span class="ml-2">${s.size}</span>
        <span class="ml-auto">₱${price.toFixed(2)}</span>
      </label>`;
      });

      quantityInput.value = 1;
      document.querySelectorAll('.addon-checkbox').forEach(c => c.checked = false);
      document.querySelectorAll('.mod-checkbox').forEach(c => c.checked = false);

      attachModalListeners();
      originalTotal
      updateTotal();
      document.getElementById('productModal').classList.remove('hidden');
    }

    function attachModalListeners() {
      document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.addEventListener('change', updateTotal);
      });
      document.querySelectorAll('.addon-checkbox').forEach(cb => {
        cb.addEventListener('change', updateTotal);
      });
    }

    function safeParse(v) {
      v = String(v || '').replace(/[^\d.]/g, '');
      const n = parseFloat(v);
      return isNaN(n) ? 0 : n;
    }

    function updateTotal() {
      const selectedSize = document.querySelector('input[name="size"]:checked');
      const base = selectedSize ? safeParse(selectedSize.dataset.price) : 0;

      let addons = 0;
      document.querySelectorAll('.addon-checkbox:checked').forEach(a => {
        addons += safeParse(a.dataset.price);
      });

      const qty = parseInt(quantityInput.value) || 1;
      const total = (base + addons) * qty;

      subtotalEl.textContent = total.toFixed(2);
    }

    // qty buttons
    document.getElementById('increaseQty').onclick = () => {
      quantityInput.value = parseInt(quantityInput.value) + 1;
      updateTotal();
    };
    document.getElementById('decreaseQty').onclick = () => {
      if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
        updateTotal();
      }
    };



    function closeModal() {
      document.getElementById('productModal').classList.add('hidden');
    }

    function updateTotal() {
      let price = 0;
      const size = document.querySelector('input[name="size"]:checked');
      if (size) price += parseFloat(size.dataset.price);
      document.querySelectorAll('.addon-checkbox:checked').forEach(a => price += parseFloat(a.dataset.price));
      subtotalEl.textContent = (price * quantityInput.value).toFixed(2);
    }

    document.getElementById('increaseQty').onclick = () => {
      quantityInput.value = parseInt(quantityInput.value) + 1;
      updateTotal();
    }
    document.getElementById('decreaseQty').onclick = () => {
      if (quantityInput.value > 1) quantityInput.value--;
      updateTotal();
    }

    function addToOrder() {
      const size = document.querySelector('input[name="size"]:checked');
      if (!size) {
        alert('Please select a size');
        return;
      }

      const orderItem = {
        product_id: selectedProduct.product_id,
        size_id: parseInt(size.dataset.id),
        quantity: parseInt(quantityInput.value),
        price: parseFloat(size.dataset.price),
        addons: Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(a => parseInt(a.dataset.id)),
        modifications: Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(m => parseInt(m.value))
      };

      cart.push(orderItem);
      closeModal();
      renderCart();
    }

    function renderCart() {
      const container = document.getElementById('productList');
      container.innerHTML = '';

      const addonsList = <?= json_encode($addons) ?>;
      const modsList = <?= json_encode($modifications) ?>;

      const merged = [];
      cart.forEach((item, idx) => {
        const key = `${item.product_id}-${item.size_id}-${JSON.stringify(item.addons)}-${JSON.stringify(item.modifications)}`;
        const found = merged.find(i => i.key === key);
        if (found) {
          found.quantity += item.quantity;
          found.indexes.push(idx);
        } else {
          merged.push({
            ...item,
            key,
            indexes: [idx]
          });
        }
      });

      let total = 0;

      merged.forEach((item, index) => {
        const product = products[item.product_id];
        const sizeObj = product.sizes.find(s => s.size_id === item.size_id);
        const sizeLabel = sizeObj ? ` (${sizeObj.size})` : '';
        const subtotal = item.price * item.quantity;
        total += subtotal;

        const addonsText = item.addons?.length ?
          item.addons.map(id => {
            const addon = addonsList.find(a => a.ADD_ONS_ID == id);
            return addon ? `${addon.ADD_ONS_NAME.toUpperCase()} (+₱${parseFloat(addon.PRICE).toFixed(2)})` : '';
          }).join(', ') :
          'None';

        const modsText = item.modifications?.length ?
          item.modifications.map(id => {
            const mod = modsList.find(m => m.MODIFICATION_ID == id);
            return mod ? mod.MODIFICATION_NAME.toUpperCase() : '';
          }).join(', ') :
          'None';

        container.innerHTML += `
      <div class="flex flex-col border-b border-gray-200 py-2 group">
        <div class="flex justify-between items-center">
          <span class="text-sm">${item.quantity}x ${product.product_name}${sizeLabel}</span>
          <div class="flex items-center gap-3">
            <span class="font-semibold text-gray-800">₱${subtotal.toFixed(2)}</span>

            <!-- Edit Button -->
            <button onclick="editCartItem(${item.indexes[0]})" class="text-blue-500 hover:text-blue-700 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.121 2.121 0 013 3L12 14l-4 1 1-4z" />
              </svg>
            </button>

            <!-- Delete Button -->
            <button onclick="removeFromCart(${item.indexes.join(',')})" class="text-red-500 hover:text-red-700 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m5-3v3" />
              </svg>
            </button>
          </div>
        </div>

        <div class="text-xs text-gray-600 ml-5 mt-1 space-y-0.5">
          <div><b>Add-ons:</b> ${addonsText}</div>
          <div><b>Mods:</b> ${modsText}</div>
        </div>
      </div>
    `;
      });

      container.innerHTML += `
    <div class="text-right font-semibold mt-2">Total: ₱${total.toFixed(2)}</div>
  `;

      if (totalDisplay) {
        totalDisplay.textContent = `₱${total.toFixed(2)}`;
      } else {
        console.warn('⚠️ totalAmount element not found in DOM');
      }

      originalTotal = total;
      updateDisplay();
    }

    /* ✏️ Edit item */
    function editCartItem(index) {
      const item = cart[index];
      if (!item) return;

      Swal.fire({
        icon: 'info',
        title: 'Edit Item',
        text: 'You can now modify this item.',
        timer: 1200,
        showConfirmButton: false
      });

      const product = products[item.product_id];
      openModal(product);

      document.querySelectorAll('input[name="size"]').forEach(radio => {
        if (parseInt(radio.dataset.id) === item.size_id) radio.checked = true;
      });

      document.querySelectorAll('.addon-checkbox').forEach(ch => {
        ch.checked = item.addons.includes(parseInt(ch.dataset.id));
      });

      document.querySelectorAll('.mod-checkbox').forEach(ch => {
        ch.checked = item.modifications.includes(parseInt(ch.value));
      });

      document.getElementById('quantity').value = item.quantity;
      updateTotal();
      selectedProduct.editingIndex = index;
    }

    /* 🗑️ Delete item */
    function removeFromCart(...indexes) {
      Swal.fire({
        title: 'Remove Item?',
        text: 'This item will be deleted from your order.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then(result => {
        if (result.isConfirmed) {
          indexes.sort((a, b) => b - a).forEach(i => cart.splice(i, 1));
          renderCart();
          Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'The item has been removed.',
            timer: 1000,
            showConfirmButton: false
          });
        }
      });
    }

    /* ✅ Add or update item */
    function addToOrder() {
      const size = document.querySelector('input[name="size"]:checked');
      if (!size) {
        Swal.fire({
          icon: 'warning',
          title: 'Missing Size',
          text: 'Please select a size first.',
          timer: 1500,
          showConfirmButton: false
        });
        return;
      }

      let basePrice = parseFloat(size.dataset.price);
      let addonsPrice = 0;

      // calculate total add-ons price
      document.querySelectorAll('.addon-checkbox:checked').forEach(a => {
        addonsPrice += parseFloat(a.dataset.price);
      });

      const totalItemPrice = basePrice + addonsPrice;

      const newItem = {
        product_id: selectedProduct.product_id,
        size_id: parseInt(size.dataset.id),
        quantity: parseInt(document.getElementById('quantity').value),
        price: totalItemPrice, // ✅ now includes add-ons
        addons: Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(a => parseInt(a.dataset.id)),
        modifications: Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(m => parseInt(m.value))
      };

      if (selectedProduct.editingIndex !== undefined) {
        cart[selectedProduct.editingIndex] = newItem;
        delete selectedProduct.editingIndex;
        Swal.fire({
          icon: 'success',
          title: 'Item Updated',
          text: 'The item was successfully updated.',
          timer: 1000,
          showConfirmButton: false
        });
      } else {
        cart.push(newItem);
        Swal.fire({
          icon: 'success',
          title: 'Added to Cart',
          text: 'Item successfully added!',
          timer: 1000,
          showConfirmButton: false
        });
      }

      closeModal();
      renderCart();
    }



    // function checkout() {
    //   if (cart.length === 0) {
    //     Swal.fire({
    //       icon: 'warning',
    //       title: 'Empty Cart',
    //       text: 'Add items before checkout!'
    //     });
    //     return;
    //   }

    //   const paymentType = currentPaymentType || 'CASH';
    //   const change = tendered - originalTotal;

    //   console.log("Sending payment:", {
    //     tendered,
    //     change,
    //     originalTotal
    //   }); // ✅ debug

    //   const formData = new FormData();
    //   formData.append('order_data', JSON.stringify(cart));
    //   formData.append('payment_type', paymentType);
    //   formData.append('tendered', tendered);
    //   formData.append('change', change);

    //   fetch(window.location.href, { // ✅ self
    //       method: 'POST',
    //       body: formData
    //     })
    //     .then(res => res.json())
    //     .then(data => {
    //       if (data.success) {
    //         Swal.fire({
    //           icon: 'success',
    //           title: 'Payment Successful!',
    //           text: `Transaction #${data.transaction_id} recorded.`,
    //           timer: 2000,
    //           showConfirmButton: false
    //         });
    //         cart = [];
    //         renderCart();
    //         closeCalculator();
    //       } else {
    //         Swal.fire({
    //           icon: 'error',
    //           title: 'Error',
    //           text: data.message
    //         });
    //       }
    //     })
    //     .catch(err => Swal.fire({
    //       icon: 'error',
    //       title: 'Server Error',
    //       text: err.message
    //     }));
    // }

    let currentPaymentType = 'CASH';

    function openEPaymentPopup() {
      currentPaymentType = 'E-PAYMENT';
      document.getElementById('EPaymentPopup').classList.remove('hidden');
    }

    function closeEPaymentPopup() {
      document.getElementById('EPaymentPopup').classList.add('hidden');
    }

    function addCash(amount) {
      tenderedAmount += amount;
      updateCalculatorDisplay();
    }



    //calc
    let originalTotal = 0;
    let total = originalTotal;
    let tendered = 0;
    let buffer = "";
    let transType = null;

    function openCalculator() {
      if (cart.length === 0) {
        Swal.fire({
          icon: "warning",
          title: "No Orders!",
          text: "Please add an order before proceeding to payment.",
          timer: 1500,
          showConfirmButton: false
        });
        return;
      }

      originalTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
      document.getElementById("calculatorModal").classList.remove("hidden");
      document.getElementById("totalAmount").textContent = `₱${originalTotal.toFixed(2)}`;
      tendered = 0;
      buffer = "";
      updateDisplay();

      Swal.fire({
        icon: "info",
        title: "Payment Window Opened",
        text: "Please enter the customer's payment.",
        timer: 1200,
        showConfirmButton: false
      });
    }




    function closeCalculator() {
      document.getElementById("calculatorModal").classList.add("hidden");
      tendered = 0;
      buffer = "";
      transType = null;
      total = originalTotal;
      updateDisplay();
    }
    // 💰 Calculator input buttons
    function manualKey(num) {
      buffer += num;
      tendered = parseFloat(buffer) || 0;
      updateDisplay();
    }

    function clearCash() {
      tendered = 0;
      buffer = "";
      updateDisplay();
    }

    function addCash(amount) {
      tendered += amount;
      updateDisplay();
    }

    // ✅ Finalize payment and send to PHP
    function finalizePayment() {
      if (cart.length === 0) {
        Swal.fire({
          icon: "warning",
          title: "Empty Cart",
          text: "Add items before finalizing payment."
        });
        return;
      }

      // make sure the displayed total is used
      originalTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

      if (tendered < originalTotal) {
        Swal.fire({
          icon: "warning",
          title: "Insufficient Payment",
          text: "Tendered amount is less than total."
        });
        return;
      }

      const change = tendered - originalTotal;
      const paymentType = currentPaymentType || "CASH";

      console.log("🟢 Sending to PHP:", {
        tendered,
        change,
        total: originalTotal,
        paymentType
      });

      const formData = new FormData();
      formData.append("order_data", JSON.stringify(cart));
      formData.append("payment_type", paymentType);
      formData.append("amount_sent", tendered);
      formData.append("change_amount", change);
      formData.append("total", originalTotal);

      fetch(window.location.href, {
          method: "POST",
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: "success",
              title: "Payment Successful!",
              text: `Change: ₱${change.toFixed(2)}`,
              timer: 2000,
              showConfirmButton: false
            });
            cart = [];
            renderCart();
            closeCalculator();
          } else {
            Swal.fire({
              icon: "error",
              title: "Error",
              text: data.message || "Payment failed."
            });
          }
        })
        .catch(err => {
          Swal.fire({
            icon: "error",
            title: "Server Error",
            text: err.message
          });
        });
    }


    // 🧾 Display update
    function updateDisplay() {
      const tenderedDisplay = document.getElementById("tenderedAmount");
      const changeDisplay = document.getElementById("changeAmount");
      const totalDisplayEl = document.getElementById("totalAmount");

      if (totalDisplayEl) totalDisplayEl.textContent = `₱${originalTotal.toFixed(2)}`;
      if (tenderedDisplay) tenderedDisplay.textContent = `₱${tendered.toFixed(2)}`;

      const change = Math.max(0, tendered - originalTotal);
      if (changeDisplay) changeDisplay.textContent = `₱${change.toFixed(2)}`;
    }


    function openQrPopup() {
      document.getElementById("qrPopup").classList.remove("hidden");
    }

    function openEPaymentPopup() {
      document.getElementById("EPaymentPopup").classList.remove("hidden");
    }

    function closeQrPopup() {
      document.getElementById("qrPopup").classList.add("hidden");
    }

    function closeEPaymentPopup() {
      document.getElementById("EPaymentPopup").classList.add("hidden");
    }

    function applyDiscount(type) {
      if (transType) {
        alert("Discount already applied: " + transType);
        return;
      }
      total = originalTotal * 0.8;
      transType = type;
      updateDisplay();
    }
  </script>

</body>

</html>