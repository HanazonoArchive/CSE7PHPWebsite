class UpdateForm {
  constructor(formId, updateButtonID) {
    this.form = document.getElementById(formId);
    this.updateButton = document.getElementById(updateButtonID);

    if (!this.form || !this.updateButton) {
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
    return {
      update_AppointmentID: this.getValue("update_appointmentID"),
      update_CustomerID: this.getValue("update_customerID"),
      update_Name: this.getValue("update_name"),
      update_ContactNumber: this.getValue("update_contactNumber"),
      update_Address: this.getValue("update_address"),
      update_Category: this.getValue("update_category"),
      update_Priority: this.getValue("update_priority"),
      update_Date: this.getValue("update_date"),
    };
  }

  getValue(id) {
    const element = document.getElementById(id);
    return element ? element.value.trim() : ""; // If empty, return empty string
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

        if (data.includes("success")) {
          console.log("Successfully Updated");
          window.location.href = "appointment.php"; // Reload on success
        }
      } catch (error) {
        console.error("Error:", error);
      }
    }

  handleSubmit(event) {
    event.preventDefault(); // Prevent default form submission

    const formData = this.getFormData();
    this.sendFormData(formData); // Send data directly
  }
}

// Initialize the form handling when the page loads
document.addEventListener("DOMContentLoaded", () => {
  new UpdateForm("appointment_form", "update_information");
});
