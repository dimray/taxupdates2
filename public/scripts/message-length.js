document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("message").addEventListener("input", updateCounter);

  function updateCounter() {
    let textarea = document.getElementById("message");
    let charCount = document.getElementById("charCount");
    let remaining = 300 - textarea.value.length;
    charCount.textContent = remaining + " characters remaining";
  }

  updateCounter();
});
