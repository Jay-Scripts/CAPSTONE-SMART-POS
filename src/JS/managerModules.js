function showModule(moduleId) {
  const modules = [
    // dashboards module
    // overview module nato for overview analytics reports ng mga total transactions total profit total sold items etc.
    // free suggestions if may maisip kapa
    "overview",

    // sales management
    // sales report dito naman more on average ng data na napasok sa database dito naten ifefetch
    "salesReports",
    // performance trend dito naman yung graph or charts ng sales per day, week, month, and year
    "performanceTrend",
    // refunds dito naten rerefund yung mga transactions  may form to preformat ka ng design as of now kase sa form mang gagaling yung data na ipupull naten sa database para marefund yung transactions
    "refund",

    // staff management
    // adding staffs dito naman form rin for account creation
    "registerStaff",
    // modify position same rin pre designded form para pasukan ng data
    "modifyPosition",

    // inventory management
    // stock entry predesign form ng insertion of stock
    "stockEntry",
    // dito naman sa stock levels more on graphs to and reports like line chart bat chart or pie charts analytics
    "stockLevel",
    // low stocks history lalabas lang rito charts for analytics views ng mga critical stocks
    "lowStockAlerts",
    // movement hist. dito naman analytics view lang ng galaw ng stocks
    "stocksMovementHistory",

    // product management
    // log waste pre design form para pag pasukan ng data ng product na iwawaste
    "logWaste",
    // disable waste same lang rin dito predesign form na paglalagyan ng data ng item na ididisable much better if kaya mo drop down or choice mo basta best sa UX
    "disableItem",
    // movement hist. dito naman analytics view lang ng galaw ng product
    "productMovementHistory",

    //customer management
    // satisfaction dashboard dito naman more on overview ng feedbacks like chat box graphs pie charts etc.
    "satisfactionDashboard",
    // complaints management eto mga low rate feedbacks lalabas dito then may viewing rin ng contacts ng  cust. para magawan ng solution
    "complaintsManagement",
    // rewards & loyalty program dito naman yung analytics view ng mga registered customer na may rewarding card or app
    "rewards&LoyaltyProgram",
    // discount dashboard dito naman lalabas yung mga senoirs and PWD transactions
    "discountDashboard",
  ];

  modules.forEach((module) => {
    const el = document.getElementById(module);
    if (el) el.classList.add("hidden");
  });

  const activeModule = document.getElementById(moduleId);
  if (activeModule) activeModule.classList.remove("hidden");
}

// ===================================================
//        ACTIVE CLASS SIDEBAR STARTS HERE
//===================================================
window.addEventListener("DOMContentLoaded", () => {
  const navItems = document.querySelectorAll(".navItem");

  // Get ang last active module from localStorage then fallback to 'overview'
  const activeModule = localStorage.getItem("activeModule") || "overview";
  showModule(activeModule);

  // Remove active class from all, then add to stored one
  navItems.forEach((el) => {
    el.classList.remove(
      "bg-[var(--background-color)]",
      "text-[var(--text-color)]"
    );
    if (el.dataset.module === activeModule) {
      el.classList.add(
        "bg-[var(--background-color)]",
        "text-[var(--text-color)]"
      );
    }
  });
  navItems.forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      const module = item.dataset.module;
      showModule(module);

      // Store to localStorage
      localStorage.setItem("activeModule", module);

      // Update active class
      navItems.forEach((el) =>
        el.classList.remove(
          "bg-[var(--background-color)]",
          "text-[var(--text-color)]"
        )
      );
      item.classList.add(
        "bg-[var(--background-color)]",
        "text-[var(--text-color)]"
      );
    });
  });
});
// ===================================================
//        ACTIVE CLASS SIDEBAR ENDS HERE
//===================================================

// ========================================================
//  OVERVIEW CHART STARTS HERE
// ========================================================

// ========================================================
// SALES OVERVIEW CHART STARTS HERE
// ========================================================
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
// ========================================================
// SALES OVERVIEW CHART ENDS HERE
// ========================================================

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
// DASHBOARD HEADER OVERVIEW REALTIME DATA DUMMY
// UPDATES (AUTO-REFRESH) STARTS HERE
// ========================================================
setInterval(() => {
  const newSales = Math.floor(Math.random() * 1000 + 45000);
  const newProfit = Math.floor(newSales * 0.18);
  const newSold = Math.floor(Math.random() * 100 + 300);

  document.getElementById("salesAmount").textContent =
    "₱" + newSales.toLocaleString();
  document.getElementById("profitAmount").textContent =
    "₱" + newProfit.toLocaleString();
  document.getElementById("itemsSold").textContent = newSold;
}, 5000);
// ========================================================
// DASHBOARD HEADER OVERVIEW REALTIME DATA DUMMY UPDATES ENDS HERE
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
// ========================================================
// PAYMENT METHOD OVERVIEW CHART ENDS HERE
// ========================================================

// ========================================================
//  OVERVIEW CHART ENDS HERE
// ========================================================

// ========================================================
//  Sales Report CHART STARTS HERE
// ========================================================

// ========================================================
// MIILKTEA SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srMtCtx = document.getElementById("srMilkteaChart").getContext("2d");
const srMtdata = {
  day: {
    labels: [
      "Winter Melon",
      "Taro",
      "Strawberry",
      "Salted Caramel",
      "Red Velvet",
      "Matcha",
      "Double Dutch",
      "Dark Chocoalate",
      "Dark Chocolate",
      "Cookies & Cream",
      "Choco Hazelnut",
      "Brown Sugar",
    ],
    data: [200, 120, 139, 239, 80, 79, 56, 300, 321, 500, 320],
  },
  week: {
    labels: [
      "Brown Sugar",
      "Cookies & Cream",
      "Strawberry",
      "Salted Caramel",
      "Taro",
      "Double Dutch",
      "Matcha",
      "Dark Chocoalate",
      "Red Velvet",
      "Choco Hazelnut",
      "Winter Melon",
    ],
    data: [400, 420, 450, 360, 220, 500, 520, 480, 499, 329, 390],
  },

  month: {
    labels: [
      "Strawberry",
      "Matcha",
      "Dark Chocoalate",
      "Cookies & Cream",
      "Salted Caramel",
      "Taro",
      "Red Velvet",
      "Double Dutch",
      "Brown Sugar",
      "Winter Melon",
      "Choco Hazelnut",
    ],
    data: [809, 1000, 1500, 1300, 1256, 1100, 1201, 1550, 1345, 1241, 1100],
  },
};
const srMilkteaChart = new Chart(srMtCtx, {
  type: "bar",
  data: {
    labels: srMtdata.week.labels,
    datasets: [
      {
        label: "Milk Tea Sales",
        data: srMtdata.week.data,
        backgroundColor: [
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
          "#60a5fa",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { display: false },
    },
    scales: {
      y: {
        beginAtZero: true,
      },
    },
    indexAxis: "y", // gawin "x" para vertical bars
  },
});

document
  .getElementById("srMilkteaFilter")
  .addEventListener("change", function () {
    const srValue = this.value;
    srMilkteaChart.data.labels = srMtdata[srValue].labels;
    srMilkteaChart.data.datasets[0].data = srMtdata[srValue].data;
    srMilkteaChart.update();
  });
// ========================================================
// MIILKTEA SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// FRUIT TEA SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srFtCtx = document.getElementById("srFruitteaChart").getContext("2d");
const srFtdata = {
  day: {
    labels: [
      "Green Apple",
      "Kiwi",
      "Lemon",
      "Passion Fruit",
      "Strawberry",
      "Watermelon",
    ],
    data: [200, 120, 139, 239, 80],
  },
  week: {
    labels: [
      "Watermelon",
      "Kiwi",
      "Strawberry",
      "Lemon",
      "Green Apple",
      "Passion Fruit",
    ],
    data: [400, 420, 450, 360, 220, 500],
  },

  month: {
    labels: [
      "Green Apple",
      "Lemon",
      "Watermelon",
      "Passion Fruit",
      "Kiwi",
      "Strawberry",
    ],
    data: [809, 1000, 1500, 1300, 1256, 1100],
  },
};
const srFruitteaChart = new Chart(srFtCtx, {
  type: "bar",
  data: {
    labels: srFtdata.week.labels,
    datasets: [
      {
        label: "Fruit Tea Sales",
        data: srFtdata.week.data,
        backgroundColor: [
          "#065909",
          "#065909",
          "#065909",
          "#065909",
          "#065909",
          "#065909",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    indexAxis: "x",
    maintainAspectRatio: false,
  },
});

document
  .getElementById("srFruitteaFilter")
  .addEventListener("change", function () {
    const srFtValue = this.value;
    srFruitteaChart.data.labels = srFtdata[srFtValue].labels;
    srFruitteaChart.data.datasets[0].data = srFtdata[srFtValue].data;
    srFruitteaChart.update();
  });
// ========================================================
// FRUIT TEA SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// SALES PER CATEGORY SalesReports CHART STARTS HERE
// ========================================================

const srStCtx = document
  .getElementById("srSalesCategoryChart")
  .getContext("2d");
const srStSalesData = [1200, 800, 600, 400, 1000, 700]; // sample data
const total = srStSalesData.reduce((a, b) => a + b, 0);
const srSalesCategoryChart = new Chart(srStCtx, {
  type: "pie",
  data: {
    labels: [
      "Milktea",
      "Praf",
      "Hot Brew",
      "Promos",
      "Iced Coffee",
      "Fruit Tea",
    ],
    datasets: [
      {
        label: "Sales",
        data: srStSalesData, // Dito naten Replace yung real sales data
        borderWidth: 1,
        backgroundColor: [
          "#6366F1", // Milktea
          "#F59E0B", // Praf
          "#10B981", // Hot Brew
          "#EF4444", // Promos
          "#3B82F6", // Iced Coffee
          "#8B5CF6", // Fruit Tea
        ],
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
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
  },
});

// ========================================================
// SALES PER CATEGORY SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// HOT BREW SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srHbCtx = document.getElementById("srHotbrewChart").getContext("2d");
const srHbData = {
  day: {
    labels: [
      "Hot Brusko",
      "Hot Choco",
      "Hot Karamel",
      "Hot Matcha",
      "Hot Moca",
    ],
    data: [200, 120, 139, 239, 80],
  },
  week: {
    labels: [
      "Hot Choco",
      "Hot Moca",
      "Hot Brusko",
      "Hot Matcha",
      "Hot Karamel",
    ],
    data: [400, 420, 450, 360, 220],
  },

  month: {
    labels: [
      "Hot Moca",
      "Hot Choco",
      "Hot Matcha",
      "Hot Karamel",
      "Hot Brusko",
    ],
    data: [809, 1000, 1500, 1300, 1256],
  },
};
const srHotbrewChart = new Chart(srHbCtx, {
  type: "bar",
  data: {
    labels: srHbData.week.labels,
    datasets: [
      {
        label: "Hot Brew Sales",
        data: srHbData.week.data,
        backgroundColor: [
          "#C2A013",
          "#C2A013",
          "#C2A013",
          "#C2A013",
          "#C2A013",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    indexAxis: "x",
    maintainAspectRatio: false,
  },
});

document
  .getElementById("srHotbrewFilter")
  .addEventListener("change", function () {
    const srHbValue = this.value;
    srHotbrewChartsrata.labels = srHbData[srHbValue].labels;
    srHotbrewChartsrata.datasets[0].data = srHbData[srHbValue].data;
    srHotbrewChartsrpdate();
  });
// ========================================================
// HOT BREW SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// PRAF SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srPCtx = document.getElementById("srPrafChart").getContext("2d");
const srPrData = {
  day: {
    labels: [
      "Caramel Matcch",
      "Chesscake",
      "Choco Cream",
      "Coffee Jelly",
      "Cookies & Cream",
      "Creamy Avocado",
      "Matcha",
      "Melon",
      "Mocha",
      "Strawberry",
      "Vanilla Coffee",
    ],
    data: [200, 120, 139, 239, 80, 90, 89, 130, 130, 78, 110],
  },
  week: {
    labels: [
      "Strawberry",
      "Choco Cream",
      "Chesscake",
      "Cookies & Cream",
      "Coffee Jelly",
      "Melon",
      "Matcha",
      "Creamy Avocado",
      "Caramel Matcch",
      "Mocha",
      "Vanilla Coffee",
    ],
    data: [400, 420, 450, 360, 220, 410, 460, 510, 300, 543, 571],
  },

  month: {
    labels: [
      "Matcha",
      "Chesscake",
      "Cookies & Cream",
      "Creamy Avocado",
      "Strawberry",
      "Choco Cream",
      "Caramel Matcch",
      "Coffee Jelly",
      "Vanilla Coffee",
      "Mocha",
      "Melon",
    ],
    data: [809, 1000, 1500, 1300, 1256, 1532, 1578, 1600, 1620, 1610, 1210],
  },
};
const srPrafChart = new Chart(srPCtx, {
  type: "bar",
  data: {
    labels: srPrData.week.labels,
    datasets: [
      {
        label: "Praf",
        data: srPrData.week.data,
        backgroundColor: [
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
          "#B06913",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    indexAxis: "y",
    maintainAspectRatio: false,
  },
});

document
  .getElementById("srHotbrewFilter")
  .addEventListener("change", function () {
    const srPrValue = this.value;
    srPrafChart.data.labels = srPrData[srPrValue].labels;
    srPrafChart.data.datasets[0].data = srPrData[srPrValue].data;
    srPrafChart.update();
  });
// ========================================================
// PRAF SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// ICED COFFEE SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srIcCtx = document.getElementById("srIcedCoffeeChart").getContext("2d");
const srIcData = {
  day: {
    labels: ["Kape Brusko", "Kape Matcch", "Kape Karamel", "Kape Vanilla"],
    data: [200, 120, 139, 239],
  },
  week: {
    labels: ["Kape Matcch", "Kape Vanilla", "Kape Karamel", "Kape Brusko"],
    data: [400, 420, 450, 360],
  },

  month: {
    labels: ["Kape Vanilla", , "Kape Karamel", "Kape Matcch", "Kape Brusko"],
    data: [809, 1000, 1500, 1300],
  },
};
const srIcedCoffeeChart = new Chart(srIcCtx, {
  type: "bar",
  data: {
    labels: srIcData.week.labels,
    datasets: [
      {
        label: "Iced Coffee",
        data: srIcData.week.data,
        backgroundColor: ["#1FB4C2", "#1FB4C2", "#1FB4C2", "#1FB4C2"],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    indexAxis: "x",
    maintainAspectRatio: false,
  },
});

document
  .getElementById("srIcedCoffeeFilter")
  .addEventListener("change", function () {
    const srIcValue = this.value;
    srIcedCoffeeChart.data.labels = srIcData[srIcValue].labels;
    srIcedCoffeeChart.data.datasets[0].data = srIcData[srIcValue].data;
    srIcedCoffeeChart.update();
  });
// ========================================================
// ICED COFFEE SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// BROSTY SalesReports CHART STARTS HERE
// ========================================================

// Render chart
const srBrostyCtx = document.getElementById("srBrostyChart").getContext("2d");
const srBrostyData = {
  day: {
    labels: [
      "Blue Berry",
      "Green Apple",
      "Honey Peach",
      "Kiwi",
      "Lemon",
      "Lychee",
      "Mango",
      "Passion Fruit",
      "StrawBerry",
      "Watermelon",
      "Passion Fruit",
    ],
    data: [200, 120, 139, 239, 220, 239, 100, 124, 39, 149, 119],
  },
  week: {
    labels: [
      "Mango",
      "Green Apple",
      "Honey Peach",
      "Kiwi",
      "Lemon",
      "Passion Fruit",
      "StrawBerry",
      "Watermelon",
      "Blue Berry",
      "Lychee",
      "Passion Fruit",
    ],
    data: [500, 600, 650, 690, 700, 760, 720, 800, 540, 780, 800],
  },

  month: {
    labels: [
      "Green Apple",
      "Honey Peach",
      "Kiwi",
      "Watermelon",
      "Blue Berry",
      "Lychee",
      "Passion Fruit",
      "Mango",
      "Lemon",
      "Passion Fruit",
      "StrawBerry",
    ],
    data: [2000, 2050, 1900, 2500, 1890, 3500, 3200, 3700, 2100, 1650, 1980],
  },
};
const srBrostyChart = new Chart(srBrostyCtx, {
  type: "bar",
  data: {
    labels: srBrostyData.week.labels,
    datasets: [
      {
        label: "Iced Coffee",
        data: srBrostyData.week.data,
        backgroundColor: [
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
          "#93C5FD",
        ],
        borderRadius: 6,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    indexAxis: "y",
    maintainAspectRatio: false,
  },
});

document
  .getElementById("srBrostyFilter")
  .addEventListener("change", function () {
    const srBrostyValue = this.value;
    srBrostyChart.data.labels = srBrostyData[srBrostyValue].labels;
    srBrostyChart.data.datasets[0].data = srBrostyData[srBrostyValue].data;
    srBrostyChart.update();
  });
// ========================================================
// BROSTY SalesReports CHART ENDS HERE
// ========================================================

// ========================================================
// PROMOS  SalesReports POLARCHART STARTS HERE
// ========================================================

// const ctx = document.getElementById("srPromosChart").getContext("2d");

// new Chart(ctx, {
//   type: "pie",
//   data: {
//     labels: [
//       "5 + 1",
//       "Black Pink",
//       "Boss Brew",
//       "Super Choco",
//       "Kape KMJS",
//       "Kape Van",
//       "Supreme Mocha",
//     ],
//     datasets: [
//       {
//         label: "Promo Sales",
//         data: [120, 80, 60, 40, 70, 200, 142], // sample values
//         backgroundColor: [
//           "#382A04",
//           "#20C0E8",
//           "#AEBCBF",
//           "#107333",
//           "#929E19",
//           "#FFF380",
//           "#12357A",
//         ],
//         borderColor: "white",
//         borderWidth: 2,
//       },
//     ],
//   },
//   options: {
//     responsive: true,
//     plugins: { legend: { display: false } },
//     scales: { y: { beginAtZero: true } },
//     indexAxis: "y",
//     maintainAspectRatio: false,
//   },
// });

const srPromosCtx = document.getElementById("srPromosChart").getContext("2d");
const srPromosSalesData = [1200, 800, 600, 400, 1000, 700]; // sample data
const srPromosTotal = srPromosSalesData.reduce((a, b) => a + b, 0);
const srPromosChart = new Chart(srPromosCtx, {
  type: "pie",
  data: {
    labels: [
      "5 + 1",
      "Black Pink",
      "Boss Brew",
      "Super Choco",
      "Kape KMJS",
      "Kape Van",
      "Supreme Mocha",
    ],
    datasets: [
      {
        label: "Sales",
        data: srPromosSalesData, // Dito naten Replace yung real sales data
        borderWidth: 1,
        backgroundColor: [
          "#6366F1", // 5 + 1
          "#F59E0B", // Black Pink
          "#10B981", // Boss Brew
          "#EF4444", // Super Choco
          "#3B82F6", //  Kape KMJS
          "#8B5CF6", //  Kape Van
          "#84CC16", //  Supreme Mocha
        ],
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: "top",
      },
      tooltip: {
        callbacks: {
          label: function (context) {
            let value = context.raw;
            let percentage = ((value / srPromosTotal) * 100).toFixed(1);
            return `${
              context.label
            }: ₱${value.toLocaleString()} (${percentage}% `;
          },
        },
      },
    },
  },
});

// ========================================================
// PROMOS  SalesReports POLARCHART STARTS HERE
// ========================================================

// ========================================================
//  Sales Reports CHART ENDS HERE
// ========================================================
