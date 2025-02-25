<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
define('JUST_URL', '/CSE7PHPWebsite/public');

require PROJECT_ROOT . "/controller/employee-controller1.php";
require PROJECT_ROOT . "/controller/employee-controller2.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/employee.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/employee/employeeAdd.js"></script>
    <script src="<?= JUST_URL ?>/js/employee/employeeUpdate.js"></script>
    <script src="<?= JUST_URL ?>/js/employee/employeeDelete.js"></script>
    <script src="<?= JUST_URL ?>/js/employee/employee-filter.js"></script>
    <div class="content">
        <div class="employeeFilterBar">
            <div class="column_filter">
                <div class="filterTitle">Order By</div>
                <select class="dropdown_filter" name="StatusBy" id="dropdownOrderBy">
                    <option value="employee.id">Employee ID</option>
                    <option value="pay">Pay</option>
                    <option value="days_of_work">Work Days</option>
                </select>
            </div>
            <div class="column_filter">
                <div class="filterTitle">Status By</div>
                <select class="dropdown_filter" name="StatusBy" id="dropdownStatusBy">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="On-Leave">On-Leave</option>
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
        <div class="employeeTable">
            <?= $table_content; ?>
        </div>
        <div class="addEmployee">
            <div class="titleHeader">Create new Employee</div>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="employeeAdd_Name" type="text" placeholder="Employee Name">
                    <input class="inputField" id="employeeAdd_ContactNumber" type="number" placeholder="Contact Number">
                </div>
                <div class="column">
                    <input class="inputField" id="employeeAdd_Role" type="text" placeholder="Employee Role">
                    <input class="inputField" id="employeeAdd_Pay" type="number" placeholder="Employee Pay">
                </div>
                <div class="column">
                    <button class="submitButton" id="submitEmployeeAdd">Create</button>
                    <p class="statusNotifier" id="QueryStatusCreate"></p>
                </div>
            </div>
        </div>
        <div class="updateEmployee">
            <div class="titleHeader">Update Employee</div>
            <div class="titleContent">
                <div class="column">
                    <select class="dropdown" name="selectEmployeeID" id="UpdateEmployee_ID">
                        <option value="">Loading...</option>
                    </select>
                    <input class="inputField" id="UpdateEmployee_NewName" type="text" placeholder="New Employee Name">
                </div>
                <div class="column">
                    <input class="inputField" id="UpdateEmployee_NewContactNumber" type="number"
                        placeholder="New Contact Number">
                    <input class="inputField" id="UpdateEmployee_NewRole" type="text" placeholder="New Employee Role">
                </div>
                <div class="column">
                    <input class="inputField" id="UpdateEmployee_NewPay" type="text" placeholder="New Employee Pay">
                    <select class="dropdown" name="status" id="UpdateEmploye_NewStatus">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                        <option value="On-Leave">On-Leave</option>
                    </select>
                </div>
                <div class="column">
                    <button class="submitButton" id="submitEmployeeUpdate">Update</button>
                    <p class="statusNotifier" id="QueryStatusUpdate"></p>
                </div>
            </div>
        </div>
        <div class="deleteEmployee">
            <div class="titleHeader">Delete Employee</div>
            <div class="titleContent">
                <div class="column">
                    <select class="dropdown" name="selectEmployeeID" id="DeleteEmployee_ID">
                        <option value="">Loading...</option>
                    </select>
                    <input class="inputField" id="DeleteEmployee_Confirmation" type="text"
                        placeholder='Type "DELETE" to confirm'>
                </div>
                <div class="column">
                    <button class="submitButton" id="submitEmployeeDelete">Delete</button>
                    <p class="statusNotifier" id="QueryStatusDelete"></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>