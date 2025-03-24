<?php
session_start();
require_once 'db.php';

$username = $userRole = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectUsername']) && isset($_POST['user_role'])) { 
    $username = trim($_POST['selectUsername']);
    $userRole = $_POST['user_role'];

    //check if username exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $errors[] = 'Username does not exist.';
        $_SESSION['error'] = 'Username does not exist.';
        header('Location: admin_page.php');
        exit();
    }
    if (empty($errors)) {
        $sql = "UPDATE users SET role=? WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $userRole, $username);
        if ($stmt->execute()) {
            header('Location: admin_page.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
        }
    }

}


?>