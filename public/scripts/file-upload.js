const fileInput = document.getElementById("csv_upload");
const fileNameSpan = document.getElementById("csv_filename");

fileInput.addEventListener("change", () => {
  if (fileInput.files.length > 0) {
    fileNameSpan.textContent = fileInput.files[0].name;
  } else {
    fileNameSpan.textContent = "";
  }
});
