document.querySelector(".generic-form").addEventListener("submit", function (e) {
  const button = this.querySelector('button[type="submit"]');
  button.disabled = true;
  button.textContent = "Sending...";
});
