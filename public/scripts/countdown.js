document.addEventListener("DOMContentLoaded", () => {
  const countdownEl = document.getElementById("countdown");

  const button = document.getElementById("countdown-button");

  if (!countdownEl) return;
  if (!button) return;

  let secondsRemaining = parseInt(countdownEl.dataset.start, 10);
  console.log(secondsRemaining);

  if (button) {
    button.disabled = true;
  }

  const interval = setInterval(() => {
    secondsRemaining = secondsRemaining - 1;
    countdownEl.textContent = secondsRemaining;

    if (secondsRemaining <= 0) {
      clearInterval(interval);
      document.getElementById("countdown-msg").classList.add("hidden");
      button.disabled = false;
    }
  }, 1000);
});
