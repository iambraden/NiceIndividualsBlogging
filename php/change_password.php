<?php
session_start();
require_once 'db.php';

$newPassword = $oldPassword = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = trim($_POST['oldPassword']);    
    $newPassword = htmlspecialchars(trim($_POST['newPassword']));

    //check if old password exists
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    if ($stmt->num_rows == 0) {
        $errors[] = 'User not found';
    }
    if (!isset($oldPassword) || !password_verify($oldPassword, $storedPassword)) {
        $errors[] = 'Passwords do not match';
    }

    //replace password
    if (empty($errors) && isset($newPassword)) {
        $hashed_newPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $hashed_newPassword, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Password changed successfully!';
            header('Location: account_settings.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
            header('Location: account_settings.php');
        }
    }

}


?>