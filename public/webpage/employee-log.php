<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

require PROJECT_ROOT . "/controller/employeeLog-controller1.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/employee-log.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <title>Employee Log</title>
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/employee-log/employeeLog-filter.js"></script>
    <div class="content">
        <div class="customerFilterBar">
            <div class="column_filter">
                <div class="filterTitle">Order By</div>
                <select class="dropdown_filter" name="Orderby" id="dropdownOrderBy">
                    <option value="appointment.date">Date</option>
                    <option value="employee_log.id">Log Number</option>
                    <option value="employee_log.employee_id">Employee ID</option>
                    <option value="appointment.id">Appointment ID</option>
                </select>
            </div>
            <div class="column_filter">
                <div class="filterTitle">Sort By</div>
                <select class="dropdown_filter" name="Sortby" id="dropdownSortBy">
                    <option value="ASC">Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
            </div>
            <button class="filterApplyButton" type="button">Apply</button>
            <p class="filterStatus" id="QueryStatus"></p>
        </div>
        <div class="employeeLogTable">
            <?= $table_content; ?>
        </div>
    </div>
</body>

</html>