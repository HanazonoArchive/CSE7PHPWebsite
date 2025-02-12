<?php
session_start();

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
            $stmt = $this->conn->prepare("SELECT id FROM customer WHERE name = :name AND address = :address AND contact_number = :contact_number LIMIT 1");
            $stmt->execute(['name' => $name, 'address' => $address, 'contact_number' => $contact_number]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                return $customer['id'];
            } else {
                $stmt = $this->conn->prepare("INSERT INTO customer (name, contact_number, address) VALUES (:name, :contact_number, :address)");
                $stmt->execute(['name' => $name, 'contact_number' => $contact_number, 'address' => $address]);
                return $this->conn->lastInsertId();
            }
        } catch (Exception $e) {
            error_log("Error handling customer: " . $e->getMessage());
            throw new Exception("An error occurred while processing customer data.");
        }
    }

    public function updateCustomer($customerID, $customer_name, $customer_contactNumber, $customer_address)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM customer WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $customerID]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                $stmt = $this->conn->prepare("UPDATE customer SET name = :name, contact_number = :contact_number, address = :address WHERE id = :id");
                $stmt->execute(['id' => $customerID, 'name' => $customer_name, 'contact_number' => $customer_contactNumber, 'address' => $customer_address]);
            } else {
                error_log("Customer ID $customerID doesn't exist in the database.");
            }
        } catch (Exception $e) {
            error_log("Error updating customer: " . $e->getMessage());
            throw new Exception("An error occurred while updating customer data.");
        }
    }

    public function deleteCustomer($customerID)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM appointment WHERE customer_id = :id LIMIT 1");
            $stmt->execute(['id' => $customerID]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($customer) {
                error_log("Can't delete appointment. Appointment ID is still associated with the appointment.");
            } else {
                $stmt = $this->conn->prepare("DELETE FROM customer WHERE id = :id");
                $stmt->execute(['id' => $customerID]);
            }
        } catch (Exception $e) {
            error_log("Error deleting customer: " . $e->getMessage());
            throw new Exception("An error occurred while deleting customer data.");
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

    public function updateAppointment($appointmentID, $appointment_date, $appointment_category, $appointment_priority)
    {
        try {
            $stmt = $this->conn->prepare("SELECT id FROM appointment WHERE id = :id LIMIT 1");
            $stmt->execute(['id' => $appointmentID]);
            $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($appointment) {
                $stmt = $this->conn->prepare("UPDATE appointment SET date = :date, category = :category, priority = :priority WHERE id = :id");
                $stmt->execute(['id' => $appointmentID, 'date' => $appointment_date, 'category' => $appointment_category, 'priority' => $appointment_priority]);
            } else {
                error_log("Appointment ID $appointmentID doesn't exist in the database.");
            }
        } catch (Exception $e) {
            error_log("Error updating appointment: " . $e->getMessage());
            throw new Exception("An error occurred while updating appointment data.");
        }
    }

    public function deleteAppointment($appointmentID)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM appointment WHERE id = :id");
            $stmt->execute(['id' => $appointmentID]);
        } catch (Exception $e) {
            error_log("Error deleting appointment: " . $e->getMessage());
            throw new Exception("An error occurred while deleting appointment data.");
        }
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
                $_SESSION['notification'] = '<div class="alert alert-warning" role="alert">All fields are required</div>';
                exit;
            }

            // Create or find customer
            $customer_id = $customerHandler->findOrCreateCustomer($customer_name, $customer_number, $customer_address);

            // Create appointment
            $appointmentHandler->createAppointment($customer_id, $appointment_date, $appointment_category, $appointment_priority, $appointment_status);

            $conn->commit();
            header('Content-Type: application/json');
            $_SESSION['notification'] = '<div class="alert alert-success" role="alert">Appointment created successfully</div>';
            echo json_encode(["status" => "success", "message" => "Appointment created successfully", "customer_id" => $customer_id]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Failed to process the request. Please try again later."]);
            $_SESSION['notification'] = '<div class="alert alert-warning" role="alert">Failed to process the request. Please try again.</div>';
        }
    } elseif ($action === "update") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $customerHandler = new Customer($conn);
            $appointmentHandler = new Appointment($conn);

            $appointmentID = trim($_POST["update_AppointmentID"] ?? "");
            $customerID = trim($_POST["update_CustomerID"] ?? "");
            $customer_name = trim($_POST["update_Name"] ?? "");
            $customer_number = trim($_POST["update_ContactNumber"] ?? "");
            $customer_address = trim($_POST["update_Address"] ?? "");
            $appointment_date = trim($_POST["update_Date"] ?? "");
            $appointment_category = trim($_POST["update_Category"] ?? "");
            $appointment_priority = trim($_POST["update_Priority"] ?? "");

            if (!empty($appointmentID) && !empty($appointment_date) && !empty($appointment_category) && !empty($appointment_priority)) {
                $appointmentHandler->updateAppointment($appointmentID, $appointment_date, $appointment_category, $appointment_priority);
                $_SESSION['notification'] = '<div class="alert alert-success" role="alert">Appointment updated successfully</div>';
            }

            if (!empty($customerID) && !empty($customer_name) && !empty($customer_number) && !empty($customer_address)) {
                $customerHandler->updateCustomer($customerID, $customer_name, $customer_number, $customer_address);
                $_SESSION['notification'] = '<div class="alert alert-success" role="alert">Customer updated successfully</div>';
            }

            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "message" => "Update successful"]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Failed to update. Please try again."]);
            $_SESSION['notification'] = '<div class="alert alert-warning" role="alert">Failed to update. Please try again.</div>';
        }
    } elseif ($action === "delete") {
        try {
            $conn = Database::getInstance();
            $conn->beginTransaction();

            $customerHandler = new Customer($conn);
            $appointmentHandler = new Appointment($conn);

            $appointmentID = trim($_POST["Delete_AppointmentID"] ?? "");
            $customerID = trim($_POST["Delete_CustomerID"] ?? "");

            if (!empty($appointmentID)) {
                $appointmentHandler->deleteAppointment($appointmentID);
                $_SESSION['notification'] = '<div class="alert alert-success" role="alert">Appointment deleted successfully</div>';
            }

            if (!empty($customerID)) {
                $customerHandler->deleteCustomer($customerID);
                $_SESSION['notification'] = '<div class="alert alert-success" role="alert">Customer deleted successfully</div>';
            }

            $conn->commit();
            header('Content-Type: application/json');
            echo json_encode(["status" => "success", "message" => "Update successful"]);
        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Transaction failed: " . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(["status" => "error", "message" => "Failed to Delete. Please try again."]);
            $_SESSION['notification'] = '<div class="alert alert-warning" role="alert">Failed to Delete. Please try again.</div>';
        }
    }
}
