document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit_customer').addEventListener('click', function() {
        let customerName = document.getElementById('customer_name').value;
        let customerNumber = document.getElementById('customer_number').value;
        let customerAddress = document.getElementById('customer_address').value;

        // Construct the SQL query
        let query = `INSERT INTO customer (name, contact_number, address) VALUES ('${customerName}', '${customerNumber}', '${customerAddress}')`;

        // Check if all fields have content
        if (customerName && customerNumber && customerAddress) {
            console.log("Form has content!");
        } else {
            console.log("No content in one or more fields");
            return; // Exit if any field is empty
        }

        console.log(query); // Log the query to check

        // Function to send the query to the server
        function fetchDataCreation() {
            console.log("Query Sent:", query);

            fetch("appointment.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "sql_query=" + encodeURIComponent(query) // Send the correct variable 'query'
            })
            .then(response => response.text())
            .then(data => {
                // Assuming you want to update the DOM with the returned data
                document.querySelector(".appointment-table").innerHTML = data;
            })
            .catch(error => console.error("Error fetching data:", error));
        }

        // Call the fetchDataCreation function to send the query
        fetchDataCreation();
    });
});
