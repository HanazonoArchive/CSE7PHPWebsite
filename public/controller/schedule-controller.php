<?php
define('PROJECT_ROOT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
include PROJECT_ROOT_DB . "/db/DBConnection.php";

class AppointmentManager {
    private $conn;
    private $default_order = "ORDER BY appointment.id ASC"; // Define as a class property

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchAppointments($order = null) {
        try {
            $order = $order ?? $this->default_order; // Use default order if not provided
            
            $stmt = $this->conn->prepare("SELECT 
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

    public function handlePostRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql_query'])) {
            $query = trim($_POST['sql_query']);

            // Extract sorting and filtering options
            $orderPattern = '/ORDER BY (appointment\.(?:id|date|priority)) (ASC|DESC)/i';
            $wherePattern = "/WHERE appointment\.status = '(Pending|Working|Completed|Cancelled)'/i";

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

            $this->fetchAppointments($queryString);
            exit;
        }
    }
}

// Initialize the database connection
$conn = Database::getInstance();
$appointmentManager = new AppointmentManager($conn);

// Handle POST request if any
$appointmentManager->handlePostRequest();

// Fetch appointments for the initial page load
ob_start();
$appointmentManager->fetchAppointments();
$table_content = ob_get_clean();