<?php
session_start();
require_once 'db.php';

$newUsername = $oldUsername = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldUsername = trim($_POST['oldUsername']);    
    $newUsername = htmlspecialchars(trim($_POST['newUsername']));

    //check if old username exists
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $oldUsername);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $errors[] = 'Username does not exist.';
        exit();
    }

    //replace username
    if (empty($errors)) {
        $sql = "UPDATE users SET username=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newUsername, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            header('Location: account_settings.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
            header('Location: account_settings.php');
            exit();
        }
    }

}


?>