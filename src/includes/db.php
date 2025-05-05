<?php
//  database connection (reuse this file wherever needed)
$servername = "localhost";
$username = "root";
$password = "";
$database = "eventspace_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>