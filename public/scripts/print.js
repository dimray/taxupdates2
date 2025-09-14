document.addEventListener("DOMContentLoaded", function () {
  const printButtons = document.querySelectorAll(".print-button");

  printButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Remove previous print areas
      document.querySelectorAll(".print-area").forEach((el) => {
        el.classList.remove("print-area");
      });

      // Find current print container
      const section = button.closest(".print-container");

      if (section) {
        // Create a print-only clone
        const clone = section.cloneNode(true);
        clone.id = "temp-print-area";
        clone.classList.add("print-area");

        // Remove non-print elements from clone
        clone.querySelectorAll(".no-print").forEach((el) => el.remove());

        // Open details element if needed
        if (clone.tagName.toLowerCase() === "details") {
          clone.setAttribute("open", true);
        }

        // Add to document
        document.body.appendChild(clone);

        setTimeout(() => {
          window.print();

          // Remove clone after printing
          document.getElementById("temp-print-area")?.remove();
        }, 100);
      }
    });
  });
});
