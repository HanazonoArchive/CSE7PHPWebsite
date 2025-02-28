class EmployeePayForm {
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
      let employeeID = document.getElementById("payEmployee_ID")?.value;
      let confirmationTEXT = document.getElementById("payEmployee_Confirmation")?.value;
  
      if (!employeeID || !confirmationTEXT){ 
        this.updateQueryStatus("Invalid ID & Total Pay!", "red", "lightcoral");
        return null;
      }
  
      return {
        employee_ID: employeeID,
        confirmationTEXT: confirmationTEXT,
        action: "payingEmployee",
      };
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
          data.includes("success") ? "Query Sent Successfully!" : "Unexpected server response",
          "green",
          "lightgreen"
        );
  
        if (data.includes("reload")) {
          setTimeout(() => window.location.reload(), 3000);
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
  document.addEventListener("DOMContentLoaded", async () => {
    new EmployeePayForm("submitEmployeePay", "QueryStatusPay");
    console.log("Employee Pay JS Loaded!");
  
    try {
      const response = await fetch("/CSE7PHPWebsite/public/controller/employee-controller1.php?fetch_Employee=true");
      const data = await response.json();
  
      const dropdown = document.getElementById("payEmployee_ID");
      dropdown.innerHTML = "<option value=''>Select Employee</option>";
  
      data.forEach((appointment) => {
        const option = document.createElement("option");
        option.value = appointment.id;
        option.textContent = `${appointment.id} - ${appointment.name}`;
        dropdown.appendChild(option);
      });
    } catch (error) {
      console.error("Error fetching appointments:", error);
    }
  });
  