class UpdateAppointmentForm {
  constructor(submitButtonId, queryStatusId) {
    this.submitButton = document.getElementById(submitButtonId);
    this.queryStatus = document.getElementById(queryStatusId);

    if (!this.submitButton) {
      console.error("Submit button not found.");
      return;
    }

    this.submitButton.addEventListener("click", () => this.handleSubmit());
  }

  getFormData() {
    let formData = {
      update_AppointmentID: this.getValue("appointmentUpdate_ID"),
      update_CustomerID: this.getValue("appointmentUpdateCustomer_ID"),
      update_Name: this.getValue("appointmentUpdateCustomer_Name"),
      update_ContactNumber: this.getValue("appointmentUpdateCustomer_ContactNumber"),
      update_Address: this.getValue("appointmentUpdateCustomer_Address"),
      update_Category: this.getValue("appointmentUpdate_Category"),
      update_Priority: this.getValue("appointmentUpdate_Priority"),
      update_Date: this.getValue("appointmentUpdate_Date"),
      action: "update",
    };

    const customerFields = [
      formData.update_CustomerID,
      formData.update_Name,
      formData.update_ContactNumber,
      formData.update_Address,
    ];
    
    const appointmentFields = [
      formData.update_AppointmentID,
      formData.update_Category,
      formData.update_Priority,
      formData.update_Date,
    ];

    const isCustomerFilled = customerFields.some((value) => value.trim());
    const isAppointmentFilled = appointmentFields.some((value) => value.trim());

    if (!isCustomerFilled && !isAppointmentFilled) {
      this.updateQueryStatus("Fill at least One!", "red", "lightcoral");
      return null;
    }

    if (!isCustomerFilled && isAppointmentFilled) {
      this.updateQueryStatus("1 Entry is filled!", "orange", "lightyellow");
    }
    
    return formData;
  }

  getValue(id) {
    return document.getElementById(id)?.value.trim() || "";
  }

  async sendFormData(formData) {
    try {
      const response = await fetch("appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(formData).toString(),
      });

      const data = await response.text();
      this.updateQueryStatus(
        data.includes("success")
          ? "Query Success!"
          : "Unexpected server response",
        "green",
        "lightgreen"
      );
    } catch (error) {
      console.error("Error fetching data:", error);
      this.updateQueryStatus("Query Sent Failed!", "red", "lightcoral");
    }
  }

  updateQueryStatus(message, textColor, bgColor) {
    if (this.queryStatus) {
      Object.assign(this.queryStatus.style, {
        color: textColor,
        backgroundColor: bgColor,
        border: `1px solid ${textColor}`,
      });
      this.queryStatus.textContent = message;
    }
  }

  handleSubmit() {
    console.log("Submit button clicked.");
    this.updateQueryStatus("Query Sending...", "gray", "lightgray");

    const formData = this.getFormData();
    if (formData) this.sendFormData(formData);
  }
}

// Initialize when the page loads
document.addEventListener("DOMContentLoaded", () => {
  new UpdateAppointmentForm("submitUpdateAppointment", "statusUpdateNotifier");
  console.log("Appointment Update JS Loaded!");
});