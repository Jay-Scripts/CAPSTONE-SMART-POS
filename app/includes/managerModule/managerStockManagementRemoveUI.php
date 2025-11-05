      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Remove Stocks Starts Here                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      <div id="removeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
          <div class="bg-[var(--background-color)] text-[var(--text-color)] rounded-lg shadow-lg w-11/12 max-w-md p-6">
              <h2 class="text-xl font-bold mb-4">Remove Item</h2>
              <form id="removeForm" class="space-y-3">
                  <label class="block">
                      Reason:
                      <select id="remove-action_type" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2">
                          <option value="">Select reason</option>
                          <option value="DAMAGED">Damaged</option>
                          <option value="EXPIRED">Expired</option>
                      </select>
                  </label>
                  <label class="block">
                      Remarks (optional):
                      <textarea id="remove-remarks" class="w-full border border-[var(--border-color)] bg-[var(--background-color)] rounded px-3 py-2" placeholder="Enter remarks"></textarea>
                  </label>
                  <div class="flex justify-end space-x-2">
                      <button type="button" id="removeCancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                      <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Remove</button>
                  </div>
              </form>
          </div>
      </div>
      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Remove Stocks Ends  Here                                                   =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->

      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Remove Stocks SCripts Starts Here                                             =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->
      <script>
          // ================= Remove =================
          const removeModal = document.getElementById('removeModal');
          const removeForm = document.getElementById('removeForm');
          const removeCancelBtn = document.getElementById('removeCancelBtn');

          // Show remove modal
          document.querySelectorAll('.remove-btn').forEach(btn => {
              btn.addEventListener('click', function() {
                  const row = this.closest('tr');
                  const itemId = this.dataset.id;

                  // Store the itemId in modal dataset
                  removeModal.dataset.itemId = itemId;

                  // Show modal
                  removeModal.classList.remove('hidden');
              });
          });

          // Cancel remove
          removeForm.addEventListener('submit', async function(e) {
              e.preventDefault();

              const itemId = removeModal.dataset.itemId;
              const actionType = document.getElementById('remove-action_type').value;
              const remarks = document.getElementById('remove-remarks').value.trim();

              if (!actionType) {
                  Swal.fire({
                      icon: 'warning',
                      title: 'Missing Reason',
                      text: 'Please select a reason before proceeding.'
                  });
                  return;
              }

              // ✅ Block special characters (allow letters, numbers, space, period, comma, and dash)
              const invalidChars = /[^a-zA-Z0-9 .,()-]/;
              if (invalidChars.test(remarks)) {
                  Swal.fire({
                      icon: 'error',
                      title: 'Invalid Remarks',
                      text: 'Remarks should not contain special characters or symbols.'
                  });
                  return;
              }

              const row = document.querySelector(`.remove-btn[data-id='${itemId}']`).closest('tr');
              const currentQuantity = parseFloat(row.children[1].textContent.trim()) || 0;

              try {
                  const res = await fetch('../../app/includes/managerModule/managerStockManagementRemove.php', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json'
                      },
                      body: JSON.stringify({
                          item_id: itemId,
                          action_type: actionType,
                          remarks,
                          last_quantity: currentQuantity
                      })
                  });

                  const result = await res.json();

                  if (result.success) {
                      removeModal.classList.add('hidden');
                      Swal.fire({
                          icon: 'success',
                          title: 'Item Removed!',
                          text: `Item marked as ${actionType.toLowerCase()}.`,
                          timer: 2000,
                          showConfirmButton: false
                      });
                  } else {
                      Swal.fire('Error!', result.message || 'Failed to remove item.', 'error');
                  }
              } catch (err) {
                  Swal.fire('Error!', 'Request failed: ' + err.message, 'error');
              }
          });
      </script>

      <!-- 
      ==========================================================================================================================================
      =                                                                                                                                        =
      =                                                          Remove Stocks SCripts Ends Here                                             =
      =                                                                                                                                        =
      ==========================================================================================================================================
    -->