<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "campusconnect";

// create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>