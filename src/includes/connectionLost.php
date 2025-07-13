<?php
$items = [
  ['name' => 'Burger', 'icon' => '🍔', 'price' => 89],
  ['name' => 'Pizza', 'icon' => '🍕', 'price' => 129],
  ['name' => 'Fries', 'icon' => '🍟', 'price' => 59],
  ['name' => 'Ice Cream', 'icon' => '🍦', 'price' => 49],
  ['name' => 'Soda', 'icon' => '🥤', 'price' => 39]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP Shopping Icons</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

  <div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">🛒 PHP Tailwind Shop</h1>

    <!-- Product Icons -->
    <div class="grid grid-cols-5 gap-4 mb-6">
      <?php foreach ($items as $item): ?>
        <button 
          class="bg-white shadow hover:bg-blue-100 p-4 text-3xl rounded-lg text-center cursor-pointer" 
          onclick="addToReceipt('<?php echo $item['name']; ?>', <?php echo $item['price']; ?>)">
          <?php echo $item['icon']; ?>
          <p class="text-sm font-semibold mt-1"><?php echo $item['name']; ?></p>
          <p class="text-xs text-gray-500">₱<?php echo $item['price']; ?></p>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Receipt -->
    <div class="bg-white rounded-xl shadow p-4">
      <h2 class="text-lg font-bold mb-2">🧾 Receipt</h2>
      <div id="receipt" class="space-y-2 text-sm text-gray-700">
        <!-- Items will appear here -->
      </div>
    </div>
  </div>

  <script>
    function addToReceipt(name, price) {
      const receipt = document.getElementById('receipt');
      const item = document.createElement('div');
      item.className = "flex justify-between border-b pb-1";
      item.innerHTML = `<span>${name}</span><span>₱${price}</span>`;
      receipt.appendChild(item);
    }
  </script>

</body>
</html>
