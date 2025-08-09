const storageKey = "theme-preference";

// Get initial theme preference
const theme = {
  value:
    localStorage.getItem(storageKey) ||
    (window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light"),
};

// Apply theme to the <html> tag and update toggle button
function applyTheme() {
  document.documentElement.setAttribute("data-theme", theme.value);

  const toggleBtn = document.querySelector("#theme-toggle");
  if (toggleBtn) {
    toggleBtn.setAttribute("aria-label", theme.value);
  }
}

// Save theme to localStorage and apply it
function saveTheme() {
  localStorage.setItem(storageKey, theme.value);
  applyTheme();
}

// Switch between light and dark mode
function toggleTheme() {
  theme.value = theme.value === "light" ? "dark" : "light";
  saveTheme();
}

// Apply theme immediately on load to avoid flicker
applyTheme();

// Set up toggle button click after page is fully loaded
window.addEventListener("load", () => {
  const toggle = document.querySelector("#theme-toggle");
  if (toggle) {
    toggle.addEventListener("click", toggleTheme);
  }
});

// Listen to system theme changes and sync it
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", (e) => {
    theme.value = e.matches ? "dark" : "light";
    saveTheme();
  });
