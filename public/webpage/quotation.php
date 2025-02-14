<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/controller/quotation-controller.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/quotation.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/quotation/quotationTableFunctions.js"></script>
    <script src="<?= JUST_URL ?>/js/quotation/quotationFunctions.js"></script>
    <div class="content">
        <div class="qoutationDetials">
            <p class="titleHeader">Document Details</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="quotationDetails_EmployeeID1" type="text" placeholder="Employee ID 1 (can be blank)">
                    <input class="inputField" id="quotationDetails_EmployeeID2" type="text" placeholder="Employee ID 2 (can be blank)">
                </div>
                <div class="column">
                    <input class="inputField" id="quotationDetails_EmployeeID3" type="text" placeholder="Employee ID 3 (can be blank)">
                    <input class="inputField" id="quotationDetails_AppointmentID" type="text" placeholder="Appointment ID">
                </div>
            </div>
        </div>
        <div class="qoutationHeader">
            <p class="titleHeader">Document Header</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationHeader_CompanyName" type="text" placeholder="Company Name">
                    <input class="inputField" id="qoutationHeader_CompanyAddress" type="text" placeholder="Company Address">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationHeader_CompanyNumber" type="text" placeholder="Company Contact #">
                    <input class="inputField" id="qoutationHeader_CompanyEmail" type="text" placeholder="Company Email (can be blank)">
                </div>
            </div>
        </div>
        <div class="qoutationUpperBody">
            <p class="titleHeader">Document Body Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationBody_Date" type="text" placeholder="YYYY-MM-DD" config-id="date">
                    <input class="inputField" id="qoutationBody_CustomerName" type="text" placeholder="Customer Name">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationBody_Location" type="text" placeholder="Location">
                    <input class="inputField" id="qoutationBody_Details" type="text" placeholder="Details">
                </div>
            </div>
        </div>
        <div class="qoutationLowerBody">
            <p class="titleHeader">Document Body Table</p>
            <div class="titleContent">
                <table id="quotationTable">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="4">Grand Total</td>
                            <td id="grandTotalInput">0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="column">
                    <button class="submitButton" onclick="addRow()">Add Row</button>
                </div>
            </div>
        </div>
        <div class="qoutationUpperFooter">
            <p class="titleHeader">Document Footer Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationFooter_Details1" type="text" placeholder="Details 1 (can be blank)">
                    <input class="inputField" id="qoutationFooter_Details2" type="text" placeholder="Details 2 (can be blank)">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationFooter_Details3" type="text" placeholder="Details 3 (can be blank)">
                    <input class="inputField" id="qoutationFooter_Details4" type="text" placeholder="Details 4 (can be blank)">
                </div>
            </div>
        </div>
        <div class="qoutationLowerFooter">
            <p class="titleHeader">Document Technician Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationFooter_TechnicianNamePreparer" type="text" placeholder="Technician Name">
                    <input class="inputField" id="qoutationFooter_TechnicianPositionPreparer" type="text" placeholder="Technician Position">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationFooter_TechnicianNameManager" type="text" placeholder="Technician Manager Name">
                    <input class="inputField" id="qoutationFooter_TechnicianPositionManager" type="text" placeholder="Technician Manager Position">
                </div>
            </div>
        </div>
        <div class="qoutationSubmitFooter">
            <div class="titleContent">
                <div class="column">
                    <button class="submitButton_Generate" id="generateQoutation">Generate</button>
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