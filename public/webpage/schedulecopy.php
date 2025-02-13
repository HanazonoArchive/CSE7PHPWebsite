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
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/schedulecopy.css">
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
                    <option value="Date">Date</option>
                    <option value="Priority">Priority</option>
                    <option value="Ticket">Ticket</option>
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
        </div>
        <div class="scheduleBar">
            <div class="scheduleTable">
                <p>TABLE HERE</p>
            </div>
            <div class="scheduleDetails">
                <p>DETAILS HERE</p>
            </div>
        </div>
    </div>
</body>

</html>