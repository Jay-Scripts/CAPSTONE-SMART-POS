          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Modify Stocks Starts Here                                                     =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
          <div id="modifyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
              <!-- Modal box -->
              <div class="bg-[var(--background-color)] text-[var(--text-color)] rounded-lg shadow-lg w-11/12 max-w-md p-6">
                  <h2 class="text-xl font-bold mb-4">Modify Item</h2>
                  <form id="modifyForm" class="space-y-3">
                      <input type="text" id="modal-item_name" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2" placeholder="Item Name">
                      <input type="number" id="modal-quantity" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2" placeholder="Quantity" min="0">
                      <select id="modal-unit" class="w-full border border-[var(--border-color)] bg-[var(--background-color)]  rounded px-3 py-2">
                          <option value="pcs">pcs</option>
                          <option value="ml">ml</option>
                          <option value="g">g</option>
                      </select>
                      <input type="date" id="modal-date_made" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2">
                      <input type="date" id="modal-date_expiry" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2">
                      <input type="text" id="modal-remarks" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2" placeholder="Enter remarks">

                      <div class="flex justify-end space-x-2">
                          <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Modify Stocks Ends Here                                                       =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Modify Stocks SCripts Starts Here                                             =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
          <script>
              document.querySelectorAll('.modify-btn').forEach(btn => {
                  btn.addEventListener('click', function() {
                      const row = this.closest('tr');
                      const itemId = this.dataset.id;

                      // Fill modal with current values
                      document.getElementById('modal-item_name').value = row.children[0].textContent.trim();
                      document.getElementById('modal-quantity').value = row.children[1].textContent.trim();
                      document.getElementById('modal-unit').value = row.children[2].textContent.trim();
                      document.getElementById('modal-date_made').value = row.children[4].textContent.trim();
                      document.getElementById('modal-date_expiry').value = row.children[5].textContent.trim();

                      const modal = document.getElementById('modifyModal');
                      modal.classList.remove('hidden');

                      // Cancel button
                      document.getElementById('cancelBtn').onclick = () => modal.classList.add('hidden');

                      document.getElementById('modifyForm').onsubmit = async (e) => {
                          e.preventDefault();

                          const data = {
                              item_name: document.getElementById('modal-item_name').value,
                              quantity: parseFloat(document.getElementById('modal-quantity').value),
                              unit: document.getElementById('modal-unit').value,
                              date_made: document.getElementById('modal-date_made').value,
                              date_expiry: document.getElementById('modal-date_expiry').value,
                              remarks: document.getElementById('modal-remarks').value.trim() // <-- new
                          };


                          modal.classList.add('hidden');

                          // Send AJAX to update DB and log adjustment
                          try {
                              const res = await fetch('../../app/includes/managerModule/managerStockManagementModify.php', {
                                  method: 'POST',
                                  headers: {
                                      'Content-Type': 'application/json'
                                  },
                                  body: JSON.stringify({
                                      item_id: itemId,
                                      ...data
                                  })
                              });
                              const result = await res.json();

                              if (result.success) {
                                  Swal.fire({
                                      icon: 'success',
                                      title: 'Updated!',
                                      text: 'Item has been updated successfully.',
                                      timer: 2000,
                                      showConfirmButton: false
                                  });

                                  // 🔥 Auto refresh after success
                                  setTimeout(() => {
                                      location.reload();
                                  }, 2000);
                              } else {
                                  Swal.fire('Error!', result.message || 'Update failed.', 'error');
                              }
                          } catch (err) {
                              Swal.fire('Error!', 'Request failed: ' + err.message, 'error');
                          }
                      };
                  });
              });
          </script>

          <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Modify Stocks SCripts Ends Here                                               =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->