function toggleCart() {
  const cart = document.getElementById("cart");
  const cartBox = document.getElementById("cartBox");

  if (cart.classList.contains("hidden")) {
    // Show modal with animation
    cart.classList.remove("hidden");
    cart.classList.add("flex");

    setTimeout(() => {
      cartBox.classList.remove("translate-y-10", "opacity-0");
      cartBox.classList.add("translate-y-0", "opacity-100");
    }, 10);
  } else {
    // Hide modal with animation
    cartBox.classList.add("translate-y-10", "opacity-0");
    cartBox.classList.remove("translate-y-0", "opacity-100");

    setTimeout(() => {
      cart.classList.add("hidden");
      cart.classList.remove("flex");
    }, 300);
  }
}

// ✅ Keep portrait-style cart behavior on all orientations
function handleOrientation() {
  const cart = document.getElementById("cart");
  const cartBox = document.getElementById("cartBox");

  // Always start hidden unless toggled
  if (!cart.classList.contains("flex")) {
    cart.classList.add("hidden");
  }

  cart.classList.remove("block");
  cartBox.classList.add("translate-y-10", "opacity-0");
}

// Listen for orientation/resize
window.addEventListener("resize", handleOrientation);
window.addEventListener("orientationchange", handleOrientation);

// Run once on page load
handleOrientation();
