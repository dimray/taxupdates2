document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("#collect-device-data");

  form.addEventListener("submit", async function (e) {
    e.preventDefault();
    collectData();
    form.submit();
  });

  function hashEmail(email) {
    let hash = 5381;
    for (let i = 0; i < email.length; i++) {
      hash = (hash << 5) + hash + email.charCodeAt(i); // hash * 33 + c
    }
    return hash >>> 0; // force unsigned
  }

  function getOrCreateDeviceID(email) {
    try {
      const emailHash = hashEmail(email);
      const key = `taxupdates_device_id_${emailHash}`;
      let id = localStorage.getItem(key);

      if (!id) {
        id =
          crypto.randomUUID?.() ||
          "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
            const r = (Math.random() * 16) | 0;
            const v = c === "x" ? r : (r & 0x3) | 0x8;
            return v.toString(16);
          });

        localStorage.setItem(key, id);
      }

      return id;
    } catch (e) {
      // Fallback if localStorage is unavailable
      return (
        crypto.randomUUID?.() ||
        "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (c) {
          const r = (Math.random() * 16) | 0;
          const v = c === "x" ? r : (r & 0x3) | 0x8;
          return v.toString(16);
        })
      );
    }
  }

  function getTimezoneOffset() {
    const offsetMinutes = new Date().getTimezoneOffset();
    const hours = String(Math.abs(Math.floor(offsetMinutes / 60))).padStart(2, "0");
    const minutes = String(Math.abs(offsetMinutes % 60)).padStart(2, "0");
    const sign = offsetMinutes > 0 ? "-" : "+";

    return `UTC${sign}${hours}:${minutes}`;
  }

  function collectData() {
    const emailInput = document.querySelector("#email");
    const email = emailInput?.value.trim().toLowerCase() || "unknown";

    const deviceID = getOrCreateDeviceID(email);

    const timezone = getTimezoneOffset();

    const data = {
      deviceID: deviceID,
      screenWidth: window.screen?.width ?? null,
      screenHeight: window.screen?.height ?? null,
      colorDepth: window.screen?.colorDepth ?? null,
      scalingFactor: window.devicePixelRatio || 1,
      windowWidth: window.innerWidth ?? null,
      windowHeight: window.innerHeight ?? null,
      userAgent: navigator.userAgent ?? "",
      timezone: timezone,
    };

    document.getElementById("device_data").value = JSON.stringify(data);
  }
});
