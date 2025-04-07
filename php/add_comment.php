<?php
session_start();
require_once 'db.php';

// check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit();
}

// get post data
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$comment = isset($_POST['comment']) ? htmlspecialchars(trim($_POST['comment'])) : '';
$user_id = $_SESSION['user_id'];

// validate input
if ($post_id <= 0 || empty($comment)) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=invalid_input');
    exit();
}

// insert comment into database
$sql = "INSERT INTO comments (post_id, user_id, content, created_at) VALUES (?, ?, ?, NOW())";
try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $post_id, $user_id, $comment);
    $stmt->execute();
    $stmt->close();
    
    // redirect back to the old page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    
} catch (Exception $e) {
    // error msg
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=database');
}
exit();
?>