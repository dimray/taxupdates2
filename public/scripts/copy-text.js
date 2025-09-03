document.addEventListener("DOMContentLoaded", () => {
  const buttons = document.querySelectorAll(".copy-button");
  const copyElements = document.querySelectorAll(".copy-element");

  buttons.forEach((button, index) => {
    const copyElement = copyElements[index];
    button.addEventListener("click", function () {
      copyTextFromElement(copyElement, button);
    });
  });

  function copyTextFromElement(element, button) {
    if (!element) return;

    let text;

    if (element.tagName === "TABLE") {
      // Format table: rows with tab-separated columns
      text = Array.from(element.querySelectorAll("tr"))
        .map((row) =>
          Array.from(row.querySelectorAll("th, td"))
            .map((cell) => cell.textContent.trim())
            .join("\t")
        )
        .join("\n");
    } else {
      // Fallback for normal elements
      text = element.textContent.trim();
    }

    navigator.clipboard
      .writeText(text)
      .then(() => changeButtonText(button, "Copied"))
      .catch((err) => changeButtonText(button, "Did not copy"));
  }

  function changeButtonText(button, message) {
    const originalText = button.textContent;
    button.textContent = message;

    setTimeout(() => {
      button.textContent = originalText;
    }, 1000);
  }
});
