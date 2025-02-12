class AppointmentForm {
  constructor(formId, updateButtonID) {
    this.form = document.getElementById(formId);
    this.updateButton = document.getElementById(updateButtonID);

    if (!this.form || !this.updateButton) {
      // Fix here
      console.error("Form or update button not found.");
      return;
    }

    this.initialize();
  }

  initialize() {
    this.updateButton.addEventListener("click", (event) =>
      this.handleSubmit(event)
    );
  }

  getFormData() {
    let updateAppointmentID = this.getValue("update_appointmentID");
    let updateCustomerID = this.getValue("update_customerID");

    let formData = {};

    if (updateAppointmentID && !updateCustomerID) {
      // Only Appointment Data
      formData = {
        update_AppointmentID: updateAppointmentID,
        update_Category: this.getValue("update_category"),
        update_Priority: this.getValue("update_priority"),
        update_Date: this.getValue("update_date"),
      };
    } else if (updateCustomerID && !updateAppointmentID) {
      // Only Customer Data
      formData = {
        update_CustomerID: updateCustomerID,
        update_Name: this.getValue("update_name"),
        update_ContactNumber: this.getValue("update_contactNumber"),
        update_Address: this.getValue("update_address"),
      };
    } else if (updateAppointmentID && updateCustomerID) {
      // Send Everything
      formData = {
        update_AppointmentID: updateAppointmentID,
        update_Category: this.getValue("update_category"),
        update_Priority: this.getValue("update_priority"),
        update_Date: this.getValue("update_date"),
        update_CustomerID: updateCustomerID,
        update_Name: this.getValue("update_name"),
        update_ContactNumber: this.getValue("update_contactNumber"),
        update_Address: this.getValue("update_address"),
      };
    }

    return formData;
  }

  getValue(id) {
    const element = document.getElementById(id);
    return element ? element.value.trim() : "";
  }

  validateFormData(formData) {
    return Object.values(formData).every((value) => value !== "");
  }

  async sendFormData(formData) {
    formData["action"] = "update";

    try {
      const response = await fetch("appointment.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(formData).toString(),
      });

      const data = await response.text();
      console.log("Server Response:", data);

      if (data.includes("success")) {
        window.location.href = "appointment.php"; // Reload on success
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

  handleSubmit(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = this.getFormData();

    if (!this.validateFormData(formData)) {
      console.log("One or more fields are empty.");
      return;
    }

    this.sendFormData(formData);
  }
}

// Initialize the form handling when the page loads
document.addEventListener("DOMContentLoaded", () => {
  new AppointmentForm("appointment_form", "update_information");
});
