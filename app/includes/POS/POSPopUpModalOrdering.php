<?php

$addons_stmt = $conn->query("SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active'");
$addons = $addons_stmt->fetchAll(PDO::FETCH_ASSOC);

$mods_stmt = $conn->query("SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active'");
$modifications = $mods_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-5 relative">
        <button onclick="closeModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-lg font-bold">&times;</button>
        <div class="flex flex-col items-center">
            <img id="modalThumb" src="" alt="Product" class="w-32 h-32 rounded-lg object-cover mb-3">
            <h2 class="text-lg font-semibold text-gray-800" id="productName"></h2>
        </div>

        <!-- Size -->
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

        <!-- Modifications -->
        <!-- Ice Level -->
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Ice Level</h3>
            <div class="flex flex-wrap gap-2" id="iceLevel">
                <?php foreach ($modifications as $mod): ?>
                    <?php if ($mod['MODIFICATION_ID'] >= 1 && $mod['MODIFICATION_ID'] <= 2): ?>
                        <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                            <input type="checkbox" class="mod-checkbox accent-blue-500"
                                value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
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
                                value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
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
            Total: ₱<span id="subtotal">0</span>
        </div>

        <!-- Add to Order -->
        <div class="mt-5 flex justify-end">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow" onclick="addToOrder()">Add to Order</button>
        </div>
    </div>
</div>