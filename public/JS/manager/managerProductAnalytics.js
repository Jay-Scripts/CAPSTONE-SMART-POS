let soldProductByCategoryChart;

async function updateSoldProductByCategoryChart() {
  const canvas = document.getElementById("soldProductByCategoryChart");
  if (!canvas) return; // chart not yet in DOM
  const ctx = canvas.getContext("2d");

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerProductAnalytics.php"
    );
    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
    const data = await res.json();

    const labels = data.map((item) => item.category_name);
    const salesData = data.map((item) => parseInt(item.total_sold));
    const total = salesData.reduce((a, b) => a + b, 0);

    if (!soldProductByCategoryChart) {
      // Create chart for the first time
      soldProductByCategoryChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Sales",
              data: salesData,
              borderWidth: 1,
              backgroundColor: [
                "#6366F1",
                "#F59E0B",
                "#10B981",
                "#EF4444",
                "#3B82F6",
                "#8B5CF6",
              ],
            },
          ],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { position: "top" },
            tooltip: {
              callbacks: {
                label: function (context) {
                  const value = context.raw;
                  const percentage = total
                    ? ((value / total) * 100).toFixed(1)
                    : 0;
                  return `${context.label}: ${value.toLocaleString()} (${percentage}%)`;
                },
              },
            },
          },
        },
      });
    } else {
      // Update chart data
      soldProductByCategoryChart.data.labels = labels;
      soldProductByCategoryChart.data.datasets[0].data = salesData;
      soldProductByCategoryChart.update();
    }
  } catch (err) {
    console.error("Error updating sold product by category chart:", err);
  }
}

// Update every second (or choose a longer interval if needed)
setInterval(updateSoldProductByCategoryChart, 1000);
