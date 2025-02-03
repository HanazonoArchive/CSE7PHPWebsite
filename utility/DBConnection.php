<?php
// utility/DBConnection.php

$host = "HoyoWorld.serv.gs:3306";
$user = "root";
$password = "aaa12345";
$database = "AirconService";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
