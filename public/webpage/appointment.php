<?php
// Define project root and URL paths
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
define('JUST_URL', '/CSE7PHPWebsite/public/');

include PROJECT_ROOT . "/controller/appointment-controller.php";
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
    <script src="<?= JUST_URL ?>js/schedule/appointment-addcustomer.js"></script>
    <div class="content">
        <div class="client-holder">
            <div class="add-client">
                <p class="form-title">Customer</p>
                <div class="client-form" id="appointment_form">
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