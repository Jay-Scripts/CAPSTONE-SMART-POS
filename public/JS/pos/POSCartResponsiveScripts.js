function toggleCart() {
  const cart = document.getElementById("cart");
  const cartBox = document.getElementById("cartBox");

  if (window.matchMedia("(orientation: portrait)").matches) {
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
}

// ✅ Reset cart whenever orientation or screen size changes
function handleOrientation() {
  const cart = document.getElementById("cart");
  const cartBox = document.getElementById("cartBox");

  if (window.matchMedia("(orientation: landscape)").matches) {
    // Always show in landscape
    cart.classList.remove("hidden");
    cart.classList.add("block");
    cart.classList.remove("flex"); // remove portrait modal flex
    cartBox.classList.remove("translate-y-10", "opacity-0");
    cartBox.classList.add("translate-y-0", "opacity-100");
  } else {
    // Go back to portrait default (hidden until triggered)
    if (!cart.classList.contains("flex")) {
      cart.classList.add("hidden");
      cart.classList.remove("block");
    }
  }
}

// Listen for orientation/resize
window.addEventListener("resize", handleOrientation);
window.addEventListener("orientationchange", handleOrientation);

// Run once on page load
handleOrientation();
