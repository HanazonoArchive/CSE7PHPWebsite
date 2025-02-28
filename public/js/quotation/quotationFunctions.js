document.addEventListener("DOMContentLoaded", () => {
  console.log("quotationFunctions script loaded");

  // Initialize autocomplete for all input fields
  document.querySelectorAll("input").forEach((input) => {
    setupAutocomplete(input);
  });

  fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_appointments=true")
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById(
        "quotationDetails_AppointmentID"
      );
      dropdown.innerHTML = "<option value=''>Select Appointment</option>";

      data.forEach((appointment) => {
        const option = document.createElement("option");
        option.value = appointment.id;
        option.textContent = `${appointment.id} - ${appointment.name}`;
        dropdown.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching appointments:", error));

    //Employee ID 1
    fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_Employee=true")
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById(
        "quotationDetails_EmployeeID1"
      );
      dropdown.innerHTML = "<option value=''>Select Employee - NONE</option>";

      data.forEach((appointment) => {
        const option = document.createElement("option");
        option.value = appointment.id;
        option.textContent = `${appointment.id} - ${appointment.name}`;
        dropdown.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching appointments:", error));

    //Employee ID 2
    fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_Employee=true")
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById(
        "quotationDetails_EmployeeID2"
      );
      dropdown.innerHTML = "<option value=''>Select Employee - NONE</option>";

      data.forEach((appointment) => {
        const option = document.createElement("option");
        option.value = appointment.id;
        option.textContent = `${appointment.id} - ${appointment.name}`;
        dropdown.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching appointments:", error));

    //Employee ID 3
    fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_Employee=true")
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById(
        "quotationDetails_EmployeeID3"
      );
      dropdown.innerHTML = "<option value=''>Select Employee - NONE</option>";

      data.forEach((appointment) => {
        const option = document.createElement("option");
        option.value = appointment.id;
        option.textContent = `${appointment.id} - ${appointment.name}`;
        dropdown.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching appointments:", error));

  document.getElementById("generateQoutation").addEventListener("click", async function () {
    
      updateQueryStatus("Sending Query...", "gray", "lightgray");

      let appointmentID = document.getElementById("quotationDetails_AppointmentID")?.value; // Important
      let employeeID1 = document.getElementById("quotationDetails_EmployeeID1")?.value; // Important
      let employeeID2 = document.getElementById("quotationDetails_EmployeeID2")?.value; // Important
      let employeeID3 = document.getElementById("quotationDetails_EmployeeID3")?.value; // Important
      let totalAmount =document.getElementById("grandTotalInput")?.innerText || "0"; //Auto Created

      // Document Header
      let dHeader = {
        companyName: document.getElementById("qoutationHeader_CompanyName")?.value,
        companyAddress: document.getElementById("qoutationHeader_CompanyAddress")?.value,
        companyNumber: document.getElementById("qoutationHeader_CompanyNumber")?.value,
        companyEmail: document.getElementById("qoutationHeader_CompanyEmail")?.value,
      };

      // Document Body Information
      let dBodyInfo = {
        quotationDate: document.getElementById("qoutationBody_Date")?.value,
        customerName: document.getElementById("qoutationBody_CustomerName")?.value,
        customerLocation: document.getElementById("qoutationBody_Location")?.value,
        customerDetails: document.getElementById("qoutationBody_Details")?.value,
        tableTotalAmmount:document.getElementById("grandTotalInput")?.innerText || "0",
      };

      // Document Footer Information
      let dFooterInfo = {
        details1: document.getElementById("qoutationFooter_Details1")?.value,
        details2: document.getElementById("qoutationFooter_Details2")?.value,
        details3: document.getElementById("qoutationFooter_Details3")?.value,
        details4: document.getElementById("qoutationFooter_Details4")?.value,
      };

      // Document Technician Information
      let dTechnicianInfo = {
        namePreparer: document.getElementById("qoutationFooter_TechnicianNamePreparer")?.value,
        positionPreparer: document.getElementById("qoutationFooter_TechnicianPositionPreparer")?.value,
        nameManager: document.getElementById("qoutationFooter_TechnicianNameManager")?.value,
        positionManager: document.getElementById("qoutationFooter_TechnicianPositionManager")?.value,
      };

      if (!appointmentID) {
        updateQueryStatus("Error: Appointment ID is required.", "red", "pink");
        return;
      }

      if (!employeeID1 && !employeeID2 && !employeeID3) {
        updateQueryStatus(
          "Error: At least one employee ID is required.",
          "red",
          "pink"
        );
        return;
      }

      let formData = {
        appointmentID: appointmentID,
        employees: [employeeID1, employeeID2, employeeID3].filter(Boolean),
        totalAmount: totalAmount,
        status: "Working",
        action: "quotationDATA",
        document: {
          header: dHeader,
          body: dBodyInfo,
          footer: dFooterInfo,
          technicianInfo: dTechnicianInfo,
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

// Send form data and handle response
async function sendFormData(formData) {
  try {
    const response = await fetch("quotation.php", {
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

// Update query status message
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
