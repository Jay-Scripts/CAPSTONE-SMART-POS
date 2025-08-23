let cart = [];

// Add to cart with quantity tracking
document.querySelectorAll(".optionChoice").forEach((card) => {
  card.addEventListener("click", () => {
    const id = card.dataset.id;
    const name = card.dataset.name;
    const sizes = JSON.parse(card.dataset.sizes);
    const size = "medio"; // fixed
    const price = sizes[size];

    // Check if already in cart
    const existing = cart.find((item) => item.id === id && item.size === size);
    if (existing) {
      existing.qty += 1;
    } else {
      cart.push({
        id,
        name,
        size,
        price,
        qty: 1,
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
  cartDiv.innerHTML = cart
    .map(
      (item, index) => `
    <div class="flex justify-between items-center p-2">
      <span>${item.qty}x ${item.name} (${item.size})</span>
     <div> <span>₱${(item.price * item.qty).toFixed(2)}</span>
      <button type="button" onclick="removeFromCart(${index})" class="text-red-500">X</button></div>
    </div>
  `
    )
    .join("");
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
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      action: "checkout",
      cart,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      alert(data.message);
      cart = [];
      renderCart();
    })
    .catch((err) => console.error(err));
});

renderCart();
