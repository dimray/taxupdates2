document.addEventListener("click", (event) => {
  // Select all open <details> elements
  const openDetails = document.querySelectorAll("details[open]");

  // Loop through each open <details> element
  openDetails.forEach((details) => {
    // Check if the clicked element is NOT a descendant of the current <details> element
    // The `contains()` method is perfect for this.
    if (!details.contains(event.target)) {
      // If the click was outside, close the <details> element
      details.removeAttribute("open");
    }
  });
});
