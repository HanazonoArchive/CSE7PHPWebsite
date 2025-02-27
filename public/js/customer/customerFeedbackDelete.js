class DeleteFeedbackForm {
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
      let feedbackID = document.getElementById("DeleteFeedback_ID")?.value;
      let confirmationTEXT = document.getElementById(
        "DeleteFeedback_Confirmation"
      )?.value;
  
      if (confirmationTEXT !== "DELETE") {
        this.updateQueryStatus(
          "Confirmation text is Invalid Or Haven't Selected Employee!",
          "red",
          "lightcoral"
        );
        return null;
      }
  
      if (!feedbackID) {
        this.updateQueryStatus("Please select an employee!", "red", "lightcoral");
        return null;
      }
  
      return {
        feedback_ID: feedbackID,
        action: "feedbackDelete",
      };
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
            ? "Query Sent Successfully!"
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
    new DeleteFeedbackForm("submitFeedbackDelete", "QueryStatusDeleteFeedback");
    console.log("Feedback Delete JS Loaded!");

    fetch(
        "/CSE7PHPWebsite/public/controller/customer-controller2.php?fetch_Feedback=true"
      )
        .then((response) => response.json())
        .then((data) => {
          const dropdown = document.getElementById("DeleteFeedback_ID");
          dropdown.innerHTML = "<option value=''>Select Feedback ID</option>";
    
          data.forEach((appointment) => {
            const option = document.createElement("option");
            option.value = appointment.id;
            option.textContent = `${appointment.id} - ${appointment.name}`;
            dropdown.appendChild(option);
          });
        })
        .catch((error) => console.error("Error fetching appointments:", error));
});
