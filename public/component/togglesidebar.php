<?php
// Get absolute path
$fullPath = $_SERVER['SCRIPT_NAME']; // Example: "/webpage/job-scheduling/Sub1.php"

// Extract directory and file name
$pathParts = explode('/', trim($fullPath, '/'));
$directory = $pathParts[count($pathParts) - 2] ?? ''; // Get the parent directory (e.g., "job-scheduling")
$currentPage = end($pathParts); // Get the file name (e.g., "Sub1.php")

// Define paths based on directory and file
$paths = [
    'schedule-and-appointment' => [
        'dashboard.php' => " | Schedule and Appointment > Dashboard",
        'schedule.php' => " | Schedule and Appointment > Schedule",
        'appointment.php' => " | Schedule and Appointment > Appointment",
    ],
    'billing-and-invoice' => [
        'quotation.php' => " | Billing and Invoice > Quotation",
        'services-report.php' => " | Billing and Invoice > Services Report",
        'billing-statement.php' => " | Billing and Invoice > Billing Statement",
    ],
    'tracking-and-report' => [
        'employee-log.php' => " | Tracking and Report > Employee Log",
        'employee-pay.php' => " | Tracking and Report > Employee Pay",
        'customer-feedback.php' => " | Tracking and Report > Customer Feedback",
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
