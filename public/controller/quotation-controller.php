<?php
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
            $stmt = $this->conn->prepare("SELECT id FROM quotation WHERE appointment_id = :id LIMIT 1");
            $stmt->execute(['id' => $appointmentID]);
            $quotation = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($quotation) {
                error_log("Quotation ID $appointmentID already exists.");
            } else {
                $stmt = $this->conn->prepare("INSERT INTO quotation (appointment_id, amount) VALUES (:appointment_id, :totalAmount)");
                $stmt->execute(['appointment_id' => $appointmentID, 'totalAmount' => $totalAmount]);

                if ($stmt->rowCount() > 0) {
                    $stmt = $this->conn->prepare("UPDATE appointment SET status = :newStatus WHERE id = :appointmentID");
                    $stmt->execute(['newStatus' => $newStatus, 'appointmentID' => $appointmentID]);
                } else {
                    error_log("Failed to insert quotation.");
                }
            }
        } catch (Exception $e) {
            error_log("Error creating quotation: " . $e->getMessage());
            throw new Exception("An error occurred while creating the quotation.");
        }
    }
}

// Process requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? ""; // For form data

    if ($action === "insertQuotation") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $quotationHandler = new Quotation($conn);

            // Handle URL-encoded form data
            $appointmentID = trim($_POST["appointmentID"] ?? "");
            $employeeID1 = trim($_POST["employeeID1"] ?? "");
            $employeeID2 = trim($_POST["employeeID2"] ?? "");
            $employeeID3 = trim($_POST["employeeID3"] ?? "");
            $newStatus = trim($_POST["status"] ?? "");
            $totalAmount = trim($_POST["totalAmount"] ?? "");

            $quotationHandler->createQuotation($employeeID1, $employeeID2, $employeeID3, $appointmentID, $totalAmount, $newStatus);

            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "message" => "Quotation created successfully"]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Failed to process the request."]);
        }
    } elseif ($action === "insertQuotationItems") {
        try {
            $items = $_POST["items"] ?? [];
    
            if (empty($items)) {
                throw new Exception("No items received.");
            }
    
            session_start();
            $_SESSION["quotation_items"] = $items;
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }    
}
