<?php
define('BASE_URL', '/CSE7PHPWebsite/public');
define('SPECIFIC_URL', '/CSE7PHPWebsite')
    ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/css/sidebar.css">
<script src="<?= BASE_URL ?>/js/sidebar.js" defer></script>

<div class="sidebar" id="sidebar">

    <a href="<?= SPECIFIC_URL ?>/public/webpage/dashboard.php" class="logo" id="logo">
        <img class="logo_icon" src="<?= BASE_URL ?>/assets/Firefly.png">
        <span class="logo-text">Coolant</span>
    </a>

    <div class="menu">
        <div class="menu-item" onclick="toggleSubmenu(1)">
            <img src="<?= BASE_URL ?>/assets/schedule-and-appointment.svg">
            <span class="menu-text">Service Requests</span>
        </div>
        <div class="submenu" id="submenu-1">
            <a href="<?= BASE_URL ?>/webpage/dashboard.php">Dashboard</a>
            <a href="<?= BASE_URL ?>/webpage/schedule.php">Schedule</a>
            <a href="<?= BASE_URL ?>/webpage/appointment.php">Appointment</a>
        </div>
        <div class="menu-item" onclick="toggleSubmenu(2)">
            <img src="<?= BASE_URL ?>/assets/billing-and-invoice.svg">
            <span class="menu-text">Payments</span>
        </div>
        <div class="submenu" id="submenu-2">
            <a href="<?= BASE_URL ?>/webpage/quotation.php">Quotation</a>
            <a href="<?= BASE_URL ?>/webpage/services-report.php">Services Report</a>
            <a href="<?= BASE_URL ?>/webpage/billing-statement.php">Billing Statement</a>
        </div>
        <div class="menu-item" onclick="toggleSubmenu(3)">
            <img src="<?= BASE_URL ?>/assets/tracking-and-report.svg">
            <span class="menu-text">Reports</span>
        </div>
        <div class="submenu" id="submenu-3">
            <a href="<?= BASE_URL ?>/webpage/customer.php">Customer</a>
            <a href="<?= BASE_URL ?>/webpage/employee.php">Employee</a>
            <a href="<?= BASE_URL ?>/webpage/employee-log.php">Employee Log</a>
        </div>
    </div>
</div>