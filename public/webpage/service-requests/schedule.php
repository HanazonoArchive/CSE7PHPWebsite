<?php
define('PROJECT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/CSE7PHPWebsite/public');
define('JUST_URL', '/CSE7PHPWebsite/public');

require PROJECT_ROOT . "/db/DBConnection.php";

// Get the database instance
$conn = Database::getInstance();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
    <link rel="stylesheet" href="<?= JUST_URL ?>/css/schedule.css">
</head>

<body>
    <?php require PROJECT_ROOT . "/component/sidebar.php"; ?>
    <?php require PROJECT_ROOT . "/component/togglesidebar.php"; ?>

    <div class="content">
        <div class="appointment-holder">
            <div class="appointment-table">
                <?php
                try {
                    // Fetch relevant appointment details
                    $query = "
                    SELECT
                        appointment.id as Ticket_Number, 
                        customer.name AS Customer_Name, 
                        appointment.date AS Appointment_Date, 
                        customer.address AS Address, 
                        appointment.category AS Category, 
                        appointment.priority AS Priority, 
                        appointment.status AS Status
                    FROM appointment
                    JOIN customer ON appointment.customer_id = customer.id
                    ORDER BY appointment.date DESC";

                    $stmt = $conn->prepare($query);
                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($results) > 0) {
                        echo "<table border='1'>";
                        echo "<tr>";

                        // Fetch column names dynamically
                        foreach (array_keys($results[0]) as $columnName) {
                            echo "<th>" . htmlspecialchars(str_replace("_", " ", $columnName)) . "</th>";
                        }

                        echo "</tr>";

                        // Fetch rows
                        foreach ($results as $row) {
                            echo "<tr>";
                            foreach ($row as $value) {
                                echo "<td>" . htmlspecialchars($value) . "</td>";
                            }
                            echo "</tr>";
                        }

                        echo "</table>";
                    } else {
                        echo "No records found.";
                    }
                } catch (PDOException $e) {
                    echo "Error fetching data: " . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>