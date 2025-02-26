class UpdateEmployeeForm {
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
      update_EmployeeID: this.getValue("UpdateEmployee_ID"),
      update_EmployeeName: this.getValue("UpdateEmployee_NewName"),
      update_EmployeeContactNumber: this.getValue(
        "UpdateEmployee_NewContactNumber"
      ),
      update_EmployeeRole: this.getValue("UpdateEmployee_NewRole"),
      update_EmployeePay: this.getValue("UpdateEmployee_NewPay"),
      update_EmployeeStatus: this.getValue("UpdateEmploye_NewStatus"),
      action: "update",
    };

    // Extract individual values for validation
    const {
      update_EmployeeID,
      update_EmployeeName,
      update_EmployeeContactNumber,
      update_EmployeeRole,
      update_EmployeePay,
      update_EmployeeStatus,
    } = formData;

    // Condition 1: Only EmployeeID and EmployeeStatus are filled
    const onlyRequiredFilled =
      update_EmployeeID &&
      update_EmployeeStatus &&
      !update_EmployeeName &&
      !update_EmployeeContactNumber &&
      !update_EmployeeRole &&
      !update_EmployeePay;

    // Condition 2: All fields are filled
    const allFieldsFilled =
      update_EmployeeID &&
      update_EmployeeStatus &&
      update_EmployeeName &&
      update_EmployeeContactNumber &&
      update_EmployeeRole &&
      update_EmployeePay;

    // Proceed only if one of the conditions is met
    if (onlyRequiredFilled || allFieldsFilled) {
      return formData;
    }

    this.updateQueryStatus(
      "Error: Employee and Status are Empty, OR All Fields are Required!",
      "red",
      "lightcoral"
    );
    return null;
  }

  getValue(id) {
    return document.getElementById(id)?.value.trim() || "";
  }

  async sendFormData(formData) {
    try {
      const response = await fetch("employee.php", {
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
      if (data.includes("reload")) {
        setTimeout(() => {
          window.location.reload();
        }, 3000); // Reload after 3 seconds
      }
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
  new UpdateEmployeeForm("submitEmployeeUpdate", "QueryStatusUpdate");
  console.log("Employee Update JS Loaded!");

  fetch(
    "/CSE7PHPWebsite/public/controller/employee-controller1.php?fetch_Employee=true"
  )
    .then((response) => response.json())
    .then((data) => {
      const dropdown = document.getElementById("UpdateEmployee_ID");
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
