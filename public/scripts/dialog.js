document.addEventListener("DOMContentLoaded", function () {
  // register the polyfill on old browsers
  const dialog = document.querySelector("dialog");
  if (!dialog.showModal) {
    dialogPolyfill.registerDialog(dialog);
  }

  document.querySelectorAll("button.open-dialog").forEach((button) => {
    button.addEventListener("click", () => {
      const dialog = button.previousElementSibling;
      if (dialog && dialog.tagName === "DIALOG") {
        dialog.showModal();
      }
    });
  });

  document.querySelectorAll("button.close-dialog").forEach((button) => {
    button.addEventListener("click", () => {
      const dialog = button.closest("dialog");
      if (dialog) {
        dialog.close();
      }
    });
  });

  // Optional: close dialog when clicking outside or pressing ESC
  document.querySelectorAll("dialog").forEach((dialog) => {
    dialog.addEventListener("click", (e) => {
      const rect = dialog.getBoundingClientRect();
      if (e.clientX < rect.left || e.clientX > rect.right || e.clientY < rect.top || e.clientY > rect.bottom) {
        dialog.close();
      }
    });
  });
});
