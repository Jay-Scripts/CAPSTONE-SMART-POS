<?php
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
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Cheese Cake (+₱10)
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Pearl (+₱10)
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Cream Cheese (+₱10)
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Coffee Jelly (+₱10)
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Crushed Oreo (+₱10)
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="addon-checkbox accent-green-500" data-price="10"> Chia Seed (+₱10)
                </label>
            </div>
        </div>

        <!-- Modifications -->
        <div class="mt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Modifications</h3>
            <div class="flex flex-wrap gap-2">
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="Less Ice"> Less Ice
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="More Ice"> More Ice
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="25% Sugar"> 25% Sugar
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="50% Sugar"> 50% Sugar
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="75% Sugar"> 75% Sugar
                </label>
                <label class="bg-gray-100 hover:bg-gray-200 rounded-lg px-3 py-1 cursor-pointer">
                    <input type="checkbox" class="mod-checkbox accent-orange-500" value="100% Sugar"> 100% Sugar
                </label>
            </div>
        </div>

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('productModal');
        const totalPrice = document.getElementById('totalPrice');
        const qtyInput = document.getElementById('quantity');
        const increaseBtn = document.getElementById('increaseQty');
        const decreaseBtn = document.getElementById('decreaseQty');
        const sizesContainer = document.getElementById('sizesContainer');
        const modalThumb = document.getElementById('modalThumb');
        const modalName = document.getElementById('productName');
        const productList = document.getElementById('productList');

        let basePrice = 0;
        let selectedProduct = "";
        let selectedSize = "";

        loadOrders();

        document.querySelectorAll('.optionChoice').forEach(choice => {
            choice.addEventListener('click', () => {
                selectedProduct = choice.dataset.name;
                const thumb = choice.dataset.thumb;
                const sizes = JSON.parse(choice.dataset.sizes);

                modalName.textContent = selectedProduct;
                modalThumb.src = thumb;
                sizesContainer.innerHTML = '';

                Object.entries(sizes).forEach(([size, price]) => {
                    const option = document.createElement('label');
                    option.className = "flex items-center justify-between bg-gray-100 rounded-lg p-2 cursor-pointer hover:bg-gray-200";
                    option.innerHTML = `
                    <div class="flex items-center gap-2">
                        <input type="radio" name="size" value="${price}" data-size="${size}" class="size-radio accent-blue-500">
                        <span>${size}</span>
                    </div>
                    <span>₱${price}</span>
                `;
                    sizesContainer.appendChild(option);
                });

                openModal();
                attachListeners();
            });
        });

        function openModal() {
            document.querySelectorAll('.addon-checkbox, .mod-checkbox').forEach(cb => cb.checked = false);
            qtyInput.value = 1;
            totalPrice.textContent = 0;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.querySelectorAll('.addon-checkbox, .mod-checkbox').forEach(cb => cb.checked = false);
            qtyInput.value = 1;
            totalPrice.textContent = 0;
            modal.classList.add('hidden');
        }

        function updateTotal() {
            const qty = parseInt(qtyInput.value);
            let addonsTotal = 0;
            document.querySelectorAll('.addon-checkbox:checked').forEach(el => {
                addonsTotal += parseInt(el.dataset.price);
            });
            totalPrice.textContent = (basePrice + addonsTotal) * qty;
        }

        function attachListeners() {
            document.querySelectorAll('.size-radio').forEach(radio => {
                radio.addEventListener('change', e => {
                    basePrice = parseInt(e.target.value);
                    selectedSize = e.target.dataset.size;
                    updateTotal();
                });
            });
            document.querySelectorAll('.addon-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));
        }

        increaseBtn.addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1;
            updateTotal();
        });

        decreaseBtn.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateTotal();
            }
        });

        function updateSubtotal() {
            let subtotal = 0;
            document.querySelectorAll('#productList .item-total').forEach(item => {
                subtotal += parseInt(item.textContent.replace('₱', '')) || 0;
            });

            // Update the subtotal display in POS
            const subtotalEl = document.getElementById('subtotal');
            if (subtotalEl) subtotalEl.textContent = `₱${subtotal.toFixed(2)}`;

            // 🔁 Sync subtotal to calculator
            const totalAmountEl = document.getElementById('totalAmount');
            if (totalAmountEl) totalAmountEl.textContent = `₱${subtotal.toFixed(2)}`;

            // 🔁 Update calculator variables directly
            if (typeof total !== "undefined") total = subtotal;
            if (typeof originalTotal !== "undefined") originalTotal = subtotal;
        }


        function addToOrder() {
            const selectedSizeBtn = document.querySelector('input[name="size"]:checked');
            if (!selectedProduct || !selectedSizeBtn) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Size Required!',
                    text: 'Please select a size before adding to order.',
                    timer: 1200,
                    showConfirmButton: false
                });
                return;
            }

            basePrice = parseInt(selectedSizeBtn.value);
            selectedSize = selectedSizeBtn.dataset.size;

            const qty = parseInt(qtyInput.value);
            const total = parseInt(totalPrice.textContent);
            const selectedAddons = Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(el => el.parentElement.textContent.trim()).join(', ') || 'None';
            const selectedMods = Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(el => el.value).join(', ') || 'None';
            const uniqueKey = `${selectedProduct}-${selectedSize}-${selectedAddons}-${selectedMods}`;
            const existingItem = Array.from(productList.children).find(item => item.dataset.key === uniqueKey);

            if (existingItem) {
                let currentQty = parseInt(existingItem.dataset.qty);
                currentQty += qty;
                const itemBase = parseInt(existingItem.dataset.base);
                const itemAddons = parseInt(existingItem.dataset.addonsTotal);
                const newTotal = (itemBase + itemAddons) * currentQty;

                existingItem.dataset.qty = currentQty;
                existingItem.querySelector('.item-text').textContent = `${selectedProduct} (${selectedSize}) ×${currentQty}`;
                existingItem.querySelector('.item-total').textContent = `₱${newTotal}`;
            } else {
                let addonsTotal = 0;
                document.querySelectorAll('.addon-checkbox:checked').forEach(el => {
                    addonsTotal += parseInt(el.dataset.price);
                });

                const itemDiv = document.createElement('div');
                itemDiv.className = "flex flex-col border-b border-gray-200 py-2";
                itemDiv.dataset.key = uniqueKey;
                itemDiv.dataset.name = selectedProduct;
                itemDiv.dataset.size = selectedSize;
                itemDiv.dataset.qty = qty;
                itemDiv.dataset.base = basePrice;
                itemDiv.dataset.addonsTotal = addonsTotal;

                itemDiv.innerHTML = `
                <div class="flex justify-between items-center">
                    <span class="item-text">×${qty} ${selectedProduct} (${selectedSize}) </span>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold item-total">₱${total}</span>

                        <button class="edit-btn  text-blue-600 p-2 text-xs font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-blue-500">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.121 2.121 0 013 3L12 14l-4 1 1-4z" />
                            </svg>
                        </button>
                        <button class="delete-btn  text-red-600 p-2 text-xs font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-red-500">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m5-3v3" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="text-xs text-gray-600 ml-1">
                    <div>Add-ons: ${selectedAddons}</div>
                    <div>Mods: ${selectedMods}</div>
                </div>
            `;

                // 🗑️ Delete
                itemDiv.querySelector('.delete-btn').addEventListener('click', () => {
                    Swal.fire({
                        title: 'Delete this item?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete',
                        cancelButtonText: 'Cancel'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            itemDiv.remove();
                            saveOrders();
                            updateSubtotal();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                timer: 1000,
                                showConfirmButton: false
                            });
                        }
                    });
                });

                // ✏️ Edit
                itemDiv.querySelector('.edit-btn').addEventListener('click', () => {
                    selectedProduct = itemDiv.dataset.name;
                    basePrice = parseInt(itemDiv.dataset.base);
                    selectedSize = itemDiv.dataset.size;
                    qtyInput.value = itemDiv.dataset.qty;
                    modalName.textContent = selectedProduct;
                    totalPrice.textContent = parseInt(itemDiv.querySelector('.item-total').textContent.replace('₱', ''));
                    modal.classList.remove('hidden');

                    const addBtn = document.querySelector('#productModal button[onclick="addToOrder()"]');
                    addBtn.onclick = function() {
                        const selectedSizeBtn = document.querySelector('input[name="size"]:checked');
                        if (!selectedSizeBtn) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Size Required!',
                                text: 'Please select a size before updating.',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            return;
                        }

                        const oldKey = itemDiv.dataset.key; // keep original key
                        itemDiv.remove();
                        addToOrder();
                        const newItem = productList.lastElementChild;
                        newItem.dataset.key = oldKey; // restore same key
                        saveOrders();
                        updateSubtotal();

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: `${selectedProduct} updated.`,
                            timer: 1200,
                            showConfirmButton: false
                        });
                    };

                });

                productList.appendChild(itemDiv);

                Swal.fire({
                    icon: 'success',
                    title: 'Added!',
                    text: `${selectedProduct} added to order.`,
                    timer: 1200,
                    showConfirmButton: false
                });
            }

            saveOrders();
            qtyInput.value = 1;
            totalPrice.textContent = 0;
            basePrice = 0;
            closeModal();
            updateSubtotal();
        }

        function saveOrders() {
            const orders = Array.from(productList.children).map(item => ({
                key: item.dataset.key,
                name: item.dataset.name,
                size: item.dataset.size,
                qty: item.dataset.qty,
                base: item.dataset.base,
                addonsTotal: item.dataset.addonsTotal,
                html: item.innerHTML
            }));
            localStorage.setItem('savedOrders', JSON.stringify(orders));
            updateSubtotal();
        }

        function loadOrders() {
            const saved = localStorage.getItem('savedOrders');
            if (saved) {
                const orders = JSON.parse(saved);
                orders.forEach(order => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = "flex flex-col border-b border-gray-200 py-2";
                    itemDiv.dataset.key = order.key;
                    itemDiv.dataset.name = order.name;
                    itemDiv.dataset.size = order.size;
                    itemDiv.dataset.qty = order.qty;
                    itemDiv.dataset.base = order.base;
                    itemDiv.dataset.addonsTotal = order.addonsTotal;
                    itemDiv.innerHTML = order.html;
                    productList.appendChild(itemDiv);

                    itemDiv.querySelector('.delete-btn')?.addEventListener('click', () => {
                        Swal.fire({
                            title: 'Delete this item?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, delete',
                            cancelButtonText: 'Cancel'
                        }).then((res) => {
                            if (res.isConfirmed) {
                                itemDiv.remove();
                                saveOrders();
                                updateSubtotal();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    });

                    itemDiv.querySelector('.edit-btn')?.addEventListener('click', () => {
                        selectedProduct = itemDiv.dataset.name;
                        basePrice = parseInt(itemDiv.dataset.base);
                        selectedSize = itemDiv.dataset.size;
                        qtyInput.value = itemDiv.dataset.qty;
                        modalName.textContent = selectedProduct;
                        totalPrice.textContent = parseInt(itemDiv.querySelector('.item-total').textContent.replace('₱', ''));
                        modal.classList.remove('hidden');

                        const addBtn = document.querySelector('#productModal button[onclick="addToOrder()"]');
                        addBtn.onclick = function() {
                            itemDiv.remove();
                            addToOrder();
                            saveOrders();
                            updateSubtotal();
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: `${selectedProduct} updated.`,
                                timer: 1200,
                                showConfirmButton: false
                            });
                        };
                    });
                });
                updateSubtotal();
            }
        }

        function finalizePayment() {
            if (tendered < total) {
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Cash!',
                    text: 'Tendered amount is less than total.',
                });
                return;
            }

            const change = tendered - total;

            Swal.fire({
                icon: 'success',
                title: 'Payment Accepted!',
                html: `<p>Type: ${transType ?? "Regular"}</p>
           <p>Change: ₱${change.toFixed(2)}</p>`,
                timer: 2000,
                showConfirmButton: false
            });

            // Save transaction to DB
            saveTransactionToDB(change);

            closeCalculator();
        }

        function saveTransactionToDB(change) {
            const orders = Array.from(document.querySelectorAll('#productList .flex'))
                .map(item => ({
                    name: item.dataset.name,
                    size: item.dataset.size,
                    qty: item.dataset.qty,
                    base: item.dataset.base,
                    addonsTotal: item.dataset.addonsTotal,
                }));

            const data = {
                staff_id: 1, // replace with logged-in staff
                orders: orders,
                total: total,
                tendered: tendered,
                change: change,
                trans_type: transType ?? 'Cash'
            };

            fetch('../../app/includes/POS/POSSubmitOrder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        console.log('Transaction saved.');
                        localStorage.removeItem('savedOrders'); // clear POS
                        document.getElementById('productList').innerHTML = '';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to save transaction'
                        });
                    }
                });
        }


        window.addToOrder = addToOrder;
        window.closeModal = closeModal;
    });
</script>