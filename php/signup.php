<?php
require_once 'db.php';

$firstname = $lastname = $email = $username = $password = $profile_picture = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Check if email or username already exists
    $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = 'Email or username already exists.';
    }
    $stmt->close();

    // Handle file upload (pfp)
    if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile-picture'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];

        // Validate file type/size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Only JPG, PNG, and GIF files are allowed.';
        }
        if ($file_size > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = 'File size must be less than 2MB.';
        }

        // Move uploaded file to the uploads directory
        if (empty($errors)) {
            $upload_dir = '../upload/';
            $profile_picture = uniqid() . '_' . basename($file_name);
            move_uploaded_file($file_tmp, $upload_dir . $profile_picture);
        }
    }

    // If no errors insert into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (firstname, lastname, email, username, password, profile_picture, role)
                VALUES (?, ?, ?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $firstname, $lastname, $email, $username, $hashed_password, $profile_picture);

        if ($stmt->execute()) {
            // home.php after successful signup
            header('Location: home.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
        }
    }
}
?>