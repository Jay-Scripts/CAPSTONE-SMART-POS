<?php
session_start();
include "../../app/config/dbConnection.php";

$customerId = $_SESSION['customer_id'] ?? null;

if (!$customerId) {
  header("Location: ../auth/customer/customerLogin.php");
  exit;
}

// Fetch points from customer_account
$userPoints = 0;
$stmt = $conn->prepare("
    SELECT ca.points, ci.FIRST_NAME, ci.LAST_NAME
    FROM customer_account ca
    JOIN CUSTOMER_INFO ci ON ca.CUSTOMER_ID = ci.CUSTOMER_ID
    WHERE ca.cust_account_id = :cust_id
    LIMIT 1
");
$stmt->bindParam(':cust_id', $customerId, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  $userPoints = (float)$row['points'];
  $customerName = $row['FIRST_NAME'] . ' ' . $row['LAST_NAME'];
} else {
  $customerName = "Guest";
}


// --------------------
// Fetch only active products and categories
// --------------------
$stmt = $conn->prepare("
   SELECT p.product_id, p.product_name, p.thumbnail_path, c.category_id, c.category_name
   FROM product_details p
   JOIN category c ON p.category_id = c.category_id
   WHERE p.status = 'active' AND c.status = 'ACTIVE'
");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];
$categories = [];
foreach ($rows as $row) {
  $catId = $row['category_id'];
  $categories[$catId] = $row['category_name'];

  $products[$catId][] = [
    'product_id' => $row['product_id'],
    'product_name' => $row['product_name'],
    'thumbnail_path' => $row['thumbnail_path']
  ];
}

// Map category names to SVG icons
$categoryIcons = [
  'MILK TEA' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2l-4 2"/><path d="M12 2v3"/><path d="M5 7h14"/><path d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7"/><path d="M7 12h10"/><circle cx="9" cy="16.5" r="1" fill="currentColor"/><circle cx="12" cy="17.5" r="1" fill="currentColor"/><circle cx="15" cy="16.5" r="1" fill="currentColor"/></svg>',
  'FRUIT TEA' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z"/><path d="M5 7h14"/><path d="M12 2v5"/><path d="M7 12h10"/><circle cx="16.5" cy="15.5" r="2"/><path d="M16.5 13.5v4"/><path d="M14.5 15.5h4"/></svg>',
  'HOT BREW' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z"/><path d="M16 10h1a3 3 0 0 1 0 6h-1"/><path d="M9 2v3"/><path d="M13 2v3"/></svg>',
  'ICED COFFEE' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z"/><path d="M6 7h12"/><path d="M12 2v5"/><rect x="9" y="11" width="2.5" height="2.5"/><rect x="12.5" y="14" width="2.5" height="2.5"/></svg>',
  'PRAF' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z"/><path d="M6 9c0-3 3-5 6-5s6 2 6 5"/><path d="M12 4V2"/><path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5"/></svg>',
  'PROMOS' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z"/><path d="M6 7h12"/><path d="M12 2v5"/><polygon points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10"/></svg>',
  'BROSTY' => '<svg class="w-5 h-5 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z"/><path d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2"/><path d="M15 5l2 4"/></svg>'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rewards App</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
</head>

<body class="bg-gray-50 min-h-screen">

  <script>
    const customerId = <?= json_encode($customerId) ?>;
    const customerName = <?= json_encode($customerName) ?>;
    console.log("Logged-in customer:", customerId, customerName);
  </script>

  <header class="flex justify-between items-center p-4 bg-white shadow sticky top-0 z-50">
    <div class="flex items-center gap-2">
      <img src="https://via.placeholder.com/40" class="h-10" alt="Logo">
      <h1 class="font-bold text-lg">Rewards App</h1>
    </div>
    <div class="text-sm font-semibold text-gray-700">
      <?= htmlspecialchars($customerName) ?> | Points: <?= $userPoints ?>
    </div>
  </header>

  <aside class="fixed top-16 left-0 h-[calc(100%-4rem)] w-20 md:w-28 bg-white p-2 shadow flex flex-col">
    <h2 class="font-bold mb-2 text-sm">Categories</h2>
    <div class="flex flex-col flex-1 gap-2">
      <?php foreach ($categories as $catId => $catName): ?>
        <button onclick="scrollToSection('cat<?= $catId ?>')"
          class="flex-1 flex flex-col items-center justify-center px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-center text-xs truncate">
          <?= $categoryIcons[strtoupper($catName)] ?? '<svg class="w-5 h-5 mb-1"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/></svg>' ?>
          <?= htmlspecialchars($catName) ?>
        </button>
      <?php endforeach; ?>
    </div>
  </aside>

  <main class="ml-20 md:ml-32 p-4 space-y-6">
    <?php foreach ($products as $catId => $prods): ?>
      <section id="cat<?= $catId ?>">
        <h2 class="text-xl font-bold mb-3"><?= htmlspecialchars($categories[$catId]) ?> Menu</h2>
        <div class="grid grid-cols-3 gap-3">
          <?php foreach ($prods as $p): ?>
            <div class="p-2 bg-white rounded-xl shadow hover:scale-105 transition cursor-pointer"
              onclick="redeemProduct(<?= $p['product_id'] ?>,'<?= htmlspecialchars($p['product_name']) ?>',0)">
              <img src="<?= $p['thumbnail_path'] ?>" class="object-cover rounded-lg w-full" alt="">
              <p class="text-center text-xs font-semibold truncate"><?= htmlspecialchars($p['product_name']) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </main>

  <div id="qrModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-4 rounded-xl flex flex-col items-center relative">
      <button onclick="closeQR()" class="absolute top-2 right-2 text-red-500 text-xl font-bold">&times;</button>
      <h3 class="font-bold mb-3">Scan this QR to redeem</h3>
      <canvas id="qrCanvas"></canvas>
    </div>
  </div>

  <script>
    function scrollToSection(id) {
      const section = document.getElementById(id);
      if (section) {
        const offsetTop = section.getBoundingClientRect().top + window.scrollY - 16;
        window.scrollTo({
          top: offsetTop,
          behavior: 'smooth'
        });
      }
    }

    function redeemProduct(productId, productName, pointsRequired) {
      if (!customerId) {
        Swal.fire('Error', 'Customer not logged in.', 'error');
        return;
      }

      Swal.fire({
        title: `Redeem ${productName}?`,
        text: `This will deduct ${pointsRequired} points from ${customerName}.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, redeem',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch('redeem_ajax.php', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                product_id: productId,
                customer_id: customerId
              })
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                generateQR(data.qrValue);
                Swal.fire('Redeemed!', data.message, 'success');
              } else {
                Swal.fire('Error', data.message, 'error');
              }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
        }
      });
    }

    function generateQR(value) {
      const qr = new QRious({
        element: document.getElementById('qrCanvas'),
        value,
        size: 200
      });
      document.getElementById('qrModal').classList.remove('hidden');
    }

    function closeQR() {
      document.getElementById('qrModal').classList.add('hidden');
    }

    document.getElementById('qrModal').addEventListener('click', e => {
      if (e.target === e.currentTarget) closeQR();
    });
  </script>

</body>

</html>