function showModule(moduleId) {
  const modules = [
    "milktea",
    "fruittea",
    "hotbrew",
    "praf",
    "icedcoffee",
    "promos",
    "addOns",
    "modify",
  ];

  modules.forEach((module) => {
    document.getElementById(`${module}-content`).classList.add("hidden");
  });
  document.getElementById(`${moduleId}-content`).classList.remove("hidden");
  const selectedLabel = document.querySelector(
    `label[for="${moduleId}_module"]`
  );
}

window.addEventListener("load", () => {
  showModule("milktea");
});
