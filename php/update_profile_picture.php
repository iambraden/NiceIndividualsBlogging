<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile-picture'])) {
    $file = $_FILES['profile-picture'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_type = $file['type'];

    // Validate file type and size
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error'] = 'Only JPG, PNG, and GIF files are allowed.';
        header('Location: profile.php');
        exit();
    }
    if ($file_size > 2 * 1024 * 1024) { // 2MB limit
        $_SESSION['error'] = 'File size must be less than 2MB.';
        header('Location: profile.php');
        exit();
    }

    // Fetch the old profile picture path
    $sql = "SELECT profile_picture FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($old_profile_picture);
    $stmt->fetch();
    $stmt->close();

    // Move the uploaded file to the uploads directory
    $upload_dir = '../upload/';

    // check if directory exists and is writable
    if (!is_dir($upload_dir)) {
        // gry to create it if it doesn't exist
        if (!mkdir($upload_dir, 0777, true)) {
            $_SESSION['error'] = 'Upload directory does not exist and could not be created.';
            header('Location: profile.php');
            exit();
        }
    }

    if (!is_writable($upload_dir)) {
        $_SESSION['error'] = 'Upload directory is not writable. Please check permissions.';
        header('Location: profile.php');
        exit();
    }

    error_log("Attempting to upload file: " . $file_name . " to " . $upload_dir);
    error_log("Directory exists: " . (is_dir($upload_dir) ? 'Yes' : 'No'));
    error_log("Directory writable: " . (is_writable($upload_dir) ? 'Yes' : 'No'));

    $profile_picture = uniqid() . '_' . basename($file_name);
    if (move_uploaded_file($file_tmp, $upload_dir . $profile_picture)) {
        error_log("File upload successful: " . $profile_picture);
      
        $sql = "UPDATE users SET profile_picture = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $profile_picture, $username);

        if ($stmt->execute()) {
            // Delete the old profile picture file
            if (!empty($old_profile_picture) && file_exists($upload_dir . $old_profile_picture)) {
                unlink($upload_dir . $old_profile_picture);
            }
            $_SESSION['success'] = 'Profile picture updated successfully!';
        } else {
            $_SESSION['error'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        error_log("File upload failed. PHP error: " . error_get_last()['message']);
        $_SESSION['error'] = 'Failed to upload file. Please check server logs.';
    }
}

header('Location: profile.php');
exit();
?>