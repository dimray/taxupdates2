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

    // Remove old buttons and dividers
    clone.querySelectorAll(".remove-group, .add-group, .group-divider").forEach((el) => el.remove());

    container.appendChild(clone);

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
    if (!container) return;
    const groups = container.querySelectorAll(`.${groupClass}`);

    groups.forEach((group, index) => {
      // Remove any leftover buttons or dividers
      group.querySelectorAll(".remove-group, .add-group, .group-divider").forEach((el) => el.remove());

      // Add buttons only on the last group
      if (index === groups.length - 1) {
        const addBtn = document.createElement("button");
        addBtn.type = "button";
        addBtn.textContent = buttonLabel;
        addBtn.className = "add-group";
        group.appendChild(addBtn);

        if (groups.length > 1) {
          const removeBtn = document.createElement("button");
          removeBtn.type = "button";
          removeBtn.textContent = "Remove";
          removeBtn.className = "remove-group";
          group.appendChild(removeBtn);
        }
      }

      // Divider (each group gets exactly one)
      const divider = document.createElement("hr");
      divider.className = "group-divider";
      group.appendChild(divider);
    });
  }

  // One listener handles both "Add" and "Remove"
  function attachDelegatedHandlers(containerId, groupClass, groupName, buttonLabel) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.addEventListener("click", (e) => {
      if (e.target.matches(".add-group")) {
        e.preventDefault();
        addGroup(containerId, groupClass, groupName, buttonLabel);
      }

      if (e.target.matches(".remove-group")) {
        e.preventDefault();
        const groupToRemove = e.target.closest(`.${groupClass}`);
        if (!groupToRemove) return;
        groupToRemove.remove();
        reassignGroupNames(containerId, groupClass, groupName);
        showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel);
      }
    });
  }

  // Assign names on load
  assignNamesOnLoad();

  // Set up all dynamic containers
  const setups = [
    // employment income
    ["share-options-container", "share-options-group", "shareOption", "Add Another"],
    ["share-award-container", "share-award-group", "sharesAwardedOrReceived", "Add Another"],
    ["lump-sum-container", "lump-sum-group", "lumpSums", "Add Another"],
    // dividend income
    ["foreign-dividend-container", "foreign-dividend-group", "foreignDividend", "Add Another"],
    ["dividend-income-received-whilst-abroad-container", "dividend-income-received-whilst-abroad-group", "dividendIncomeReceivedWhilstAbroad", "Add Another"],
    // foreign income
    ["unremittable-foreign-income-container", "unremittable-foreign-income-group", "unremittableForeignIncome", "Add Another"],
    // insurance income
    ["life-insurance-container", "life-insurance-group", "lifeInsurance", "Add Another"],
    ["capital-redemption-container", "capital-redemption-group", "capitalRedemption", "Add Another"],
    ["life-annuity-container", "life-annuity-group", "lifeAnnuity", "Add Another"],
    ["voided-isa-container", "voided-isa-group", "voidedIsa", "Add Another"],
    ["foreign-container", "foreign-group", "foreign", "Add Another"],
    // pension income
    ["foreign-pensions-container", "foreign-pensions-group", "foreignPensions", "Add Another"],
    ["overseas-pension-contributions-container", "overseas-pension-contributions-group", "overseasPensionContributions", "Add Another"],
    // other income
    ["post-cessation-receipts-container", "post-cessation-receipts-group", "postCessationReceipts", "Add Another"],
    ["business-receipts-container", "business-receipts-group", "businessReceipts", "Add Another"],
    ["all-other-income-received-whilst-abroad-container", "all-other-income-received-whilst-abroad-group", "allOtherIncomeReceivedWhilstAbroad", "Add Another"],
  ];

  setups.forEach(([containerId, groupClass, groupName, buttonLabel]) => {
    const container = document.getElementById(containerId);
    if (container) {
      attachDelegatedHandlers(containerId, groupClass, groupName, buttonLabel);
      showButtonsOnlyOnLast(containerId, groupClass, groupName, buttonLabel);
    }
  });
});
