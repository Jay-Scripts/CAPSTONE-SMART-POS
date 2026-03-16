<section class="flex justify-center items-center h-screen">
    <div class="w-full h-screen p-4 flex flex-col gap-0">

        <!-- ── PAGE HEADER ── -->
        <div class="flex items-center justify-between mb-4">
            <div>

            </div>
            <!-- Save All banner (shows when unsaved changes exist) -->
            <div id="saveAllBanner" class="hidden items-center gap-2">
                <span class="text-xs text-amber-500 font-semibold animate-pulse">Unsaved changes</span>
                <button onclick="saveAllPrices()"
                    class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 active:scale-95
                               text-white text-sm font-bold flex items-center gap-2 transition-all duration-200 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 -960 960 960" fill="currentColor">
                        <path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z" />
                    </svg>
                    Save All
                </button>
            </div>
        </div>

        <div
            id="menuContainer"
            class="border border-[var(--container-border)] rounded-2xl overflow-hidden bg-[var(--background-color)] shadow-lg flex flex-col flex-1 min-h-0">

            <!-- ── CATEGORY NAV ── -->
            <div class="px-3 pt-3 pb-2 border-b border-[var(--container-border)]">
                <fieldset id="orderCategory" class="flex flex-wrap gap-2" aria-label="Order Categories">

                    <?php
                    $cats = [
                        ['id' => 'milktea',    'label' => 'Milk Tea',    'svg' => '<path d="M14 2l-4 2"/><path d="M12 2v3"/><path d="M5 7h14"/><path d="M6 7l1.2 11.2A2 2 0 0 0 9.19 20h5.62a2 2 0 0 0 1.99-1.8L18 7"/><path d="M7 12h10"/><circle cx="9" cy="16.5" r="1" fill="currentColor" stroke="none"/><circle cx="12" cy="17.5" r="1" fill="currentColor" stroke="none"/><circle cx="15" cy="16.5" r="1" fill="currentColor" stroke="none"/>'],
                        ['id' => 'fruittea',   'label' => 'Fruit Tea',   'svg' => '<path d="M6 7h12l-1 11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2L6 7z"/><path d="M5 7h14"/><path d="M12 2v5"/><path d="M7 12h10"/><circle cx="16.5" cy="15.5" r="2"/><path d="M16.5 13.5v4"/><path d="M14.5 15.5h4"/>'],
                        ['id' => 'hotbrew',    'label' => 'Hot Brew',    'svg' => '<path d="M4 8h12v8a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z"/><path d="M16 10h1a3 3 0 0 1 0 6h-1"/><path d="M9 2v3"/><path d="M13 2v3"/>'],
                        ['id' => 'icedcoffee', 'label' => 'Iced Coffee', 'svg' => '<path d="M7 7h10l-1.2 11.2A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z"/><path d="M6 7h12"/><path d="M12 2v5"/><rect x="9" y="11" width="2.5" height="2.5"/><rect x="12.5" y="14" width="2.5" height="2.5"/>'],
                        ['id' => 'praf',       'label' => 'Praf',        'svg' => '<path d="M6 9h12l-1.2 9.2A2 2 0 0 1 14.8 20H9.2a2 2 0 0 1-2-1.8L6 9z"/><path d="M6 9c0-3 3-5 6-5s6 2 6 5"/><path d="M12 4V2"/><path d="M9 9c.5-1 1.5-1.5 3-1.5s2.5.5 3 1.5"/>'],
                        ['id' => 'promos',     'label' => 'Promos',      'svg' => '<path d="M7 7h10l-1.2 10.5A2 2 0 0 1 13.8 20H10.2a2 2 0 0 1-2-1.8L7 7z"/><path d="M6 7h12"/><path d="M12 2v5"/><polygon points="16 10 17 12 19 12 17.5 13.5 18 15.5 16 14.5 14 15.5 14.5 13.5 13 12 15 12 16 10"/>'],
                        ['id' => 'brosty',     'label' => 'Brosty',      'svg' => '<path d="M7 10h10l-1.5 8.5a2 2 0 01-2 1.5h-3a2 2 0 01-2-1.5L7 10z"/><path d="M7.5 10a3.5 3.5 0 013-2 3.5 3.5 0 013 2 3.5 3.5 0 013-2 3.5 3.5 0 013 2"/><path d="M15 5l2 4"/>'],
                    ];
                    $first = true;
                    foreach ($cats as $cat):
                    ?>
                        <div class="categoryButtons">
                            <input type="radio" id="<?= $cat['id'] ?>_module" name="module"
                                class="hidden peer" <?= $first ? 'checked' : '' ?>
                                onclick="showModuleDisableProduct('<?= $cat['id'] ?>')" />
                            <label for="<?= $cat['id'] ?>_module"
                                class="group flex items-center gap-2 px-3 py-2 rounded-xl border border-[var(--container-border)]
                                       bg-[var(--background-color)] text-[var(--text-color)] cursor-pointer select-none
                                       transition-all duration-200
                                       peer-checked:bg-[var(--text-color)] peer-checked:text-[var(--background-color)]
                                       peer-checked:border-[var(--text-color)] peer-checked:shadow-md
                                       hover:border-[var(--text-color)] active:scale-95">
                                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <?= $cat['svg'] ?>
                                </svg>
                                <span class="text-xs font-semibold tracking-wide whitespace-nowrap"><?= $cat['label'] ?></span>
                            </label>
                        </div>
                    <?php $first = false;
                    endforeach; ?>

                </fieldset>
            </div>
            <!-- ── END CATEGORY NAV ── -->

            <!-- ── SCROLLABLE PRODUCT AREA ── -->
            <div class="flex-1 overflow-y-auto px-4 py-4">

                <?php
                $sectionMap = [
                    'milktea'    => ['title' => 'Milk Tea',    'cat_id' => 1],
                    'fruittea'   => ['title' => 'Fruit Tea',   'cat_id' => 2],
                    'hotbrew'    => ['title' => 'Hot Brew',    'cat_id' => 3],
                    'praf'       => ['title' => 'Praf',        'cat_id' => 4],
                    'icedcoffee' => ['title' => 'Iced Coffee', 'cat_id' => 6],
                    'promos'     => ['title' => 'Promos',      'cat_id' => 7],
                    'brosty'     => ['title' => 'Brosty',      'cat_id' => 5],
                ];

                foreach ($sectionMap as $secId => $info):
                    // Fetch all products + sizes for this category
                    $stmt = $conn->prepare("
                            SELECT
                                p.product_id,
                                p.product_name,
                                p.status        AS product_status,
                                ps.size_id,
                                ps.size,
                                ps.regular_price,
                                ps.promo_price,
                                ps.status       AS size_status
                            FROM product_details p
                            LEFT JOIN product_sizes ps ON p.product_id = ps.product_id
                            WHERE p.category_id = :cat_id
                            ORDER BY p.product_name, FIELD(ps.size,'medio','grande','hot brew','promo')
                        ");
                    $stmt->execute([':cat_id' => $info['cat_id']]);
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Group sizes under each product
                    $products = [];
                    foreach ($rows as $row) {
                        $pid = $row['product_id'];
                        if (!isset($products[$pid])) {
                            $products[$pid] = [
                                'product_id'     => $pid,
                                'product_name'   => $row['product_name'],
                                'product_status' => $row['product_status'],
                                'sizes'          => [],
                            ];
                        }
                        if ($row['size_id']) {
                            $products[$pid]['sizes'][] = [
                                'size_id'       => $row['size_id'],
                                'size'          => $row['size'],
                                'regular_price' => $row['regular_price'],
                                'promo_price'   => $row['promo_price'],
                                'size_status'   => $row['size_status'],
                            ];
                        }
                    }
                ?>

                    <!-- Section -->
                    <section id="<?= $secId ?>" class="<?= ($secId === 'milktea') ? '' : 'hidden' ?>">

                        <!-- Section title -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-px flex-1 bg-[var(--container-border)]"></div>
                            <h1 class="text-xs font-bold tracking-widest uppercase text-[var(--text-color)] opacity-50">
                                <?= $info['title'] ?>
                            </h1>
                            <div class="h-px flex-1 bg-[var(--container-border)]"></div>
                        </div>

                        <?php if (empty($products)): ?>
                            <p class="text-center text-sm opacity-40 py-8 text-[var(--text-color)]">No products found.</p>
                        <?php else: ?>

                            <!-- Product cards grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                <?php foreach ($products as $prod): ?>
                                    <div class="rounded-2xl border border-[var(--container-border)] bg-[var(--background-color)]
                                        p-4 flex flex-col gap-3 shadow-sm hover:shadow-md transition-shadow duration-200
                                        <?= $prod['product_status'] === 'inactive' ? 'opacity-50' : '' ?>">

                                        <!-- Product name + status badge -->
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="text-sm font-bold text-[var(--text-color)] leading-tight">
                                                <?= htmlspecialchars($prod['product_name']) ?>
                                            </h3>
                                            <span class="shrink-0 text-[10px] font-semibold px-2 py-0.5 rounded-full
                                        <?= $prod['product_status'] === 'active'
                                            ? 'bg-green-500/15 text-green-500'
                                            : 'bg-red-500/15 text-red-400' ?>">
                                                <?= ucfirst($prod['product_status']) ?>
                                            </span>
                                        </div>

                                        <!-- Size price rows -->
                                        <?php if (empty($prod['sizes'])): ?>
                                            <p class="text-xs opacity-40 text-[var(--text-color)]">No sizes configured.</p>
                                        <?php else: ?>
                                            <div class="flex flex-col gap-2">
                                                <?php foreach ($prod['sizes'] as $sz): ?>
                                                    <div class="rounded-xl border border-[var(--container-border)] bg-[var(--calc-bg-btn)] px-3 py-2
                                                <?= $sz['size_status'] === 'inactive' ? 'opacity-40' : '' ?>">

                                                        <!-- Size label -->
                                                        <div class="flex items-center justify-between mb-1.5">
                                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[var(--text-color)] opacity-60">
                                                                <?= htmlspecialchars($sz['size']) ?>
                                                            </span>
                                                            <span class="text-[9px] px-1.5 py-0.5 rounded-full
                                                <?= $sz['size_status'] === 'active'
                                                        ? 'bg-green-500/10 text-green-500'
                                                        : 'bg-red-500/10 text-red-400' ?>">
                                                                <?= ucfirst($sz['size_status']) ?>
                                                            </span>
                                                        </div>

                                                        <!-- Regular + Promo price inputs -->
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div class="flex flex-col gap-0.5">
                                                                <label class="text-[9px] opacity-50 text-[var(--text-color)] uppercase tracking-wide">Regular</label>
                                                                <div class="relative">
                                                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-50 text-xs">₱</span>
                                                                    <input
                                                                        type="number"
                                                                        min="0" step="0.01"
                                                                        class="price-input w-full pl-5 pr-2 py-1.5 text-xs rounded-lg
                                                               border border-[var(--container-border)]
                                                               bg-[var(--background-color)] text-[var(--text-color)]
                                                               focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
                                                                        value="<?= number_format((float)$sz['regular_price'], 2, '.', '') ?>"
                                                                        data-size-id="<?= $sz['size_id'] ?>"
                                                                        data-field="regular_price"
                                                                        onchange="markUnsaved(this)" />
                                                                </div>
                                                            </div>
                                                            <div class="flex flex-col gap-0.5">
                                                                <label class="text-[9px] opacity-50 text-[var(--text-color)] uppercase tracking-wide">Promo</label>
                                                                <div class="relative">
                                                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-[var(--text-color)] opacity-50 text-xs">₱</span>
                                                                    <input
                                                                        type="number"
                                                                        min="0" step="0.01"
                                                                        class="price-input w-full pl-5 pr-2 py-1.5 text-xs rounded-lg
                                                               border border-[var(--container-border)]
                                                               bg-[var(--background-color)] text-[var(--text-color)]
                                                               focus:outline-none focus:ring-2 focus:ring-amber-400 transition"
                                                                        value="<?= number_format((float)$sz['promo_price'], 2, '.', '') ?>"
                                                                        data-size-id="<?= $sz['size_id'] ?>"
                                                                        data-field="promo_price"
                                                                        onchange="markUnsaved(this)" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Per-card Save button -->
                                        <button
                                            onclick="saveProductPrices(this, <?= $prod['product_id'] ?>)"
                                            class="save-btn mt-1 w-full py-2 rounded-xl border border-[var(--container-border)]
                                           text-[var(--text-color)] text-xs font-semibold
                                           hover:bg-[var(--text-color)] hover:text-[var(--background-color)]
                                           active:scale-95 transition-all duration-200 flex items-center justify-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 -960 960 960" fill="currentColor">
                                                <path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z" />
                                            </svg>
                                            Save
                                        </button>

                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php endif; ?>
                    </section>

                <?php endforeach; ?>

                <!-- Required by managerDisableProduct.js — must exist in DOM -->
                <section id="modify" class="hidden"></section>
                <section id="addOns" class="hidden"></section>

            </div>
            <!-- ── END SCROLLABLE PRODUCT AREA ── -->

        </div>
    </div>
</section>

<script>
    /* ── track which inputs changed ── */
    let _unsavedInputs = new Set();

    function markUnsaved(input) {
        input.classList.add('ring-2', 'ring-amber-400', 'border-amber-400');
        _unsavedInputs.add(input);
        document.getElementById('saveAllBanner').classList.remove('hidden');
        document.getElementById('saveAllBanner').classList.add('flex');
    }

    function clearUnsaved(inputs) {
        inputs.forEach(inp => {
            inp.classList.remove('ring-2', 'ring-amber-400', 'border-amber-400');
            _unsavedInputs.delete(inp);
        });
        if (_unsavedInputs.size === 0) {
            document.getElementById('saveAllBanner').classList.add('hidden');
            document.getElementById('saveAllBanner').classList.remove('flex');
        }
    }

    /* ── collect sizes from a product card ── */
    function collectSizes(productId) {
        const inputs = document.querySelectorAll(`.price-input[data-size-id]`);
        const sizeMap = {};

        // Only collect inputs belonging to this product's card
        const card = document.querySelector(`[data-product-id="${productId}"]`);
        const cardInputs = card ?
            card.querySelectorAll('.price-input') :
            document.querySelectorAll(`.price-input[data-product-id="${productId}"]`);

        cardInputs.forEach(inp => {
            const sid = inp.dataset.sizeId;
            const field = inp.dataset.field;
            if (!sizeMap[sid]) sizeMap[sid] = {};
            sizeMap[sid][field] = inp.value;
        });

        return {
            sizeMap,
            cardInputs: Array.from(cardInputs)
        };
    }

    /* ── save single product ── */
    async function saveProductPrices(btn, productId) {
        // Gather all price inputs inside this card
        const card = btn.closest('.rounded-2xl');
        const inputs = card.querySelectorAll('.price-input');
        const sizeMap = {};

        inputs.forEach(inp => {
            const sid = inp.dataset.sizeId;
            const field = inp.dataset.field;
            if (!sizeMap[sid]) sizeMap[sid] = {};
            sizeMap[sid][field] = inp.value;
        });

        const sizes = Object.entries(sizeMap).map(([size_id, fields]) => ({
            size_id,
            regular_price: fields.regular_price ?? 0,
            promo_price: fields.promo_price ?? 0,
        }));

        btn.disabled = true;
        btn.innerHTML = `<svg class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Saving…`;

        try {
            const res = await fetch('../../app/includes/managerModule/updateProductPrices.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sizes
                }),
            });
            const data = await res.json();

            if (data.success) {
                clearUnsaved(Array.from(inputs));
                btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 -960 960 960" fill="currentColor"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg> Saved`;
                btn.classList.add('bg-green-500/10', 'text-green-500', 'border-green-400');
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 -960 960 960" fill="currentColor"><path d="M840-680v480q0 33-23.5 56.5T760-120H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h480l160 160Zm-80 34L646-760H200v560h560v-446ZM480-240q50 0 85-35t35-85q0-50-35-85t-85-35q-50 0-85 35t-35 85q0 50 35 85t85 35ZM240-560h360v-160H240v160Zm-40-86v446-560 114Z"/></svg> Save`;
                    btn.classList.remove('bg-green-500/10', 'text-green-500', 'border-green-400');
                }, 2000);
            } else {
                throw new Error(data.message || 'Save failed');
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = `Save`;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message
            });
        }
    }

    /* ── save ALL unsaved inputs across all categories ── */
    async function saveAllPrices() {
        if (_unsavedInputs.size === 0) return;

        const sizeMap = {};
        _unsavedInputs.forEach(inp => {
            const sid = inp.dataset.sizeId;
            const field = inp.dataset.field;
            if (!sizeMap[sid]) sizeMap[sid] = {};
            sizeMap[sid][field] = inp.value;
        });

        // For fields not in unsaved set, get their current values
        document.querySelectorAll('.price-input').forEach(inp => {
            const sid = inp.dataset.sizeId;
            const field = inp.dataset.field;
            if (sizeMap[sid] && !sizeMap[sid][field]) {
                sizeMap[sid][field] = inp.value;
            }
        });

        const sizes = Object.entries(sizeMap).map(([size_id, fields]) => ({
            size_id,
            regular_price: fields.regular_price ?? 0,
            promo_price: fields.promo_price ?? 0,
        }));

        try {
            const res = await fetch('../../app/includes/managerModule/updateProductPrices.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sizes
                }),
            });
            const data = await res.json();

            if (data.success) {
                clearUnsaved(Array.from(_unsavedInputs));
                Swal.fire({
                    icon: 'success',
                    title: 'All prices saved!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Save failed');
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: err.message
            });
        }
    }
</script>