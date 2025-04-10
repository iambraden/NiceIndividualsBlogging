<?php
$config = require '../../config.php';

// Create connection
$conn = new mysqli(
    $config['servername'],
    $config['username'],
    $config['password'],
    $config['dbname'],
    $config['port']
);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
