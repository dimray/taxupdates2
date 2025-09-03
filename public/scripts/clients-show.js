document.addEventListener("DOMContentLoaded", () => {
  const nameInput = document.getElementById("filter_name");
  const ninoInput = document.getElementById("filter_nino");

  if (nameInput && ninoInput) {
    const rows = document.querySelectorAll("tbody tr");

    function filterRows() {
      const nameVal = nameInput.value.toLowerCase();
      const ninoVal = ninoInput.value.toLowerCase();

      rows.forEach((row) => {
        // Find the first and second <td> children (skipping the hidden inputs)
        const nameCell = row.querySelector("td:first-of-type");
        const ninoCell = row.querySelector("td:nth-of-type(2)");

        // Guard against missing cells
        if (!nameCell || !ninoCell) {
          return;
        }

        const name = nameCell.textContent.toLowerCase();
        const nino = ninoCell.textContent.toLowerCase();

        const matchesName = name.includes(nameVal);
        const matchesNino = nino.includes(ninoVal);

        row.style.display = matchesName && matchesNino ? "" : "none";
      });
    }

    nameInput.addEventListener("input", filterRows);
    ninoInput.addEventListener("input", filterRows);
  }
});
