<?php

$addons_stmt = $conn->query("SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active'");
$addons = $addons_stmt->fetchAll(PDO::FETCH_ASSOC);

$mods_stmt = $conn->query("SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active'");
$modifications = $mods_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center ">
    <div class="bg-white rounded-xl shadow-lg w-11/12 max-w-md p-5 relative animate-[fadeIn_0.3s_ease]">
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
        <div class="mt-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-1">Add-ons</h3>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($addons as $addon): ?>
                    <label class="relative flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-lg cursor-pointer 
                          hover:bg-green-50 transition transform hover:scale-105">
                        <!-- Hidden checkbox -->
                        <input type="checkbox" class="addon-checkbox peer absolute opacity-0 w-0 h-0"
                            data-id="<?= $addon['ADD_ONS_ID'] ?>"
                            data-price="<?= $addon['PRICE'] ?>">
                        <!-- Visible label content -->
                        <span class="text-gray-700 text-sm font-medium peer-checked:bg-green-100 peer-checked:border-green-400 
                             peer-checked:text-green-700 px-2 py-1 rounded-lg transition">
                            <?= htmlspecialchars($addon['ADD_ONS_NAME']) ?>
                            <span class="text-gray-500 text-xs">(+₱<?= number_format($addon['PRICE'], 2) ?>)</span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Modifications -->
        <!-- Ice Level -->
        <div class="mt-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Ice Level</h3>
            <div class="flex flex-wrap gap-2" id="iceLevel">
                <?php foreach ($modifications as $mod): ?>
                    <?php if ($mod['MODIFICATION_ID'] >= 1 && $mod['MODIFICATION_ID'] <= 2): ?>
                        <label class="relative flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-lg  cursor-pointer 
                              hover:bg-blue-50 transition transform hover:scale-105">
                            <!-- Hidden checkbox -->
                            <input type="checkbox" class="mod-checkbox peer absolute opacity-0 w-0 h-0"
                                value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                            <!-- Visible label content -->
                            <span class="text-gray-700 text-sm font-medium peer-checked:bg-blue-100 px-3 py-1 rounded-lg peer-checked:border-blue-400 
                                 peer-checked:text-blue-700 transition">
                                <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                            </span>
                        </label>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>


        <!-- Sugar Level -->
        <div class="mt-2">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Sugar Level</h3>
            <div class="flex flex-wrap gap-2" id="sugarLevel">
                <?php foreach ($modifications as $mod): ?>
                    <?php if ($mod['MODIFICATION_ID'] >= 3 && $mod['MODIFICATION_ID'] <= 6): ?>
                        <label class="relative flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-lg cursor-pointer 
                              hover:bg-red-50 transition transform hover:scale-105">
                            <!-- Hidden checkbox -->
                            <input type="checkbox" class="mod-checkbox peer absolute opacity-0 w-0 h-0"
                                value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                            <!-- Visible label content -->
                            <div class="text-gray-700 text-sm font-medium peer-checked:bg-red-100 px-3 py-1 rounded-lg peer-checked:border-red-400 
                                 peer-checked:text-red-700 transition">
                                <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                            </div>
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
        <div class="flex items-center justify-between mt-2">
            <span class="text-sm font-medium text-gray-700">Quantity:</span>
            <div class="flex items-center gap-3">
                <button id="decreaseQty"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded flex items-center justify-center">−</button>

                <input id="quantity" type="number" value="1" min="1" max="99"
                    class="w-16 text-center border border-gray-300 rounded py-1">

                <button id="increaseQty"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 w-8 h-8 rounded flex items-center justify-center">+</button>
            </div>
        </div>

        <script>
            const qty = document.getElementById("quantity");

            qty.addEventListener("input", () => {
                qty.value = qty.value.slice(0, 2);
            });
        </script>



        <!-- Total -->
        <div class="mt-2 text-right text-sm font-semibold text-gray-800">
            Total: ₱<span id="subtotal">0</span>
        </div>

        <!-- Add to Order -->
        <div class="mt-4 flex justify-end">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow" onclick="addToOrder()">Add to Order</button>
        </div>
    </div>
</div>