document.addEventListener("DOMContentLoaded", function () {
  const claimedSelect = document.getElementById("rentARoomClaimed");
  const jointlyContainer = document.getElementById("jointlyLetContainer");

  // Do nothing if the elements aren't on the page
  if (!claimedSelect || !jointlyContainer) {
    return;
  }

  function toggleJointlyVisibility() {
    const isClaimed = claimedSelect.value === "true";

    if (isClaimed) {
      jointlyContainer.classList.remove("hidden");
    } else {
      jointlyContainer.classList.add("hidden");
    }
  }

  claimedSelect.addEventListener("change", toggleJointlyVisibility);
  toggleJointlyVisibility();
});
