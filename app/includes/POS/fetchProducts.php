<?php
include "../../app/config/dbConnection.php";

if (!isset($category_id)) {
    echo "<p class='text-red-500'>Category not specified.</p>";
    return;
}

$stmt = $conn->prepare("
SELECT pd.product_id, pd.product_name, pd.thumbnail_path, ps.size_id, ps.size, ps.regular_price
FROM product_details pd
JOIN product_sizes ps ON pd.product_id = ps.product_id
WHERE pd.category_id = ?
AND pd.status = 'active'
ORDER BY pd.product_name ASC
");
$stmt->execute([$category_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products = [];
foreach ($rows as $row) {
    $id = $row['product_id'];
    if (!isset($products[$id])) {
        $products[$id] = [
            'product_id' => $id,
            'product_name' => $row['product_name'],
            'thumbnail_path' => $row['thumbnail_path'],
            'sizes' => []
        ];
    }
    $products[$id]['sizes'][] = [
        'size_id' => $row['size_id'],
        'size' => $row['size'],
        'price' => $row['regular_price']
    ];
}
?>

<section class="flex flex-wrap justify-center gap-2">
    <?php foreach ($products as $product): ?>
        <div class="optionChoice cursor-pointer aspect-square w-[47%] sm:w-[15%] bg-transparent rounded-lg border border-gray-400 p-2"
            onclick='openModal(<?= json_encode($product) ?>)'>
            <img src="<?= $product['thumbnail_path'] ?>" class="object-cover">
            <h3 class="text-center text-[var(--text-color)] font-semibold"><?= htmlspecialchars($product['product_name']) ?></h3>
        </div>
    <?php endforeach; ?>
</section>

<script>
    /* ================================
   STATE VARIABLES
   ================================ */
    let selectedProduct = null;
    let cart = [];
    let quantityInput = null;
    let subtotalEl = null;
    let totalDisplay = null;
    let originalTotal = 0;
    let total = 0;
    let tendered = 0;
    let buffer = "";
    let transType = null;
    let currentPaymentType = 'CASH';
    let discountRateTemp = 0;
    let discountRate = 0;
    let discountAmount = originalTotal * discountRate;

    // Products & options from PHP
    const allProducts = <?= json_encode($allProducts) ?>;
    const addonsList = <?= json_encode($addons) ?>;
    const modsList = <?= json_encode($modifications) ?>;
    let products = {};
    Object.values(allProducts).forEach(group => {
        Object.values(group).forEach(prod => {
            products[prod.product_id] = prod;
        });
    });

    /* ================================
       WAIT FOR DOM TO LOAD
       ================================ */
    document.addEventListener('DOMContentLoaded', () => {
        quantityInput = document.getElementById('quantity');
        subtotalEl = document.getElementById('subtotal');
        totalDisplay = document.querySelector('#totalAmount');

        // Qty buttons
        document.getElementById('increaseQty').onclick = () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
            updateTotal();
        };
        document.getElementById('decreaseQty').onclick = () => {
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
                updateTotal();
            }
        };

        // Discount buttons now open manager QR modal instead of applying discount directly
        document.getElementById('scBtn').onclick = () => openManagerQrModal(0.2); // 20% discount SC
        document.getElementById('pwdBtn').onclick = () => openManagerQrModal(0.2); // 20% discount PWD


    });

    /* ================================
       UTILITY FUNCTIONS
       ================================ */
    function safeParse(v) {
        v = String(v || '').replace(/[^\d.]/g, '');
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    /* ================================
       MODAL FUNCTIONS
       ================================ */
    function openModal(product) {
        selectedProduct = product;
        document.getElementById('modalThumb').src = product.thumbnail_path;
        document.getElementById('productName').textContent = product.product_name;

        const sizesContainer = document.getElementById('sizesContainer');
        sizesContainer.innerHTML = '';
        product.sizes.forEach((s, i) => {
            const price = Number(s.price) || 0;
            sizesContainer.innerHTML += `
        <label class="flex items-center gap-2 cursor-pointer z-50">
          <input type="radio" name="size" data-id="${s.size_id}" data-price="${price}" ${i === 0 ? 'checked' : ''} onchange="updateTotal()">
          <span class="ml-2">${s.size}</span>
          <span class="ml-auto">₱${price.toFixed(2)}</span>
        </label>`;
        });

        quantityInput.value = 1;
        document.querySelectorAll('.addon-checkbox').forEach(c => c.checked = false);
        document.querySelectorAll('.mod-checkbox').forEach(c => c.checked = false);

        attachModalListeners();
        updateTotal();
        document.getElementById('productModal').classList.remove('hidden');
    }

    function attachModalListeners() {
        document.querySelectorAll('input[name="size"]').forEach(radio => {
            radio.addEventListener('change', updateTotal);
        });
        document.querySelectorAll('.addon-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));
    }

    function updateTotal() {
        const selectedSize = document.querySelector('input[name="size"]:checked');
        const base = selectedSize ? safeParse(selectedSize.dataset.price) : 0;

        let addons = 0;
        document.querySelectorAll('.addon-checkbox:checked').forEach(a => {
            addons += safeParse(a.dataset.price);
        });

        const qty = parseInt(quantityInput.value) || 1;
        const totalItem = (base + addons) * qty;

        if (subtotalEl) subtotalEl.textContent = totalItem.toFixed(2);
    }

    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    /* ================================
       CART FUNCTIONS
       ================================ */
    function renderCart() {
        const container = document.getElementById('productList');
        if (!container) return;

        container.innerHTML = '';

        const merged = [];
        cart.forEach((item, idx) => {
            const key = `${item.product_id}-${item.size_id}-${JSON.stringify(item.addons)}-${JSON.stringify(item.modifications)}`;
            const found = merged.find(i => i.key === key);
            if (found) {
                found.quantity += item.quantity;
                found.indexes.push(idx);
            } else {
                merged.push({
                    ...item,
                    key,
                    indexes: [idx]
                });
            }
        });

        let cartTotal = 0;
        merged.forEach(item => {
            const product = products[item.product_id];
            const sizeObj = product.sizes.find(s => s.size_id === item.size_id);
            const sizeLabel = sizeObj ? ` (${sizeObj.size})` : '';
            const subtotal = item.price * item.quantity;
            cartTotal += subtotal;

            const addonsText = item.addons?.length ?
                item.addons.map(id => {
                    const addon = addonsList.find(a => a.ADD_ONS_ID == id);
                    return addon ? `${addon.ADD_ONS_NAME.toUpperCase()} (+₱${parseFloat(addon.PRICE).toFixed(2)})` : '';
                }).join(', ') : 'None';

            const modsText = item.modifications?.length ?
                item.modifications.map(id => {
                    const mod = modsList.find(m => m.MODIFICATION_ID == id);
                    return mod ? mod.MODIFICATION_NAME.toUpperCase() : '';
                }).join(', ') : 'None';

            container.innerHTML += `
      <div class="flex flex-col border-b border-gray-200 py-2 group">
        <div class="flex justify-between items-center">
          <span class="text-sm">${item.quantity}x ${product.product_name}${sizeLabel}</span>
          <div class="flex items-center gap-3">
            <span class="font-semibold">₱${subtotal.toFixed(2)}</span>
            <button onclick="editCartItem(${item.indexes[0]})" class="text-blue-500 hover:text-blue-700 transition">Edit</button>
            <button onclick="removeFromCart(${item.indexes.join(',')})" class="text-red-500 hover:text-red-700 transition">Delete</button>
          </div>
        </div>
        <div class="text-xs ml-5 mt-1 space-y-0.5">
          <div><b>Add-ons:</b> ${addonsText}</div>
          <div><b>Mods:</b> ${modsText}</div>
        </div>
      </div>`;
        });

        container.innerHTML += `<div class="text-right font-semibold mt-2">Total: ₱${cartTotal.toFixed(2)}</div>`;

        originalTotal = cartTotal;
        updateDisplay();
    }
    async function checkStockBeforeAdd(productId, sizeId, qty) {
        const res = await fetch(`../../app/includes/POS/checkStock.php?product_id=${productId}&size_id=${sizeId}&qty=${qty}`);
        const data = await res.json();

        if (!data.ok) {
            Swal.fire({
                icon: "error",
                title: "Insufficient Stock",
                text: data.message
            });
            return false;
        }

        return true;
    }


    async function addToOrder() {
        const size = document.querySelector('input[name="size"]:checked');
        if (!size) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Size',
                text: 'Please select a size first.',
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }

        const basePrice = parseFloat(size.dataset.price);
        let addonsPrice = 0;
        document.querySelectorAll('.addon-checkbox:checked').forEach(a => addonsPrice += parseFloat(a.dataset.price));

        const newItem = {
            product_id: selectedProduct.product_id,
            size_id: parseInt(size.dataset.id),
            quantity: parseInt(quantityInput.value),
            price: basePrice + addonsPrice,
            addons: Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(a => parseInt(a.dataset.id)),
            modifications: Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(m => parseInt(m.value)),
            category: selectedProduct.category // make sure category is set for each product
        };

        // ✅ Check stock with cart quantities included
        const ok = await checkStockBeforeAdd(newItem.product_id, newItem.size_id, newItem.quantity);
        if (!ok) return;

        if (selectedProduct.editingIndex !== undefined) {
            cart[selectedProduct.editingIndex] = newItem;
            delete selectedProduct.editingIndex;
            Swal.fire({
                icon: 'success',
                title: 'Item Updated',
                timer: 1000,
                showConfirmButton: false
            });
        } else {
            cart.push(newItem);
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                timer: 1000,
                showConfirmButton: false
            });
        }

        closeModal();
        renderCart();
    }


    function editCartItem(index) {
        const item = cart[index];
        if (!item) return;

        const product = products[item.product_id];
        openModal(product);

        document.querySelectorAll('input[name="size"]').forEach(radio => {
            radio.checked = parseInt(radio.dataset.id) === item.size_id;
        });

        document.querySelectorAll('.addon-checkbox').forEach(ch => ch.checked = item.addons.includes(parseInt(ch.dataset.id)));
        document.querySelectorAll('.mod-checkbox').forEach(ch => ch.checked = item.modifications.includes(parseInt(ch.value)));

        quantityInput.value = item.quantity;
        updateTotal();
        selectedProduct.editingIndex = index;
    }

    function removeFromCart(...indexes) {
        Swal.fire({
            title: 'Remove Item?',
            text: 'This item will be deleted from your order.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                indexes.sort((a, b) => b - a).forEach(i => cart.splice(i, 1));
                renderCart();
                Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'The item has been removed.',
                    timer: 1000,
                    showConfirmButton: false
                });
            }
        });
    }

    /* ================================
       DISPLAY & CALCULATOR
       ================================ */
    function updateDisplay() {
        const tenderedDisplay = document.getElementById("tenderedAmount");
        const changeDisplay = document.getElementById("changeAmount");
        const totalDisplayEl = document.getElementById("totalAmount");
        const discountDisplayEl = document.getElementById("discountAmount");
        const epayDisplayEl = document.getElementById("epayAmount");

        const discountAmount = originalTotal * discountRate;
        let remainingTotal = originalTotal - discountAmount - epayAmount;
        if (remainingTotal < 0) remainingTotal = 0;

        // Update summary
        if (totalDisplayEl) totalDisplayEl.textContent = `₱${(originalTotal - discountAmount).toFixed(2)}`;
        if (discountDisplayEl) discountDisplayEl.textContent = `₱${discountAmount.toFixed(2)}`;
        if (epayDisplayEl) epayDisplayEl.textContent = `₱${epayAmount.toFixed(2)}`;
        if (tenderedDisplay) tenderedDisplay.textContent = `₱${tendered.toFixed(2)}`;

        const change = Math.max(0, tendered - remainingTotal);
        if (changeDisplay) changeDisplay.textContent = `₱${change.toFixed(2)}`;

        total = originalTotal - discountAmount; // send this to PHP as discounted total
    }



    function applyDiscount(rate) {
        discountRate = rate;
        updateDisplay();
    }

    function openCalculator() {
        if (cart.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "No Orders!",
                text: "Please add an order before proceeding to payment.",
                timer: 1500,
                showConfirmButton: false
            });
            return;
        }
        originalTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        document.getElementById("calculatorModal").classList.remove("hidden");
        tendered = 0;
        buffer = "";
        updateDisplay();
    }

    function closeCalculator() {
        document.getElementById("calculatorModal").classList.add("hidden");
        tendered = 0;
        buffer = "";
        transType = null;
        total = originalTotal;
        updateDisplay();
    }

    function manualKey(num) {
        buffer += num;
        tendered = parseFloat(buffer) || 0;
        updateDisplay();
    }

    function clearCash() {
        tendered = 0;
        buffer = "";
        updateDisplay();
    }

    function addCash(amount) {
        tendered += amount;
        updateDisplay();
    }



    /* ================================
       PAYMENT FINALIZATION
       ================================ */
    function finalizePayment() {
        if (cart.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "Empty Cart",
                text: "Add items before finalizing payment."
            });
            return;
        }

        const epayAmount = parseFloat(document.getElementById('epayAmountHidden').value) || 0;
        const refNumber = document.getElementById('refNumberHidden').value || '';
        const discountAmount = parseFloat(document.getElementById('discountAmount').innerText.replace(/[^0-9.-]+/g, "")) || 0;
        const totalAfterDiscount = originalTotal - discountAmount;
        const remainingTotal = Math.max(0, totalAfterDiscount - epayAmount);

        if (tendered < remainingTotal) {
            Swal.fire({
                icon: "warning",
                title: "Insufficient Payment",
                text: "Tendered amount is less than remaining total."
            });
            return;
        }

        const change = tendered - remainingTotal;

        // Automatically select payment type
        let paymentType = "CASH";
        if (epayAmount > 0) paymentType = "E-PAYMENT"; // ✅ Set to E-Payment if any epay value exists

        // Build FormData
        const formData = new FormData();
        formData.append("order_data", JSON.stringify(cart));
        formData.append("payment_type", paymentType);
        formData.append("amount_sent", tendered);
        formData.append("change_amount", change);
        formData.append("total", totalAfterDiscount);
        formData.append("epay_amount", epayAmount);
        formData.append("refNumber", refNumber);

        const discType = document.getElementById('discountType').value;
        if (discType) {
            const discountData = {
                type: discType,
                id_num: document.getElementById('discountId').value,
                first_name: document.getElementById('discountFirstName').value,
                last_name: document.getElementById('discountLastName').value,
                disc_total: discountAmount
            };
            formData.append('discount_data', JSON.stringify(discountData));
        }

        // Send to PHP
        fetch('', {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Payment Successful!",
                        text: `Change: ₱${change.toFixed(2)}`,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Print receipt
                        fetch(`../../app/includes/POS/printReceipt.php?id=${data.transaction_id}`)
                            .then(res => res.text())
                            .then(receiptHTML => {
                                const printWindow = window.open('', '_blank', 'width=400,height=600');
                                printWindow.document.write(receiptHTML);
                                printWindow.document.close();
                                printWindow.focus();

                                // Refresh page after printing
                                printWindow.onafterprint = function() {
                                    location.reload();
                                };

                                printWindow.print();
                            });

                        // Reset cart & calculator
                        cart = [];
                        renderCart();
                        closeCalculator();

                        // Reset discount
                        discountRate = 0;
                        discountRateTemp = 0;
                        document.getElementById("discountSection").classList.add("hidden");
                        document.getElementById("discountType").value = "";
                        document.getElementById("discountId").value = "";
                        document.getElementById("discountFirstName").value = "";
                        document.getElementById("discountLastName").value = "";
                        document.getElementById("discountAmount").value = "";
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "Payment failed."
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Server Error",
                    text: err.message
                });
            });
    }
</script>