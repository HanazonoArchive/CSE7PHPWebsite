<?php
// Define project root and URL paths
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/db/DBConnection.php"; // Include the PDO connection

try {
    $conn = Database::getInstance(); // This will return the PDO connection
} catch (Exception $e) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $e->getMessage()]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $customer_name = trim($_POST["customer_name"] ?? "");
    $customer_number = trim($_POST["customer_number"] ?? "");
    $customer_address = trim($_POST["customer_address"] ?? "");
    $appointment_date = trim($_POST["appointment_date"] ?? "");
    $appointment_category = trim($_POST["appointment_category"] ?? "");
    $appointment_priority = trim($_POST["appointment_priority"] ?? "");
    $appointment_status = "Confirmed"; // Default status

    // Check if all required fields are filled
    if (!$customer_name || !$customer_number || !$customer_address || !$appointment_date || !$appointment_category || !$appointment_priority) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    try {
        // Start a transaction
        $conn->beginTransaction();

        // 1. Check if customer already exists
        $stmt = $conn->prepare("SELECT id FROM customer WHERE name = :name AND address = :address LIMIT 1");
        $stmt->execute([
            'name' => $customer_name,
            'address' => $customer_address
        ]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // Customer already exists, use existing ID
            $customer_id = $customer['id'];

        } else {
            // 2. Insert new customer
            $stmt = $conn->prepare("INSERT INTO customer (name, contact_number, address) VALUES (:name, :contact_number, :address)");
            $stmt->execute([
                'name' => $customer_name,
                'contact_number' => $customer_number,
                'address' => $customer_address
            ]);

            // Get the last inserted customer ID
            $customer_id = $conn->lastInsertId();
        }

        // 3. Insert appointment using the customer ID
        $stmt = $conn->prepare("INSERT INTO appointment (customer_id, date, category, priority, status) 
                                VALUES (:customer_id, :date, :category, :priority, :status)");
        $stmt->execute([
            'customer_id' => $customer_id,
            'date' => $appointment_date,
            'category' => $appointment_category,
            'priority' => $appointment_priority,
            'status' => $appointment_status
        ]);

        // Commit transaction
        $conn->commit();

        echo json_encode(["status" => "success", "message" => "Appointment created successfully", "customer_id" => $customer_id]);
    } catch (Exception $e) {
        $conn->rollBack(); // Rollback transaction on error
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/appointment.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/schedule/appointment-addcustomer.js"></script>
    <div class="content">
        <div class="client-holder">
            <div class="add-client">
                <p class="form-title">Customer</p>
                <div class="client-form">
                    <label class="client-header">Name </label>
                    <input class="textfield" type="text" id="customer_name" name="name" required>

                    <label class="client-header">Contact Number </label>
                    <input class="textfield" type="tel" id="customer_number" name="contact_number"
                        pattern="[0-9]{4}[0-9]{3}[0-9]{4}" required>

                    <label class="client-header">Address </label>
                    <input class="textfield" type="address" id="customer_address" name="address" required>

                    <button class="send_buttons" id="submit_customer" type="button">Create</button>
                </div>
            </div>
            <div class="add-client">
                <p class="form-title">Appointment</p>
                <div class="client-form">
                    <label class="client-header">Date </label>
                    <input class="textfield" type="text" id="appointment_date" config-id="date" name="date" required>

                    <label class="client-header">Category </label>
                    <select class="category_input" name="category" id="appointment_category">
                        <option value="Installation">Installation</option>
                        <option value="Repair">Repair</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>

                    <label class="client-header">Priority </label>
                    <select class="category_input" name="priority" id="appointment_priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="client-holder1">
            <div class="add-client">
                <p class="form-title">Update Information</p>
                <div class="client-form">
                    <label class="client-header">Customer ID </label>
                    <input class="textfield" type="text" id="text" name="text" required>

                    <label class="client-header">Select Category</label>
                    <select class="category_input" name="category" id="category">
                        <option value="installation">Installation</option>
                        <option value="repair">Repair</option>
                        <option value="maintenance">Maintenance</option>
                    </select>

                    <label class="client-header">Select Priority</label>
                    <select class="category_input" name="priority" id="priority">
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>

                    <label class="client-header">Update Date </label>
                    <input class="textfield" type="text" id="date" name="date" required>
                </div>
            </div>

            <div class="add-client">
                <p class="form-title_whitespace">-</p>
                <div class="client-form">
                    <label class="client-header">Update Name</label>
                    <input class="textfield" type="text" id="name" name="name" required>

                    <label class="client-header">Update Number </label>
                    <input class="textfield" type="tel" id="contact_number" name="contact_number"
                        pattern="[0-9]{4}[0-9]{3}[0-9]{4}" required>

                    <label class="client-header">Update Address </label>
                    <input class="textfield" type="address" id="address" name="address" required>

                    <button class="send_buttons" type="submit">Update</button>
                </div>
            </div>

        </div>
        <div class="client-holder2">
            <div class="add-client">
                <p class="form-title">Delete</p>
                <div class="client-form">
                    <label class="client-header">Customer ID </label>
                    <input class="textfield" type="text" id="text" name="text" required>
                    <button class="send_buttons" type="submit">Delete</button>

                    <label class="client-header">Appointment ID </label>
                    <input class="textfield" type="text" id="text" name="text" required>
                    <button class="send_buttons" type="submit">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        let appointmentInput = document.querySelector('[config-id="date"]');
        config = {
            minDate: "today"
        };
        flatpickr(appointmentInput, config);
    </script>
</body>

</html>