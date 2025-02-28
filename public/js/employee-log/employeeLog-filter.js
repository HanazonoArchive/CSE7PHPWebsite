document.addEventListener("DOMContentLoaded", () => {
    console.log("EmployeeLog Filter JS Loaded");

    const orderBy = document.getElementById("dropdownOrderBy");
    const sortBy = document.getElementById("dropdownSortBy");
    const applyButton = document.querySelector(".filterApplyButton");
    const queryStatus = document.getElementById("QueryStatus"); // Get the QueryStatus element

    applyButton.addEventListener("click", () => {
        const orderValue = orderBy.value;
        const sortValue = sortBy.value;

        let orderQuery = `ORDER BY ${orderValue} ${sortValue}`;

        queryStatus.textContent = `Query Sent: ${orderValue}, ${sortValue}.`;
        queryStatus.style.color = "black";
        queryStatus.style.backgroundColor = "lightgray";
        queryStatus.style.border = "1px solid gray";

        fetchFilteredData(orderQuery);
    });

    async function fetchFilteredData(orderQuery) {
        console.log("Query Sent:", orderQuery);

        try {
            const response = await fetch("employee-log.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "sql_query=" + encodeURIComponent(orderQuery)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.text();
            document.querySelector(".employeeLogTable").innerHTML = data; // Updated to match the div ID

            queryStatus.textContent = `Query Sent Successfully!`; // Success log
            queryStatus.style.color = "green";
            queryStatus.style.backgroundColor = "lightgreen";
            queryStatus.style.border = "1px solid green";
        } catch (error) {
            console.error("Error fetching data:", error);
            queryStatus.textContent = "Query Sent Failed!"; // Error log
            queryStatus.style.color = "red";
            queryStatus.style.backgroundColor = "lightcoral";
            queryStatus.style.border = "1px solid red";
        }
    }
});
