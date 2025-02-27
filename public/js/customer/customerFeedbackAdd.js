class CreateFeedbackForm {
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
            appointment_ID: this.getValue("AddFeedback_AppointmentID"),
            feedback_comment: this.getValue("AddFeedback_Comment"),
            action: "feedbackCreate"
        };

        console.log("Collected Form Data:", formData); // Debug: Log collected data

        // Check for empty fields (excluding numeric fields)
        if (Object.entries(formData).some(([key, value]) => typeof value === "string" && !value.trim())) {
            this.updateQueryStatus("Please fill all fields!", "red", "lightcoral");
            return null;
        }

        return formData;
    }

    getValue(id) {
        return document.getElementById(id)?.value.trim() || "";
    }

    clearForm() {
        console.log("Clearing form fields..."); // Debug: Log when clearing

        document.getElementById("AddFeedback_AppointmentID").value = "";
        document.getElementById("AddFeedback_Comment").value = "";

        console.log("Form cleared successfully!");
    }

    async sendFormData(formData) {
        try {
            console.log("Sending Data to Server:", new URLSearchParams(formData).toString());

            const response = await fetch("customer.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(formData).toString(),
            });

            const data = await response.text();
            console.log("Server Response:", data);

            if (data.includes("success")) {
                this.updateQueryStatus("Query Sent Successfully!", "green", "lightgreen");
                this.clearForm(); // Clear the form on success

                if (data.includes("reload")) {
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000); // Reload after 3 seconds
                }
            } else {
                this.updateQueryStatus("Unexpected server response", "red", "lightcoral");
            }
        } catch (error) {
            console.error("Error fetching data:", error);
            this.updateQueryStatus("Query Sent Failed!", "red", "lightcoral");
        }
    }

    updateQueryStatus(message, textColor, bgColor) {
        if (this.queryStatus) {
            Object.assign(this.queryStatus.style, { color: textColor, backgroundColor: bgColor, border: `1px solid ${textColor}` });
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
    new CreateFeedbackForm("submitFeedbackAdd", "QueryStatusCreate");
    console.log("Feedback Create JS Loaded!");

    fetch(
        "/CSE7PHPWebsite/public/controller/customer-controller2.php?fetch_Appointment=true"
      )
        .then((response) => response.json())
        .then((data) => {
          const dropdown = document.getElementById("AddFeedback_AppointmentID");
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
