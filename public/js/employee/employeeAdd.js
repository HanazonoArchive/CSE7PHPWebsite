class CreateEmployeeForm {
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
            employee_name: this.getValue("employeeAdd_Name"),
            employee_contactNumber: this.getValue("employeeAdd_ContactNumber"),
            employee_role: this.getValue("employeeAdd_Role"),
            employee_status: "Present",
            employee_pay: this.getValue("employeeAdd_Pay"),
            employee_workDays: "0",
            action: "create"
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

        document.getElementById("employeeAdd_Name").value = "";
        document.getElementById("employeeAdd_ContactNumber").value = "";
        document.getElementById("employeeAdd_Role").value = "";
        document.getElementById("employeeAdd_Pay").value = "";

        console.log("Form cleared successfully!");
    }

    async sendFormData(formData) {
        try {
            console.log("Sending Data to Server:", new URLSearchParams(formData).toString());

            const response = await fetch("employee.php", {
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
    new CreateEmployeeForm("submitEmployeeAdd", "QueryStatusCreate");
    console.log("Employee Create JS Loaded!");
});
