<?php
$servername = "localhost";
$username = "george";
$password = "q0m81k4j";
$dbname = "my_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
