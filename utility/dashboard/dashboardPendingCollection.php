<?php
// Example placeholder for database connection and query
require './utility/DBConnection.php'; // Ensure you have a database connection file

$query = "SELECT client_name, location, total_collection FROM today_work ORDER BY schedule_date ASC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['client_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_collection']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No Payment hasn't collected today.</td></tr>";
}
