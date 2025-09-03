document.addEventListener("DOMContentLoaded", function () {
  const errorElement = document.querySelector(".form-error");
  if (errorElement) {
    errorElement.scrollIntoView({ behavior: "smooth", block: "center" });
  }
});
