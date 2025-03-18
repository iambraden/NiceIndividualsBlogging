<?php
session_start();
require_once 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

// Validate inputs
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Please fill in all fields.';
    header('Location: signin.php');
    exit();
}

// Fetch user from the database
$sql = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_username, $db_password, $db_role);
    $stmt->fetch();

    // Verify password
    if (password_verify($password, $db_password)) {
        // Check if the selected role matches the user's role
        if ($role === $db_role) {
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $db_role;
            header('Location: profile.php');
            exit();
        } else {
            // Role error
            $_SESSION['error'] = 'Invalid role selected.';
            header('Location: signin.php');
            exit();
        }
    } else {
        // wrong password
        $_SESSION['error'] = 'Invalid username or password.';
        header('Location: signin.php');
        exit();
    }
} else {
    // User not found
    $_SESSION['error'] = 'Invalid username or password.';
    header('Location: signin.php');
    exit();
}

$stmt->close();
$conn->close();
?>