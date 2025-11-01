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
?>

<section class="flex justify-center p-4 sm:p-6">
    <div class="w-full bg-[var(--background-color)] text-[var(--text-color)] shadow-md rounded-2xl p-4 sm:p-6 border border-[var(--border-color)]">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-5">
            <h2 class="text-xl sm:text-2xl font-bold">Inventory Products</h2>
            <button id="openModalBtn" class="rounded-lg bg-green-600 text-white px-5 py-2 text-sm font-semibold shadow hover:bg-green-700 hover:scale-[1.02] transition-transform focus:ring-2 focus:ring-green-400">
                Add Inventory Items
            </button>
        </div>
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
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                                <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['status'] ?></td>
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
                        <tr class=" hover:bg-gray-50">
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                            <td class="py-1 px-2 sm:px-4  border border-[var(--border-color)]"><?= $item['status'] ?></td>
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
            // SweetAlert actions same as before
            document.querySelectorAll('.restock-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const itemId = this.dataset.id;
                    Swal.fire({
                        title: 'Restock Item ID: ' + itemId,
                        input: 'number',
                        inputLabel: 'Quantity to Add',
                        inputAttributes: {
                            min: 1
                        },
                        showCancelButton: true
                    }).then(result => {
                        if (result.isConfirmed) {
                            Swal.fire('Success!', 'Item restocked.', 'success');
                        }
                    });
                });
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

    <!-- ================== Modal ================== -->
    <div id="inventoryModal" class="fixed inset-0 bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md p-6 animate-[fadeIn_0.3s_ease]">
            <h2 class="text-lg sm:text-xl font-bold mb-4 text-center text-gray-800">Receive Inventory</h2>
            <form id="inventoryForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="item_name">Item Name</label>
                    <input type="text" id="item_name" required class="w-full p-2 border rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="inv_category">Inventory Category</label>
                    <select id="inv_category" required class="w-full p-2 border rounded-lg">
                        <option value="" disabled selected>Select inventory category</option>
                        <?php foreach ($invCategories as $cat): ?>
                            <option value="<?= $cat['inv_category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="prod_category">Product Category</label>
                    <select id="prod_category" class="w-full p-2 border rounded-lg">
                        <option value="" disabled selected>Select product category</option>
                        <?php foreach ($prodCategories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="product">Product</label>
                    <select id="product" class="w-full p-2 border rounded-lg">
                        <option value="" disabled selected>Select product</option>
                        <?php foreach ($products as $prod): ?>
                            <option value="<?= $prod['product_id'] ?>"><?= htmlspecialchars($prod['product_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="quantity">Quantity</label>
                        <input type="number" id="quantity" required min="1" class="w-full p-2 border rounded-lg" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="unit">Unit</label>
                        <select id="unit" class="w-full p-2 border rounded-lg">
                            <option value="pcs">Pieces (pcs)</option>
                            <option value="g">Grams (g)</option>
                            <option value="ml">Milliliters (ml)</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="date_made">Date Made</label>
                        <input type="date" id="date_made" required class="w-full p-2 border rounded-lg" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="date_expiry">Expiry Date</label>
                        <input type="date" id="date_expiry" required class="w-full p-2 border rounded-lg" />
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="closeModalBtn" class="bg-gray-200 px-4 py-2 rounded-lg">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

</section>