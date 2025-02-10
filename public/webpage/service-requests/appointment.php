<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/db/DBConnection.php"; // Assuming this initializes a PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Only process if it's a POST request
    try {
        $conn = Database::getInstance();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error handling

        // Check if all required fields are provided
        if (
            !empty($_POST['customer_name']) &&
            !empty($_POST['customer_number']) &&
            !empty($_POST['customer_address']) &&
            !empty($_POST['appointment_date']) &&
            !empty($_POST['appointment_category']) &&
            !empty($_POST['appointment_priority']) &&
            !empty($_POST['appointment_status'])
        ) {
            // Start a transaction
            $conn->beginTransaction();

            // Insert customer first
            $customer_query = "INSERT INTO customer (name, contact_number, address) VALUES (:name, :contact_number, :address)";
            $stmt = $conn->prepare($customer_query);
            $stmt->execute([
                ':name' => $_POST['customer_name'],
                ':contact_number' => $_POST['customer_number'],
                ':address' => $_POST['customer_address']
            ]);
            $customer_id = $conn->lastInsertId(); // Get the last inserted ID

            // Insert appointment with the retrieved customer ID
            $appointment_query = "INSERT INTO appointment (date, category, priority, status, customer_id) 
                                  VALUES (:date, :category, :priority, :status, :customer_id)";
            $stmt = $conn->prepare($appointment_query);
            $stmt->execute([
                ':date' => $_POST['appointment_date'],
                ':category' => $_POST['appointment_category'],
                ':priority' => $_POST['appointment_priority'],
                ':status' => $_POST['appointment_status'],
                ':customer_id' => $customer_id
            ]);

            // Commit the transaction
            $conn->commit();

            echo json_encode(["success" => true, "message" => "Appointment added successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid request: missing required fields."]);
        }
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $conn->rollBack();
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }

    $conn = null; // Close connection
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
                        <option value="High">High</option>
                        <option value="Medium">Medium</option>
                        <option value="Low">Low</option>
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