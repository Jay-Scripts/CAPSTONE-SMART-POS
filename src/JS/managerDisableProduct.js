function showModuleDisableProduct(moduleId) {
  const modules = [
    "milktea",
    "fruittea",
    "hotbrew",
    "praf",
    "icedcoffee",
    "promos",
    "addOns",
    "modify",
    "brosty",
  ];

  modules.forEach((module) => {
    document.getElementById(module).classList.add("hidden");
  });
  document.getElementById(moduleId).classList.remove("hidden");
  const selectedLabel = document.querySelector(
    `label[for="${moduleId}_module"]`
  );
}

window.addEventListener("load", () => {
  showModuleDisableProduct("milktea");
});
