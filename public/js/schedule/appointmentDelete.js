class DeleteForm {
    constructor(deleteButtonID) {
      this.deleteButton = document.getElementById(deleteButtonID); // Corrected variable name
  
      if (!this.deleteButton) {
        console.error("Delete button not found.");
        return;
      }
  
      this.initialize();
    }
  
    initialize() {
      this.deleteButton.addEventListener("click", (event) =>
        this.handleSubmit(event)
      );    
    }
  
    getFormData() {
      return {
        Delete_AppointmentID: this.getValue("delete_appointment"),
        Delete_CustomerID: this.getValue("delete_customer"),
      };
    }
  
    getValue(id) {
      const element = document.getElementById(id);
      return element ? element.value.trim() : ""; // If empty, return an empty string
    }
  
    async sendFormData(formData) {
      formData["action"] = "delete";
  
      try {
        const response = await fetch("appointment.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams(formData).toString(),
        });
  
        const data = await response.text();
        if (data.includes("success")) {
            console.log("Successfully deleted");
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
    new DeleteForm("deleteInformation");
  });
  