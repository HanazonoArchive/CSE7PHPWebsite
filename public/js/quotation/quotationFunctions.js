document.addEventListener("DOMContentLoaded", () => {
  console.log("quotationFunctions script loaded");

  document.getElementById("generateQoutation").addEventListener("click", async function () {
      updateQueryStatus("Sending Query...", "gray", "lightgray");

      let appointmentID = document.getElementById("quotationDetails_AppointmentID")?.value;
      let employeeID1 = document.getElementById("quotationDetails_EmployeeID1")?.value;
      let employeeID2 = document.getElementById("quotationDetails_EmployeeID2")?.value;
      let employeeID3 = document.getElementById("quotationDetails_EmployeeID3")?.value;
      let totalAmount = document.getElementById("grandTotalInput")?.innerText || "0";

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
        updateQueryStatus("Error: At least one employee ID is required.", "red", "pink");
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
      data.includes("success") ? "Query Success!" : "Unexpected server response",
      "green",
      "lightgreen"
    );
  } catch (error) {
    console.error("Error fetching data:", error);
    updateQueryStatus("Query Sent Failed!", "red", "lightcoral");
  }
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
