<?php
require_once "db.php";
session_start();

// check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// initialize redirect url once at the top
$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'home.php';

// helper function to append parameters to the redirect url
function appendToUrl($url, $param) {
    return $url . (strpos($url, '?') !== false ? '&' : '?') . $param;
}

// get post ID from either GET or POST
$post_id = $_GET['id'] ?? $_POST['postId'] ?? null;

// validate post ID exists
if (empty($post_id)) {
    header("Location: " . appendToUrl($redirect_url, 'error=missingid'));
    exit();
}

// verify user is the post owner
$sql = "SELECT p.id FROM posts p JOIN users u ON p.user_id = u.id 
        WHERE p.id = ? AND u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $post_id, $_SESSION['username']);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows < 1) {
    // not the post owner or post doesn't exist
    header("Location: " . appendToUrl($redirect_url, 'error=unauthorized'));
    exit();
}
$stmt->close();

// delete the post
$sql = "DELETE FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);

if ($stmt->execute()) {
    header("Location: " . appendToUrl($redirect_url, 'post=deleted'));
} else {
    // handle db error
    error_log("Execution failed: " . $stmt->error);
    header("Location: " . appendToUrl($redirect_url, 'error=dberror'));
}
$stmt->close();
exit();
?>