document.addEventListener("DOMContentLoaded", () => {
  const themeToggle = document.getElementById("light-dark");
  const body = document.body;
  const localStorageKey = "themePreference";

  // --- 1. Apply saved theme on load ---
  const savedTheme = localStorage.getItem(localStorageKey);
  if (savedTheme === "dark") {
    body.classList.add("dark-theme");
  }

  // --- 2. Handle click event ---
  themeToggle.addEventListener("click", () => {
    // Toggle the 'dark-theme' class on the body
    body.classList.toggle("dark-theme");

    // Save the new preference
    if (body.classList.contains("dark-theme")) {
      localStorage.setItem(localStorageKey, "dark");
    } else {
      localStorage.setItem(localStorageKey, "light");
    }
  });
});
