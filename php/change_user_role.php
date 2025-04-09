<?php
session_start();
require_once 'db.php';

$username = $userByEmail = $userRole = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_role'])) {
    if (isset($_POST['selectUsername']) || isset($_POST['selectUserByEmail'])){
        $username = trim($_POST['selectUsername']);
        $userByEmail = trim($_POST['selectUserByEmail']);
        $userRole = $_POST['user_role'];

        //check if user exists
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $userByEmail);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows == 0) {
            $errors[] = 'User does not exist.';
            $_SESSION['error'] = 'User does not exist.';
            header('Location: admin_page.php');
            exit();
        }
        if (empty($errors)) {
            $sql = "UPDATE users SET role=? WHERE username=? OR email=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $userRole, $username, $userByEmail);
            if ($stmt->execute()) {
                header('Location: home.php');
                exit();
            } else {
                // Log database errors
                error_log('Database error: ' . $stmt->error);
            }
        }
    }

}
?>