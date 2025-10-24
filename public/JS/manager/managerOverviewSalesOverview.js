async function loadWeeklySalesChart() {
  const ctx = document.getElementById("ovSalesChart").getContext("2d");

  try {
    const response = await fetch(
      "../../app/includes/managerModule/managerOverviewSalesOverview.php"
    ); // your PHP script
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
// Load chart
loadWeeklySalesChart();
