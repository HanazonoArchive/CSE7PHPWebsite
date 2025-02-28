class CancelAppointmentForm {
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
        let appointmentID = document.getElementById("cancelAppointment_ID")?.value;

        if (!appointmentID) {
            this.updateQueryStatus("Please select an Appointment!", "red", "lightcoral");
            return null;
        }

        return JSON.stringify({
            appointment_ID: appointmentID,
            action: "cancel"
        });
    }

    async sendFormData(formData) {
        try {
            const response = await fetch("quotation.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: formData, // JSON string
            });

            const data = await response.text();
            this.updateQueryStatus(
                data.includes("success") ? "Query Sent Successfully!" : "Unexpected server response",
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
    new CancelAppointmentForm("submitCancelAppointment", "QueryStatusCancel");
    console.log("Quotation Cancel JS Loaded!");

    fetch("/CSE7PHPWebsite/public/controller/quotation-controller.php?fetch_appointments=true")
        .then((response) => response.json())
        .then((data) => {
            const dropdown = document.getElementById("cancelAppointment_ID");
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
