<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/db/DBConnection.php";

// Get the database instance
$conn = Database::getInstance();

$query = "
    SELECT
        appointment.id AS Ticket_Number, 
        customer.name AS Customer_Name, 
        appointment.date AS Appointment_Date, 
        customer.address AS Address, 
        appointment.category AS Category, 
        appointment.priority AS Priority, 
        appointment.status AS Status
    FROM appointment
    JOIN customer ON appointment.customer_id = customer.id
";

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
    <script src="<?= JUST_URL ?>/js/schedule.js"></script>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>

    <div class="content">
        <div class="appointment-holderv3">
            <div class="appointment_filter">
                <div class="filter-column">
                    <p class="filter_header">Filter by</p>
                    <div class="filter-column-row">
                        <button class="filter_button" id="filter_DATE">Date</button>
                        <button class="filter_button" id="filter_PRIORITY">Priority</button>
                        <button class="filter_button" id="filter_TICKET">Ticket #</button>
                    </div>
                </div>
                <div class="filter-column">
                    <p class="filter_header">Status</p>
                    <div class="filter-column-row">
                        <button class="filter_button" id="status_PENDING">Pending</button>
                        <button class="filter_button" id="status_CONFIRMED">Confirmed</button>
                        <button class="filter_button" id="status_COMPLETED">Completed</button>
                    </div>
                </div>
                <div class="filter-column">
                    <p class="filter_header">Order by</p>
                    <div class="filter-column-row">
                        <button class="filter_button" id="order_ASC">Ascending</button>
                        <button class="filter_button" id="order_DESC">Descending</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="appointment-holder">
            <div class="appointment-table">
                <?php
                try {
                    $stmt = $conn->prepare($query);
                
                    if ($filterType === "filter" || $filterType === "status") {
                        $stmt->bindParam(":filterValue", $filterValue);
                    }
                
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                    if (count($results) > 0) {
                        echo "<table border='1'><tr>";
                
                        foreach (array_keys($results[0]) as $columnName) {
                            echo "<th>" . htmlspecialchars(str_replace("_", " ", $columnName)) . "</th>";
                        }
                
                        echo "</tr>";
                
                        foreach ($results as $row) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }
                
                        echo "</table>";
                    } else {
                        echo "No records found.";
                    }
                } catch (PDOException $e) {
                    echo "Error fetching data: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
        <div class="appointment-holderv2">
            <div class="appointment_details">
                <p class="appointment_header">Schedule Details</p>
                <div class="information-column">
                    <div class="column">
                        <p class="information_header">Customer ID</p>
                        <p class="highlighted_information" id="customer_id">1</p>
                        <p class="information_header">Customer Name</p>
                        <p class="highlighted_information" id="customer_name"> Yurine Rose Hanazono</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Contact Number</p>
                        <p class="highlighted_information" id="customer_contact-number">09551004950 </p>
                        <p class="information_header">Address</p>
                        <p class="highlighted_information" id="customer_address">Davao City</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Appointment ID</p>
                        <p class="highlighted_information" id="appointment_id">1</p>
                        <p class="information_header">Date</p>
                        <p class="highlighted_information" id="appointment_date">2025-01-7</p>
                    </div>
                    <div class="column">
                        <p class="information_header">Category</p>
                        <p class="highlighted_information" id="appointment_category">Installation</p>
                        <p class="information_header">Priority</p>
                        <p class="highlighted_information" id="appointment_priority">High</p>
                    </div>

                    <div class="column">
                        <p class="information_header">Status</p>
                        <p class="highlighted_information" id="appointment_status">Pending</p>
                        <p class="information_header">Assign</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>