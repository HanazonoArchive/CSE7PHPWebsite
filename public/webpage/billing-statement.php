<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/controller/billingStatement-controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Statement</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/billing-statement.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/billing-statement/billingStatementFunction.js"></script>
    <div class="content">
        <div class="billingStatementDetails">
            <p class="titleHeader">Document Details</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="billingDetails_AppointmentID" type="text"
                        placeholder="Appointment ID">
                </div>
            </div>
        </div>
        <div class="appointmentTable">
            <p class="titleHeader">Customer Table</p>
            <?php
            $appointmentManager->fetchAppointments();
            ?>
        </div>
        <div class="billingStatementHeader">
            <p class="titleHeader">Document Header</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="billingHeader_CompanyName" type="text" placeholder="Company Name">
                    <input class="inputField" id="billingHeader_CompanyAddress" type="text"
                        placeholder="Company Address">
                </div>
                <div class="column">
                    <input class="inputField" id="billingHeader_CompanyNumber" type="number"
                        placeholder="Company Contact #">
                    <input class="inputField" id="billingHeader_CompanyEmail" type="text" placeholder="Company Email">
                </div>
            </div>
        </div>
        <div class="billingStatementUpperBody">
            <p class="titleHeader">Document Body Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="billingBody_Date" type="text" placeholder="YYYY-MM-DD"
                        config-id="date">
                    <input class="inputField" id="billingBody_CustomerName" type="text" placeholder="Customer Name">
                </div>
                <div class="column">
                    <input class="inputField" id="billingBody_Location" type="text" placeholder="Customer Location">
                </div>
            </div>
        </div>

        <div class="billingStatementUpperFooter">
            <p class="titleHeader">Document Billing Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="billingFooter_AuthorizedName" type="text" placeholder="Athorized by">
                    <input class="inputField" id="billingFooter_AuthorizedRole" type="text" placeholder="Role">
                </div>
                <div class="column">
                    <input class="inputField" id="billingFooter_Remarks" type="text" placeholder="Remarks">
                </div>
            </div>
        </div>
        <div class="billingStatementSubmitFooter">
            <div class="titleContent">
                <div class="column">
                    <button class="submitButton_Generate" id="generateBillingReport">Generate</button>
                    <a class="visitPrint" href="<?= JUST_URL ?>/printablepage/print-billingStatement.php">Visit Print</a>
                </div>
                <div class="column">
                    <p class="statusNotifier" id="statusGenerateNotifier"></p>
                </div>
            </div>
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