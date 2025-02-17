<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

include PROJECT_ROOT . "/controller/serviceReport-controller.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Report</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/serviceReport.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="<?= JUST_URL ?>/js/serviceReport/serviceReportTableFunctions.js"></script>
    <script src="<?= JUST_URL ?>/js/serviceReport/serviceReportFunctions.js"></script>
    <div class="content">
        <div class="serviceReportDetials">
            <p class="titleHeader">Document Details</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="quotationDetails_AppointmentID" type="number" placeholder="Appointment ID">
                </div>
            </div>
        </div>
        <div class="appointmentTable">
            <p class="titleHeader">Customer Table</p>
            <?php
            $appointmentManager->fetchAppointments();
            ?>
        </div>
        <div class="serviceReportHeader">
            <p class="titleHeader">Document Header</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationHeader_CompanyName" type="text" placeholder="Company Name">
                    <input class="inputField" id="qoutationHeader_CompanyAddress" type="text" placeholder="Company Address">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationHeader_CompanyNumber" type="number" placeholder="Company Contact #">
                    <input class="inputField" id="qoutationHeader_CompanyEmail" type="text" placeholder="Company Email">
                </div>
            </div>
        </div>
        <div class="serviceReportUpperBody">
            <p class="titleHeader">Document Body Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationBody_Date" type="text" placeholder="YYYY-MM-DD" config-id="date">
                    <input class="inputField" id="qoutationBody_CustomerName" type="text" placeholder="Customer Name">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationBody_Location" type="text" placeholder="Customer Location">
                </div>
            </div>
        </div>
        <div class="serviceReportUpperFooter">
            <p class="titleHeader">Document Service Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationFooter_Details1" type="text" placeholder="Complaint">
                    <input class="inputField" id="qoutationFooter_Details2" type="text" placeholder="Diagnosed">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationFooter_Details3" type="text" placeholder="Activity Performed">
                    <input class="inputField" id="qoutationFooter_Details4" type="text" placeholder="Recommendation">
                </div>
            </div>
        </div>
        <div class="serviceReportLowerBody">
            <p class="titleHeader">Document Body Table</p>
            <div class="titleContent">
                <table id="serviceReportTable">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Activity Performed</th>
                            <th>Quantity</th>
                            <th>Price</th>
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

        <div class="serviceReportLowerFooter">
            <p class="titleHeader">Document Technician Information</p>
            <div class="titleContent">
                <div class="column">
                    <input class="inputField" id="qoutationFooter_TechnicianNamePreparer" type="text" placeholder="Preparer Name">
                    <input class="inputField" id="qoutationFooter_TechnicianPositionPreparer" type="text" placeholder="Preparer Position">
                </div>
                <div class="column">
                    <input class="inputField" id="qoutationFooter_TechnicianNameManager" type="text" placeholder="Manager Name">
                    <input class="inputField" id="qoutationFooter_TechnicianPositionManager" type="text" placeholder="Manager Position">
                </div>
            </div>
        </div>
        <div class="servicReportSubmitFooter">
            <div class="titleContent">
                <div class="column">
                    <button class="submitButton_Generate" id="generateServiceReport">Generate</button>
                    <a class="visitPrint" href="<?= JUST_URL ?>/printablepage/print-quotation.php">Visit Print</a>
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