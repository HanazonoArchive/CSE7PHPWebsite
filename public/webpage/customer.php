<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

require PROJECT_ROOT . "/controller/customer-controller1.php";
require PROJECT_ROOT . "/controller/customer-controller2.php";
require PROJECT_ROOT . "/controller/customer-controller3.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/customer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="<?= JUST_URL ?>/js/customer/customerUpdate.js"></script>
    <script src="<?= JUST_URL ?>/js/customer/customerDelete.js"></script>
    <script src="<?= JUST_URL ?>/js/customer/customerFeedbackAdd.js"></script>
    <script src="<?= JUST_URL ?>/js/customer/customerFeedbackUpdate.js"></script>
    <script src="<?= JUST_URL ?>/js/customer/customerFeedbackDelete.js"></script>
    <script src="<?= JUST_URL ?>/js/customer/customer-filter.js"></script>
    <div class="content">
        <div class="customerFilterBar">
            <div class="column_filter">
                <div class="filterTitle">Sort By</div>
                <select class="dropdown_filter" name="Sortby" id="dropdownSortBy">
                    <option value="ASC">Ascending</option>
                    <option value="DESC">Descending</option>
                </select>
            </div>
            <button class="filterApplyButton" type="button">Apply</button>
            <p class="filterStatus" id="QueryStatus"></p>
        </div>
        <div class="customerTable">
            <?= $table_content; ?>
        </div>
        <div class="customerFeedbackTable">
            <?= $table_content_feedback; ?>
        </div>

        <div class="updateCustomer">
            <div class="titleHeader">Update Customer</div>
            <div class="titleContent">
                <div class="column">
                    <select class="dropdown" name="selectCustomerID" id="UpdateCustomer_ID">
                        <option value="">Loading...</option>
                    </select>
                    <input class="inputField" id="UpdateCustomer_NewName" type="text" placeholder="New Customer Name">
                </div>
                <div class="column">
                    <input class="inputField" id="UpdateCustomer_NewContactNumber" type="number"
                        placeholder="New Customer Number">
                    <input class="inputField" id="UpdateCustomer_NewAddress" type="text" placeholder="New Customer Address">
                </div>
                <div class="column">
                    <button class="submitButton" id="submitCustomerUpdate">Update</button>
                    <p class="statusNotifier" id="QueryStatusUpdate"></p>
                </div>
            </div>
        </div>

        <div class="feedbackContainer">
            <div class="addCustomerFeedback">
                <div class="titleHeader">Create Customer Feedback</div>
                <div class="titleContent">
                    <div class="column">
                        <select class="dropdown" name="selectAppointmentID" id="AddFeedback_AppointmentID">
                            <option value="">Loading...</option>
                        </select>
                        <input class="inputField" id="AddFeedback_Comment" type="text" placeholder="Comment">
                    </div>
                    <div class="column">
                        <button class="submitButton" id="submitFeedbackAdd">Create</button>
                        <p class="statusNotifier" id="QueryStatusCreate"></p>
                    </div>
                </div>
            </div>
            <div class="updateCustomerFeedback">
                <div class="titleHeader">Update Customer Feedback</div>
                <div class="titleContent">
                    <div class="column">
                        <select class="dropdown" name="selectFeedbackID" id="UpdateFeedback_ID">
                            <option value="">Loading...</option>
                        </select>
                        <input class="inputField" id="UpdateFeedback_NewComment" type="text" placeholder="New Comment">
                    </div>
                    <div class="column">
                        <button class="submitButton" id="submitFeedbackUpdate">Update</button>
                        <p class="statusNotifier" id="QueryStatusUpdate"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="deleteContainer">
            <div class="deleteCustomer">
                <div class="titleHeader">Delete Customer</div>
                <div class="titleContent">
                    <div class="column">
                        <select class="dropdown" name="selectCustomerID" id="DeleteCustomer_ID">
                            <option value="">Loading...</option>
                        </select>
                        <input class="inputField" id="DeleteCustomer_Confirmation" type="text"
                            placeholder='Type "DELETE" to confirm'>
                    </div>
                    <div class="column">
                        <button class="submitButton" id="submitCustomerDelete">Delete</button>
                        <p class="statusNotifier" id="QueryStatusDelete"></p>
                    </div>
                </div>
            </div>
            <div class="deleteCustomerFeedback">
                <div class="titleHeader">Delete Customer Feedback</div>
                <div class="titleContent">
                    <div class="column">
                        <select class="dropdown" name="selectFeedbackID" id="DeleteFeedback_ID">
                            <option value="">Loading...</option>
                        </select>
                        <input class="inputField" id="DeleteFeedback_Confirmation" type="text"
                            placeholder='Type "DELETE" to confirm'>
                    </div>
                    <div class="column">
                        <button class="submitButton" id="submitFeedbackDelete">Delete</button>
                        <p class="statusNotifier" id="QueryStatusDelete"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>