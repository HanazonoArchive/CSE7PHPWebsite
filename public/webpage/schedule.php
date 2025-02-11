<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/controller/schedule-controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/schedule.css">
</head>

<body>
    <script src="<?= JUST_URL ?>/js/schedule/schedule-filter.js"></script>
    <script src="<?= JUST_URL ?>/js/schedule/schedule-details.js"></script>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>

    <div class="content">
        <div class="appointment-holderv3">
            <div class="appointment_filter">
                <div class="filter-column">
                    <p class="filter_header">Order by</p>
                    <div class="filter-column-row">
                        <button class="filter_button" data-filter="appointment.date">Date</button>
                        <button class="filter_button" data-filter="appointment.priority">Priority</button>
                        <button class="filter_button" data-filter="appointment.id">Ticket #</button>
                    </div>
                </div>
                <div class="filter-column">
                    <p class="filter_header">Filter by</p>
                    <div class="filter-column-row">
                        <button class="filter_button" data-status="Pending">Pending</button>
                        <button class="filter_button" data-status="Working">Working</button>
                        <button class="filter_button" data-status="Completed">Completed</button>
                        <button class="filter_button" data-status="Cancelled">Cancelled</button>
                    </div>
                </div>
                <div class="filter-column">
                    <p class="filter_header">Sort by</p>
                    <div class="filter-column-row">
                        <button class="filter_button active" data-order="ASC">Ascending</button>
                        <button class="filter_button" data-order="DESC">Descending</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="appointment-holder">
            <div class="appointment-table">
                <?= $table_content; ?>
            </div>
        </div>
        <div class="appointment-holderv2">
            <div class="appointment_details">
                <p class="appointment_header">Schedule Details</p>
                <div class="information-column">
                    <div class="column">
                        <p class="information_header">Customer ID</p>
                        <p class="highlighted_information" id="customer_id">-</p>
                        <p class="information_header">Customer Name</p>
                        <p class="highlighted_information" id="customer_name">-</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Contact Number</p>
                        <p class="highlighted_information" id="customer_contact-number">-</p>
                        <p class="information_header">Address</p>
                        <p class="highlighted_information" id="customer_address">-</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Appointment ID</p>
                        <p class="highlighted_information" id="appointment_id">-</p>
                        <p class="information_header">Date</p>
                        <p class="highlighted_information" id="appointment_date">-</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Category</p>
                        <p class="highlighted_information" id="appointment_category">-</p>
                        <p class="information_header">Priority</p>
                        <p class="highlighted_information" id="appointment_priority">-</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Status</p>
                        <p class="highlighted_information" id="appointment_status">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
