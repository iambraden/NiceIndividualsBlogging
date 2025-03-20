<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campusconnect";

// Create connection (had to force port 3307 to avoid errors)
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
