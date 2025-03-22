<?php
// filepath: c:\xampp\htdocs\cosc360blog\php\create_post.php
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
    $title = trim($_POST['postTitle']);
    $body = trim($_POST['postBody']);
    $username = $_SESSION['username'];
    
    // validate input
    if (empty($title) || empty($body)) {
        // redirect back with error
        header("Location: home.php?error=emptyfields");
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
    $sql = "INSERT INTO posts (user_id, title, content, created_at)
            VALUES (?,?,?,NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $body);

    if( $stmt->execute() ) {
        //redirect to homepage w/ success message
        header("Location: home.php?post=success");
    }else{
        //handle db error
        header("Location: home.php?error=dberror");
    }
    $stmt->close();
    exit();
} else {
    // if not a POST request, redirect to home
    header("Location: home.php");
    exit();
}
?>