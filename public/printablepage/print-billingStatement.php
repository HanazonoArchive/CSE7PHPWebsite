<?php
session_start();
define('PROJECT_DB', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public/');
include_once PROJECT_DB . "db/DBConnection.php";

$conn = Database::getInstance();

$quotationID = $_SESSION['QuotationID_BS'] ?? null;
$quotationAmount = $_SESSION['QuotationAmount_BS'] ?? null;
$serviceReportID = $_SESSION['ServiceReportID_BS'] ?? null;
$serviceReportAmount = $_SESSION['ServiceReportAmount_BS'] ?? null;
$billingStatementAmount = $_SESSION['Amount_BS'] ?? null;

$serviceReportDataDecoded = [];
$quotationDataDecoded = [];

// Check if Service Report ID exists
if ($serviceReportID) {
    $stmt1 = $conn->prepare("SELECT data FROM service_report_data WHERE service_report_id = :serviceReportID");
    $stmt1->execute(['serviceReportID' => $serviceReportID]);
    $serviceReportData = $stmt1->fetch(PDO::FETCH_ASSOC);

    if ($serviceReportData) {
        $serviceReportDataDecoded = json_decode($serviceReportData['data'], true);

        if ($serviceReportDataDecoded === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error decoding Service Report JSON: " . json_last_error_msg());
        }
    } else {
        error_log("Service Report Data not found for ID: " . htmlspecialchars($serviceReportID));
    }
} else {
    error_log("Service Report ID is missing in session.");
}

// Check if Quotation ID exists
if ($quotationID) {
    $stmt2 = $conn->prepare("SELECT data FROM quotation_data WHERE quotation_id = :quotationID");
    $stmt2->execute(['quotationID' => $quotationID]);
    $quotationData = $stmt2->fetch(PDO::FETCH_ASSOC);

    if ($quotationData) {
        $quotationDataDecoded = json_decode($quotationData['data'], true);

        if ($quotationDataDecoded === null && json_last_error() !== JSON_ERROR_NONE) {
            error_log("Error decoding Quotation JSON: " . json_last_error_msg());
        }
    } else {
        error_log("Quotation Data not found for ID: " . htmlspecialchars($quotationID));
    }
} else {
    error_log("Quotation ID is missing in session.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Billing Statement Print</title>
    <link rel="stylesheet" href="print-billingStatement.css">
</head>

<body>
    <div class="content">
        <div class="header">
            <p class="companyName"><?php echo $_SESSION['dHeader_BS']['companyName'] ?? "No data found"; ?></p>
            <p class="CompanyDetails"><?php echo $_SESSION['dHeader_BS']['companyAddress'] ?? "No data found"; ?></p>
            <p class="CompanyDetails"><?php echo $_SESSION['dHeader_BS']['companyNumber'] ?? "No data found"; ?></p>
            <p class="CompanyDetails"><?php echo $_SESSION['dHeader_BS']['companyEmail'] ?? "No data found"; ?></p>
        </div>
        <hr class="HorizontalLine">
        <p class="documentTITLE">Billing Statement</p>
        <div class="body">
            <p class="documentBody"><strong>Date:</strong> <?php echo $_SESSION['dBody_BS']['billingDate'] ?? "No data found"; ?></p>
            <p class="documentBody"><strong>Customer Name:</strong> <?php echo $_SESSION['dBody_BS']['customerName'] ?? "No data found"; ?></p>
            <p class="documentBody"><strong>Location:</strong> <?php echo $_SESSION['dBody_BS']['customerLocation'] ?? "No data found"; ?></p>
            <p class="documentBody"><strong>Billing Statement #:</strong> <?php echo $_SESSION['BillingStatementID'] ?? "No data found"; ?></p>
        </div>
        <div class="footer">
            <table border="1" cellspacing="0" cellpadding="5">
                <tr>
                    <th>Complain:</th>
                    <th>Diagnosed:</th>
                    <th>Activity Performed:</th>
                    <th>Recommendation:</th>
                </tr>
                <tr>
                    <td><?php echo $_SESSION['dFooter_SR']['complaint'] ?? "No data found"; ?></td>
                    <td><?php echo $_SESSION['dFooter_SR']['diagnosed'] ?? "No data found"; ?></td>
                    <td><?php echo $_SESSION['dFooter_SR']['activityPerformed'] ?? "No data found"; ?></td>
                    <td><?php echo $_SESSION['dFooter_SR']['recommendation'] ?? "No data found"; ?></td>
                </tr>
            </table>
        </div>
        <div class="table">
            <?php if (!empty($_SESSION['itemsSR']) && is_array($_SESSION['itemsSR'])) : ?>
                <table border="1" cellspacing="0" cellpadding="5">
                    <tr>
                        <th>Unit</th>
                        <th>Activity Performed</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($_SESSION['itemsSR'] as $item) : ?>
                        <tr>
                            <td><?= $item['item'] ?></td>
                            <td><?= $item['description'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= $item['price'] ?></td>
                            <td><?= $item['total'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <p>No items found in session.</p>
            <?php endif; ?>
        </div>
        <div class="techInfo">
            <div class="columnSR">
                <p class="TechInfoText"><strong><?php echo $_SESSION['dTechnicianInfo_SR']['preparerName'] ?? "No data found"; ?></strong></p>
                <p class="TechInfoText"><?php echo $_SESSION['dTechnicianInfo_SR']['preparerPosition'] ?? "No data found"; ?> Signature over Printed Name:</p>
            </div>
            <div class="columnSR">
                <p class="TechInfoText"><strong><?php echo $_SESSION['dTechnicianInfo_SR']['managerName'] ?? "No data found"; ?></strong></p>
                <p class="TechInfoText">Customer Signature over Printed Name:</p>
            </div>
        </div>
    </div>
</body>

</html>