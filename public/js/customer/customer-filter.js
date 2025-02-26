document.addEventListener("DOMContentLoaded", () => {
    console.log("Customer Filter JS Loaded!");

    const sortBy = document.getElementById("dropdownSortBy");
    const applyButton = document.querySelector(".filterApplyButton");
    const queryStatus = document.getElementById("QueryStatus");
  
    applyButton.addEventListener("click", () => {
      const sortValue = sortBy.value;
  
      let query = `ORDER BY customer.id ${sortValue}`;
  
      queryStatus.textContent = `Query Sent: ${sortValue}.`;
      queryStatus.style.color = "black";
      queryStatus.style.backgroundColor = "lightgray";
      queryStatus.style.border = "1px solid gray";
      fetchFilteredData(query);
    });
  
    async function fetchFilteredData(query) {
      console.log("Query Sent:", query);
  
      try {
        const response = await fetch("customer.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "sql_query=" + encodeURIComponent(query),
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
        console.error("Error fetching data:", error);
        queryStatus.textContent = "Query Sent Failed!"; // Error log
        queryStatus.style.color = "red";
        queryStatus.style.backgroundColor = "lightcoral";
        queryStatus.style.border = "1px solid red";
      }
    }
  });
  