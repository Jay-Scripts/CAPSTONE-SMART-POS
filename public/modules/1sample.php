<?php
// =================== PHP SECTION ===================
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
  echo json_encode(['success' => false, 'message' => 'DB connection failed: ' . $e->getMessage()]);
  exit;
}

// =================== HANDLE POST ORDER ===================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_data'])) {
  $order_data = json_decode($_POST['order_data'], true);
  if (!$order_data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
  }

  try {
    $conn->beginTransaction();

    $total = 0;
    foreach ($order_data as $item) {
      $total += $item['price'] * $item['quantity'];
    }

    $staff_id = 69; // replace with session staff_id
    $stmt = $conn->prepare("INSERT INTO REG_TRANSACTION (STAFF_ID, TOTAL_AMOUNT) VALUES (?, ?)");
    $stmt->execute([$staff_id, $total]);
    $transaction_id = $conn->lastInsertId();

    foreach ($order_data as $item) {
      $stmt = $conn->prepare("INSERT INTO TRANSACTION_ITEM (REG_TRANSACTION_ID, PRODUCT_ID, SIZE_ID, QUANTITY, PRICE) VALUES (?, ?, ?, ?, ?)");
      $stmt->execute([
        $transaction_id,
        $item['product_id'],
        $item['size_id'],
        $item['quantity'],
        $item['price']
      ]);
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

    $conn->commit();
    echo json_encode(['success' => true, 'transaction_id' => $transaction_id]);
    exit;
  } catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
  }
}

// =================== FETCH PRODUCTS ===================
$stmt = $conn->query("
    SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price, ps.size_id
    FROM product_details pd
    JOIN product_sizes ps ON pd.product_id = ps.product_id
    WHERE pd.status='active'
    ORDER BY pd.product_name ASC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$addons_stmt = $conn->query("SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active'");
$addons = $addons_stmt->fetchAll(PDO::FETCH_ASSOC);

$mods_stmt = $conn->query("SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active'");
$modifications = $mods_stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: text/html');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>POS Product Modal</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-5">

  <h2 class="text-xl font-bold mb-4">Products</h2>
  <div class="grid grid-cols-3 gap-4">
    <?php foreach ($products as $product): ?>
      <div class="bg-white rounded-lg shadow p-3 cursor-pointer" onclick="openModal(<?= $product['product_id'] ?>)">
        <img src="<?= $product['thumbnail_path'] ?>" class="w-full h-32 object-cover rounded mb-2">
        <h3 class="text-center font-semibold"><?= $product['product_name'] ?></h3>
        <p class="text-center"><?= $product['size'] ?> - ₱<?= $product['regular_price'] ?></p>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Cart Preview -->
  <h2 class="text-xl font-bold mt-8 mb-2">Cart</h2>
  <div id="cartContainer" class="bg-white rounded-lg shadow p-3"></div>
  <button onclick="checkout()" class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded mt-2">Pay</button>

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
      <div class="mt-4">
        <h3 class="text-sm font-semibold text-gray-700 mb-2">Modifications</h3>
        <div class="flex flex-wrap gap-2" id="modifications">
          <?php foreach ($modifications as $mod): ?>
            <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
              <input type="checkbox" class="mod-checkbox accent-blue-500" value="<?= $mod['MODIFICATION_ID'] ?>">
              <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

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
        Total: ₱<span id="totalPrice">0</span>
      </div>

      <!-- Add to Order -->
      <div class="mt-5 flex justify-end">
        <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow" onclick="addToOrder()">Add to Order</button>
      </div>
    </div>
  </div>

  <script>
    let products = <?= json_encode($products) ?>;
    let selectedProduct = null;
    let cart = [];
    let quantityInput = document.getElementById('quantity');
    let totalPriceEl = document.getElementById('totalPrice');

    function openModal(productId) {
      selectedProduct = products.find(p => p.product_id == productId);
      document.getElementById('modalThumb').src = selectedProduct.thumbnail_path;
      document.getElementById('productName').textContent = selectedProduct.product_name;

      let sizesContainer = document.getElementById('sizesContainer');
      sizesContainer.innerHTML = '';
      products.filter(p => p.product_id == productId).forEach(s => {
        sizesContainer.innerHTML += `
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="size" value="${s.size}" data-id="${s.size_id}" data-price="${s.regular_price}" onchange="updateTotal()">
        ${s.size} (₱${s.regular_price})
      </label>`;
      });

      quantityInput.value = 1;
      document.querySelectorAll('.addon-checkbox').forEach(ch => ch.checked = false);
      document.querySelectorAll('.mod-checkbox').forEach(ch => ch.checked = false);
      updateTotal();

      document.getElementById('productModal').classList.remove('hidden');
    }

    function closeModal() {
      document.getElementById('productModal').classList.add('hidden');
    }

    function updateTotal() {
      let price = 0;
      const size = document.querySelector('input[name="size"]:checked');
      if (size) price += parseFloat(size.dataset.price);
      document.querySelectorAll('.addon-checkbox:checked').forEach(a => price += parseFloat(a.dataset.price));
      totalPriceEl.textContent = (price * quantityInput.value).toFixed(2);
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
      const container = document.getElementById('cartContainer');
      container.innerHTML = '';
      let total = 0;
      cart.forEach((item, index) => {
        const product = products.find(p => p.product_id == item.product_id);
        const subtotal = item.price * item.quantity;
        total += subtotal;
        container.innerHTML += `
      <div class="flex justify-between items-center border-b py-1">
        <span>${item.quantity} x ${product.product_name}</span>
        <span>₱${subtotal.toFixed(2)}</span>
        <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">X</button>
      </div>`;
      });
      container.innerHTML += `<div class="text-right font-semibold mt-2">Total: ₱${total.toFixed(2)}</div>`;
    }

    function removeFromCart(index) {
      cart.splice(index, 1);
      renderCart();
    }

    function checkout() {
      if (cart.length === 0) {
        alert('Cart is empty');
        return;
      }

      fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'order_data=' + encodeURIComponent(JSON.stringify(cart))
        })
        .then(r => r.json())
        .then(res => {
          if (res.success) {
            alert('Transaction complete! ID: ' + res.transaction_id);
            cart = [];
            renderCart();
          } else alert('Error: ' + res.message);
        })
        .catch(e => console.error(e));
    }
  </script>

</body>

</html>