<?php

$addons_stmt = $conn->query("SELECT ADD_ONS_ID, ADD_ONS_NAME, PRICE FROM PRODUCT_ADD_ONS WHERE status='active'");
$addons = $addons_stmt->fetchAll(PDO::FETCH_ASSOC);

$mods_stmt = $conn->query("SELECT MODIFICATION_ID, MODIFICATION_NAME FROM PRODUCT_MODIFICATIONS WHERE status='active'");
$modifications = $mods_stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- Product Order Modal -->
<div id="productModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-[var(--background-color)] text-[var(--text-color)] border border-[var(--container-border)]
                rounded-2xl shadow-2xl w-11/12 max-w-md mx-4 animate-[fadeIn_0.25s_ease] flex flex-col max-h-[90vh]">

        <!-- ── HEADER ── -->
        <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-[var(--container-border)] shrink-0">
            <div class="flex items-center gap-3">
                <img id="modalThumb" src="" alt="Product"
                    class="w-12 h-12 rounded-xl object-cover border border-[var(--container-border)]">
                <h2 class="text-base font-bold text-[var(--text-color)] leading-tight" id="productName"></h2>
            </div>
            <button onclick="closeModal()"
                class="w-8 h-8 flex items-center justify-center rounded-full border border-[var(--container-border)]
                       text-[var(--text-color)] hover:bg-red-500 hover:text-white hover:border-red-500
                       transition-all duration-200 text-lg leading-none shrink-0">
                &times;
            </button>
        </div>

        <!-- ── SCROLLABLE BODY ── -->
        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4"
            style="scrollbar-width: thin; scrollbar-color: var(--container-border) transparent;">

            <!-- Size -->
            <div id="sizeOptions">
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)] mb-2">Select Size</p>
                <div id="sizesContainer" class="flex flex-col gap-2"></div>
            </div>

            <!-- Add-ons -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)] mb-2">Add-ons</p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($addons as $addon): ?>
                        <label class="relative cursor-pointer select-none">
                            <input type="checkbox" class="addon-checkbox peer absolute opacity-0 w-0 h-0"
                                data-id="<?= $addon['ADD_ONS_ID'] ?>"
                                data-price="<?= $addon['PRICE'] ?>">
                            <span class="flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl
                                     border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                                     text-[var(--text-color)] transition-all duration-150 active:scale-95
                                     peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-500">
                                <?= htmlspecialchars($addon['ADD_ONS_NAME']) ?>
                                <span class="opacity-70">+₱<?= number_format($addon['PRICE'], 2) ?></span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Ice Level -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)] mb-2">Ice Level</p>
                <div class="flex flex-wrap gap-2" id="iceLevel">
                    <?php foreach ($modifications as $mod): ?>
                        <?php if ($mod['MODIFICATION_ID'] >= 1 && $mod['MODIFICATION_ID'] <= 2): ?>
                            <label class="relative cursor-pointer select-none">
                                <input type="checkbox" class="mod-checkbox peer absolute opacity-0 w-0 h-0"
                                    value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                                <span class="flex items-center px-3 py-1.5 text-xs font-semibold rounded-xl
                                     border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                                     text-[var(--text-color)] transition-all duration-150 active:scale-95
                                     peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-500">
                                    <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                                </span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sugar Level -->
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)] mb-2">Sugar Level</p>
                <div class="flex flex-wrap gap-2" id="sugarLevel">
                    <?php foreach ($modifications as $mod): ?>
                        <?php if ($mod['MODIFICATION_ID'] >= 3 && $mod['MODIFICATION_ID'] <= 6): ?>
                            <label class="relative cursor-pointer select-none">
                                <input type="checkbox" class="mod-checkbox peer absolute opacity-0 w-0 h-0"
                                    value="<?= htmlspecialchars($mod['MODIFICATION_ID']) ?>">
                                <span class="flex items-center px-3 py-1.5 text-xs font-semibold rounded-xl
                                     border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                                     text-[var(--text-color)] transition-all duration-150 active:scale-95
                                     peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500">
                                    <?= htmlspecialchars($mod['MODIFICATION_NAME']) ?>
                                </span>
                            </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <script>
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

        </div>
        <!-- ── END SCROLLABLE BODY ── -->

        <!-- ── FOOTER ── -->
        <div class="px-5 py-4 border-t border-[var(--container-border)] shrink-0 space-y-3">

            <!-- Quantity + Total -->
            <div class="flex items-center justify-between">

                <!-- Qty stepper -->
                <div class="flex items-center gap-1">
                    <span class="text-xs font-semibold uppercase tracking-wide opacity-50 text-[var(--text-color)] mr-2">Qty</span>
                    <button id="decreaseQty"
                        class="w-8 h-8 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                               text-[var(--text-color)] font-bold text-lg flex items-center justify-center
                               hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                               active:scale-90 transition-all duration-150">
                        −
                    </button>
                    <input id="quantity" type="number" value="1" min="1" readonly
                        class="w-10 h-8 text-center text-sm font-bold bg-transparent text-[var(--text-color)]
                               border border-[var(--container-border)] rounded-xl focus:outline-none" />
                    <button id="increaseQty"
                        class="w-8 h-8 rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)]
                               text-[var(--text-color)] font-bold text-lg flex items-center justify-center
                               hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                               active:scale-90 transition-all duration-150">
                        +
                    </button>
                </div>

                <!-- Total -->
                <div class="text-right">
                    <p class="text-xs opacity-50 text-[var(--text-color)] uppercase tracking-wide">Total</p>
                    <p class="text-xl font-bold text-[var(--text-color)]">₱<span id="subtotal">0</span></p>
                </div>
            </div>

            <!-- Add to Order -->
            <button onclick="addToOrder()"
                class="w-full py-3 rounded-2xl bg-green-600 hover:bg-green-500 active:scale-[0.98]
                       text-white font-bold text-sm tracking-wide flex items-center justify-center gap-2
                       transition-all duration-200 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 -960 960 960" fill="currentColor">
                    <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z" />
                </svg>
                Add to Order
            </button>

        </div>
        <!-- ── END FOOTER ── -->

    </div>
</div>