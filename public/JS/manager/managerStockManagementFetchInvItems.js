async function loadInventoryItems() {
  try {
    const response = await fetch(
      "../../app/includes/managerModule/managerStockManagementFetchInvItems.php"
    );
    const data = await response.json();
    const tableBody = document.getElementById("inventoryTableBody");
    tableBody.innerHTML = "";

    data.forEach((item) => {
      const dateMade = item.date_made
        ? new Date(item.date_made).toLocaleDateString()
        : "—";
      const dateExpiry = item.date_expiry
        ? new Date(item.date_expiry).toLocaleDateString()
        : "—";

      const row = `
        <tr>
          <td class="px-4 py-3">${item.item_name}</td>
          <td class="px-4 py-3">${item.category_name}</td>
          <td class="px-4 py-3">${item.quantity} ${item.unit}</td>

          <td class="px-4 py-3">
            <span class="px-2 py-1 rounded-full text-xs font-semibold 
              ${
                item.status === "IN STOCK"
                  ? "bg-green-100 text-green-700"
                  : item.status === "LOW STOCK"
                    ? "bg-yellow-100 text-yellow-700"
                    : "bg-red-100 text-red-700"
              }">
              ${item.status}
            </span>
          </td>
                    <td class="px-4 py-3">${dateMade}</td>
          <td class="px-4 py-3">${dateExpiry}</td>
          <td class="px-4 py-3">${item.added_by_name}</td>
         
        </tr>`;
      tableBody.insertAdjacentHTML("beforeend", row);
    });
  } catch (err) {
    console.error("Error fetching inventory:", err);
  }
}

setInterval(loadInventoryItems, 1000);
loadInventoryItems();
