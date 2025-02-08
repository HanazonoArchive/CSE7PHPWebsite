<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/db/DBConnection.php";

$conn = Database::getInstance();

// Default sorting order
$default_order = "ORDER BY appointment.id ASC";

// Check if there's a new filter from a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql_query'])) {
    $query = trim($_POST['sql_query']);

    // Extract sorting and filtering options
    $orderPattern = '/ORDER BY (appointment\.(?:id|date|priority)) (ASC|DESC)/i';
    $wherePattern = "/WHERE appointment\.status = '(Pending|Confirmed|Completed)'/i";

    $orderClause = "";
    $whereClause = "";

    if (preg_match($orderPattern, $query, $matches)) {
        $column = $matches[1];
        $direction = strtoupper($matches[2]);
        $orderClause = "ORDER BY $column $direction";
    }

    if (preg_match($wherePattern, $query, $matches)) {
        $status = $matches[1];
        $whereClause = "WHERE appointment.status = '$status'";
    }

    $queryString = $whereClause;
    if (!empty($orderClause)) {
        $queryString .= " " . $orderClause;
    }

    // Fetch filtered data and return as a response
    fetchAppointments($conn, $queryString);
    exit;
}

// Function to fetch and display appointment data
function fetchAppointments($conn, $order)
{
    try {
        $stmt = $conn->prepare("SELECT 
                appointment.id AS Ticket_Number, 
                customer.name AS Customer_Name, 
                appointment.date AS Appointment_Date, 
                customer.address AS Address, 
                appointment.category AS Category, 
                appointment.priority AS Priority, 
                appointment.status AS Status,
                customer.id AS Customer_ID,
                customer.contact_number AS Contact_Number
            FROM appointment
            JOIN customer ON appointment.customer_id = customer.id
            $order");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($results) > 0) {
            echo "<table border='1' class='appointment-table'>";
            echo "<tr>";
            $headers = ['Ticket_Number', 'Customer_Name', 'Appointment_Date', 'Address', 'Category', 'Priority', 'Status'];
            foreach ($headers as $columnName) {
                echo "<th>" . htmlspecialchars(str_replace("_", " ", $columnName)) . "</th>";
            }
            echo "</tr>";

            foreach ($results as $row) {
                // Convert row data into a JSON string for JS handling
                $rowData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                echo "<tr onclick='updateDetails($rowData)'>";
                foreach ($headers as $column) {
                    echo "<td>" . htmlspecialchars($row[$column]) . "</td>";
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
}

// Default query for initial page load
ob_start();
fetchAppointments($conn, $default_order);
$table_content = ob_get_clean();
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
                        <button class="filter_button" data-status="Confirmed">Confirmed</button>
                        <button class="filter_button" data-status="Completed">Completed</button>
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
