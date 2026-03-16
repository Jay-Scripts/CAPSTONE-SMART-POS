// ================================================================
// Chart instances
// ================================================================
let weeklySalesChart = null;
let topProductsChart = null;
let paymentChart = null;
let topCashierChart = null;

function destroyCharts() {
  if (weeklySalesChart) {
    weeklySalesChart.destroy();
    weeklySalesChart = null;
  }
  if (topProductsChart) {
    topProductsChart.destroy();
    topProductsChart = null;
  }
  if (paymentChart) {
    paymentChart.destroy();
    paymentChart = null;
  }
  if (topCashierChart) {
    topCashierChart.destroy();
    topCashierChart = null;
  }
}

// ================================================================
// Period Selector
// ================================================================
const MONTHS = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

function pad(n) {
  return String(n).padStart(2, "0");
}
function daysInMonth(y, m) {
  return new Date(y, m, 0).getDate();
}
function fmtShort(d) {
  return `${pad(d.getMonth() + 1)}/${pad(d.getDate())}`;
}

function populateWeeks(month, year, autoSelect = false) {
  const sel = document.getElementById("weekSel");
  if (!sel) return;
  sel.innerHTML = `<option value="full">Full month — ${MONTHS[month - 1]}</option>`;

  let start = 1,
    weekNum = 1;
  const days = daysInMonth(year, month);

  while (start <= days) {
    const end = Math.min(start + 6, days);
    const sd = new Date(year, month - 1, start);
    const ed = new Date(year, month - 1, end);
    const startISO = `${year}-${pad(month)}-${pad(start)}`;
    const endISO = `${year}-${pad(month)}-${pad(end)}`;

    const opt = document.createElement("option");
    opt.value = `${startISO}|${endISO}`;
    opt.textContent = `Week ${weekNum} · ${fmtShort(sd)} – ${fmtShort(ed)}`;
    sel.appendChild(opt);

    start += 7;
    weekNum++;
  }

  if (autoSelect) {
    const today = new Date().toISOString().split("T")[0];
    for (const opt of sel.options) {
      if (opt.value !== "full") {
        const [s, e] = opt.value.split("|");
        if (today >= s && today <= e) {
          sel.value = opt.value;
          break;
        }
      }
    }
  }

  updateRangeBadge();
}

function updateRangeBadge() {
  const val = document.getElementById("weekSel")?.value;
  const month = parseInt(document.getElementById("monthSel")?.value);
  const year = new Date().getFullYear();
  if (!val || !month) return;

  let text = "";
  if (val === "full") {
    text = `${MONTHS[month - 1]} 1 – ${MONTHS[month - 1]} ${daysInMonth(year, month)}, ${year}`;
  } else {
    const [s, e] = val.split("|");
    const sd = new Date(s + "T00:00:00");
    const ed = new Date(e + "T00:00:00");
    text = `${MONTHS[sd.getMonth()]} ${sd.getDate()} – ${MONTHS[ed.getMonth()]} ${ed.getDate()}, ${year}`;
  }

  const badge = document.getElementById("rangeText");
  if (badge) badge.textContent = text;
}

function getSelectedPeriod() {
  const val = document.getElementById("weekSel")?.value || "full";
  const month = parseInt(
    document.getElementById("monthSel")?.value || new Date().getMonth() + 1,
  );
  const year = new Date().getFullYear();

  if (val === "full") {
    return {
      mode: "month",
      start: `${year}-${pad(month)}-01`,
      end: `${year}-${pad(month)}-${pad(daysInMonth(year, month))}`,
    };
  }

  const [start, end] = val.split("|");
  return { mode: "week", start, end };
}

function onMonthChange() {
  const month = parseInt(document.getElementById("monthSel").value);
  populateWeeks(month, new Date().getFullYear(), false);
  onPeriodChange();
}

function onWeekChange() {
  updateRangeBadge();
  onPeriodChange();
}

function onPeriodChange() {
  destroyCharts();
  fetchKPI();
  updateWeeklySalesChart();
  updateTopProductsChart();
  updatePaymentBreakdownChart();
  updateTopCashierChart();
}

(function initPeriodSelector() {
  const now = new Date();
  const monthSel = document.getElementById("monthSel");
  const weekSel = document.getElementById("weekSel");

  if (monthSel) monthSel.addEventListener("change", onMonthChange);
  if (weekSel) weekSel.addEventListener("change", onWeekChange);

  if (monthSel) {
    monthSel.value = now.getMonth() + 1;
    populateWeeks(now.getMonth() + 1, now.getFullYear(), true);
  }
})();

// ================================================================
// Shared bar color palette — one color per bar index
// ================================================================
const BAR_COLORS = [
  "#3b82f6", // blue
  "#8b5cf6", // purple
  "#10b981", // green
  "#f59e0b", // amber
  "#ef4444", // red
  "#06b6d4", // cyan
  "#f97316", // orange
  "#ec4899", // pink
];

function getBarColors(count) {
  return Array.from(
    { length: count },
    (_, i) => BAR_COLORS[i % BAR_COLORS.length],
  );
}

// ================================================================
// KPI
// ================================================================
async function fetchKPI() {
  try {
    const { mode, start, end } = getSelectedPeriod();
    const res = await fetch(
      `../../app/includes/managerModule/managerKPI.php?mode=${mode}&start=${start}&end=${end}`,
    );
    const data = await res.json();

    if (data.status === "success") {
      document.getElementById("salesAmount").textContent =
        "₱ " + parseFloat(data.total_sales).toFixed(2);
      document.getElementById("transactions").textContent =
        data.total_transactions;
      document.getElementById("itemsSold").textContent =
        data.total_products_sold;
    }
  } catch (err) {
    console.error("Error fetching KPI:", err);
  }
}

// ================================================================
// Sales Overview Chart
// ================================================================
async function updateWeeklySalesChart() {
  const canvas = document.getElementById("ovSalesChart");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  try {
    const { mode, start, end } = getSelectedPeriod();
    const res = await fetch(
      `../../app/includes/managerModule/managerOverviewSalesOverview.php?mode=${mode}&start=${start}&end=${end}`,
    );
    if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
    const data = await res.json();

    let labels, salesData;
    if (mode === "week") {
      labels = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
      salesData = labels.map((day) => data[day] || 0);
    } else {
      labels = data.map((d) => `Day ${d.label}`);
      salesData = data.map((d) => parseFloat(d.total) || 0);
    }

    if (weeklySalesChart) {
      weeklySalesChart.data.labels = labels;
      weeklySalesChart.data.datasets[0].data = salesData;
      weeklySalesChart.update("none");
    } else {
      weeklySalesChart = new Chart(ctx, {
        type: "line",
        data: {
          labels,
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
            y: { beginAtZero: true, ticks: { callback: (v) => "₱" + v } },
          },
        },
      });
    }
  } catch (err) {
    console.error("Error updating sales chart:", err);
  }
}

// ================================================================
// Top Products Chart
// ================================================================
async function updateTopProductsChart() {
  const canvas = document.getElementById("topProductsChart");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  try {
    const { mode, start, end } = getSelectedPeriod();
    const res = await fetch(
      `../../app/includes/managerModule/managerOverviewTopSellingProduct.php?mode=${mode}&start=${start}&end=${end}`,
    );
    const data = await res.json();

    const labels = data.map((item) => item.product_name);
    const values = data.map((item) => parseInt(item.total_sold));
    const colors = getBarColors(values.length);

    if (topProductsChart) {
      topProductsChart.data.labels = labels;
      topProductsChart.data.datasets[0].data = values;
      topProductsChart.data.datasets[0].backgroundColor = colors;
      topProductsChart.update("none");
    } else {
      topProductsChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels,
          datasets: [
            {
              label: "Items Sold",
              data: values,
              backgroundColor: colors,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: { y: { beginAtZero: true } },
        },
      });
    }
  } catch (err) {
    console.error("Error updating top products:", err);
  }
}

// ================================================================
// Payment Breakdown Chart
// ================================================================
async function updatePaymentBreakdownChart() {
  const canvas = document.getElementById("paymentChart");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  try {
    const { mode, start, end } = getSelectedPeriod();
    const res = await fetch(
      `../../app/includes/managerModule/managerOverviewPaymentBreakdown.php?mode=${mode}&start=${start}&end=${end}`,
    );
    const data = await res.json();

    const labels = data.map((p) => p.TYPE);
    const values = data.map((p) => parseFloat(p.total_amount));
    const colors = getBarColors(values.length);

    if (paymentChart) {
      paymentChart.data.labels = labels;
      paymentChart.data.datasets[0].data = values;
      paymentChart.data.datasets[0].backgroundColor = colors;
      paymentChart.update("none");
    } else {
      paymentChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels,
          datasets: [
            {
              data: values,
              backgroundColor: colors,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { position: "bottom" } },
        },
      });
    }
  } catch (err) {
    console.error("Error updating payment breakdown:", err);
  }
}

// ================================================================
// Top Cashier Chart
// ================================================================
async function updateTopCashierChart() {
  const canvas = document.getElementById("topCashierChart");
  if (!canvas) return;
  const ctx = canvas.getContext("2d");

  try {
    const { mode, start, end } = getSelectedPeriod();
    const res = await fetch(
      `../../app/includes/managerModule/managerOverviewTopCashier.php?mode=${mode}&start=${start}&end=${end}`,
    );
    const data = await res.json();

    const labels = data.map((item) => item.staff_name);
    const values = data.map((item) => parseFloat(item.total_sales));
    const colors = getBarColors(values.length);

    if (topCashierChart) {
      topCashierChart.data.labels = labels;
      topCashierChart.data.datasets[0].data = values;
      topCashierChart.data.datasets[0].backgroundColor = colors;
      topCashierChart.update("none");
    } else {
      topCashierChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels,
          datasets: [
            {
              label: "₱ Sales",
              data: values,
              backgroundColor: colors,
            },
          ],
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { callback: (v) => "₱" + v } },
          },
        },
      });
    }
  } catch (err) {
    console.error("Error updating top cashier chart:", err);
  }
}

// ================================================================
// Initial load + polling every 30 seconds (silent — no destroy)
// ================================================================
fetchKPI();
updateWeeklySalesChart();
updateTopProductsChart();
updatePaymentBreakdownChart();
updateTopCashierChart();

setInterval(() => {
  fetchKPI();
  updateWeeklySalesChart();
  updateTopProductsChart();
  updatePaymentBreakdownChart();
  updateTopCashierChart();
}, 30000);
