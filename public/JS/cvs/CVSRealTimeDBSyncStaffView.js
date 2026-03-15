async function loadOrders() {
  try {
    const res = await fetch(
      "../../app/includes/CVS/CVSfetchOrdersStaffView.php",
    );
    const data = await res.json();

    const servingDiv = document.getElementById("nowServing");
    if (!servingDiv) return;

    if (data.serving.length > 0) {
      servingDiv.innerHTML = data.serving
        .map(
          (id) => `
          <div class="bg-green-600 rounded-lg p-3 shadow-sm">
            <p class="text-white font-bold text-3xl">#${id}</p>
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

loadNowServing();
setInterval(loadNowServing, 1000);
