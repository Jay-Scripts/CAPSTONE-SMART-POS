<?php
try {
    // Inventory categories
    $invCategories = $conn->query("SELECT inv_category_id, category_name FROM inventory_category ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Product categories
    $prodCategories = $conn->query("SELECT category_id, category_name FROM category ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Products
    $products = $conn->query("SELECT product_id, product_name FROM product_details ORDER BY product_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    // ===================== Materials (Inventory category 2) =====================
    $materials = $conn->prepare("
        SELECT 
            ii.item_id,
            ii.item_name,
            ii.quantity,
            ii.unit,
            ii.status,
            ii.date_made,
            ii.date_expiry,
            si.staff_name AS added_by
        FROM inventory_item ii
        LEFT JOIN staff_info si ON ii.added_by = si.staff_id
        WHERE ii.inv_category_id = 2
        ORDER BY ii.item_name
    ");
    $materials->execute();
    $materialsItems = $materials->fetchAll(PDO::FETCH_ASSOC);

    // ===================== Other categories =====================
    $data = $conn->query("
        SELECT 
            c.category_id,
            c.category_name,
            ii.item_id,
            ii.item_name,
            ii.quantity,
            ii.unit,
            ii.status,
            ii.date_made,
            ii.date_expiry,
            si.staff_name AS added_by
        FROM category c
        LEFT JOIN inventory_item ii ON c.category_id = ii.category_id
        LEFT JOIN staff_info si ON ii.added_by = si.staff_id
        ORDER BY c.category_id, ii.item_name
    ")->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['category_id']]['category_name'] = $row['category_name'];
        $grouped[$row['category_id']]['items'][] = $row;
    }
} catch (PDOException $e) {
    $materialsItems = [];
    $grouped = [];
}
function getStatusClass($status)
{
    switch (strtoupper($status)) {
        case 'IN STOCK':
            return 'bg-green-100 text-green-800 border-green-300';
        case 'LOW STOCK':
            return 'bg-yellow-100 text-yellow-800 border-yellow-300';
        case 'OUT OF STOCK':
            return 'bg-red-100 text-red-800 border-red-300';
        case 'SOON TO EXPIRE':
            return 'bg-orange-100 text-orange-800 border-orange-300';
        case 'UNAVAILABLE':
            return 'bg-gray-200 text-gray-700 border-gray-300';
        default:
            return 'bg-gray-100 text-gray-800 border-gray-300';
    }
}

?>

<section class="flex justify-center p-4 sm:p-6">
    <div class="w-full bg-[var(--background-color)] text-[var(--text-color)] shadow-md rounded-2xl p-4 sm:p-6 border border-[var(--border-color)]">


        <!-- 
      ==========================================================================================================================================
      =                                                    Adding Modal UI Starts Here                                                         =
      ==========================================================================================================================================
    -->
        <?php
        include "../../app/includes/managerModule/managersStockManagementStockAddUI.php";
        ?>
        <!-- 
      ==========================================================================================================================================
      =                                                    Adding Modal UI Ends Here                                                           =
      ==========================================================================================================================================
    -->
        <?php

        // Fetch ingredients grouped by POS category
        function getIngredientsByCategory($conn)
        {
            $stmt = $conn->prepare("
        SELECT ii.*, s.staff_name, c.category_name
        FROM inventory_item ii
        JOIN staff_info s ON ii.added_by = s.staff_id
        LEFT JOIN category c ON ii.category_id = c.category_id
        WHERE ii.inv_category_id = 1
        ORDER BY c.category_name, ii.item_name
    ");
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $grouped = [];
            foreach ($items as $item) {
                $cat = $item['category_name'] ?? 'Uncategorized';
                $grouped[$cat][] = $item;
            }
            return $grouped;
        }

        $ingredientsByCat = getIngredientsByCategory($conn);

        // Materials (single table)
        function getMaterials($conn)
        {
            $stmt = $conn->prepare("
        SELECT ii.*, s.staff_name
        FROM inventory_item ii
        JOIN staff_info s ON ii.added_by = s.staff_id
        WHERE ii.inv_category_id = 2
        ORDER BY ii.item_name
    ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $materials = getMaterials($conn);

        ?>


        <!-- ================= Ingredients Tables (Mobile-First) ================= -->
        <?php foreach ($ingredientsByCat as $catName => $items): ?>
            <h2 class="text-xl sm:text-2xl font-bold mb-2"><?= htmlspecialchars($catName) ?></h2>
            <div class="overflow-x-auto mb-6">
                <table class="min-w-full bg-[var(--background-color)] rounded">
                    <thead>
                        <tr>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Item</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Quantity</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Unit</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Status</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Manufacturing Date</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Expiry Date</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Added By</th>
                            <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr
                                data-id="<?= $item['item_id'] ?>"
                                data-inv-category-id="<?= $item['inv_category_id'] ?>"
                                data-product-id="<?= $item['product_id'] ?? '' ?>"
                                data-category-id="<?= $item['category_id'] ?? '' ?>"
                                class="border-b border-gray-200 hover:bg-blue-400 hover:scale-[101%] hover:text-white">

                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                                <td class="py-1 px-2 sm:px-4 border text-center border border-[var(--border-color)]">
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold border <?= getStatusClass($item['status']) ?>">
                                        <?= htmlspecialchars($item['status']) ?>
                                    </span>
                                </td>

                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['date_made'] ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['staff_name']) ?></td>
                                <td class="py-1 px-2 sm:px-4 space-x-1 flex flex-wrap  border border-[var(--border-color)]">
                                    <button class="restock-btn bg-green-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Restock</button>
                                    <button class="modify-btn bg-blue-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Modify</button>
                                    <button class="remove-btn bg-red-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>

        <!-- ================= Materials Table ================= -->
        <h2 class="text-xl sm:text-2xl font-bold mb-2">Materials</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 bg-[var(--background-color)] shadow rounded">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Item</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Quantity</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Unit</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Status</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Manufacturing Date</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Expiry Date</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Added By</th>
                        <th class="py-2 px-2 sm:px-4 border border-[var(--border-color)]">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($materials as $item): ?>
                        <tr
                            data-id="<?= $item['item_id'] ?>"
                            data-inv-category-id="<?= $item['inv_category_id'] ?>"
                            data-product-id="<?= $item['product_id'] ?? '' ?>"
                            data-category-id="<?= $item['category_id'] ?? '' ?>"
                            class="border-b border-gray-200 hover:bg-blue-400 hover:scale-[101%] hover:text-white">

                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                            <td class="py-1 px-2 sm:px-4 border text-center border border-[var(--border-color)]">
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold border <?= getStatusClass($item['status']) ?>">
                                    <?= htmlspecialchars($item['status']) ?>
                                </span>
                            </td>

                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['date_made'] ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['staff_name']) ?></td>
                            <td class="py-1 px-2 sm:px-4 space-x-1 flex flex-wrap  border border-[var(--border-color)]">
                                <button class="restock-btn bg-green-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Restock</button>
                                <button class="modify-btn bg-blue-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Modify</button>
                                <button class="remove-btn bg-red-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- Restock Modal -->
        <div id="restockModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 w-full max-w-md shadow-xl">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Restock Item</h2>
                <form id="restockForm" class="space-y-4">

                    <!-- Item Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Item Name</label>
                        <input id="restock-item_name" type="text" readonly
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <!-- Unit -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit</label>
                        <input id="restock-unit" type="text" readonly
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                        <input id="restock-quantity" type="number" min="0.01" step="0.01" required placeholder="Enter quantity"
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <!-- Manufacturing Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Manufacturing Date</label>
                        <input id="restock-date_made" type="date" required
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date</label>
                        <input id="restock-date_expiry" type="date" required
                            class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
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






        <!-- Modal backdrop -->
        <div id="modifyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <!-- Modal box -->
            <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6">
                <h2 class="text-xl font-bold mb-4">Modify Item</h2>
                <form id="modifyForm" class="space-y-3">
                    <input type="text" id="modal-item_name" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Item Name">
                    <input type="number" id="modal-quantity" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Quantity" min="0">
                    <select id="modal-unit" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="pcs">pcs</option>
                        <option value="ml">ml</option>
                        <option value="g">g</option>
                    </select>
                    <input type="date" id="modal-date_made" class="w-full border border-gray-300 rounded px-3 py-2">
                    <input type="date" id="modal-date_expiry" class="w-full border border-gray-300 rounded px-3 py-2">
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Remove Item Modal -->
        <div id="removeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg w-11/12 max-w-md p-6">
                <h2 class="text-xl font-bold mb-4">Remove Item</h2>
                <form id="removeForm" class="space-y-3">
                    <label class="block">
                        Reason:
                        <select id="remove-action_type" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">Select reason</option>
                            <option value="DAMAGED">Damaged</option>
                            <option value="EXPIRED">Expired</option>
                        </select>
                    </label>
                    <label class="block">
                        Remarks (optional):
                        <textarea id="remove-remarks" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Enter remarks"></textarea>
                    </label>
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="removeCancelBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">Remove</button>
                    </div>
                </form>
            </div>
        </div>

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
                            date_expiry: document.getElementById('modal-date_expiry').value
                        };

                        // Update UI immediately
                        row.children[0].textContent = data.item_name;
                        row.children[1].textContent = data.quantity;
                        row.children[2].textContent = data.unit;
                        row.children[4].textContent = data.date_made;
                        row.children[5].textContent = data.date_expiry;

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
                            } else {
                                Swal.fire('Error!', result.message || 'Update failed.', 'error');
                            }
                        } catch (err) {
                            Swal.fire('Error!', 'Request failed: ' + err.message, 'error');
                        }
                    };
                });
            });


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
            removeCancelBtn.addEventListener('click', () => {
                removeModal.classList.add('hidden');
            });

            // Submit remove
            removeForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const itemId = removeModal.dataset.itemId;
                const actionType = document.getElementById('remove-action_type').value;
                const remarks = document.getElementById('remove-remarks').value;

                if (!actionType) {
                    alert('Please select a reason');
                    return;
                }

                // 🔹 Get current quantity from the table
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
                            last_quantity: currentQuantity // 🔹 include quantity
                        })
                    });

                    const result = await res.json();

                    if (result.success) {
                        row.children[3].textContent = 'UNAVAILABLE';
                        row.children[1].textContent = 0;
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
    </div>


</section>