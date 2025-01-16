<?php
// Database configuration
$host = 'HoyoWorld.serv.gs'; // e.g., 'example.com' or 'localhost'
$username = 'root';
$password = 'aaa12345';
$dbname = 'VaporGames';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all table names
$sql = "SHOW TABLES";
$result = $conn->query($sql);

// Check if any tables are found
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Table Name</th></tr>"; // Table header

    // Output data of each row (table)
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row['Tables_in_' . $dbname] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "No tables found in the database.";
}

// Close connection
$conn->close();
?>
