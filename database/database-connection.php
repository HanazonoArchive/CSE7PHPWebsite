<?php
$dsn = 'mysql:host=Hoyoworld.serv.gs;port=3306;dbname=AirconService;charset=utf8mb4';
$user = 'root';             // Username
$password = 'aaa12345';     // Password

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the MySQL database!";

    // Example query
    $stmt = $pdo->query("SELECT * FROM your_table");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row["id"] . " - Name: " . $row["name"] . "<br>";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
