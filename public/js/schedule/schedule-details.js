function updateDetails(data) {
    document.getElementById('customer_id').textContent = data.Customer_ID;
    document.getElementById('customer_name').textContent = data.Customer_Name;
    document.getElementById('customer_contact-number').textContent = data.Contact_Number;
    document.getElementById('customer_address').textContent = data.Address;
    document.getElementById('appointment_id').textContent = data.Ticket_Number;
    document.getElementById('appointment_date').textContent = data.Appointment_Date;
    document.getElementById('appointment_category').textContent = data.Category;
    document.getElementById('appointment_priority').textContent = data.Priority;
    document.getElementById('appointment_status').textContent = data.Status;
}