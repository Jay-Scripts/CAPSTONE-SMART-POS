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



<script>
    const allProducts = <?= json_encode($allProducts) ?>;
    const totalDisplay = document.querySelector('#totalAmount');
    let products = {};
    Object.values(allProducts).forEach(group => {
        Object.values(group).forEach(prod => {
            products[prod.product_id] = prod;
        });
    });

    let selectedProduct = null;
    let cart = [];
    let quantityInput = document.getElementById('quantity');
    let subtotalEl = document.getElementById('subtotal');

    function openModal(product) {
        selectedProduct = product;
        document.getElementById('modalThumb').src = product.thumbnail_path;
        document.getElementById('productName').textContent = product.product_name;

        // build size options dynamically
        const sizesContainer = document.getElementById('sizesContainer');
        sizesContainer.innerHTML = '';
        product.sizes.forEach((s, i) => {
            const price = Number(s.price) || 0;
            sizesContainer.innerHTML += `
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="size" data-id="${s.size_id}" data-price="${price}" 
               ${i === 0 ? 'checked' : ''} onchange="updateTotal()">
        <span class="ml-2">${s.size}</span>
        <span class="ml-auto">₱${price.toFixed(2)}</span>
      </label>`;
        });

        quantityInput.value = 1;
        document.querySelectorAll('.addon-checkbox').forEach(c => c.checked = false);
        document.querySelectorAll('.mod-checkbox').forEach(c => c.checked = false);

        attachModalListeners();
        originalTotal
        updateTotal();
        document.getElementById('productModal').classList.remove('hidden');
    }

    function attachModalListeners() {
        document.querySelectorAll('input[name="size"]').forEach(radio => {
            radio.addEventListener('change', updateTotal);
        });
        document.querySelectorAll('.addon-checkbox').forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
    }

    function safeParse(v) {
        v = String(v || '').replace(/[^\d.]/g, '');
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    function updateTotal() {
        const selectedSize = document.querySelector('input[name="size"]:checked');
        const base = selectedSize ? safeParse(selectedSize.dataset.price) : 0;

        let addons = 0;
        document.querySelectorAll('.addon-checkbox:checked').forEach(a => {
            addons += safeParse(a.dataset.price);
        });

        const qty = parseInt(quantityInput.value) || 1;
        const total = (base + addons) * qty;

        subtotalEl.textContent = total.toFixed(2);
    }

    // qty buttons
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



    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }


    document.getElementById('increaseQty').onclick = () => {
        quantityInput.value = parseInt(quantityInput.value) + 1;
        updateTotal();
    }
    document.getElementById('decreaseQty').onclick = () => {
        if (quantityInput.value > 1) quantityInput.value--;
        updateTotal();
    }

    function addToOrder() {
        const size = document.querySelector('input[name="size"]:checked');
        if (!size) {
            alert('Please select a size');
            return;
        }

        const orderItem = {
            product_id: selectedProduct.product_id,
            size_id: parseInt(size.dataset.id),
            quantity: parseInt(quantityInput.value),
            price: parseFloat(size.dataset.price),
            addons: Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(a => parseInt(a.dataset.id)),
            modifications: Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(m => parseInt(m.value))
        };

        cart.push(orderItem);
        closeModal();
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('productList');
        container.innerHTML = '';

        const addonsList = <?= json_encode($addons) ?>;
        const modsList = <?= json_encode($modifications) ?>;

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

        let total = 0;

        merged.forEach((item, index) => {
            const product = products[item.product_id];
            const sizeObj = product.sizes.find(s => s.size_id === item.size_id);
            const sizeLabel = sizeObj ? ` (${sizeObj.size})` : '';
            const subtotal = item.price * item.quantity;
            total += subtotal;

            const addonsText = item.addons?.length ?
                item.addons.map(id => {
                    const addon = addonsList.find(a => a.ADD_ONS_ID == id);
                    return addon ? `${addon.ADD_ONS_NAME.toUpperCase()} (+₱${parseFloat(addon.PRICE).toFixed(2)})` : '';
                }).join(', ') :
                'None';

            const modsText = item.modifications?.length ?
                item.modifications.map(id => {
                    const mod = modsList.find(m => m.MODIFICATION_ID == id);
                    return mod ? mod.MODIFICATION_NAME.toUpperCase() : '';
                }).join(', ') :
                'None';

            container.innerHTML += `
      <div class="flex flex-col border-b border-gray-200 py-2 group">
        <div class="flex justify-between items-center">
          <span class="text-sm">${item.quantity}x ${product.product_name}${sizeLabel}</span>
          <div class="flex items-center gap-3">
            <span class="font-semibold text-gray-800">₱${subtotal.toFixed(2)}</span>

            <!-- Edit Button -->
            <button onclick="editCartItem(${item.indexes[0]})" class="text-blue-500 hover:text-blue-700 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-blue-500">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2.121 2.121 0 013 3L12 14l-4 1 1-4z" />
              </svg>
            </button>

            <!-- Delete Button -->
            <button onclick="removeFromCart(${item.indexes.join(',')})" class="text-red-500 hover:text-red-700 transition">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-red-500">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m5-3v3" />
              </svg>
            </button>
          </div>
        </div>

        <div class="text-xs text-gray-600 ml-5 mt-1 space-y-0.5">
          <div><b>Add-ons:</b> ${addonsText}</div>
          <div><b>Mods:</b> ${modsText}</div>
        </div>
      </div>
    `;
        });

        container.innerHTML += `
    <div class="text-right font-semibold mt-2">Total: ₱${total.toFixed(2)}</div>
  `;

        if (totalDisplay) {
            totalDisplay.textContent = `₱${total.toFixed(2)}`;
        } else {
            console.warn('⚠️ totalAmount element not found in DOM');
        }

        originalTotal = total;
        updateDisplay();
    }

    /* ✏️ Edit item */
    function editCartItem(index) {
        const item = cart[index];
        if (!item) return;



        const product = products[item.product_id];
        openModal(product);

        document.querySelectorAll('input[name="size"]').forEach(radio => {
            if (parseInt(radio.dataset.id) === item.size_id) radio.checked = true;
        });

        document.querySelectorAll('.addon-checkbox').forEach(ch => {
            ch.checked = item.addons.includes(parseInt(ch.dataset.id));
        });

        document.querySelectorAll('.mod-checkbox').forEach(ch => {
            ch.checked = item.modifications.includes(parseInt(ch.value));
        });

        document.getElementById('quantity').value = item.quantity;
        updateTotal();
        selectedProduct.editingIndex = index;
    }

    /* 🗑️ Delete item */
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

    /* ✅ Add or update item */
    function addToOrder() {
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

        let basePrice = parseFloat(size.dataset.price);
        let addonsPrice = 0;

        // calculate total add-ons price
        document.querySelectorAll('.addon-checkbox:checked').forEach(a => {
            addonsPrice += parseFloat(a.dataset.price);
        });

        const totalItemPrice = basePrice + addonsPrice;

        const newItem = {
            product_id: selectedProduct.product_id,
            size_id: parseInt(size.dataset.id),
            quantity: parseInt(document.getElementById('quantity').value),
            price: totalItemPrice, // ✅ now includes add-ons
            addons: Array.from(document.querySelectorAll('.addon-checkbox:checked')).map(a => parseInt(a.dataset.id)),
            modifications: Array.from(document.querySelectorAll('.mod-checkbox:checked')).map(m => parseInt(m.value))
        };

        if (selectedProduct.editingIndex !== undefined) {
            cart[selectedProduct.editingIndex] = newItem;
            delete selectedProduct.editingIndex;
            Swal.fire({
                icon: 'success',
                title: 'Item Updated',
                text: 'The item was successfully updated.',
                timer: 1000,
                showConfirmButton: false
            });
        } else {
            cart.push(newItem);
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart',
                text: 'Item successfully added!',
                timer: 1000,
                showConfirmButton: false
            });
        }

        closeModal();
        renderCart();
    }




    let currentPaymentType = 'CASH';

    function openEPaymentPopup() {
        currentPaymentType = 'E-PAYMENT';
        document.getElementById('EPaymentPopup').classList.remove('hidden');
    }

    function closeEPaymentPopup() {
        document.getElementById('EPaymentPopup').classList.add('hidden');
    }

    function addCash(amount) {
        tenderedAmount += amount;
        updateCalculatorDisplay();
    }



    //calc
    let originalTotal = 0;
    let total = originalTotal;
    let tendered = 0;
    let buffer = "";
    let transType = null;

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
        document.getElementById("totalAmount").textContent = `₱${originalTotal.toFixed(2)}`;
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
    // 💰 Calculator input buttons
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

    // ✅ Finalize payment and send to PHP
    function finalizePayment() {
        if (cart.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "Empty Cart",
                text: "Add items before finalizing payment."
            });
            return;
        }

        // make sure the displayed total is used
        originalTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        if (tendered < originalTotal) {
            Swal.fire({
                icon: "warning",
                title: "Insufficient Payment",
                text: "Tendered amount is less than total."
            });
            return;
        }

        const change = tendered - originalTotal;
        const paymentType = currentPaymentType || "CASH";

        console.log("🟢 Sending to PHP:", {
            tendered,
            change,
            total: originalTotal,
            paymentType
        });

        const formData = new FormData();
        formData.append("order_data", JSON.stringify(cart));
        formData.append("payment_type", paymentType);
        formData.append("amount_sent", tendered);
        formData.append("change_amount", change);
        formData.append("total", originalTotal);

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
                        // 🧾 Fetch and print receipt
                        fetch(`../../app/includes/POS/printReceipt.php?id=${data.transaction_id}`)
                            .then(res => res.text())
                            .then(receiptHTML => {
                                const printWindow = window.open('', '_blank', 'width=400,height=600');
                                printWindow.document.write(receiptHTML);
                                printWindow.document.close();
                                printWindow.focus();
                                printWindow.print();
                            });

                        // 🧹 Clear cart and close calculator after print
                        cart = [];
                        renderCart();
                        closeCalculator();
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


    // 🧾 Display update
    function updateDisplay() {
        const tenderedDisplay = document.getElementById("tenderedAmount");
        const changeDisplay = document.getElementById("changeAmount");
        const totalDisplayEl = document.getElementById("totalAmount");

        if (totalDisplayEl) totalDisplayEl.textContent = `₱${originalTotal.toFixed(2)}`;
        if (tenderedDisplay) tenderedDisplay.textContent = `₱${tendered.toFixed(2)}`;

        const change = Math.max(0, tendered - originalTotal);
        if (changeDisplay) changeDisplay.textContent = `₱${change.toFixed(2)}`;
    }


    function openQrPopup() {
        document.getElementById("qrPopup").classList.remove("hidden");
    }

    function openEPaymentPopup() {
        document.getElementById("EPaymentPopup").classList.remove("hidden");
    }

    function closeQrPopup() {
        document.getElementById("qrPopup").classList.add("hidden");
    }

    function closeEPaymentPopup() {
        document.getElementById("EPaymentPopup").classList.add("hidden");
    }
</script>