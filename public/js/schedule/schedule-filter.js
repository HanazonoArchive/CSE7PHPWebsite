class AppointmentFilter {
    constructor() {
        this.filterButtons = document.querySelectorAll(".filter_button[data-filter]"); // Column filters
        this.orderButtons = document.querySelectorAll(".filter_button[data-order]"); // ASC/DESC buttons
        this.statusButtons = document.querySelectorAll(".filter_button[data-status]"); // Status filters

        this.currentOrder = "ASC"; // Default order
        this.currentFilter = ""; // Default column (none selected initially)
        this.currentStatus = ""; // Default status (none selected)
        this.queryStorage = this.buildQuery(); // Initial query

        this.initialize();
    }

    initialize() {
        this.filterButtons.forEach(btn => {
            btn.addEventListener("click", () => this.handleFilterClick("filter", btn.getAttribute("data-filter"), this.filterButtons));
        });

        this.orderButtons.forEach(btn => {
            btn.addEventListener("click", () => this.handleFilterClick("order", btn.getAttribute("data-order"), this.orderButtons));
        });

        this.statusButtons.forEach(btn => {
            btn.addEventListener("click", () => this.handleFilterClick("status", btn.getAttribute("data-status"), this.statusButtons));
        });

        this.fetchFilteredData(); // Initial data fetch
    }

    buildQuery() {
        let query = "";

        if (this.currentStatus) {
            query = `WHERE appointment.status = '${this.currentStatus}' ORDER BY appointment.id ${this.currentOrder}`;
        } else if (this.currentFilter) {
            query = `ORDER BY ${this.currentFilter} ${this.currentOrder}`;
        } else {
            query = `ORDER BY appointment.id ${this.currentOrder}`;
        }

        return query;
    }

    handleFilterClick(type, value, buttons) {
        if (type === "filter") {
            // If switching from status to filter, deactivate status
            if (this.currentStatus) {
                this.currentStatus = "";
                this.deactivateButtons(this.statusButtons);
            }
            this.currentFilter = value;
        } else if (type === "order") {
            this.currentOrder = value;
        } else if (type === "status") {
            // If switching from filter to status, deactivate filter
            if (this.currentFilter) {
                this.currentFilter = "";
                this.deactivateButtons(this.filterButtons);
            }
            this.currentStatus = value;
        }

        this.activateButton(type, value, buttons);
        this.updateQueryAndFetch();
    }

    deactivateButtons(buttons) {
        buttons.forEach(btn => btn.classList.remove("active"));
    }

    activateButton(type, value, buttons) {
        this.deactivateButtons(buttons);
        document.querySelector(`[data-${type}="${value}"]`).classList.add("active");
    }

    updateQueryAndFetch() {
        this.queryStorage = this.buildQuery();
        this.fetchFilteredData();
    }

    async fetchFilteredData() {
        console.log("Query Sent:", this.queryStorage);
        try {
            const response = await fetch("schedule.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "sql_query=" + encodeURIComponent(this.queryStorage)
            });

            const data = await response.text();
            document.querySelector(".appointment-table").innerHTML = data;
        } catch (error) {
            console.error("Error fetching data:", error);
        }
    }
}

// Initialize the filter system when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => new AppointmentFilter());
