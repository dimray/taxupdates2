const menuToggle = document.querySelector(".menu-toggle");
const primaryNavigation = document.querySelector(".primary-navigation");

function openMenu() {
  menuToggle.setAttribute("aria-expanded", "true");
  primaryNavigation.setAttribute("data-state", "opening");
  document.body.classList.add("no-scroll");

  // Force reflow so the browser applies the "opening" state
  primaryNavigation.offsetWidth;

  requestAnimationFrame(() => {
    primaryNavigation.setAttribute("data-state", "opened");
  });
}

function closeMenu() {
  menuToggle.setAttribute("aria-expanded", "false");
  primaryNavigation.setAttribute("data-state", "closing");

  const onTransitionEnd = (e) => {
    if (e.propertyName === "clip-path") {
      primaryNavigation.setAttribute("data-state", "closed");
      document.body.classList.remove("no-scroll");
      primaryNavigation.removeEventListener("transitionend", onTransitionEnd);
    }
  };

  primaryNavigation.addEventListener("transitionend", onTransitionEnd);
}

function handleClick() {
  const isOpen = menuToggle.getAttribute("aria-expanded") === "true";
  if (isOpen) return closeMenu();
  return openMenu();
}

menuToggle.addEventListener("click", handleClick);
