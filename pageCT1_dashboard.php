<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/page-css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <?php require "components/navbar.php"; ?>

    <div class="dashboard_content">

        <!-- Row 1: Dashboard Title -->
        <div class="dashboard_title">
            <h1>Dashboard</h1>
        </div>

        <!-- Row 2: Dashboard Main Layout -->
        <div class="dashboard_main">

            <!-- Column 1: Left Side (Today's Work) -->
            <div class="column_left">
                <div class="today_schedule">
                    <p>Today's Work</p>
                    <div class="table_wrapper_left">
                        <table class="today_work">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Service Type</th>
                                    <th>Schedule Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php include "utility/dashboard/dashboardWorkToday.php"; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Column 2: Right Side (Stacked Rows) -->
            <div class="column_right">

                <!-- Row 1: Pending Collection -->
                <div class="column_right_up">
                    <div class="pending_payment">
                        <p>Pending Collection</p>
                        <div class="table_wrapper_right">
                            <table class="pending_collection">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Total Collection</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include "utility/dashboard/dashboardPendingCollection.php"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Employee Work -->
                <div class="column_right_down">
                    <div class="employee_work_section">
                        <p>Employee Work</p>
                        <div class="table_wrapper_right">
                            <table class="employee_work">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Days of Work</th>
                                        <th>Total Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php include "utility/dashboard/dashboardEmployeeWork.php"; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> <!-- End of Column Right -->

        </div> <!-- End of Dashboard Main -->

    </div> <!-- End of Dashboard Content -->

</body>

</html>