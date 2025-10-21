const ovSalesCtx = document.getElementById("ovSalesChart").getContext("2d");

const ovSalesData = {
  day: {
    labels: ["9AM", "11AM", "1PM", "3PM", "5PM"],
    data: [300, 450, 350, 500, 620],
  },
  week: {
    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
    data: [1200, 1500, 1000, 1800, 1600, 2100, 1700],
  },
  month: {
    labels: ["Week 1", "Week 2", "Week 3", "Week 4"],
    data: [5200, 6100, 5800, 6900],
  },
};

const ovSalesChart = new Chart(ovSalesCtx, {
  type: "line",
  data: {
    labels: ovSalesData.week.labels,
    datasets: [
      {
        label: "₱ Sales",
        data: ovSalesData.week.data,
        borderColor: "#3b82f6",
        backgroundColor: "rgba(59, 130, 246, 0.1)",
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

document
  .getElementById("ovSalesFilter")
  .addEventListener("change", function () {
    const value = this.value;
    ovSalesChart.data.labels = ovSalesData[value].labels;
    ovSalesChart.data.datasets[0].data = ovSalesData[value].data;
    ovSalesChart.update();
  });
