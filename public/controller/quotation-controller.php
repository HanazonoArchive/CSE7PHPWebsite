<?php
session_start();
define('PROJECT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB . "db/DBConnection.php";

class Quotation
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createQuotation($employeeID1, $employeeID2, $employeeID3, $appointmentID, $totalAmount, $newStatus)
    {
        try {

            // 1st: Check if the quotation already exists
            $stmt1 = $this->conn->prepare("SELECT id FROM quotation WHERE appointment_id = :id LIMIT 1");
            $stmt1->execute(['id' => $appointmentID]);
            $quotation = $stmt1->fetch(PDO::FETCH_ASSOC);

            if ($quotation) {
                // 2nd: Notify the existing quotation
                error_log("Quotation ID $appointmentID already exists.");
            } else {
                // 2nd: Create a new quotation
                error_log("Creating new quotation for appointment ID: $appointmentID");
                $stmt2 = $this->conn->prepare("INSERT INTO quotation (appointment_id, amount) VALUES (:appointment_id, :totalAmount)");
                $stmt2->execute(['appointment_id' => $appointmentID, 'totalAmount' => $totalAmount]);
                $quotationID = $this->conn->lastInsertId();

                $_SESSION['quotationID_QUO'] = $quotationID; // Store the quotation ID in the session

                if ($quotationID) {
                    // 3rd: Update the appointment status to new status
                    $stmt3 = $this->conn->prepare("UPDATE appointment SET status = :newStatus WHERE id = :appointmentID");
                    if ($stmt3->execute(['newStatus' => $newStatus, 'appointmentID' => $appointmentID])) {
                        error_log("Appointment ID $appointmentID updated successfully.");

                        if (isset($_SESSION['data_QUO'])) {
                            error_log("Data successfully stored in session.");
                            $data = $_SESSION['data_QUO'];
                            $this->createQuotationTableData($data);
                        } else {
                            error_log("Failed to store data in session.");
                            throw new Exception("Failed to store data in session.");
                        }
                    } else {
                        error_log("Failed to update appointment status.");
                    }
                } else {
                    error_log("Failed to insert quotation.");
                }
            }
        } catch (Exception $e) {
            error_log("Error creating quotation: " . $e->getMessage());
            throw new Exception("An error occurred while creating the quotation.");
        }
    }

    public function createQuotationTableData($data)
    {
        try {
            // 1st: Check if the Quotation ID exists
            $quotationID = $_SESSION['quotationID_QUO'];
            if (!$quotationID) {
                error_log("Quotation ID is not found.");
                throw new Exception("Quotation ID is required.");
            } else {
                error_log("Quotation ID found: $quotationID");

                // 2nd: Check if the data for Quotation exists
                $stmt1 = $this->conn->prepare("SELECT id FROM quotation_data WHERE quotation_id = :id LIMIT 1");
                $stmt1->execute(['id' => $quotationID]);
                $quotationdataID = $stmt1->fetch(PDO::FETCH_ASSOC);

                if ($quotationdataID !== false) {
                    error_log("Quotation data already exists.");
                } else {
                    // 3rd: Create a new quotation data
                    error_log("Creating new quotation data for quotation ID: $quotationID");

                    $data = json_encode($data); // Convert the data to JSON
                    $stmt2 = $this->conn->prepare("INSERT INTO quotation_data (quotation_id, data) VALUES (:quotation_id, :jsonData)");
                    if ($stmt2->execute(['quotation_id' => $quotationID, 'jsonData' => $data])) {
                        error_log("Quotation data ID created successfully.");
                    } else {
                        error_log("Failed to insert quotation data.");
                    }
                    if ($stmt2->rowCount() > 0) {
                        error_log("Quotation data ID $quotationdataID created successfully.");
                    } else {
                        error_log("Failed to insert quotation data.");
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error creating quotation table data: " . $e->getMessage());
            throw new Exception("An error occurred while creating the quotation table data.");
        }
    }
}


// Process the request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    if (isset($data["action"]) && $data["action"] === "quotationDATA") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $quotationHandler = new Quotation($conn);

            // Database DATA INFORMATION
            $appointmentID = trim($data["appointmentID"] ?? "");
            $employees = $data["employees"] ?? [];
            $totalAmount = trim($data["totalAmount"] ?? "");
            $newStatus = trim($data["status"] ?? "");

            $documentData = $data["document"] ?? []; // Get the document data safely
            $_SESSION['dHeader'] = $documentData["header"] ?? [];
            $_SESSION['dBody'] = $documentData["body"] ?? [];
            $_SESSION['dFooter'] = $documentData["footer"] ?? [];
            $_SESSION['dTechnicianInfo'] = $documentData["technicianInfo"] ?? [];

            // Ensure at least one employee ID is provided
            if (empty($employees)) {
                throw new Exception("At least one employee ID is required.");
            }

            // Call the method with employee IDs dynamically
            $quotationHandler->createQuotation(
                $employees[0] ?? "",
                $employees[1] ?? "",
                $employees[2] ?? "",
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
    } elseif (isset($data["action"]) && $data["action"] === "quotationTABLE") {
        try {
            $conn = Database::getInstance();

            $items = $data["items"] ?? [];

            if (ob_get_contents()) {
                ob_end_clean();
            }

            // Store items separately
            $_SESSION['items'] = $items;
            $_SESSION['data_QUO'] = $data;

            header('Content-Type: application/json');
            echo json_encode([
                "status" => "success",
                "message" => "Quotation items processed successfully",
                "items" => $items
            ]);
            exit;
        } catch (Exception $e) {
            if (ob_get_length()) {
                ob_clean();
            }

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
