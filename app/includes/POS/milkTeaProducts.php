<?php
include "POSAddonsAndMods.php";

$sql = "
    SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size, ps.regular_price
    FROM product_details pd
    JOIN product_sizes ps ON pd.product_id = ps.product_id
    WHERE pd.category_id = 1
      AND pd.status = 'active'
    ORDER BY pd.product_name ASC
";
$stmt = $conn->prepare($sql);
$stmt->execute();

$products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['product_id'];
    $products[$id]['name'] = $row['product_name'];
    $products[$id]['thumbnail'] = $row['thumbnail_path'];
    $products[$id]['sizes'][$row['size']] = $row['regular_price'];
}
?>

<!-- Product List -->
<section class="flex flex-wrap justify-center gap-2">
    <?php foreach ($products as $id => $product): ?>
        <div class="optionChoice cursor-pointer aspect-square w-[47%] sm:w-[15%] bg-transparent rounded-lg border border-gray-400 p-2"
            data-id="<?= htmlspecialchars($id) ?>"
            data-name="<?= htmlspecialchars($product['name']) ?>"
            data-thumb="<?= htmlspecialchars($product['thumbnail']) ?>"
            data-sizes='<?= json_encode($product['sizes']) ?>'>
            <img src="<?= htmlspecialchars($product['thumbnail']) ?>" class="object-cover" />
            <p class="text-center text-xs font-bold"><?= htmlspecialchars($product['name']) ?></p>
        </div>
    <?php endforeach; ?>
</section>

<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-5 relative">
        <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>

        <div class="flex flex-col items-center">
            <img id="modalThumb" src="" alt="Product" class="w-32 h-32 rounded-lg object-cover mb-3">
            <h2 class="text-lg font-semibold text-gray-800" id="productName"></h2>
        </div>

        <div id="sizeOptions" class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Select Size</h3>
            <div id="sizesContainer" class="flex flex-col gap-2"></div>
        </div>

        <!-- Add-ons -->
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Add-ons</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($addons as $addon): ?>
                    <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                        <input type="checkbox" class="addon-checkbox accent-green-500"
                            data-id="<?= $addon['ADD_ONS_ID'] ?>"
                            data-price="<?= $addon['PRICE'] ?>">
                        <?= htmlspecialchars($addon['ADD_ONS_NAME']) ?> (+₱<?= number_format($addon['PRICE'], 2) ?>)
                    </label>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Ice Level -->
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Ice Level</h3>
            <div class="flex flex-wrap gap-2" id="iceLevel">
                <?php foreach ($modifications as $mod): ?>
                    <?php if ($mod['MODIFICATION_ID'] >= 1 && $mod['MODIFICATION_ID'] <= 2): ?>
                        <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                            <input type="checkbox" class="mod-checkbox accent-blue-500"
                                value="<?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>">
                            <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sugar Level -->
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Sugar Level</h3>
            <div class="flex flex-wrap gap-2" id="sugarLevel">
                <?php foreach ($modifications as $mod): ?>
                    <?php if ($mod['MODIFICATION_ID'] >= 3 && $mod['MODIFICATION_ID'] <= 6): ?>
                        <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                            <input type="checkbox" class="mod-checkbox accent-red-500"
                                value="<?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>">
                            <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            // Make checkboxes behave like radio buttons
            function singleCheck(containerId) {
                const container = document.getElementById(containerId);
                container.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                    cb.addEventListener('change', () => {
                        if (cb.checked) {
                            container.querySelectorAll('input[type="checkbox"]').forEach(other => {
                                if (other !== cb) other.checked = false;
                            });
                        }
                    });
                });
            }

            singleCheck('iceLevel');
            singleCheck('sugarLevel');
        </script>




        <!-- Quantity -->
        <div class="flex items-center justify-between mt-4">
            <span class="text-sm font-medium text-gray-700">Quantity:</span>
            <div class="flex items-center space-x-2">
                <button id="decreaseQty" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 rounded">−</button>
                <input id="quantity" type="number" value="1" min="1" class="w-12 text-center border rounded" readonly>
                <button id="increaseQty" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 rounded">+</button>
            </div>
        </div>

        <!-- Total -->
        <div class="mt-4 text-right text-sm font-semibold text-gray-800">
            Total: ₱<span id="totalPrice">0</span>
        </div>

        <!-- Add to Order -->
        <div class="mt-5 flex justify-end">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow" onclick="addToOrder()">Add to Order</button>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>