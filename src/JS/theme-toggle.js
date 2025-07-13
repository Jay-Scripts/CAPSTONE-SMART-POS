const storageKey = "theme-preference";

const onClick = () => {
  // Flip current theme value
  theme.value = theme.value === "light" ? "dark" : "light";
  setPreference();
};

const getColorPreference = () => {
  if (localStorage.getItem(storageKey)) {
    return localStorage.getItem(storageKey);
  } else {
    return window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  }
};

const setPreference = () => {
  localStorage.setItem(storageKey, theme.value);
  reflectPreference();
};

const reflectPreference = () => {
  document.documentElement.setAttribute("data-theme", theme.value);

  const toggleBtn = document.querySelector("#theme-toggle");
  if (toggleBtn) {
    toggleBtn.setAttribute("aria-label", theme.value);
  }
};

const theme = {
  value: getColorPreference(),
};

// Reflect theme preference early to avoid FOUC (Flash of Unstyled Content)
reflectPreference();

// Use addEventListener instead of window.onload
window.addEventListener("load", () => {
  reflectPreference();

  const toggle = document.querySelector("#theme-toggle");
  if (toggle) {
    toggle.addEventListener("click", onClick);
  }
});

// Sync with system theme changes
window
  .matchMedia("(prefers-color-scheme: dark)")
  .addEventListener("change", ({ matches: isDark }) => {
    theme.value = isDark ? "dark" : "light";
    setPreference();
  });
