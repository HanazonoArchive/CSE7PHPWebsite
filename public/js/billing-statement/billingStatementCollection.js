class PendingCollectionForm {
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
        let pendingCollection_ID = document.getElementById("selectCollection_ID")?.value;
        let collectionStatus = document.getElementById("statusCollection")?.value;

        if (!pendingCollection_ID) {
            this.updateQueryStatus("Please select an Appointment!", "red", "lightcoral");
            return null;
        }

        return JSON.stringify({
            collection_ID: pendingCollection_ID,
            collectionStatus: collectionStatus,
            action: "PendingCollection"
        });
    }

    async sendFormData(formData) {
        try {
            const response = await fetch("billing-statement.php", {
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
    new PendingCollectionForm("submitCollection", "QueryCollectionStatus");
    console.log("Collection JS Loaded!");

    fetch("/CSE7PHPWebsite/public/controller/billingStatement-controller.php?fetch_pending_collection=true")
        .then((response) => response.json())
        .then((data) => {
            const dropdown = document.getElementById("selectCollection_ID");
            dropdown.innerHTML = "<option value=''>Select Collection ID</option>";

            data.forEach((appointment) => {
                const option = document.createElement("option");
                option.value = appointment.id;
                option.textContent = `${appointment.id} - ${appointment.name}`;
                dropdown.appendChild(option);
            });
        })
        .catch((error) => console.error("Error fetching appointments:", error));
});
