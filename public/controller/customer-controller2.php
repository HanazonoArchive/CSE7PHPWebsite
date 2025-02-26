<?php
define('PROJECT_DB2', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB2 . "db/DBConnection.php";

class CustomerFeedbackManager
{
    private $conn;
    private $default_order = "ORDER BY customer_feedback.id ASC"; // Define as a class property

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchCustomerFeedback($order = null)
    {
        try {
            $order = $order ?? $this->default_order; // Use default order if not provided

            $stmt = $this->conn->prepare("SELECT 
                    customer_feedback.id AS Feedback_ID, 
                    customer_feedback.appointment_id AS Appointment_ID, 
                    customer_feedback.feedback AS Feedback
                FROM customer_feedback $order");

            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($results) > 0) {
                echo "<table border='1' class='appointment-table'>";
                echo "<tr>";
                $headers = ['Feedback_ID', 'Appointment_ID', 'Feedback'];
                foreach ($headers as $columnName) {
                    echo "<th>" . htmlspecialchars(str_replace("_", " ", $columnName)) . "</th>";
                }
                echo "</tr>";

                foreach ($results as $row) {
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

    public function handlePostRequest()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sql_query'])) {
            $query = trim($_POST['sql_query']);

            // Ensure the query always has a valid ORDER BY
            if (!str_contains($query, "ORDER BY")) {
                $query .= " ORDER BY customer_feedback.id ASC";
            }

            $this->fetchCustomerFeedback($query);
            exit;
        }
    }
    public function fetchCustomerIDs()
    {
        try {
            // Enable error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            // Ensure proper headers are set before any output
            header('Content-Type: application/json');

            // Debug: Check if database connection is valid
            if (!$this->conn) {
                echo json_encode(["error" => "Database connection is not initialized."]);
                exit;
            }

            // Debug: Print SQL query before execution
            $sql = "
            SELECT customer.id, customer.name 
            FROM customer
            ORDER BY customer.id ASC
        ";
            // Debug: Log the query
            error_log("Executing SQL: " . $sql);

            $stmt = $this->conn->prepare($sql);

            if (!$stmt) {
                echo json_encode(["error" => "SQL statement preparation failed."]);
                exit;
            }

            // Debug: Execute and check for errors
            if (!$stmt->execute()) {
                echo json_encode(["error" => "SQL execution failed.", "info" => $stmt->errorInfo()]);
                exit;
            }

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Debug: Check if results are empty
            if (empty($results)) {
                echo json_encode(["message" => "No pending appointments found."]);
                exit;
            }

            echo json_encode($results);
        } catch (PDOException $e) {
            // Debug: Ensure error is JSON formatted
            echo json_encode(["error" => "Exception occurred: " . $e->getMessage()]);
        }
    }
}

if (isset($_GET['fetch_Customer'])) {
    $conn = Database::getInstance();
    $feedbackManager = new CustomerFeedbackManager($conn);
    $feedbackManager->fetchCustomerIDs(); // Calls the function to output JSON
    exit; // Stop further execution
}

// Initialize the database connection
$conn = Database::getInstance();
$customerFeedbackManager = new CustomerFeedbackManager($conn);

// Handle POST request if any
$customerFeedbackManager->handlePostRequest();

// Fetch appointments for the initial page load
ob_start();
$customerFeedbackManager->fetchCustomerFeedback();
$table_content_feedback = ob_get_clean();
