<?php
$menuItems = [
    'schedule-and-appointment' => [
        'label' => 'Schedule & Appointment',
        'subitems' => [
            'dashboard.php' => 'Dashboard',
            'schedule.php' => 'Schedule',
            'appointment-details.php' => 'Appointment Details'
        ]
    ],
    'billing-and-invoice' => [
        'label' => 'Billing & Invoice',
        'subitems' => [
            'quotation.php' => 'Quotation',
            'service-call-report.php' => 'Service Call Report',
            'billing-statement.php' => 'Billing Statement'
        ]
    ],
    'tracking-and-report' => [
        'label' => 'Tracking & Report',
        'subitems' => [
            'employee-work-log.php' => 'Employee Work Log',
            'employee-pay.php' => 'Employee Pay',
            'customer-feedback.php' => 'Customer Feedback'
        ]
    ],
    'admin' => [
        'label' => 'Admin',
        'subitems' => [
            'user-management.php' => 'User Management',
            'system-settings.php' => 'System Settings'
        ]
    ]
];

// Define the icons for each menu item (main and submenus)
$iconMapping = [
    'appointment-details.php' => 'Icons/White/AppointmentDetails.png',
    'billing-statement.php' => 'Icons/White/BillingStatement.png',
    'customer-feedback.php' => 'Icons/White/CustomerFeedback.png',
    'dashboard.php' => 'Icons/White/Dashboard.png',
    'employee-pay.php' => 'Icons/White/EmployeePay.png',
    'employee-work-log.php' => 'Icons/White/EmployeeWorkLog.png',
    'quotation.php' => 'Icons/White/Quotation.png',
    'schedule.php' => 'Icons/White/Schedule.png',
    'service-call-report.php' => 'Icons/White/ServiceCallReport.png',
    'system-settings.php' => 'Icons/White/SystemSettings.png',
    'user-management.php' => 'Icons/White/UserManagement.png',
];
?>

<div class="sidebar">
    <?php echo '<link rel="stylesheet" type="text/css" href="assets/css/sidebar.css">'; ?>
    <?php echo '<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Press+Start+2P&display=swap" rel="stylesheet">'?>

    

    <?php foreach ($menuItems as $key => $section): ?>
        <div class="menu-section">
            <a href="javascript:void(0);" class="menu-header" onclick="toggleMenu('<?php echo $key; ?>')">
                <!-- Only display icon if it exists in the icon mapping, otherwise show only the label -->
                <?php if (isset($iconMapping[$key])): ?>
                    <img src="<?php echo $iconMapping[$key]; ?>" class="menu-icon" alt="<?php echo $section['label']; ?> Icon">
                <?php endif; ?>
                <?php echo $section['label']; ?>
            </a>
            <div id="<?php echo $key; ?>-submenu" class="submenu" style="display: none;">
                <?php foreach ($section['subitems'] as $href => $label): ?>
                    <a href="<?php echo $href; ?>" class="submenu-item">
                        <!-- Use the mapped icon for each submenu item -->
                        <img src="<?php echo $iconMapping[$href]; ?>" class="submenu-icon" alt="<?php echo $label; ?> Icon">
                        <?php echo $label; ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Link external script -->
<script src="scripts/sidebar.js"></script>
