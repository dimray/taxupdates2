document.addEventListener("DOMContentLoaded", () => {
  const loader = document.getElementById("loader");
  // Variable to hold the ID of the timeout function
  let loaderTimeout;
  const DELAY_MS = 1000; // 1 second delay

  function hideLoader() {
    // Clear any pending timeout when the page is loaded/restored
    if (loaderTimeout) {
      clearTimeout(loaderTimeout);
      loaderTimeout = null;
    }
    loader.classList.add("loader-hidden");
    loader.classList.remove("loader-visible");
  }

  function showLoader() {
    loaderTimeout = setTimeout(() => {
      // 1. Ensure display: flex is applied (required for its internal layout)
      loader.classList.add("loader-visible");
      // 2. The transition takes care of the opacity/visibility change
      loader.classList.remove("loader-hidden");
    }, DELAY_MS);
  }

  // --- Initial/Restoration Logic ---

  // Always call hideLoader on normal page load.
  // This clears any lingering timeout from a previous fast transition if somehow one wasn't cleared.
  hideLoader();

  // Hide loader if page is restored from back/forward cache
  window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
      hideLoader();
    }
  });

  // --- Event Listener Logic (Amended) ---

  // Only show loader for links with class 'hmrc-connection'
  document.querySelectorAll("a.hmrc-connection[href]").forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;

      // Start the 1-second delay timer
      showLoader();
    });
  });

  // Only show loader for forms with class 'hmrc-connection'
  document.querySelectorAll("form.hmrc-connection").forEach((form) => {
    form.addEventListener("submit", () => {
      // Start the 1-second delay timer
      showLoader();
    });
  });
});
