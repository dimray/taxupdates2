document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("zero-adjustments-toggle");

  if (!toggle) return;

  toggle.addEventListener("change", function () {
    const inputs = document.querySelectorAll("#zero-adjustments-form input[type='number']");
    const selects = document.querySelectorAll("#zero-adjustments-form select");

    if (toggle.checked) {
      inputs.forEach((input) => {
        input.disabled = true;
        input.value = "";
      });

      selects.forEach((select) => {
        select.disabled = true;
        select.selectedIndex = 0; // reset to first option
      });
    } else {
      inputs.forEach((input) => {
        input.disabled = false;
      });

      selects.forEach((select) => {
        select.disabled = false;
      });
    }
  });
});
