<?php
session_start();

$host = "localhost";
$port = 3307;
$dbName = "sampol";
$username = "root";
$password = "";

try {
  $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
  $conn = new PDO($dsn, $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if (isset($_GET['check'])) {
    echo "Connected";
    exit;
  }
} catch (PDOException $e) {
  if (isset($_GET['check'])) {
    echo "FAIL";
    exit;
  }
  die("DB Connection failed: " . $e->getMessage());
}

// Manager info
$manager_id = $_SESSION['staff_id'] ?? 'N/A';
$manager_name = $_SESSION['staff_name'] ?? 'N/A';

// Month filter (YYYY-MM)
$monthFilter = $_GET['month'] ?? date('Y-m');
$whereMonth = '';

if ($monthFilter) {
  $whereMonth = " AND DATE_FORMAT(dt.TRANSACTION_TIME, '%Y-%m') = :month";
}

try {
  $stmt = $conn->prepare("
        SELECT 
            dt.FIRST_NAME,
            dt.LAST_NAME,
            dt.ID_TYPE,
            dt.DISC_TOTAL_AMOUNT,
            rt.TOTAL_AMOUNT AS amount_paid,
            (IFNULL(rt.TOTAL_AMOUNT,0) + dt.DISC_TOTAL_AMOUNT) AS total_before_disc,
            dt.TRANSACTION_TIME
        FROM DISC_TRANSACTION dt
        LEFT JOIN REG_TRANSACTION rt 
            ON dt.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE 1=1 $whereMonth
        ORDER BY dt.TRANSACTION_TIME DESC
    ");

  if ($monthFilter) $stmt->bindValue(':month', $monthFilter, PDO::PARAM_STR);
  $stmt->execute();
  $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $discounts = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Discount Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      border: 1px solid #333;
      padding: 8px;
      text-align: left;
    }

    th {
      background: #f2f2f2;
    }

    .right {
      text-align: right;
    }

    .hoverRow:hover {
      background-color: #3b82f6;
      color: white;
    }

    button {
      cursor: pointer;
      margin: 2px;
    }

    .pagination button {
      min-width: 30px;
    }
  </style>
</head>

<body>

  <header class="mb-4 p-5">
    <h2 class="text-2xl font-bold mb-2">Discount Dashboard</h2>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
      <input type="text" id="searchDiscount" placeholder="Search customer..." class="p-2 border rounded w-full sm:w-1/2">
      <select id="filterDiscType" class="p-2 border rounded w-full sm:w-1/3">
        <option value="">All Types</option>
        <option value="PWD">PWD</option>
        <option value="SC">SC</option>
      </select>
      <input type="month" id="filterMonth" value="<?= htmlspecialchars($monthFilter) ?>" class="p-2 border rounded w-full sm:w-1/4">
      <button id="printDiscount" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-all">Print</button>
    </div>
  </header>

  <div class="overflow-x-auto rounded-lg p-5">
    <table id="discountTable" class="min-w-full border-collapse bg-white">
      <thead class="bg-gray-100 text-black sticky top-0 z-10">
        <tr>
          <th>Customer</th>
          <th>Discount Type</th>
          <th>Discount Amount</th>
          <th>Amount Paid</th>
          <th>Total Before Discount</th>
          <th>Transaction Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($discounts) === 0): ?>
          <tr>
            <td colspan="6" class="text-center py-4">No discounted transactions found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($discounts as $d): ?>
            <tr class="hoverRow" data-type="<?= $d['ID_TYPE'] ?>">
              <td><?= htmlspecialchars($d['FIRST_NAME'] . ' ' . $d['LAST_NAME']) ?></td>
              <td><?= $d['ID_TYPE'] ?></td>
              <td><?= number_format($d['DISC_TOTAL_AMOUNT'], 2) ?></td>
              <td><?= number_format($d['amount_paid'] ?? 0, 2) ?></td>
              <td><?= number_format($d['total_before_disc'], 2) ?></td>
              <td><?= $d['TRANSACTION_TIME'] ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="mt-4 flex justify-center gap-2 pagination" id="discountPagination"></div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const table = document.getElementById('discountTable');
      const searchInput = document.getElementById('searchDiscount');
      const filterSelect = document.getElementById('filterDiscType');
      const pagination = document.getElementById('discountPagination');
      const rowsPerPage = 10;
      let currentPage = 1;
      const rows = Array.from(table.querySelectorAll('tbody tr')).filter(r => r.cells.length > 1);

      function renderTable() {
        const filterText = searchInput.value.toLowerCase();
        const filterType = filterSelect.value;

        const filteredRows = rows.filter(row => {
          const text = row.textContent.toLowerCase();
          const type = row.dataset.type;
          return text.includes(filterText) && (filterType === '' || type === filterType);
        });

        rows.forEach(r => r.style.display = 'none');
        filteredRows.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage).forEach(r => r.style.display = '');

        pagination.innerHTML = '';
        if (filteredRows.length <= rowsPerPage) return;

        const totalPages = Math.ceil(filteredRows.length / rowsPerPage);

        const prevBtn = document.createElement('button');
        prevBtn.textContent = '<';
        prevBtn.disabled = currentPage === 1;
        prevBtn.addEventListener('click', () => {
          currentPage--;
          renderTable();
        });
        pagination.appendChild(prevBtn);

        for (let i = 1; i <= totalPages; i++) {
          const btn = document.createElement('button');
          btn.textContent = i;
          btn.style.background = i === currentPage ? '#3b82f6' : '#e5e7eb';
          btn.style.color = i === currentPage ? 'white' : 'black';
          btn.addEventListener('click', () => {
            currentPage = i;
            renderTable();
          });
          pagination.appendChild(btn);
        }

        const nextBtn = document.createElement('button');
        nextBtn.textContent = '>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.addEventListener('click', () => {
          currentPage++;
          renderTable();
        });
        pagination.appendChild(nextBtn);
      }

      searchInput.addEventListener('input', () => {
        currentPage = 1;
        renderTable();
      });
      filterSelect.addEventListener('change', () => {
        currentPage = 1;
        renderTable();
      });

      renderTable();
    });

    document.getElementById('filterMonth').addEventListener('change', e => {
      const month = e.target.value;
      if (!month) return;
      window.location.href = `?month=${month}`;
    });

    document.getElementById('printDiscount').addEventListener('click', () => {
      const month = document.getElementById('filterMonth').value;
      if (!month) {
        alert('Select a month');
        return;
      }
      window.open(`../../app/includes/managerModule/printDiscounts.php?month=${encodeURIComponent(month)}`, "_blank");
    });
  </script>
</body>

</html>