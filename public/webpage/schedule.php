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
        <div class="filterBar">
            <div class="Column">
                <p class="filterTitle">Order by</p>
                <select class="dropdown" name="OrderBy" id="dropdownOrderBy">
                    <option value="appointment.id">Ticket</option>
                    <option value="Priority">Priority</option>
                    <option value="Date">Date</option>
                </select>
            </div>
            <div class="Column">
                <p class="filterTitle">Filter by</p>
                <select class="dropdown" name="FilterBy" id="dropdownFilterBy">
                    <option value="Pending">Pending</option>
                    <option value="Working">Working</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="Column">
                <p class="filterTitle">Sort by</p>
                <select class="dropdown" name="SortBy" id="dropdownSortBy">
                    <option value="ASC">Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
            </div>
            <button class="filterApplyButton" type="button">Apply</button>
            <p class="filterStatus" id="QueryStatus"></p>
        </div>
        <div class="scheduleBar">
            <div class="scheduleTable">
                <?= $table_content; ?>
            </div>
            <div class="scheduleDetails">
                <p class="detailsHeader">Customer Information</p>
                <div class="detailsRow">
                    <p class=detailsTitle>Customer ID</p>
                    <p class="detialsContent" id="customer_id">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Customer Name</p>
                    <p class="detialsContent" id="customer_name">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Contact Number</p>
                    <p class="detialsContent" id="customer_contact-number">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Address</p>
                    <p class="detialsContent" id="customer_address">-</p>
                </div>
                <hr>
                <p class="detailsHeader">Appoinment Information</p>
                <div class="detailsRow">
                    <p class=detailsTitle>Appointment ID</p>
                    <p class="detialsContent" id="appointment_id">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Date</p>
                    <p class="detialsContent" id="appointment_date">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Category</p>
                    <p class="detialsContent" id="appointment_category">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Priority</p>
                    <p class="detialsContent" id="appointment_priority">-</p>
                </div>
                <div class="detailsRow">
                    <p class=detailsTitle>Status</p>
                    <p class="detialsContent" id="appointment_status">-</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>