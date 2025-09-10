const menuToggle = document.querySelector(".menu-toggle");
const primaryNavigation = document.querySelector(".primary-navigation");

function openMenu() {
  menuToggle.setAttribute("aria-expanded", "true");
  primaryNavigation.setAttribute("data-state", "opened");
  document.body.classList.add("no-scroll");
}

function closeMenu() {
  menuToggle.setAttribute("aria-expanded", "false");
  primaryNavigation.setAttribute("data-state", "closing");

  primaryNavigation.addEventListener(
    "animationend",
    () => {
      primaryNavigation.setAttribute("data-state", "closed");
      document.body.classList.remove("no-scroll");
    },
    {
      once: true,
    }
  );
}

function handleClick() {
  const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
  if (isOpen) return closeMenu();
  return openMenu();
}

menuToggle.addEventListener("click", handleClick);
