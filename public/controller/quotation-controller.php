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
            $quotationHandler = new Quotation($conn);

            $items = $data["items"] ?? [];

            // Ensure clean buffer before output
            if (ob_get_length()) ob_clean();

            // Store items separately
            $_SESSION['items'] = $items;

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
