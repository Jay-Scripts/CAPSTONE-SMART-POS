const alertEl = document.getElementById("successAlert");

// Show alert with animation
window.addEventListener("DOMContentLoaded", () => {
  alertEl.classList.remove("opacity-0", "-translate-y-10");
  alertEl.classList.add("opacity-100", "translate-y-0");

  // Auto hide after 3 seconds
  setTimeout(() => {
    closeAlert();
  }, 3000);
});

function closeAlert() {
  alertEl.classList.remove("opacity-100", "translate-y-0");
  alertEl.classList.add("opacity-0", "-translate-y-10");
  // Remove from DOM after animation
  setTimeout(() => alertEl.remove(), 500);
}
