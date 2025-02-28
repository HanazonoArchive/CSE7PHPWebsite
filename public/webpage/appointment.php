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
    <script src="<?= JUST_URL ?>js/appointment/appointmentAddCustomer.js"></script>
    <script src="<?= JUST_URL ?>js/appointment/appointmentUpdateInformation.js"></script>
    <script src="<?= JUST_URL ?>js/appointment/appointmentDelete.js"></script>
    <div class="content">
        <div class="appointmentCreate">
            <p class="titleHeader">Create Appointment</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="appointmentCreateCustomer_Name" type="text"
                        placeholder="Customer Name">
                    <input class="inputField" id="appointmentCreateCustomer_ContactNumber" type="number"
                        placeholder="Contact Number">
                </div>
                <div class="column">
                    <input class="inputField" id="appointmentCreateCustomer_Address" type="text" placeholder="Address">
                    <input class="inputField" id="appointmentCreate_Date" config-id="date" type="text"
                        placeholder="YYYY-MM-DD">
                </div>
                <div class="column">
                    <select name="selection" class="dropdown" id="appointmentCreate_Category">
                        <option value="Installation">Installation</option>
                        <option value="Repair">Repair</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                    <select name="selection" class="dropdown" id="appointmentCreate_Priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
                <div class="column">
                    <button class="submitButton" id="submitCreateAppointment" type="button">Create</button>
                    <p class="statusNotifier" id="statusCreateNotifier"></p>
                </div>
            </div>
        </div>
        <div class="appointmentUpdate">
            <p class="titleHeader">Update Information</p>
            <div class="titleContent">
                <div class="column">
                    <select class="dropdown" id="appointmentUpdate_ID">
                        <option value="">Loading...</option>
                    </select>
                    <select name="selection" class="dropdown" id="appointmentUpdate_Category">
                        <option value="Installation">Installation</option>
                        <option value="Repair">Repair</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
                </div>
                <div class="column">
                    <select name="selection" class="dropdown" id="appointmentUpdate_Priority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                    <input class="inputField" id="appointmentUpdate_Date" config-id="date" type="text"
                        placeholder="YYYY-MM-DD">
                </div>
                <div class="column">
                    <button class="submitButton" id="submitUpdateAppointment" type="button">Update</button>
                    <p class="statusNotifier" id="statusUpdateNotifier"></p>
                </div>
            </div>
        </div>
        <div class="appointmentDelete">
            <p class="titleHeader">Delete Information</p>
            <div class="titleContent">
                <div class="column">
                    <select class="dropdown" id="appointmentDelete_AppointmentID">
                        <option value="">Loading...</option>
                    </select>
                    <input class="inputField" id="appointmentDelete_Confirmation" type="text"
                        placeholder='Type "DELETE" to confirm'>
                </div>
                <div class="column">
                    <button class="submitButton" id="submitDeleteAppointment" type="button">Delete</button>
                    <p class="statusNotifier" id="statusDeleteNotifier"></p>
                </div>
            </div>
        </div>
        <div class="appointmentTable">
            <p class="titleHeader">Customer Table</p>
            <?php
            $appointmentManager->fetchAppointments();
            ?>
        </div>  
    </div>
    <script>
        document.querySelectorAll('[config-id="date"]').forEach((datePicker) => {
            flatpickr(datePicker, {
                minDate: "today"
            });
        });
    </script>
</body>

</html>