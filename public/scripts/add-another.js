document.addEventListener("DOMContentLoaded", function () {
  function assignNamesOnLoad() {
    document.querySelectorAll("[data-group]").forEach((groupWrapper) => {
      const groupName = groupWrapper.getAttribute("data-group");
      const container = groupWrapper.parentElement;
      const siblings = container.querySelectorAll(`[data-group="${groupName}"]`);
      const index = Array.from(siblings).indexOf(groupWrapper);

      groupWrapper.querySelectorAll("[data-name]").forEach((input) => {
        const field = input.getAttribute("data-name");
        input.name = convertFieldToName(field, groupName, index);
      });
    });
  }

  function convertFieldToName(field, groupName, index) {
    const parts = field.split(".");
    let name = `${groupName}[${index}][${parts[0]}]`;
    for (let i = 1; i < parts.length; i++) {
      name += `[${parts[i]}]`;
    }
    return name;
  }

  function addGroup(containerId, groupClass, groupName, buttonLabel) {
    const container = document.getElementById(containerId);
    const groups = container.querySelectorAll(`.${groupClass}`);
    const lastGroup = groups[groups.length - 1];
    const clone = lastGroup.cloneNode(true);
    const currentIndex = groups.length;

    // Clear all fields
    clone.querySelectorAll("input, select").forEach((input) => {
      if (input.type === "checkbox") input.checked = false;
      else input.value = "";
      const field = input.getAttribute("data-name");
      if (field) input.name = convertFieldToName(field, groupName, currentIndex);
    });

    // Remove old buttons
    clone.querySelectorAll(".remove-group, .add-group").forEach((btn) => btn.remove());

    container.appendChild(clone);

    // Reassign indexes
    reassignGroupNames(containerId, groupClass, groupName);
    showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel);
  }

  function reassignGroupNames(containerId, groupClass, groupName) {
    const container = document.getElementById(containerId);
    const groups = container.querySelectorAll(`.${groupClass}`);
    groups.forEach((group, index) => {
      group.querySelectorAll("[data-name]").forEach((input) => {
        const field = input.getAttribute("data-name");
        input.name = convertFieldToName(field, groupName, index);
      });
    });
  }

  function showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel) {
    const container = document.getElementById(containerId);
    const groups = container.querySelectorAll(`.${groupClass}`);

    groups.forEach((group, index) => {
      group.querySelectorAll(".remove-group, .add-group").forEach((btn) => btn.remove());

      if (index === groups.length - 1) {
        const addBtn = document.createElement("button");
        addBtn.type = "button";
        addBtn.textContent = buttonLabel;
        addBtn.className = "add-group";
        addBtn.addEventListener("click", () => {
          addGroup(containerId, groupClass, groupName, buttonLabel);
        });
        group.appendChild(addBtn);

        if (groups.length > 1) {
          const removeBtn = document.createElement("button");
          removeBtn.type = "button";
          removeBtn.textContent = "Remove";
          removeBtn.className = "remove-group";
          removeBtn.addEventListener("click", () => {
            group.remove();
            reassignGroupNames(containerId, groupClass, groupName);
            showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel);
          });
          group.appendChild(removeBtn);
        }
      }
    });
  }

  function setupInitialButton(containerId, groupClass, groupName, buttonLabel) {
    showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel);
  }

  function setupIfExists(containerId, groupClass, groupName, buttonLabel) {
    const container = document.getElementById(containerId);
    if (container) {
      setupInitialButton(containerId, groupClass, groupName, buttonLabel);
    }
  }

  // Assign names on load
  assignNamesOnLoad();

  // Setup for each view using function
  setupIfExists("foreign-property-annual-submission-container", "foreign-property-annual-submission-group", "foreignPropertyAnnualSubmission", "Add Another Country");
});
