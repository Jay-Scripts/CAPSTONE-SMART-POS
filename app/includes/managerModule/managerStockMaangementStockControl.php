<!-- Include jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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

        <!-- ================== Materials Table ================== -->
        <section class="bg-[var(--background-color)] text-[var(--text-color)] border-2 m-5 border-[var(--border-color)] rounded-lg shadow-xl mb-6 p-2">
            <header class="shadow-sm border-b border-[var(--border-color)] m-2 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Materials </h2>
                    <p class="text-sm">Total Items: <?= count($materialsItems) ?></p>
                </div>
            </header>
            <div class="p-3">
                <table class="materialsTable categoryTable w-auto text-sm text-center border-2  border[var(--border-color)]">
                    <thead>
                        <tr class="border-2 border[var(--border-color)]">
                            <th class="py-2 px-3 border border[var(--border-color)]">Item</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Quantity</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Unit</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Status</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Mfg Date</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Exp Date</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Added By</th>
                            <th class="py-2 px-3 border border[var(--border-color)]">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materialsItems as $item): ?>
                            <tr class="border-b border[var(--border-color)] hover:bg-blue-400/10 hover:scale-[101%] transition">
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['quantity'] ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['unit'] ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)] <?= $item['status'] === 'OUT OF STOCK' ? 'text-red-500' : ($item['status'] === 'LOW STOCK' ? 'text-yellow-500' : 'text-green-500') ?>">
                                    <?= $item['status'] ?>
                                </td>
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['date_made'] ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)]"><?= htmlspecialchars($item['added_by'] ?? 'Unknown') ?></td>
                                <td class="py-2 px-3 border border[var(--border-color)] space-x-2">
                                    <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">Restock</button>
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Modify</button>
                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ================== Other Categories ================== -->
        <?php foreach ($grouped as $catId => $category): ?>
            <section class="bg-[var(--background-color)] text-[var(--text-color)] border-2 m-5 border-[var(--border-color)] rounded-lg shadow-xl mb-6 p-2">
                <header class="shadow-sm border-b border-[var(--border-color)] m-2 px-6 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold"><?= htmlspecialchars($category['category_name']) ?></h2>
                        <p class="text-sm">Total Items Category: <?= count($category['items'] ?? []) ?></p>
                    </div>
                </header>
                <div>
                    <?php if (!empty($category['items'][0]['item_id'])): ?>
                        <div class="p-3">
                            <table class="materialsTable categoryTable w-auto text-sm text-center border-2  border[var(--border-color)]">

                                <thead>
                                    <tr class="border-2 border[var(--border-color)]">
                                        <th class="py-2 px-3 border border[var(--border-color)]">Item</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Quantity</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Unit</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Status</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Mfg Date</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Exp Date</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Added By</th>
                                        <th class="py-2 px-3 border border[var(--border-color)]">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($category['items'] as $item): ?>
                                        <tr class="border-b border[var(--border-color)] hover:bg-blue-400/10 hover:scale-[101%] transition">
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['quantity'] ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['unit'] ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)] <?= $item['status'] === 'OUT OF STOCK' ? 'text-red-500' : ($item['status'] === 'LOW STOCK' ? 'text-yellow-500' : 'text-green-500') ?>">
                                                <?= $item['status'] ?>
                                            </td>
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['date_made'] ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)]"><?= htmlspecialchars($item['added_by'] ?? 'Unknown') ?></td>
                                            <td class="py-2 px-3 border border[var(--border-color)] space-x-2">
                                                <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">Restock</button>
                                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Modify</button>
                                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-400 text-sm italic">No inventory items linked to this category yet.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endforeach; ?>

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

<!-- ================== DataTables Initialization ================== -->
<script>
    $(document).ready(function() {
        $('.categoryTable').each(function() {
            if (!$.fn.dataTable.isDataTable(this)) {
                $(this).DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [5, 10, 25, 50],
                    columnDefs: [{
                        orderable: false,
                        targets: 7
                    }],
                    language: {
                        search: "Search items:",
                        lengthMenu: "Show _MENU_ items per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ items"
                    }
                });
            }
        });
    });

    function updateMaterialsTable() {
        $.ajax({
            url: '../../app/includes/managerModule/managerStockManagementFetchInvItems.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const tbody = $('.materialsTable tbody');
                tbody.empty(); // clear current table

                response.materials.forEach(item => {
                    const row = `
                    <tr class="border-b border[var(--border-color)] hover:bg-blue-400/10 hover:scale-[101%] transition">
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.item_name}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.quantity}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.unit}</td>
                        <td class="py-2 px-3 border border[var(--border-color)] ${item.status === 'OUT OF STOCK' ? 'text-red-500' : item.status === 'LOW STOCK' ? 'text-yellow-500' : 'text-green-500'}">${item.status}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.date_made}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.date_expiry}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">${item.added_by || 'Unknown'}</td>
                        <td class="py-2 px-3 border border[var(--border-color)]">
                            <button class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600">Restock</button>
                            <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Modify</button>
                            <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                        </td>
                    </tr>
                `;
                    tbody.append(row);
                });
            }
        });
    }

    // Initial load
    updateMaterialsTable();

    // Refresh every 1 second
    setInterval(updateMaterialsTable, 1000);
</script>