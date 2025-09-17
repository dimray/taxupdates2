document.addEventListener("DOMContentLoaded", function () {
  const toggle = document.getElementById("zero-adjustments-toggle");

  if (!toggle) return;

  toggle.addEventListener("change", function () {
    const inputs = document.querySelectorAll("#zero-adjustments-form input[type='number']");

    if (toggle.checked) {
      inputs.forEach((input) => {
        input.setAttribute("disabled", "disabled");
      });
    } else {
      inputs.forEach((input) => {
        input.removeAttribute("disabled");
      });
    }
  });
});
