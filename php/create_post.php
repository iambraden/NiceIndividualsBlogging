<?php
// filepath: c:\xampp\htdocs\cosc360blog\php\create_post.php
session_start();

// check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get form data
    $title = $_POST['postTitle'];
    $body = $_POST['postBody'];
    $username = $_SESSION['username'];
    
    // validate input
    if (empty($title) || empty($body)) {
        // Redirect back with error
        header("Location: home.php?error=emptyfields");
        exit();
    }
    
    // TODO: Connect to database and insert the post
    
    // redirect back to the homepage
    header("Location: home.php?post=success");
    exit();
} else {
    // if not a POST request, redirect to home
    header("Location: home.php");
    exit();
}
?>