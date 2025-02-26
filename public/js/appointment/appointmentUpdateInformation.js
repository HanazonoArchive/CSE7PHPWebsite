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
      update_Category: this.getValue("appointmentUpdate_Category"),
      update_Priority: this.getValue("appointmentUpdate_Priority"),
      update_Date: this.getValue("appointmentUpdate_Date"),
      action: "update",
    };

    if(!formData.update_AppointmentID) {
      this.updateQueryStatus("Select an appointment to update!", "red", "lightcoral");
      return null
    }

    if (!formData.update_Date) {
      this.updateQueryStatus("Date is required!", "red", "lightcoral");
      return null;
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

  fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_appointments=true")
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById(
        "appointmentUpdate_ID"
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
});