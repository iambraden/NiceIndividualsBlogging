<?php
require_once "db.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $post_id = $_POST['postId'];
    $title = trim($_POST['postTitle']);
    $body = trim($_POST['postBody']);
    $username = $_SESSION['username'];
    
    // Validate input
    if (empty($title) || empty($body) || empty($post_id)) {
        header("Location: home.php?error=emptyfields");
        exit();
    }
    
    // Verify user is the post owner
    $sql = "SELECT p.id FROM posts p JOIN users u ON p.user_id = u.id 
            WHERE p.id = ? AND u.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $post_id, $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows < 1) {
        // Not the post owner or post doesn't exist
        header("Location: home.php?error=unauthorized");
        exit();
    }
    $stmt->close();
    
    // Update the post
    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $post_id);
    
    if ($stmt->execute()) {
        header("Location: home.php?post=updated");
    } else {
        header("Location: home.php?error=dberror");
    }
    $stmt->close();
    exit();
} else {
    // If not a POST request, redirect to home
    header("Location: home.php");
    exit();
}
?>