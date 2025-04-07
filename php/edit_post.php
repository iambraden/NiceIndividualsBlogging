<?php
require_once "db.php";
session_start();

// check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $post_id = $_POST['postId'];
    $title = htmlspecialchars(trim($_POST['postTitle']));
    $body = htmlspecialchars(trim($_POST['postBody']));
    $username = $_SESSION['username'];
    
    // Initialize redirect URL once at the top
    $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'home.php';
    
    // helper function to append parameters to the redirect url
    function appendToUrl($url, $param) {
        return $url . (strpos($url, '?') !== false ? '&' : '?') . $param;
    }
    
    // validate input
    if (empty($title) || empty($body) || empty($post_id)) {
        header("Location: " . appendToUrl($redirect_url, 'error=emptyfields'));
        exit();
    }
    
    // verify user is the post owner
    $sql = "SELECT p.id FROM posts p JOIN users u ON p.user_id = u.id 
            WHERE p.id = ? AND u.username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $post_id, $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows < 1) {
        // not the post owner or post doesn't exist
        header("Location: " . appendToUrl('home.php', 'error=unauthorized'));
        exit();
    }
    $stmt->close();
    
    // update the post
    $sql = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $body, $post_id);
    
    if ($stmt->execute()) {
        header("Location: " . appendToUrl($redirect_url, 'post=success'));
    } else {
        // handle db error
        error_log("Execution failed: " . $stmt->error);
        header("Location: " . appendToUrl($redirect_url, 'error=dberror'));
    }
    $stmt->close();
    exit();
} else {
    // if not a POST request, redirect to home
    header("Location: home.php");
    exit();
}
?>