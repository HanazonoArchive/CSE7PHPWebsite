<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

require PROJECT_ROOT . "/controller/dashboard-controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="<?= JUST_URL ?>/js/dashboard/dashboardFunctions.js"></script>
    <div class="content">
        <div class="dashboardDivider1">
            <div class="dashboardDivider1_1">
                <div class="dashboardDivider1_1_1">
                    <p class="titleHeader">Schedule this Week</p>
                    <div class="scheduleTable">
                        <?= $scheduleWeekTable; ?>
                    </div>
                </div>
                <div class="dashboardDivider1_1_2">
                    <p class="titleHeader">Pending Collection</p>
                    <div class="pendingTable">
                        <?= $pendingCollectionTable; ?>
                    </div>
                </div>
            </div>
            <div class="dashboardDivider1_2">
                <div class="dashboardDivider1_2_1">
                    <div class="dashboardDivider1_2_1_1">
                        <p class="titleHeader">Employee Available</p>
                        <div class="progressContainer">
                            <div id="employeeProgress" class="progressCircle">
                                <div class="centerCircle">
                                    <span id="employeeCount">0/0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dashboardDivider1_2_1_2">
                        <p class="titleHeader">Completed Appointment</p>
                        <div class="progressContainer">
                            <div id="appointmentProgress" class="progressCircle">
                                <div class="centerCircle">
                                    <span id="appointmentCount">0/0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboardDivider1_2_2">
                    <div class="dashboardDivider1_2_2_1">
                        <p class="titleHeader">Employee</p>
                        <div class="employeeTable">
                            <?= $employeeTable; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>