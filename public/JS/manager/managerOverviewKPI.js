async function fetchKPI() {
  try {
    const res = await fetch("../../app/includes/managerModule/managerKPI.php");
    const data = await res.json();

    if (data.status === "success") {
      document.getElementById("salesAmount").textContent =
        "₱ " + parseFloat(data.total_sales).toFixed(2);
      document.getElementById("transactions").textContent =
        data.total_transactions;
      document.getElementById("itemsSold").textContent =
        data.total_products_sold;
    } else {
      console.error(data.message);
    }
  } catch (err) {
    console.error("Error fetching KPI:", err);
  }
}

fetchKPI();

setInterval(fetchKPI, 1000);
