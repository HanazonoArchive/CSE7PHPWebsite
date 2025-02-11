class AppointmentForm {
    constructor(formId, submitButtonId) {
        this.form = document.getElementById(formId);
        this.submitButton = document.getElementById(submitButtonId);

        if (!this.form || !this.submitButton) {
            console.error("Form or submit button not found.");
            return;
        }

        this.initialize();
    }

    initialize() {
        this.submitButton.addEventListener("click", () => this.handleSubmit());
    }

    getFormData() {
        return {
            customer_name: this.getValue("customer_name"),
            customer_number: this.getValue("customer_number"),
            customer_address: this.getValue("customer_address"),
            appointment_date: this.getValue("appointment_date"),
            appointment_category: this.getValue("appointment_category"),
            appointment_priority: this.getValue("appointment_priority"),
            appointment_status: "Pending", // Default status
        };
    }

    getValue(id) {
        const element = document.getElementById(id);
        return element ? element.value.trim() : "";
    }

    validateFormData(formData) {
        return Object.values(formData).every(value => value !== "");
    }

    async sendFormData(formData) {
        try {
            const response = await fetch("appointment.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(formData).toString(),
            });

            const data = await response.text();
            console.log("Server Response:", data);

            if (data.includes("success")) {
                window.location.href = "appointment.php"; // Reload the page on success
            }
        } catch (error) {
            console.error("Error:", error);
        }
    }

    handleSubmit() {
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
    new AppointmentForm("appointment_form", "submit_customer");
});
