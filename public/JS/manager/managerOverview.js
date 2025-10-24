// ================================================================================================================================================================================================================================
//                                                                                                                   KPI Starts here
// ================================================================================================================================================================================================================================
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
// ================================================================================================================================================================================================================================
//                                                                                                                   KPI Ends here
// ================================================================================================================================================================================================================================

// ================================================================================================================================================================================================================================
//                                                                                                          Weekly Sales Overview Starts here
// ================================================================================================================================================================================================================================
let weeklySalesChart;

async function updateWeeklySalesChart() {
  const canvas = document.getElementById("ovSalesChart");
  if (!canvas) return; // chart not yet in DOM
  const ctx = canvas.getContext("2d");

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerOverviewSalesOverview.php"
    );
    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
    const data = await res.json();

    const labels = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    const salesData = labels.map((day) => data[day] || 0);

    if (!weeklySalesChart) {
      // Create chart for the first time
      weeklySalesChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "₱ Sales",
              data: salesData,
              borderColor: "#3b82f6",
              backgroundColor: "rgba(59,130,246,0.1)",
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointBackgroundColor: "#3b82f6",
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { callback: (value) => "₱" + value },
            },
          },
        },
      });
    } else {
      // Update chart data
      weeklySalesChart.data.datasets[0].data = salesData;
      weeklySalesChart.update();
    }
  } catch (err) {
    console.error("Error updating weekly sales chart:", err);
  }
}

// Update every second
setInterval(updateWeeklySalesChart, 1000);

// ================================================================================================================================================================================================================================
//                                                                                                          Weekly Sales Overview Ends here
// ================================================================================================================================================================================================================================

// ================================================================================================================================================================================================================================
// Dummy
// ================================================================================================================================================================================================================================
// Function to fetch and render top products
let topProductsChart, paymentChart;

// ========================================================
// Top Products Chart - Real-time
// ========================================================
async function updateTopProductsChart() {
  const canvas = document.getElementById("topProductsChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerOverviewTopSellingProduct.php"
    );
    const data = await res.json();

    const labels = data.map((item) => item.product_name);
    const values = data.map((item) => item.total_sold);

    if (!topProductsChart) {
      topProductsChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels,
          datasets: [
            { label: "Items Sold", data: values, backgroundColor: "#3b82f6" },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true } },
        },
      });
    } else {
      topProductsChart.data.labels = labels;
      topProductsChart.data.datasets[0].data = values;
      topProductsChart.update();
    }
  } catch (err) {
    console.error("Error updating top products:", err);
  }
}

// ========================================================
// Payment Breakdown Chart - Real-time
// ========================================================
async function updatePaymentBreakdownChart() {
  const canvas = document.getElementById("paymentChart");
  if (!canvas) return;

  const ctx = canvas.getContext("2d");

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerOverviewPaymentBreakdown.php"
    );
    const data = await res.json();

    const labels = data.map((p) => p.TYPE);
    const values = data.map((p) => parseFloat(p.total_amount));

    if (!paymentChart) {
      paymentChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels,
          datasets: [{ data: values, backgroundColor: ["#10b981", "#3b82f6"] }],
        },
        options: {
          responsive: true,
          plugins: { legend: { position: "bottom" } },
        },
      });
    } else {
      paymentChart.data.labels = labels;
      paymentChart.data.datasets[0].data = values;
      paymentChart.update();
    }
  } catch (err) {
    console.error("Error updating payment breakdown:", err);
  }
}

// ========================================================
// Set Interval - Real-time every 1 second
// ========================================================
setInterval(() => {
  updateTopProductsChart();
  updatePaymentBreakdownChart();
}, 1000);
