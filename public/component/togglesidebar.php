<?php
// Get absolute path
$fullPath = $_SERVER['SCRIPT_NAME']; // Example: "/webpage/job-scheduling/Sub1.php"

// Extract directory and file name
$pathParts = explode('/', trim($fullPath, '/'));
$directory = $pathParts[count($pathParts) - 2] ?? ''; // Get the parent directory (e.g., "job-scheduling")
$currentPage = end($pathParts); // Get the file name (e.g., "Sub1.php")

// Define paths based on directory and file
$paths = [
    'webpage' => [
        'dashboard.php' => " | Service Requests > Dashboard",
        'schedule.php' => " | Service Requests > Schedule",
        'appointment.php' => " | Service Requests > Appointment",
        'quotation.php' => " | Payments > Quotation",
        'services-report.php' => " | Payments > Services Report",
        'billing-statement.php' => " | Payments > Billing Statement",
        'employee-log.php' => " | Reports > Employee Log",
        'employee-pay.php' => " | Reports > Employee Pay",
        'customer-feedback.php' => " | Reports > Customer Feedback",
    ]
];

// Determine path text
$pathText = $paths[$directory][$currentPage] ?? " | Home";

?>

<div class="toggle-btn-container" id="toggle-btn-container">
    <button class="toggle-btn" onclick="toggleSidebar()">
        <img src="<?= BASE_URL ?>/assets/expand_navbar.svg" alt="Expand Navbar">
    </button>
    <p><?= $pathText; ?></p>
</div>
