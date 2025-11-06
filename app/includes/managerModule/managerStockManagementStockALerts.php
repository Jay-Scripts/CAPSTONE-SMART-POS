 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
     <input type="text" id="searchStock" placeholder="Search items..." class="p-2 border border-[var(--border-color)] rounded w-full sm:w-1/2 bg-[var(--background-color)] text-[var(--text-color)]">
     <select id="filterStatus" class="p-2 border border-[var(--border-color)] rounded bg-[var(--background-color)] text-[var(--text-color)] w-full sm:w-1/3">
         <option value="">All Statuses</option>
         <option value="LOW STOCK">Low Stock</option>
         <option value="SOON TO EXPIRE">Soon to Expire</option>
         <option value="EXPIRED">Expired</option>
     </select>
 </div>

 <?php
    try {
        $alertItems = $conn->query("
      SELECT 
          ii.item_id,
          ii.item_name,
          ii.quantity,
          ii.unit,
          ii.status,
          ii.date_expiry,
          s.staff_name
      FROM inventory_item ii
      LEFT JOIN staff_info s ON ii.added_by = s.staff_id
      WHERE UPPER(ii.status) IN ('LOW STOCK', 'SOON TO EXPIRE', 'EXPIRED')
      ORDER BY FIELD(ii.status, 'LOW STOCK', 'SOON TO EXPIRE', 'EXPIRED'), ii.item_name
    ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $alertItems = [];
    }
    ?>

 <?php if (!empty($alertItems)): ?>
     <div class="overflow-x-auto border border-[var(--border-color)] rounded-lg ">
         <table id="stockTable" class="min-w-full border-collapse bg-[var(--glass-bg)] text-[var(--text-color)]">
             <thead class=" sticky top-0 z-10">
                 <tr>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Item</th>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Quantity</th>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Unit</th>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Status</th>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Expiry</th>
                     <th class="py-2 px-4 border border-[var(--border-color)]">Added By</th>
                 </tr>
             </thead>
             <tbody>
                 <?php foreach ($alertItems as $item): ?>
                     <tr class="hover:bg-blue-400 hover:text-white transition" data-status="<?= strtoupper($item['status']) ?>">
                         <td class="py-2 px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                         <td class="py-2 px-4 border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                         <td class="py-2 px-4 border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                         <td class="py-2 px-4 border border-[var(--border-color)]">
                             <span class="px-2 py-1 rounded-lg text-xs font-semibold border <?= getStatusClass($item['status']) ?>">
                                 <?= htmlspecialchars($item['status']) ?>
                             </span>
                         </td>
                         <td class="py-2 px-4 border border-[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                         <td class="py-2 px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['staff_name']) ?></td>
                     </tr>
                 <?php endforeach; ?>
             </tbody>
         </table>
     </div>

     <div class="mt-4 flex justify-center gap-2" id="pagination"></div>

 <?php else: ?>
     <p class=" text-sm">No low stock, soon-to-expire, or expired items found.</p>
 <?php endif; ?>

 <script>
     document.addEventListener('DOMContentLoaded', () => {
         const table = document.getElementById('stockTable');
         const searchInput = document.getElementById('searchStock');
         const filterSelect = document.getElementById('filterStatus');
         const pagination = document.getElementById('pagination');
         const rowsPerPage = 10;
         let currentPage = 1;

         const rows = Array.from(table.querySelectorAll('tbody tr'));

         function renderTable() {
             const filterText = searchInput.value.toLowerCase();
             const filterStatus = filterSelect.value;

             const filteredRows = rows.filter(row => {
                 const text = row.textContent.toLowerCase();
                 const status = row.dataset.status;
                 return text.includes(filterText) && (filterStatus === '' || status === filterStatus);
             });

             const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
             currentPage = Math.min(currentPage, totalPages) || 1;

             rows.forEach(r => r.style.display = 'none');

             const start = (currentPage - 1) * rowsPerPage;
             const end = start + rowsPerPage;
             filteredRows.slice(start, end).forEach(r => r.style.display = '');

             pagination.innerHTML = '';
             for (let i = 1; i <= totalPages; i++) {
                 const btn = document.createElement('button');
                 btn.textContent = i;
                 btn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200'}`;
                 btn.addEventListener('click', () => {
                     currentPage = i;
                     renderTable();
                 });
                 pagination.appendChild(btn);
             }
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
 </script>