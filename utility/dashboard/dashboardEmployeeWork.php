<?php
// Example placeholder for database connection and query
require './utility/DBConnection.php'; // Ensure you have a database connection file

$query = "SELECT employee_name, days_work, total_salary, schedule_date FROM today_work ORDER BY schedule_date ASC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['employee_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['days_work']) . "</td>";
        echo "<td>" . htmlspecialchars($row['total_salary']) . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>All Employee Salary are paid today.</td></tr>";
}
