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
        `,
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
          <div  class="flex items-center justify-start  gap-2 px-4 py-2 rounded-2xl
                      border border-green-500/30 bg-green-500/10
">
              <div class="w-2 h-10 rounded-full bg-green-500 shrink-0"></div>

               <div> <p class="text-xs opacity-50 text-[var(--text-color)] uppercase tracking-wide font-semibold">Order</p>

            <p class="text-2xl font-bold text-[var(--text-color)]">#${id}</p></div>
              
          </div>
        `,
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
