document.addEventListener("DOMContentLoaded", function () {
    const filterButtons = document.querySelectorAll(".filter_button[data-filter]"); // Column filters
    const orderButtons = document.querySelectorAll(".filter_button[data-order]"); // ASC/DESC buttons
    const statusButtons = document.querySelectorAll(".filter_button[data-status]"); // Status filters

    let currentOrder = "ASC"; // Default order
    let currentFilter = ""; // Default column (none selected initially)
    let currentStatus = ""; // Default status (none selected)
    let queryStorage = buildQuery(); // Initial query

    // Function to update and fetch data
    function updateQueryAndFetch() {
        queryStorage = buildQuery();
        fetchFilteredData();
    }

    // Function to build SQL query with restrictions
    function buildQuery() {
        let query = "";

        if (currentStatus) {
            query = `WHERE appointment.status = '${currentStatus}' ORDER BY appointment.id ${currentOrder}`;
        } else if (currentFilter) {
            query = `ORDER BY ${currentFilter} ${currentOrder}`;
        } else {
            query = `ORDER BY appointment.id ${currentOrder}`;
        }

        return query;
    }

    // Function to handle button clicks and auto-deactivate conflicting filters
    function handleFilterClick(type, value, buttons) {
        if (type === "filter") {
            // If switching from status to filter, deactivate status
            if (currentStatus) {
                currentStatus = "";
                statusButtons.forEach(btn => btn.classList.remove("active"));
            }
            currentFilter = value;
        } else if (type === "order") {
            currentOrder = value;
        } else if (type === "status") {
            // If switching from filter to status, deactivate filter
            if (currentFilter) {
                currentFilter = "";
                filterButtons.forEach(btn => btn.classList.remove("active"));
            }
            currentStatus = value;
        }

        // Remove 'active' class from all buttons of the same type
        buttons.forEach(btn => btn.classList.remove("active"));
        document.querySelector(`[data-${type}="${value}"]`).classList.add("active");

        updateQueryAndFetch(); // Update and fetch data
    }

    // Attach event listeners
    filterButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            handleFilterClick("filter", this.getAttribute("data-filter"), filterButtons);
        });
    });

    orderButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            handleFilterClick("order", this.getAttribute("data-order"), orderButtons);
        });
    });

    statusButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            handleFilterClick("status", this.getAttribute("data-status"), statusButtons);
        });
    });

    // Function to fetch sorted/filtered data
    function fetchFilteredData() {
        console.log("Query Sent:", queryStorage);
        fetch("schedule.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "sql_query=" + encodeURIComponent(queryStorage)
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector(".appointment-table").innerHTML = data;
        })
        .catch(error => console.error("Error fetching data:", error));
    }
});
