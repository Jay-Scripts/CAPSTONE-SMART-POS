<?php
try {
    // 
    //   ==========================================================================================================================================
    //   =                                                          Inventory categories                                                          =
    //   ==========================================================================================================================================
    $invCategories = $conn->query("SELECT inv_category_id, category_name FROM inventory_category ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    //   ==========================================================================================================================================
    //   =                                                          Product categories                                                          =
    //   ==========================================================================================================================================
    // 
    $prodCategories = $conn->query("SELECT category_id, category_name FROM category ORDER BY category_name ASC")->fetchAll(PDO::FETCH_ASSOC);

    //   ==========================================================================================================================================
    //   =                                                          Base Ingredients Products                                                     =
    //   ==========================================================================================================================================
    // 
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


        <!-- //   
         ==========================================================================================================================================
    //   =                                                          Ingredients Table Starts Here                                                 =
    //   ========================================================================================================================================== -->
        <?php foreach ($ingredientsByCat as $catName => $items): ?>
            <h2 class="text-xl sm:text-2xl font-bold mb-2"><?= htmlspecialchars($catName) ?></h2>

            <!-- Search bar per category -->
            <input type="text" class="search-ingredients mb-2 p-2 border border-[var(--border-color)] bg-[var(--background-color)] text-[var(--text-color)] rounded w-full" placeholder="Search <?= htmlspecialchars($catName) ?>...">

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

                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                                <td class="py-1 px-2 sm:px-4 text-center border border-[var(--border-color)]">
                                    <span class="px-2 py-1 rounded-lg text-xs font-semibold border <?= getStatusClass($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span>
                                </td>
                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['date_made'] ?></td>
                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                                <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['staff_name']) ?></td>
                                <td class="py-1 px-2 sm:px-4 space-x-1 flex flex-wrap border border-[var(--border-color)]">
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

        <!-- //   
         ==========================================================================================================================================
    //   =                                                          Ingredients Table Ends Here                                                   =
    //   ========================================================================================================================================== -->




        <!-- //   
         ==========================================================================================================================================
    //   =                                                          Materials Table Starts Here                                                   =
    //   ========================================================================================================================================== -->
        <h2 class="text-xl sm:text-2xl font-bold mb-2">Materials</h2>

        <!-- Search bar for Materials -->
        <input type="text" class="search-materials mb-2 p-2 border border-[var(--border-color)] bg-[var(--background-color)] text-[var(--text-color)] rounded w-full" placeholder="Search Materials...">

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
                    <?php foreach ($materials as $item): ?>
                        <tr
                            data-id="<?= $item['item_id'] ?>"
                            data-inv-category-id="<?= $item['inv_category_id'] ?>"
                            data-product-id="<?= $item['product_id'] ?? '' ?>"
                            data-category-id="<?= $item['category_id'] ?? '' ?>"
                            class="border-b border-gray-200 hover:bg-blue-400 hover:scale-[101%] hover:text-white">

                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['item_name']) ?></td>
                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['quantity'] ?></td>
                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['unit'] ?></td>
                            <td class="py-1 px-2 sm:px-4 text-center border border-[var(--border-color)]">
                                <span class="px-2 py-1 rounded-lg text-xs font-semibold border <?= getStatusClass($item['status']) ?>"><?= htmlspecialchars($item['status']) ?></span>
                            </td>
                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['date_made'] ?></td>
                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= $item['date_expiry'] ?></td>
                            <td class="py-1 px-2 sm:px-4 border border-[var(--border-color)]"><?= htmlspecialchars($item['staff_name']) ?></td>
                            <td class="py-1 px-2 sm:px-4 space-x-1 flex flex-wrap border border-[var(--border-color)]">
                                <button class="restock-btn bg-green-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Restock</button>
                                <button class="modify-btn bg-blue-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Modify</button>
                                <button class="remove-btn bg-red-500 text-white px-2 py-1 rounded text-xs sm:text-sm" data-id="<?= $item['item_id'] ?>">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <!-- //   
         ==========================================================================================================================================
    //   =                                                          Materials Table Ends Here                                                     =
    //   ========================================================================================================================================== -->
    </div>

    <?php
    //   ==========================================================================================================================================
    //   =                                                          Restock BTN Includes                                                          =
    //   ==========================================================================================================================================
    include "../../app/includes/managerModule/managerStockManagementRestockUI.php";
    //   ==========================================================================================================================================
    //   =                                                          Modify BTN Includes                                                           =
    //   ==========================================================================================================================================
    include "../../app/includes/managerModule/managerStockManagementModifyUI.php";
    //   ==========================================================================================================================================
    //   =                                                          Remove BTN Includes                                                           =
    //   ==========================================================================================================================================
    include "../../app/includes/managerModule/managerStockManagementRemoveUI.php";
    ?>



    </div>


</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // Ingredients search
        document.querySelectorAll('.search-ingredients').forEach(input => {
            const table = input.nextElementSibling.querySelector('table');
            input.addEventListener('input', () => {
                const filter = input.value.toLowerCase();
                table.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
                });
            });
        });

        // Materials search
        const matInput = document.querySelector('.search-materials');
        const matTable = matInput.nextElementSibling.querySelector('table');
        matInput.addEventListener('input', () => {
            const filter = matInput.value.toLowerCase();
            matTable.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(filter) ? '' : 'none';
            });
        });

    });
</script>