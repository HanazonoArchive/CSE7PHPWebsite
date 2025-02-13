document.addEventListener("DOMContentLoaded", () => {
    console.log("Schedule Filter JS Loaded");

    const orderBy = document.getElementById("dropdownOrderBy");
    const filterBy = document.getElementById("dropdownFilterBy");
    const sortBy = document.getElementById("dropdownSortBy");
    const applyButton = document.querySelector(".filterApplyButton");
    const queryStatus = document.getElementById("QueryStatus"); // Get the QueryStatus element

    applyButton.addEventListener("click", () => {
        const orderValue = orderBy.value;
        const filterValue = filterBy.value;
        const sortValue = sortBy.value;

        let query = `WHERE appointment.status = '${filterValue}' ORDER BY ${orderValue} ${sortValue}`;
        
        queryStatus.textContent = `Query Sent: ${filterValue}, ${orderValue}, ${sortValue}.`;
        queryStatus.style.color = "black";
        queryStatus.style.backgroundColor = "lightgray";
        queryStatus.style.border = "1px solid gray";
        fetchFilteredData(query);
    });

    async function fetchFilteredData(query) {
        console.log("Query Sent:", query);

        try {
            const response = await fetch("schedule.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "sql_query=" + encodeURIComponent(query)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.text();
            document.querySelector(".appointment-table").innerHTML = data;
            queryStatus.textContent = `Query Sent Succesfully!`; // Success log
            queryStatus.style.color = "green";
            queryStatus.style.backgroundColor = "lightgreen";
            queryStatus.style.border = "1px solid green";
        } catch (error) {
            console.error("Error fetching data:", error)
            queryStatus.textContent = "Query Sent Failed!"; // Error log
            queryStatus.style.color = "red";
            queryStatus.style.backgroundColor = "lightcoral";
            queryStatus.style.border = "1px solid red";
        }
    }
});
