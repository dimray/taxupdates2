document.addEventListener("DOMContentLoaded", () => {
  let index = 1;
  const clientRowsContainer = document.getElementById("client-rows-container");

  // Use a single event listener on the container
  clientRowsContainer.addEventListener("click", (event) => {
    const target = event.target;

    if (target.classList.contains("add-row-btn")) {
      event.preventDefault();
      addNewRow();
    } else if (target.classList.contains("remove-row-btn")) {
      event.preventDefault();
      // Use the closest method to get the row to remove
      const rowToRemove = target.closest(".client-row");
      removeRow(rowToRemove);
    }
  });

  function addNewRow() {
    // Before adding the new row, clear buttons from the current last row
    const lastRow = clientRowsContainer.lastElementChild;
    const lastRowActions = lastRow.querySelector(".row-actions");
    lastRowActions.innerHTML = ""; // This is the key line

    // Create the new row HTML
    const newRow = document.createElement("div");
    newRow.classList.add("client-row");
    newRow.innerHTML = `        
        <div class="client-inputs">
            <div class="form-input">
                <label for="name_${index}">Name</label>
                <input type="text" name="clients[${index}][name]" id="name_${index}">
            </div>
            <div class="form-input">
                <label for="nino_${index}">NI Number</label>
                <input type="text" name="clients[${index}][nino]" id="nino_${index}">
            </div>
        </div>
        <div class="row-actions">
            <button type="button" class="add-row-btn">+</button>
            <button type="button" class="remove-row-btn">-</button>
        </div>
    `;

    clientRowsContainer.appendChild(newRow);

    const nameInput = newRow.querySelector(`#name_${index}`);
    if (nameInput) nameInput.focus();

    index++;
  }

  function removeRow(row) {
    // We must not remove the last remaining row
    if (clientRowsContainer.children.length <= 1) {
      return;
    }

    row.remove();

    // After removing a row, update the buttons on the new last row
    const newLastRow = clientRowsContainer.lastElementChild;
    const rowActions = newLastRow.querySelector(".row-actions");

    // Clear any existing buttons and add the correct ones
    rowActions.innerHTML = "";

    const addBtn = document.createElement("button");
    addBtn.type = "button";
    addBtn.classList.add("add-row-btn");
    addBtn.textContent = "+";
    rowActions.appendChild(addBtn);

    // Only add a remove button if there are more than 1 rows
    if (clientRowsContainer.children.length > 1) {
      const removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.classList.add("remove-row-btn");
      removeBtn.textContent = "-";
      rowActions.appendChild(removeBtn);
    }
  }
});
