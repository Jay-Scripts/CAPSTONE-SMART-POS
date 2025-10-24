let charts = {};

async function updateCategoryChart(
  categoryId,
  canvasId,
  chartLabel,
  indexAxis = "y",
  bgColor = "#60a5fa"
) {
  const ctx = document.getElementById(canvasId).getContext("2d");

  try {
    const res = await fetch(
      `../../app/includes/managerModule/managerProductAnalyticsBarCharts.php?category_id=${categoryId}`
    );
    const data = await res.json();

    const labels = data.map((item) => item.product_name);
    const sales = data.map((item) => parseInt(item.total_sold));

    if (!charts[canvasId]) {
      charts[canvasId] = new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: chartLabel,
              data: sales,
              backgroundColor: labels.map(() => bgColor),
              borderRadius: 6,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          indexAxis: indexAxis,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true } },
        },
      });
    } else {
      charts[canvasId].data.labels = labels;
      charts[canvasId].data.datasets[0].data = sales;
      charts[canvasId].update();
    }
  } catch (err) {
    console.error("Error fetching category sales:", err);
  }
}

// Example usage
setInterval(() => {
  updateCategoryChart(1, "srMilkteaChart", "Milk Tea Sales", "y", "#60a5fa");
  updateCategoryChart(2, "srFruitteaChart", "Fruit Tea Sales", "x", "#065909");
  updateCategoryChart(3, "srHotbrewChart", "Hot Brew Sales", "x", "#C2A013");
  updateCategoryChart(4, "srPrafChart", "Praf Sales", "y", "#B06913");
  updateCategoryChart(5, "srBrostyChart", "Brosty Sales", "y", "#93C5FD");
  updateCategoryChart(
    6,
    "srIcedCoffeeChart",
    "Iced Coffee Sales",
    "x",
    "#1FB4C2"
  );
}, 1000); // Update every 1 sec
