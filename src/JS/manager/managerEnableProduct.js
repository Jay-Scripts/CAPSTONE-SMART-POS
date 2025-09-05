function showModuleEnableProduct(moduleId) {
  const enableModules = [
    "enableMilktea",
    "enableFruittea",
    "enableHotbrew",
    "enablePraf",
    "enableIcedCoffee",
    "enablePromos",
    "enableAddOns",
    "enableModify",
    "enableBrosty",
  ];

  // Hide all sections
  enableModules.forEach((mod) => {
    document.getElementById(mod).classList.add("hidden");
  });

  // Show the selected section
  document.getElementById(moduleId).classList.remove("hidden");
}
