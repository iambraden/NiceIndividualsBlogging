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
        $errors[] = 'Current username does not exist.';
    }
    //replace username
    if (empty($errors)) {
        $sql = "UPDATE users SET username=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newUsername, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $newUsername;
            $_SESSION['success'] = 'Username changed successfully!';
            header('Location: account_settings.php');
            exit();
        } else {
            error_log('Database error: ' . $stmt->error);
            $errors[] = 'An error occurred while updating the username.';
        }
    }

    //redirect back with errors
    $_SESSION['error'] = $errors;
    header('Location: account_settings.php');
    exit();
}
?>