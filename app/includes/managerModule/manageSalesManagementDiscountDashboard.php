<?php
$monthFilter = $_GET['month'] ?? null;
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
            (rt.TOTAL_AMOUNT + dt.DISC_TOTAL_AMOUNT) AS total_before_disc,
            dt.TRANSACTION_TIME
        FROM DISC_TRANSACTION dt
        INNER JOIN REG_TRANSACTION rt 
            ON dt.REG_TRANSACTION_ID = rt.REG_TRANSACTION_ID
        WHERE 1=1 $whereMonth
        ORDER BY dt.TRANSACTION_TIME DESC
    ");

    if ($monthFilter) $stmt->bindValue(':month', $monthFilter);
    $stmt->execute();
    $discounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $discounts = [];
}

?>
<header class="mb-4 p-5">
    <h2 class="text-2xl font-bold mb-2">Discount Dashboard</h2>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <input type="text" id="searchDiscount" placeholder="Search customer..."
            class="p-2 border border-[var(--border-color)] bg-[var(--background-color)] rounded w-full sm:w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <select id="filterDiscType"
            class="p-2 border  bg-[var(--background-color)] rounded w-full sm:w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">All Types</option>
            <option value="PWD">PWD</option>
            <option value="SC">SC</option>
        </select>
        <input type="month" id="filterMonth" value="<?= htmlspecialchars($monthFilter ?? '') ?>"
            class="p-2 border border-[var(--border-color)] bg-[var(--background-color)] rounded w-full sm:w-1/4 focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button id="printDiscount"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-all">Print</button>
    </div>
</header>
<div class="overflow-x-auto rounded-lg p-5">
    <table id="discountTable" class="min-w-full border-collapse  bg-[var(--glass-bg)]">
        <thead class="bg-gray-100 text-black sticky top-0 z-10">
            <tr>
                <th class="py-2 px-4 border">Customer</th>
                <th class="py-2 px-4 border">Discount Type</th>
                <th class="py-2 px-4 border">Discount Amount</th>
                <th class="py-2 px-4 border">Amount Paid</th>
                <th class="py-2 px-4 border">Total Before Discount</th>
                <th class="py-2 px-4 border">Transaction Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($discounts as $d): ?>
                <tr class="hover:bg-blue-400 hover:text-white transition" data-type="<?= $d['ID_TYPE'] ?>">
                    <td class="py-2 px-4 border"><?= htmlspecialchars($d['FIRST_NAME'] . ' ' . $d['LAST_NAME']) ?></td>
                    <td class="py-2 px-4 border"><?= $d['ID_TYPE'] ?></td>
                    <td class="py-2 px-4 border"><?= number_format($d['DISC_TOTAL_AMOUNT'], 2) ?></td>
                    <td class="py-2 px-4 border"><?= number_format($d['amount_paid'], 2) ?></td>
                    <td class="py-2 px-4 border"><?= number_format($d['total_before_disc'], 2) ?></td>
                    <td class="py-2 px-4 border"><?= $d['TRANSACTION_TIME'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="mt-4 flex justify-center gap-2" id="discountPagination"></div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const table = document.getElementById('discountTable');
        const searchInput = document.getElementById('searchDiscount');
        const filterSelect = document.getElementById('filterDiscType');
        const pagination = document.getElementById('discountPagination');
        const rowsPerPage = 10;
        let currentPage = 1;

        const rows = Array.from(table.querySelectorAll('tbody tr'));

        function renderTable() {
            const filterText = searchInput.value.toLowerCase();
            const filterType = filterSelect.value;

            const filteredRows = rows.filter(row => {
                const text = row.textContent.toLowerCase();
                const type = row.dataset.type;
                return text.includes(filterText) && (filterType === '' || type === filterType);
            });

            if (filteredRows.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'No Results',
                    text: 'No discounted transactions match your search/filter.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
            currentPage = Math.min(currentPage, totalPages);

            rows.forEach(r => r.style.display = 'none');
            filteredRows.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage)
                .forEach(r => r.style.display = '');

            // Pagination
            pagination.innerHTML = '';
            if (totalPages <= 1) return;

            const prevBtn = document.createElement('button');
            prevBtn.textContent = '<';
            prevBtn.disabled = currentPage === 1;
            prevBtn.className = `px-3 py-1 rounded ${prevBtn.disabled ? 'bg-gray-200' : 'bg-gray-300'}`;
            prevBtn.addEventListener('click', () => {
                currentPage--;
                renderTable();
            });
            pagination.appendChild(prevBtn);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = `px-3 py-1 rounded ${i===currentPage?'bg-blue-500 text-white':'bg-gray-200'}`;
                btn.addEventListener('click', () => {
                    currentPage = i;
                    renderTable();
                });
                pagination.appendChild(btn);
            }

            const nextBtn = document.createElement('button');
            nextBtn.textContent = '>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.className = `px-3 py-1 rounded ${nextBtn.disabled ? 'bg-gray-200' : 'bg-gray-300'}`;
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

    // Month filter reload
    document.getElementById('filterMonth').addEventListener('change', e => {
        const month = e.target.value;
        if (!month) {
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Month',
                text: 'Please select a valid month before filtering.',
            });
            return;
        }
        window.location.href = `?month=${month}`;
    });

    // Print in new window
    document.getElementById('printDiscount').addEventListener('click', () => {
        const month = document.getElementById('filterMonth').value || '';
        if (!month) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Month',
                text: 'Please select a month before printing.',
            });
            return;
        }
        // Open dedicated print page
        const url = `../../app/includes/managerModule/printDiscounts.php?month=${encodeURIComponent(month)}`;
        window.open(url, "_blank");
    });
</script>