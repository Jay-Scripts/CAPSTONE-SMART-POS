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
  const activeModule = localStorage.getItem("activeModule") || "overview";
  showModule(activeModule);

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

      // Hide current active module
      document.querySelector(".module:not(.hidden)")?.classList.add("hidden");

      // Show new one
      showModule(module);

      localStorage.setItem("activeModule", module);

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
