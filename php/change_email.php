<?php
session_start();
require_once 'db.php';

$oldEmail = $newEmail = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldEmail = trim($_POST['oldEmail']);    
    $newEmail = htmlspecialchars(trim($_POST['newEmail']));

    //check if old email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $oldEmail);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 0) {
        $errors[] = 'Email is incorrect';
    }
    //check if new email is valid
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    //replace email
    if (empty($errors)) {
        $sql = "UPDATE users SET email=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $newEmail, $_SESSION['user_id']);
        if ($stmt->execute()) {
            header('Location: account_settings.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
        }
    }

}


?>