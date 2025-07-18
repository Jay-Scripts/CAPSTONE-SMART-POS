function showModule(moduleId) {
  const modules = [
    // dashboards module
    // overview module nato for overview analytics reports ng mga total transactions total profit total sold items etc.
    // free suggestions if may maisip kapa
    "overview",
    //  real time metrics more on graph
    "realTimeMetrics",

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

window.addEventListener("DOMContentLoaded", () => {
  showModule("overview");

  document.querySelectorAll(".navItem").forEach((item) => {
    item.addEventListener("click", (e) => {
      e.preventDefault();
      const module = item.dataset.module;
      showModule(module);
    });
  });
});
