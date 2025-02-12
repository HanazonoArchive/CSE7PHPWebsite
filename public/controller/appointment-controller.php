<?php

define('PROJECT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB . "db/DBConnection.php";

class Customer
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function findOrCreateCustomer($name, $contact_number, $address)
    {
        try {
            // Check if customer already exists
            $stmt = $this->conn->prepare("SELECT id FROM customer WHERE name = :name AND address = :address AND contact_number = :contact_number LIMIT 1");
            $stmt->execute(['name' => $name, 'address' => $address, 'contact_number' => $contact_number]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                return $customer['id']; // Return existing customer ID
            } else {
                // Insert new customer
                $stmt = $this->conn->prepare("INSERT INTO customer (name, contact_number, address) VALUES (:name, :contact_number, :address)");
                $stmt->execute(['name' => $name, 'contact_number' => $contact_number, 'address' => $address]);
                return $this->conn->lastInsertId(); // Return new customer ID
            }
        } catch (Exception $e) {
            error_log("Error handling customer: " . $e->getMessage());
            throw new Exception("An error occurred while processing customer data.");
        }
    }

    public function updateCustomer($customerID, $customer_name, $customer_contanctNumber, $customer_address){
        try {

            $stmt = $this->conn->prepare("SELECT id FROM customer WHERE id = :id AND name = :name AND address = :address AND contact_number = :contact_number LIMIT 1");
            $stmt->execute(['id' => $customerID ,'name' => $customer_name, 'address' => $customer_address, 'contact_number' => $customer_contanctNumber]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                
                // Update! the customer data
            } else {
                log(1 . "Customer Didn't Exist");
            }
        } catch (Exception $e) {
            error_log(message: "Error updating Customer". $e->getMessage());
            throw new Exception("An error occured while updating customer data");
        }
    }
}

class Appointment
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createAppointment($customer_id, $date, $category, $priority, $status)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO appointment (customer_id, date, category, priority, status) VALUES (:customer_id, :date, :category, :priority, :status)");
            $stmt->execute([
                'customer_id' => $customer_id,
                'date' => $date,
                'category' => $category,
                'priority' => $priority,
                'status' => $status
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Error creating appointment: " . $e->getMessage());
            throw new Exception("An error occurred while creating the appointment.");
        }
    }

    public function updateAppointment($customer_id, $date, $category, $priority, $status) {

    }
}

// Process request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"] ?? "";

    if ($action === "create") {
        try {
            $conn = Database::getInstance(); // Get database connection
            $conn->beginTransaction();

            $customerHandler = new Customer($conn);
            $appointmentHandler = new Appointment($conn);

            // Retrieve and sanitize input data
            $customer_name = trim($_POST["customer_name"] ?? "");
            $customer_number = trim($_POST["customer_number"] ?? "");
            $customer_address = trim($_POST["customer_address"] ?? "");
            $appointment_date = trim($_POST["appointment_date"] ?? "");
            $appointment_category = trim($_POST["appointment_category"] ?? "");
            $appointment_priority = trim($_POST["appointment_priority"] ?? "");
            $appointment_status = "Pending"; // Default status

            if (!$customer_name || !$customer_number || !$customer_address || !$appointment_date || !$appointment_category || !$appointment_priority) {
                header('Content-Type: application/json');
                echo json_encode(["status" => "error", "message" => "All fields are required"]);
                exit;
            }

            // Create or find customer
            $customer_id = $customerHandler->findOrCreateCustomer($customer_name, $customer_number, $customer_address);

            // Create appointment
            $appointmentHandler->createAppointment($customer_id, $appointment_date, $appointment_category, $appointment_priority, $appointment_status);

            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "message" => "Appointment created successfully", "customer_id" => $customer_id]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Failed to process the request. Please try again later."]);
        }
    } elseif ($action === "update") {

    }
}
