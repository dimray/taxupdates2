const fileInput = document.getElementById("csv_upload");
const fileNameSpan = document.getElementById("csv_filename");
const errorContainer = document.querySelector(".error-messages");

fileInput.addEventListener("change", () => {
  // Update filename display
  if (fileInput.files.length > 0) {
    fileNameSpan.textContent = fileInput.files[0].name;
  } else {
    fileNameSpan.textContent = "";
  }

  // Clear upload errors when a new file is selected
  if (errorContainer) {
    errorContainer.remove();
  }
});
