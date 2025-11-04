document.addEventListener("DOMContentLoaded", () => {
  const loader = document.getElementById("loader");

  function hideLoader() {
    loader.classList.add("display-none");
    loader.classList.remove("display-flex");
  }

  function showLoader() {
    loader.classList.remove("display-none");
    loader.classList.add("display-flex");
  }

  // Always hide loader on normal page load
  hideLoader();

  // Hide loader if page is restored from back/forward cache
  window.addEventListener("pageshow", (event) => {
    if (event.persisted) {
      hideLoader();
    }
  });

  // Only show loader for links with class 'hmrc-connection'
  document.querySelectorAll("a.hmrc-connection[href]").forEach((link) => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");
      if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;
      showLoader();
    });
  });

  // Only show loader for forms with class 'hmrc-connection'
  document.querySelectorAll("form.hmrc-connection").forEach((form) => {
    form.addEventListener("submit", () => showLoader());
  });
});
