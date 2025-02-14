document.addEventListener("DOMContentLoaded", () => {
  console.log("quotationFunctions script loaded");

  document.getElementById("generateQoutation").addEventListener("click", async function () {
      updateQueryStatus("Sending Query...", "gray", "lightgray");
      let appointmentID = document.getElementById("quotationDetails_AppointmentID")?.value;
      let employeeID1 = document.getElementById("quotationDetails_EmployeeID1")?.value;
      let employeeID2 = document.getElementById("quotationDetails_EmployeeID2")?.value;
      let employeeID3 = document.getElementById("quotationDetails_EmployeeID3")?.value;
      let totalAmount = document.getElementById("grandTotalInput")?.innerText || "0";

      if (!appointmentID) {
        updateQueryStatus("Error: Appointment ID is required.", "red", "pink");
        return;
      }

      if (!employeeID1 && !employeeID2 && !employeeID3) {
        updateQueryStatus("Error: At least one employee ID is required.", "red", "pink");
        return;
      }

      let formData = {
        employeeID1: employeeID1 || "",
        employeeID2: employeeID2 || "",
        employeeID3: employeeID3 || "",
        appointmentID: appointmentID,
        totalAmount: totalAmount,
        status: "Working",
        action: "insertQuotation",
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
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams(formData).toString(),
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
