function updateDetails(data) {
    console.log("Schedule Details JS Loaded");

    document.getElementById('customer_id').textContent = data.Customer_ID;
    document.getElementById('customer_name').textContent = data.Customer_Name;
    document.getElementById('customer_contact-number').textContent = data.Contact_Number;
    document.getElementById('customer_address').textContent = data.Address;
    document.getElementById('appointment_id').textContent = data.Ticket_Number;
    document.getElementById('appointment_date').textContent = data.Appointment_Date;
    document.getElementById('appointment_category').textContent = data.Category;

    // STATUS ELEMENT
    const statusElement = document.getElementById('appointment_status');
    statusElement.textContent = data.Status;

    let statusColor = "#000"; // Default Black
    let statusBgColor = "#f8f9fa"; // Default Light Gray
    switch (data.Status) {
        case "Pending":
            statusColor = "#f1c40f"; // Yellow
            statusBgColor = "#fff3cd"; // Light Yellow
            break;
        case "Working":
            statusColor = "#3498db"; // Blue
            statusBgColor = "#d6eaf8"; // Light Blue
            break;
        case "Completed":
            statusColor = "#2ecc71"; // Green
            statusBgColor = "#d4edda"; // Light Green
            break;
        case "Cancelled":
            statusColor = "#e74c3c"; // Red
            statusBgColor = "#f8d7da"; // Light Red
            break;
    }
    applyStyles(statusElement, statusColor, statusBgColor);

    // PRIORITY ELEMENT
    const priorityElement = document.getElementById('appointment_priority');
    priorityElement.textContent = data.Priority;

    let priorityColor = "#000"; // Default Black
    let priorityBgColor = "#f8f9fa"; // Default Light Gray
    switch (data.Priority) {
        case "Low":
            priorityColor = "#2ecc71"; // Green
            priorityBgColor = "#d4edda"; // Light Green
            break;
        case "Medium":
            priorityColor = "#f1c40f"; // Yellow
            priorityBgColor = "#fff3cd"; // Light Yellow
            break;
        case "High":
            priorityColor = "#e67e22"; // Orange
            priorityBgColor = "#fdebd0"; // Light Orange
            break;
        case "Urgent":
            priorityColor = "#e74c3c"; // Red
            priorityBgColor = "#f8d7da"; // Light Red
            break;
    }
    applyStyles(priorityElement, priorityColor, priorityBgColor);

    // APPOINTMENT DATE COLOR
    const dateElement = document.getElementById('appointment_date');
    let dateColor = getDateColor(data.Appointment_Date);
    let dateBgColor = getLightColor(dateColor);
    applyStyles(dateElement, dateColor, dateBgColor);

    // Debugging Logs
    console.log(`Status: ${data.Status}, Applied Color: ${statusColor}`);
    console.log(`Priority: ${data.Priority}, Applied Color: ${priorityColor}`);
    console.log(`Appointment Date: ${data.Appointment_Date}, Applied Color: ${dateColor}`);
}

// Function to determine the date color
function getDateColor(appointmentDate) {
    const currentDate = new Date();
    const appointment = new Date(appointmentDate);
    const timeDiff = appointment - currentDate;
    const daysDiff = timeDiff / (1000 * 60 * 60 * 24); // Convert ms to days

    if (daysDiff <= 7) {
        return "#e74c3c"; // Red (Less than a week)
    } else if (appointment.getMonth() === currentDate.getMonth() && appointment.getFullYear() === currentDate.getFullYear()) {
        return "#f1c40f"; // Yellow (This month)
    } else {
        return "#0000FF"; // Green (More than a month away)
    }
}

// Function to get light version of a color
function getLightColor(color) {
    const lightColors = {
        "#e74c3c": "#f8d7da", // Light Red
        "#f1c40f": "#fff3cd", // Light Yellow
        "#0000FF": "#ADD8E6", // Light Green
        "#3498db": "#d6eaf8"  // Light Blue
    };
    return lightColors[color] || "#f8f9fa"; // Default Light Gray
}

// Function to apply styles to elements
function applyStyles(element, color, bgColor) {
    element.style.color = color;
    element.style.backgroundColor = bgColor;
    element.style.border = `1px solid ${color}`;
    element.style.padding = "2px";
    element.style.borderRadius = "5px";
}