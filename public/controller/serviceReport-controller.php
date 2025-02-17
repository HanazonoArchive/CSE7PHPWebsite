<?php
session_start();
define('PROJECT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB . "db/DBConnection.php";

class ServiceReport
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createServiceReport($appointmentID, $totalAmount, $newStatus)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM quotation WHERE appointment_id = :appointmentID LIMIT 1");
            $stmt->execute(['appointmentID' => $appointmentID]);
            $quotationID = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($quotationID) {
                $quotationID = $quotationID['id'];
                $stmt = $this->conn->prepare("INSERT INTO service_report (appointment_id, quotation_id) VALUES (:appointmentID, :quotationID)");
                $stmt->execute(['appointmentID' => $appointmentID, 'quotationID' => $quotationID]);            

                if ($stmt->rowCount() > 0) {
                    $stmt = $this->conn->prepare("UPDATE appointment SET status = :newStatus WHERE id = :appointmentID");
                    $stmt->execute(['newStatus' => $newStatus, 'appointmentID' => $appointmentID]);
                } else {
                    error_log("Failed to create service report for appointment ID: $appointmentID");
                    throw new Exception("Failed to create service report for appointment ID: $appointmentID");
                }

            } else {
                error_log("Quotation not found for appointment ID: $appointmentID");
            }
        } catch (Exception $e) {
            error_log("Error creating quotation: " . $e->getMessage());
            throw new Exception("An error occurred while creating the quotation.");
        }
    }
}


// Process the request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (isset($data["action"]) && $data["action"] === "serviceReportDATA") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $serviceReportHandler = new ServiceReport($conn);

            // Database DATA INFORMATION
            $appointmentID = trim($data["appointmentID"] ?? "");
            $totalAmount = trim($data["totalAmount"] ?? "");
            $newStatus = trim($data["status"] ?? "");

            $documentData = $data["document"] ?? []; // Get the document data safely
            $_SESSION['dHeader_SR'] = $documentData["header"] ?? [];
            $_SESSION['dBody_SR'] = $documentData["body"] ?? [];
            $_SESSION['dFooter_SR'] = $documentData["footer"] ?? [];
            $_SESSION['dTechnicianInfo_SR'] = $documentData["technicianInfo"] ?? [];

            // Call the method with employee IDs dynamically
            $serviceReportHandler->createServiceReport(
                $appointmentID,
                $totalAmount,
                $newStatus
            );

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
    } elseif (isset($data["action"]) && $data["action"] === "serviceReportTABLE") {
        try {
            $conn = Database::getInstance();
            $serviceReportHandler = new ServiceReport($conn);

            $items = $data["items"] ?? [];

            // Ensure clean buffer before output
            if (ob_get_length()) ob_clean();

            // Store items separately
            $_SESSION['itemsSR'] = $items;

            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => "Quotation items processed successfully",
                "items" => $items
            ]);
            exit;
        } catch (Exception $e) {
            if (ob_get_length()) ob_clean();

            header('Content-Type: application/json');
            echo json_encode([
                "status" => "error",
                "message" => "Failed to process the request.",
                "error" => $e->getMessage()
            ]);
            exit;
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
                JOIN customer ON appointment.customer_id = customer.id
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
                echo "No records found.";
            }
        } catch (PDOException $e) {
            echo "Error fetching data: " . $e->getMessage();
        }
    }
}

// Initialize database connection
$conn = Database::getInstance();
$appointmentManager = new AppointmentManager($conn);
