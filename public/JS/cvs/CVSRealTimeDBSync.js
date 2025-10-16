async function loadOrders() {
  try {
    const res = await fetch("../../app/includes/CVS/CVSfetchOrders.php");
    const data = await res.json();

    const preparingDiv = document.getElementById("preparingOrders");
    const servingDiv = document.getElementById("nowServing");

    // Preparing Orders
    if (data.preparing.length > 0) {
      preparingDiv.innerHTML = data.preparing
        .map(
          (id) => `
          <div class="bg-[var(--panel-bg)] rounded-lg p-3 shadow-sm">
            <p class="text-3xl text-[var(--text-color)] font-semibold">#${id}</p>
          </div>
        `
        )
        .join("");
    } else {
      preparingDiv.innerHTML = `<p class="text-gray-400 italic">No preparing orders</p>`;
    }

    // Now Serving
    if (data.serving.length > 0) {
      servingDiv.innerHTML = data.serving
        .map(
          (id) => `
          <div class="bg-green-600 rounded-lg p-3 shadow-sm">
            <p class="text-white font-bold text-3xl">#${id}</p>
          </div>
        `
        )
        .join("");
    } else {
      servingDiv.innerHTML = `<p class="text-gray-400 italic">No orders serving now</p>`;
    }
  } catch (error) {
    console.error("Error fetching orders:", error);
  }
}

// Initial load + auto refresh every 1 seconds
loadOrders();
setInterval(loadOrders, 1000);
