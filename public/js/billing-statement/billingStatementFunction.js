document.addEventListener("DOMContentLoaded", () => {
    console.log("billingStatementFunctions script loaded");
  
    // Initialize autocomplete for all input fields
    document.querySelectorAll("input").forEach((input) => {
      setupAutocomplete(input);
    });
  
    document
      .getElementById("generateBillingReport")
      .addEventListener("click", async function () {
        updateQueryStatus("Sending Query...", "gray", "lightgray");
  
        let appointmentID = document.getElementById("billingDetails_AppointmentID")?.value;
  
        // Document Header
        let dHeader = {
          companyName: document.getElementById("billingHeader_CompanyName")?.value,
          companyAddress: document.getElementById("billingHeader_CompanyAddress")?.value,
          companyNumber: document.getElementById("billingHeader_CompanyNumber")?.value,
          companyEmail: document.getElementById("billingHeader_CompanyEmail")?.value,
        };
  
        // Document Body Information
        let dBodyInfo = {
          billingDate: document.getElementById("billingBody_Date")?.value,
          customerName: document.getElementById("billingBody_CustomerName")?.value,
          customerLocation: document.getElementById("billingBody_Location")?.value,
        };
  
        // Document Footer Information
        let dFooterInfo = {
          authorizedName: document.getElementById("billingFooter_AuthorizedName")?.value,
          authorizedRole: document.getElementById("billingFooter_AuthorizedRole")?.value,
          remarks: document.getElementById("billingFooter_Remarks")?.value,
        };
  
        if (!appointmentID) {
          updateQueryStatus("Error: Appointment ID is required.", "red", "pink");
          return;
        }
  
        let formData = {
          appointmentID: appointmentID,
          action: "billingStatementDATA",
          document: {
            header: dHeader,
            body: dBodyInfo,
            footer: dFooterInfo,
          },
        };
  
        this.disabled = true; // Disable button while processing
        try {
          await sendFormData(formData);
        } finally {
          this.disabled = false; // Ensure button is re-enabled even if request fails
        }
      });
  });
  
  // Autocomplete Setup
  function setupAutocomplete(input) {
    let fieldName = input.id || input.name || "generic";
    let savedInputs =
      JSON.parse(localStorage.getItem(`saved_${fieldName}`)) || [];
  
    // Add datalist for suggestions
    let dataList = document.createElement("datalist");
    dataList.id = `list_${fieldName}`;
    document.body.appendChild(dataList);
    input.setAttribute("list", dataList.id);
  
    // Populate suggestions
    updateDatalist(dataList, savedInputs);
  
    input.addEventListener("input", () => {
      updateDatalist(
        dataList,
        savedInputs.filter((item) =>
          item.toLowerCase().includes(input.value.toLowerCase())
        )
      );
    });
  
    input.addEventListener("blur", () => {
      let value = input.value.trim();
      if (value && !savedInputs.includes(value)) {
        savedInputs.push(value);
        localStorage.setItem(`saved_${fieldName}`, JSON.stringify(savedInputs));
        updateDatalist(dataList, savedInputs);
      }
    });
  }
  
  // Update datalist with suggestions
  function updateDatalist(dataList, suggestions) {
    dataList.innerHTML = "";
    suggestions.forEach((item) => {
      let option = document.createElement("option");
      option.value = item;
      dataList.appendChild(option);
    });
  }
  
  async function sendFormData(formData) {
    try {
      const response = await fetch("billing-statement.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      });
  
      const data = await response.text();
      console.log(formData);
      updateQueryStatus(
        data.includes("success")
          ? "Query Success!"
          : "Unexpected server response",
        "green",
        "lightgreen"
      );

      if (data.includes("success")) {
        clearAllInputs();
    }
    } catch (error) {
      console.error("Error fetching data:", error);
      updateQueryStatus("Query Sent Failed!", "red", "lightcoral");
    }
  }
  
  // Clear all input fields
  function clearAllInputs() {
    document.querySelectorAll("input").forEach((input) => {
      input.value = "";
    });
  }
  
  function updateQueryStatus(message, textColor, bgColor) {
    const statusNotifier = document.getElementById("statusGenerateNotifier");
    if (statusNotifier) {
      console.log("Updating status notifier:", message);
      Object.assign(statusNotifier.style, {
        color: textColor,
        backgroundColor: bgColor,
        border: `1px solid ${textColor}`,
      });
      statusNotifier.textContent = message;
    }
  }
  