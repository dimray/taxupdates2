document.addEventListener("DOMContentLoaded", function () {
  const printButtons = document.querySelectorAll(".print-button");

  printButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      // Find the print container
      const section = button.closest(".print-container");

      if (section) {
        // Add a class to the print container and the body
        document.body.classList.add("is-printing");
        section.classList.add("is-printing-target");

        // Open details elements within the target
        section.querySelectorAll("details").forEach((details) => {
          details.setAttribute("open", true);
        });

        // Call the print function
        window.print();

        // Clean up classes after printing
        // Use setTimeout to ensure cleanup happens after the print dialog is closed
        setTimeout(() => {
          document.body.classList.remove("is-printing");
          section.classList.remove("is-printing-target");
        }, 500); // 500ms delay to be safe
      }
    });
  });
});
