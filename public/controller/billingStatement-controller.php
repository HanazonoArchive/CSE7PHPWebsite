<?php
session_start();
define('PROJECT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB . "db/DBConnection.php";

class BillingStatement
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createServiceReport($appointmentID)
    {
        try {
            // 1st: get the ID from quotation using appointment.id
            $stmt1 = $this->conn->prepare("SELECT id, amount FROM quotation WHERE appointment_id = :appointmentID LIMIT 1");
            $stmt1->execute(['appointmentID' => $appointmentID]);
            $quotation = $stmt1->fetch(PDO::FETCH_ASSOC);

            // 2nd: put the quotation ID into a variable
            $quotationID = $quotation['id']; // QUOTATION ID
            $quotationAmount = $quotation['amount']; // QUOTATION AMOUNT

            // 3rd: get the ID from service_report using quotation.id
            $stmt2 = $this->conn->prepare("SELECT id, amount FROM service_report WHERE quotation_id = :quotationID LIMIT 1");
            $stmt2->execute(['quotationID' => $quotationID]);
            $serviceReport = $stmt2->fetch(PDO::FETCH_ASSOC);

            // 4th: put the serviceReport ID into a variable
            $serviceReportID = $serviceReport['id']; // SERVICE REPORT ID
            $serviceReportAmount = $serviceReport['amount']; // SERVICE REPORT AMOUNT

            if ($quotationID && $serviceReportID && $quotationAmount && $serviceReportAmount) {

                // 5th: check if the billing statement already exist
                $stmt3 = $this->conn->prepare("SELECT id FROM billing_statement WHERE quotation_id = :quotationID AND service_report_id = :serviceReportID LIMIT 1");
                $stmt3->execute(['quotationID' => $quotationID, 'serviceReportID' => $serviceReportID]);
                $billingStatement = $stmt3->fetch(PDO::FETCH_ASSOC);

                if ($billingStatement) {
                    error_log("Billing Statement already exist!");
                } else {
                    error_log("QuotationID, ServiceReportID, quotationAmount, and serviceReportAmount was found!");

                    $newTotalAmount = $quotationAmount + $serviceReportAmount;

                    $stmt4 = $this->conn->prepare("INSERT INTO billing_statement (quotation_id, service_report_id, amount) VALUES (:quotationID, :serviceReportID, :newAmount)");
                    $stmt4->execute(['quotationID' => $quotationID, 'serviceReportID' => $serviceReportID, 'newAmount' => $newTotalAmount]);

                    $_SESSION['BillingStatementID'] = $this->conn->lastInsertId();
                    $_SESSION['Amount_BS'] = $newTotalAmount;
                    $_SESSION['QuotationID_BS'] = $quotationID;
                    $_SESSION['QuotationAmount_BS'] = $quotationAmount;
                    $_SESSION['ServiceReportID_BS'] = $serviceReportID;
                    $_SESSION['ServiceReportAmount_BS'] = $serviceReportAmount;

                    error_log("all the INFO has been SESSIONED! (Billing Statement)");
                }
            }
        } catch (Exception $e) {
            error_log("Error creating service report: " . $e->getMessage());
            throw new Exception("An error occurred while creating the service report.");
        }
    }
}

// Process the request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (isset($data["action"]) && $data["action"] === "billingStatementDATA") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $serviceReportHandler = new BillingStatement($conn);

            // Database DATA INFORMATION
            $appointmentID = trim($data["appointmentID"] ?? "");

            $documentData = $data["document"] ?? []; // Get the document data safely
            $_SESSION['dHeader_BS'] = $documentData["header"] ?? [];
            $_SESSION['dBody_BS'] = $documentData["body"] ?? [];
            $_SESSION['dFooter_BS'] = $documentData["footer"] ?? [];

            // Call the method with employee IDs dynamically
            $serviceReportHandler->createServiceReport($appointmentID);

            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "message" => "Quotation created successfully"]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "error",
                "message" => "Failed to process the request.",
                "error" => $e->getMessage()
            ]);
        }
    }
}

class AppointmentManager
{
    private $conn;
    private $default_order = "ORDER BY appointment.id ASC";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function fetchAppointments($order = null)
    {
        try {
            $order = $order ?? $this->default_order;

            $stmt = $this->conn->prepare("SELECT
                    customer.name AS Customer_Name,
                    customer.address AS Customer_Address,
                    appointment.id AS Appointment_ID,
                    appointment.category AS Appointment_Category,
                    appointment.date AS Appointment_Date,
                    appointment.status AS Appointment_Status
                FROM appointment
                JOIN customer ON appointment.customer_id = customer.id WHERE appointment.status = 'Completed'
                $order");

            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($results) {
                echo "<table border='1' class='appointment-table'>";
                echo "<th>Customer Name</th><th>Address</th><th>Appointment ID</th><th>Category</th><th>Date</th><th>Status</th></tr>";

                foreach ($results as $row) {
                    echo "<tr onclick='updateDetails(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")'>";
                    echo "<td>{$row['Customer_Name']}</td><td>{$row['Customer_Address']}</td><td>{$row['Appointment_ID']}</td><td>{$row['Appointment_Category']}</td><td>{$row['Appointment_Date']}</td><td>{$row['Appointment_Status']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No Completed Work Orders found.";
            }
        } catch (PDOException $e) {
            echo "Error fetching data: " . $e->getMessage();
        }
    }
    public function fetchAppointmentIDs()
    {
        try {
            $stmt = $this->conn->prepare("
            SELECT appointment.id, customer.name 
            FROM appointment
            JOIN customer ON appointment.customer_id = customer.id
            WHERE appointment.status = 'Completed'
            ORDER BY appointment.id ASC
        ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode($results);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching appointment IDs: " . $e->getMessage()]);
        }
    }
}

// Check if request is made to fetch appointment IDs
if (isset($_GET['fetch_appointments'])) {
    $conn = Database::getInstance();
    $appointmentManager = new AppointmentManager($conn);
    $appointmentManager->fetchAppointmentIDs(); // Calls the function to output JSON
    exit; // Stop further execution
}

// Initialize database connection
$conn = Database::getInstance();
$appointmentManager = new AppointmentManager($conn);
