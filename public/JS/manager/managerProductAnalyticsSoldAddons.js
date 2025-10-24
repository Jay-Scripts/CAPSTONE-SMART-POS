let soldAddOnsChart;

async function updateSoldAddOnsChart() {
  const canvas = document.getElementById("soldAddOnsChart");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  try {
    const res = await fetch(
      "../../app/includes/managerModule/managerProductAnalitycsAddons.php"
    );
    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
    const data = await res.json();

    const labels = data.map((item) => item.add_ons_name);
    const salesData = data.map((item) => parseInt(item.total_sold));
    const total = salesData.reduce((a, b) => a + b, 0);

    if (!soldAddOnsChart) {
      soldAddOnsChart = new Chart(ctx, {
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
                "#84CC16",
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
                  return `${context.label}: ${value} (${percentage}%)`;
                },
              },
            },
          },
        },
      });
    } else {
      soldAddOnsChart.data.labels = labels;
      soldAddOnsChart.data.datasets[0].data = salesData;
      soldAddOnsChart.update();
    }
  } catch (err) {
    console.error("Error updating sold add-ons chart:", err);
  }
}

// Update every second
setInterval(updateSoldAddOnsChart, 1000);
