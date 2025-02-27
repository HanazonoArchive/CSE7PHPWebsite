class UpdateCustomerForms {
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
      // Get the customer ID first
      const customerID = this.getValue("UpdateCustomer_ID");
      const customerName = this.getValue("UpdateCustomer_NewName");
      const customerContactNumber = this.getValue("UpdateCustomer_NewContactNumber");
      const customerAddress = this.getValue("UpdateCustomer_NewAddress");
  
      // Check if any required field is empty
      if (!customerID || !customerName || !customerContactNumber || !customerAddress) {
        this.updateQueryStatus(
          "Error: All fields are required!",
          "red",
          "lightcoral"
        );
        return null;
      }
  
      // Build formData only if all fields are filled
      let formData = {
        update_CustomerID: customerID,
        update_CustomerName: customerName,
        update_CustomerContactNumber: customerContactNumber,
        update_CustomerAddress: customerAddress,
        action: "customerUpdate",
      };
  
      return formData;
    }
  
    getValue(id) {
      return document.getElementById(id)?.value.trim() || "";
    }
  
    async sendFormData(formData) {
      try {
        const response = await fetch("customer.php", {
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
      if (formData) {
        this.sendFormData(formData);
      }
    }
  }
  
  // Initialize when the page loads
  document.addEventListener("DOMContentLoaded", () => {
    new UpdateCustomerForms("submitCustomerUpdate", "QueryStatusUpdate");
    console.log("Customer Update JS Loaded!");
  
    fetch(
      "/CSE7PHPWebsite/public/controller/customer-controller2.php?fetch_Customer=true"
    )
      .then((response) => response.json())
      .then((data) => {
        const dropdown = document.getElementById("UpdateCustomer_ID");
        dropdown.innerHTML = "<option value=''>Select Customer</option>";
  
        data.forEach((appointment) => {
          const option = document.createElement("option");
          option.value = appointment.id;
          option.textContent = `${appointment.id} - ${appointment.name}`;
          dropdown.appendChild(option);
        });
      })
      .catch((error) => console.error("Error fetching appointments:", error));
  });
  