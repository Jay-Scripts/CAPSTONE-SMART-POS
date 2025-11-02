      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Restocks Stocks Starts Here                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      <div id="restockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
          <div class="bg-[var(--background-color)] text-[var(--text-color)] border-[var(--border-color)] border rounded-2xl p-6 w-full max-w-md shadow-xl animate-[fadeIn_0.3s_ease]">
              <h2 class="text-xl font-semibold mb-4">Restock Item</h2>
              <form id="restockForm" class="space-y-4">
                  <!-- Item Name -->
                  <div>
                      <label class="block text-sm font-medium">Item Name</label>
                      <input id="restock-item_name" type="text" readonly
                          class="w-full mt-1 p-2 border rounded-lg border-[var(--border-color)] bg-[var(--background-color)]">
                  </div>
                  <!-- Unit -->
                  <div>
                      <label class="block text-sm font-medium">Unit</label>
                      <input id="restock-unit" type="text" readonly
                          class="w-full mt-1 p-2 border rounded-lg border-[var(--border-color)] bg-[var(--background-color)]">
                  </div>
                  <!-- Quantity -->
                  <div>
                      <label class="block text-sm font-medium">Quantity</label>
                      <input id="restock-quantity" type="number" min="0.01" step="0.01" required placeholder="Enter quantity"
                          class="w-full mt-1 p-2 border rounded-lg border-[var(--border-color)] bg-[var(--background-color)]">
                  </div>
                  <!-- Manufacturing Date -->
                  <div>
                      <label class="block text-sm font-medium">Manufacturing Date</label>
                      <input id="restock-date_made" type="date" required
                          class="w-full mt-1 p-2 border rounded-lg border-[var(--border-color)] bg-[var(--background-color)]">
                  </div>
                  <!-- Expiry Date -->
                  <div>
                      <label class="block text-sm font-medium">Expiry Date</label>
                      <input id="restock-date_expiry" type="date" required
                          class="w-full mt-1 p-2 border rounded-lg border-[var(--border-color)] bg-[var(--background-color)]">
                  </div>
                  <!-- Buttons -->
                  <div class="flex justify-end gap-2">
                      <button type="button" id="restockCancelBtn"
                          class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Cancel</button>
                      <button type="submit"
                          class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Restock</button>
                  </div>
              </form>
          </div>
      </div>
      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Restocks Stocks Starts Here                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Restocks Scripts Starts Here                                                  =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      <script>
          // ================= Restock =================
          const restockModal = document.getElementById('restockModal');
          const restockForm = document.getElementById('restockForm');
          const restockCancelBtn = document.getElementById('restockCancelBtn');
          // Show restock modal
          document.querySelectorAll('.restock-btn').forEach(btn => {
              btn.addEventListener('click', function() {
                  const row = this.closest('tr');

                  restockModal.dataset.itemId = this.dataset.id;
                  restockModal.dataset.invCategoryId = row.dataset.invCategoryId;
                  restockModal.dataset.productId = row.dataset.productId || null;
                  restockModal.dataset.categoryId = row.dataset.categoryId || null;

                  const itemName = row.querySelector('td:nth-child(1)').textContent.trim();
                  const unit = row.querySelector('td:nth-child(3)').textContent.trim();

                  document.getElementById('restock-item_name').value = itemName;
                  document.getElementById('restock-unit').value = unit;
                  document.getElementById('restock-quantity').value = '';
                  document.getElementById('restock-date_made').value = '';
                  document.getElementById('restock-date_expiry').value = '';

                  restockModal.classList.remove('hidden');
                  restockModal.classList.add('flex');
              });
          });
          // Cancel button
          restockCancelBtn.addEventListener('click', () => {
              restockModal.classList.add('hidden');
          });
          // Submit restock form
          restockForm.addEventListener('submit', async function(e) {
              e.preventDefault();

              const itemId = restockModal.dataset.itemId;
              const invCategoryId = restockModal.dataset.invCategoryId;
              const productId = restockModal.dataset.productId;
              const categoryId = restockModal.dataset.categoryId;
              const itemName = document.getElementById('restock-item_name').value;
              const unit = document.getElementById('restock-unit').value;
              const quantity = document.getElementById('restock-quantity').value;
              const dateMade = document.getElementById('restock-date_made').value;
              const dateExpiry = document.getElementById('restock-date_expiry').value;
              try {
                  const res = await fetch('../../app/includes/managerModule/managerStockManagementRestock.php', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({
                          item_id: itemId,
                          inv_category_id: invCategoryId,
                          product_id: productId,
                          category_id: categoryId,
                          item_name: itemName,
                          unit: unit,
                          quantity: quantity,
                          date_made: dateMade,
                          date_expiry: dateExpiry
                      })
                  });
                  const result = await res.json();
                  if (result.success) {
                      Swal.fire({
                          icon: 'success',
                          title: 'Item Restocked',
                          text: `${itemName} added with ${quantity} ${unit}.`,
                          timer: 2000,
                          showConfirmButton: false
                      });
                      restockModal.classList.add('hidden');
                  } else {
                      Swal.fire('Error!', result.message || 'Failed to restock item.', 'error');
                  }
              } catch (err) {
                  Swal.fire('Error!', 'Request failed: ' + err.message, 'error');
              }
          });
      </script>
      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Restocks Scripts Ends Here                                                    =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->