<?php
session_start();
require_once 'db.php';

// if logged on
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = 'You must be logged in to like a post.';
    header("Location: signin.php");
    exit();
}

// Get post id
if (!isset($_POST['post_id'])) {
    $_SESSION['error'] = 'Post ID is required.';
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
$post_id = intval($_POST['post_id']);

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Check if the user has already liked the post
$sql = "SELECT id FROM likes WHERE user_id = ? AND post_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $post_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // unlike
    $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $post_id);
} else {
    // like
    $sql = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $post_id);
}

// Execute the query
if (!$stmt->execute()) {
    $_SESSION['error'] = 'Failed to toggle like.';
}

$stmt->close();
$conn->close();

// Back to the previous page
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>