document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('submit_customer').addEventListener('click', function() {
        let customerName = document.getElementById('customer_name').value.trim();
        let customerNumber = document.getElementById('customer_number').value.trim();
        let customerAddress = document.getElementById('customer_address').value.trim();

        let appointmentDate = document.getElementById('appointment_date').value.trim();
        let appointmentCategory = document.getElementById('appointment_category').value.trim();
        let appointmentPriority = document.getElementById('appointment_priority').value.trim();
        let appointmentStatus = "Confirmed"; // Default

        // Check if all fields have content
        if (!customerName || !customerNumber || !customerAddress || !appointmentDate || !appointmentCategory || !appointmentPriority) {
            console.log("One or more fields are empty");
            return;
        }

        // Prepare data to send
        let formData = new URLSearchParams();
        formData.append("customer_name", customerName);
        formData.append("customer_number", customerNumber);
        formData.append("customer_address", customerAddress);
        formData.append("appointment_date", appointmentDate);
        formData.append("appointment_category", appointmentCategory);
        formData.append("appointment_priority", appointmentPriority);
        formData.append("appointment_status", appointmentStatus);

        // Send the request to the backend
        fetch("appointment.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData.toString()
        })
        .then(response => response.text())
        .then(data => {
            console.log("Server Response:", data);
            if (data.includes("success")) { // Adjust this condition based on your server response
                window.location.href = "appointment.php"; // Reload the page
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
