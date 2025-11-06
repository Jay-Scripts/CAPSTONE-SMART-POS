   <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
       <input type="text" id="searchLogs" placeholder="Search logs..." class="p-2 border border-[var(--border-color)] rounded w-full sm:w-1/2 bg-[var(--background-color)] text-[var(--text-color)]">
       <select id="filterActionType" class="p-2 border border-[var(--border-color)] rounded bg-[var(--background-color)] text-[var(--text-color)] w-full sm:w-1/3">
           <option value="">All Actions</option>
           <option value="RESTOCK">Restock</option>
           <option value="ADJUSTMENT">Adjustment</option>
           <option value="EXPIRED">Expired</option>
           <option value="DAMAGED">Damaged</option>
           <option value="INVENTORY">Inventory</option>
       </select>
   </div>

   <?php
    try {
        $logs = $conn->query("
            SELECT 
                l.*,
                ii.item_name,
                s.staff_name
            FROM inventory_item_logs l
            LEFT JOIN inventory_item ii ON l.item_id = ii.item_id
            LEFT JOIN staff_info s ON l.staff_id = s.staff_id
            ORDER BY l.date_logged DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $logs = [];
    }
    ?>

   <?php if (!empty($logs)): ?>
       <div class="overflow-x-auto border border-[var(--border-color)] rounded-lg">
           <table id="logsTable" class="min-w-full border-collapse bg-[var(--glass-bg)] ">
               <thead class=" sticky top-0 z-10">
                   <tr>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Item</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Action</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">By</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Before</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Adjusted</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">After</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Remarks</th>
                       <th class="py-2 px-4 border border-[var(--border-color)]">Date</th>
                   </tr>
               </thead>
               <tbody>
                   <?php foreach ($logs as $log): ?>
                       <tr class="hover:bg-blue-400 hover:text-white transition" data-action="<?= $log['action_type'] ?>">
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= htmlspecialchars($log['item_name']) ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= $log['action_type'] ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= htmlspecialchars($log['staff_name']) ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= $log['last_quantity'] ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= $log['quantity_adjusted'] ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= $log['total_after'] ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= htmlspecialchars($log['remarks']) ?></td>
                           <td class="py-2 px-4 border border-[var(--border-color)]"><?= $log['date_logged'] ?></td>
                       </tr>
                   <?php endforeach; ?>
               </tbody>
           </table>
       </div>

       <div class="mt-4 flex justify-center gap-2" id="logsPagination"></div>

   <?php else: ?>
       <p class="ztext-sm">No stock history found.</p>
   <?php endif; ?>

   <script>
       document.addEventListener('DOMContentLoaded', () => {
           const table = document.getElementById('logsTable');
           const searchInput = document.getElementById('searchLogs');
           const filterSelect = document.getElementById('filterActionType');
           const pagination = document.getElementById('logsPagination');
           const rowsPerPage = 10;
           let currentPage = 1;

           const rows = Array.from(table.querySelectorAll('tbody tr'));

           function renderTable() {
               const filterText = searchInput.value.toLowerCase();
               const filterAction = filterSelect.value;

               const filteredRows = rows.filter(row => {
                   const text = row.textContent.toLowerCase();
                   const action = row.dataset.action;
                   return text.includes(filterText) && (filterAction === '' || action === filterAction);
               });

               const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
               currentPage = Math.min(currentPage, totalPages) || 1;

               // Hide all rows
               rows.forEach(r => r.style.display = 'none');

               // Show current page rows
               const start = (currentPage - 1) * rowsPerPage;
               const end = start + rowsPerPage;
               filteredRows.slice(start, end).forEach(r => r.style.display = '');

               // Render pagination
               pagination.innerHTML = '';

               // Previous button
               const prevBtn = document.createElement('button');
               prevBtn.textContent = '<';
               prevBtn.disabled = currentPage === 1;
               prevBtn.className = `px-3 py-1 rounded ${prevBtn.disabled ? 'bg-gray-300' : 'bg-gray-200'}`;
               prevBtn.addEventListener('click', () => {
                   currentPage--;
                   renderTable();
               });
               pagination.appendChild(prevBtn);

               // Page number buttons (max 5 visible at a time)
               let startPage = Math.max(1, currentPage - 2);
               let endPage = Math.min(totalPages, startPage + 4);
               startPage = Math.max(1, endPage - 4); // adjust start if at end

               for (let i = startPage; i <= endPage; i++) {
                   const btn = document.createElement('button');
                   btn.textContent = i;
                   btn.className = `px-3 py-1 rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200'}`;
                   btn.addEventListener('click', () => {
                       currentPage = i;
                       renderTable();
                   });
                   pagination.appendChild(btn);
               }

               // Next button
               const nextBtn = document.createElement('button');
               nextBtn.textContent = '>';
               nextBtn.disabled = currentPage === totalPages || totalPages === 0;
               nextBtn.className = `px-3 py-1 rounded ${nextBtn.disabled ? 'bg-gray-300' : 'bg-gray-200'}`;
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
   </script>