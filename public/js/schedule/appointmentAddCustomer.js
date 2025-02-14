class CreateAppointmentForm {
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
            customer_name: this.getValue("appointmentCreateCustomer_Name"),
            customer_number: this.getValue("appointmentCreateCustomer_ContactNumber"),
            customer_address: this.getValue("appointmentCreateCustomer_Address"),
            appointment_date: this.getValue("appointmentCreate_Date"),
            appointment_category: this.getValue("appointmentCreate_Category"),
            appointment_priority: this.getValue("appointmentCreate_Priority"),
            appointment_status: "Pending",
            action: "create"
        };

        // Check for empty fields
        if (Object.values(formData).some(value => !value.trim())) {
            this.updateQueryStatus("Please fill all fields!", "red", "lightcoral");
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
            this.updateQueryStatus(data.includes("success") ? "Query Sent Successfully!" : "Unexpected server response", "green", "lightgreen");
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
    new CreateAppointmentForm("submitCreateAppointment", "statusCreateNotifier");
    console.log("Appointment Create JS Loaded!");
});
