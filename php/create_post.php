<?php
require_once "db.php";
session_start();

// DEBUG: Log the POST data to see what we're receiving
error_log("POST DATA: " . print_r($_POST, true));

// check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $title = trim($_POST['postTitle']);
    $body = trim($_POST['postBody']);
    $topic = trim($_POST['postTopic']);
    $username = $_SESSION['username'];

    // Initialize redirect URL once at the top
    $redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'home.php';
    
    // helper function to append parameters to the redirect url
    function appendToUrl($url, $param) {
        return $url . (strpos($url, '?') !== false ? '&' : '?') . $param;
    }
    
    // validate input
    if (empty($title) || empty($body)) {
        // redirect back with error
        header("Location: " . appendToUrl($redirect_url, 'error=emptyfields'));
        exit();
    }
    
    // get user info from db
    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    //insert post into database linked to user's id
    $sql = "INSERT INTO posts (user_id, title, content, topic, created_at)
            VALUES (?,?,?,?,NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $title, $body, $topic);

    if($stmt->execute()) {
        // Redirect with success message
        header("Location: " . appendToUrl($redirect_url, 'post=success'));
    } else {
        // Handle db error
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