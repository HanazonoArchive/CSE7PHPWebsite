document.addEventListener("DOMContentLoaded", () => {
    console.log("quotationFunctions script loaded");
  
    document.getElementById("generateServiceReport").addEventListener("click", async function () {
        updateQueryStatus("Sending Query...", "gray", "lightgray");
  
        let appointmentID = document.getElementById("serviceReportDetails_AppointmentID")?.value;
        let totalAmount = document.getElementById("grandTotalInput")?.innerText || "0";
  
        // Document Header
        let dHeader = {
          companyName: document.getElementById("serviceReportHeader_CompanyName")?.value,
          companyAddress: document.getElementById("serviceReportHeader_CompanyAddress")?.value,
          companyNumber: document.getElementById("serviceReportHeader_CompanyNumber")?.value,
          companyEmail: document.getElementById("serviceReportHeader_CompanyEmail")?.value,
        };
  
        // Document Body Information
        let dBodyInfo = {
          serviceReportDate: document.getElementById("serviceReportBody_Date")?.value,
          customerName: document.getElementById("serviceReportBody_CustomerName")?.value,
          customerLocation: document.getElementById("serviceReportBody_Location")?.value,
          tableTotalAmount: document.getElementById("grandTotalInput")?.innerText || "0",
        };
  
        // Document Footer Information
        let dFooterInfo = {
          complaint: document.getElementById("serviceReportFooter_Complaint")?.value,
          diagnosed: document.getElementById("serviceReportFooter_Diagnosed")?.value,
          activityPerformed: document.getElementById("serviceReportFooter_ActivityPerformed")?.value,
          recommendation: document.getElementById("serviceReportFooter_Recommendation")?.value,
        };
  
        // Document Technician Information
        let dTechnicianInfo = {
          preparerName: document.getElementById("serviceReportFooter_PreparerName")?.value,
          preparerPosition: document.getElementById("serviceReportFooter_PreparerPosition")?.value,
          managerName: document.getElementById("serviceReportFooter_ManagerName")?.value,
        };
  
        if (!appointmentID) {
          updateQueryStatus("Error: Appointment ID is required.", "red", "pink");
          return;
        }
  
        let formData = {
          appointmentID: appointmentID,
          totalAmount: totalAmount,
          status: "Completed",
          action: "serviceReportDATA",
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
      const response = await fetch("services-report.php", {
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
  