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

async function loadWeeklySalesChart() {
  const ctx = document.getElementById("ovSalesChart").getContext("2d");

  try {
    const response = await fetch(
      "../../app/includes/managerModule/managerOverviewSalesOverview.php"
    );
    const data = await response.json();

    // Chart.js expects arrays in order Mon–Sun
    const labels = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    const salesData = labels.map((day) => data[day] || 0);

    new Chart(ctx, {
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
            ticks: {
              callback: (value) => "₱" + value,
            },
          },
        },
      },
    });
  } catch (err) {
    console.error("Error loading weekly sales:", err);
  }
}
loadWeeklySalesChart();

// ================================================================================================================================================================================================================================
//                                                                                                          Weekly Sales Overview Ends here
// ================================================================================================================================================================================================================================

// ================================================================================================================================================================================================================================
// Dummy
// ================================================================================================================================================================================================================================

document
  .getElementById("ovSalesFilter")
  .addEventListener("change", function () {
    const value = this.value;
    ovSalesChart.data.labels = ovSalesData[value].labels;
    ovSalesChart.data.datasets[0].data = ovSalesData[value].data;
    ovSalesChart.update();
  });

// ========================================================
// TOP SELLING PRODUCTS CHART STARTS HERE
// ========================================================
const ovTsCtx = document.getElementById("ovTsChart").getContext("2d");

const ovTsData = {
  day: {
    labels: ["Fruit tea", "Praf", "Hot Choco"],
    data: [20, 15, 10],
  },
  week: {
    labels: [
      "Hot Brew",
      "Milk Tea",
      "Iced Coffee",
      "fruit Tea",
      "Praf",
      "Promos",
    ],
    data: [120, 93, 75, 68, 55],
  },
  month: {
    labels: [
      "Milk Tea",
      "fruit Tea",
      "Hot Brew",
      "Praf",
      "Iced Coffee",
      "Promos",
    ],
    data: [400, 350, 320, 280, 200, 180],
  },
};

const ovTsChart = new Chart(ovTsCtx, {
  type: "bar",
  data: {
    labels: ovTsData.week.labels,
    datasets: [
      {
        label: "Units Sold",
        data: ovTsData.week.data,
        backgroundColor: [
          "#60a5fa",
          "#34d399",
          "#fbbf24",
          "#f87171",
          "#c084fc",
          "#818cf8",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
  },
});

document.getElementById("ovTsFilter").addEventListener("change", function () {
  const value = this.value;
  ovTsChart.data.labels = ovTsData[value].labels;
  ovTsChart.data.datasets[0].data = ovTsData[value].data;
  ovTsChart.update();
});
// ========================================================
// TOP SELLING PRODUCTS CHART ENDS HERE
// ========================================================

// ========================================================
// PAYMENT METHOD OVERVIEW STARTS HERE
// ========================================================

window.addEventListener("DOMContentLoaded", () => {
  const ovPaymentMethodCtx = document
    .getElementById("ovPaymentMethodChart")
    ?.getContext("2d");

  if (!ovPaymentMethodCtx) {
    console.error("❌ ovPaymentMethodChart canvas not found.");
    return;
  }

  // Custom plugin to draw center total
  const doughnutCenterText = {
    id: "doughnutCenterText",
    beforeDraw(chart) {
      const { width, height, ctx } = chart;
      const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);

      ctx.save();
      ctx.font = "bold 16px sans-serif";
      ctx.fillStyle = "#111";
      ctx.textAlign = "center";
      ctx.textBaseline = "middle";

      ctx.fillText("Total", width / 2, height / 2 - 10);
      ctx.fillText(`₱${total.toLocaleString()}`, width / 2, height / 2 + 12);
      ctx.restore();
    },
  };

  const ovPaymentMethodChart = new Chart(ovPaymentMethodCtx, {
    type: "doughnut",
    data: {
      labels: ["Cash", "E-payment"],
      datasets: [
        {
          label: "Payment Methods",
          data: [0, 0],
          backgroundColor: ["#10B981", "#3B82F6"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: "bottom",
        },
        tooltip: {
          callbacks: {
            label: function (context) {
              let value = context.raw;
              let percentage = ((value / total) * 100).toFixed(1);
              return `${
                context.label
              }: ₱${value.toLocaleString()} (${percentage}% `;
            },
          },
        },
      },
      animation: {
        duration: 3000,
        easing: "linear",
      },
    },
    plugins: [doughnutCenterText], // ⬅️ Register the plugin
  });

  // Dummy data update function STARTS HERE
  function updatePaymentMethods() {
    const cash = Math.floor(Math.random() * 1000);
    const ePayment = Math.floor(Math.random() * 1000);
    ovPaymentMethodChart.data.datasets[0].data = [cash, ePayment];
    ovPaymentMethodChart.update();
  }
  // Dummy data update function ENDS HERE

  // Initial call + auto update
  updatePaymentMethods();
  setInterval(updatePaymentMethods, 5000);
});
