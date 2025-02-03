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

        <!-- Row 2: Split into Two Columns -->
        <div class="dashboard_main">

            <!-- Column 1: Full-width Table -->
            <div class="column_left">
                <div class="today_schedule">
                    <p>Today's Work</p>
                    <table class="work_today">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Service Type</th>
                                <th>Schedule Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "utility/dashboard/dashboardWorkToday.php" ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Column 2: Two Stacked Tables -->
            <div class="column_right_up">
                <!-- Row 1 -->
                <div class="pending_payment">
                    <p>Pending Collection</p>
                    <table class="work_today">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Total Collection</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "utility/dashboard/dashboardPendingCollection.php" ?>
                        </tbody>
                    </table>
                </div>

                <!-- Row 2: Another Table (Add Content If Needed) -->
                <div class="column_right_down">
                    <p>Employee Work</p>
                    <table class="work_today">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Day's of Work</th>
                                <th>Total Salary</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "utility/dashboard/dashboardEmployeeWork.php" ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- End of Row 2 -->

    </div>
</body>

</html>
