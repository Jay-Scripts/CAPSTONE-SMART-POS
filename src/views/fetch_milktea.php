<?php
include_once "../config/dbConnection.php"; // including the Database Handler
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Milk Tea POS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="p-5 bg-gray-100 text-black">

  <h1 class="text-center text-2xl font-bold mb-5">Milk Tea Menu</h1>

  <!-- MENU -->
  <?php
  include_once "../controllers/milkTeaProducts.php"; // Including the milktea fetching logic  

  ?>
  <!-- CART -->
  <h2 class="text-xl font-bold mt-8 mb-3">Cart</h2>
  <div id="CART" class="bg-white p-3 rounded shadow"></div>

  <!-- CHECKOUT -->
  <button id="checkoutBtn" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Checkout</button>

  <script>
    let cart = [];

    // Add to cart with quantity tracking
    document.querySelectorAll(".optionChoice").forEach(card => {
      card.addEventListener("click", () => {
        const id = card.dataset.id;
        const name = card.dataset.name;
        const sizes = JSON.parse(card.dataset.sizes);
        const size = 'medio'; // fixed
        const price = sizes[size];

        // Check if already in cart
        const existing = cart.find(item => item.id === id && item.size === size);
        if (existing) {
          existing.qty += 1;
        } else {
          cart.push({
            id,
            name,
            size,
            price,
            qty: 1
          });
        }
        renderCart();
      });
    });

    function renderCart() {
      const cartDiv = document.getElementById("CART");
      if (cart.length === 0) {
        cartDiv.innerHTML = "<p class='text-gray-500'>Cart is empty</p>";
        return;
      }
      cartDiv.innerHTML = cart.map((item, index) => `
    <div class="flex justify-between items-center border-b py-1">
      <span>${item.qty}x ${item.name} (${item.size})</span>
      <span>₱${(item.price * item.qty).toFixed(2)}</span>
      <button type="button" onclick="removeFromCart(${index})" class="text-red-500">X</button>
    </div>
  `).join("");
    }

    function removeFromCart(index) {
      cart.splice(index, 1);
      renderCart();
    }

    document.getElementById("checkoutBtn").addEventListener("click", () => {
      if (cart.length === 0) {
        alert("Cart is empty!");
        return;
      }
      fetch("", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            action: "checkout",
            cart
          })
        })
        .then(res => res.json())
        .then(data => {
          alert(data.message);
          cart = [];
          renderCart();
        })
        .catch(err => console.error(err));
    });

    renderCart();
  </script>

</body>

</html>