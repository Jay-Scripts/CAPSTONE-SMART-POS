function showModule(moduleId) {
  const modules = [
    "overview",
    "salesReports",
    "performanceTrend",
    "refund",
    "registerStaff",
    "modifyPosition",
    "modifyStatus",
    "stockEntry",
    "stockLevel",
    "lowStockAlerts",
    "stocksMovementHistory",
    "logWaste",
    "disableProduct",
    "enableProduct",
    "productMovementHistory",
    "satisfactionDashboard",
    "complaintsManagement",
    "rewards&LoyaltyProgram",
    "discountDashboard",
  ];

  modules.forEach((module) => {
    const el = document.getElementById(module);
    if (el) el.classList.add("hidden");
  });

  const activeModule = document.getElementById(moduleId);
  if (activeModule) activeModule.classList.remove("hidden");
}

// ==========================================
// =       ACTIVE CLASS SIDEBAR STARTS HERE =
// ==========================================
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

// ========================================================
// PROMOS  SalesReports POLARCHART STARTS HERE
// ========================================================

// ========================================================
//  Sales Reports CHART ENDS HERE
// ========================================================
