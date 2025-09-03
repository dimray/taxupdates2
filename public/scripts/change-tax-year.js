document.addEventListener("DOMContentLoaded", function () {
  const selectElement = document.getElementById("select_tax_year");
  const formElement = document.getElementById("change_tax_year");

  if (selectElement && formElement) {
    selectElement.addEventListener("change", function () {
      formElement.submit();
    });
  }
});
