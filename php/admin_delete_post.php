<?php
require_once "db.php";
session_start();

$redirect_url = isset($_POST['redirect_url']) ? $_POST['redirect_url'] : 'home.php';

function appendToUrl($url, $param) {
    return $url . (strpos($url, '?') !== false ? '&' : '?') . $param;
}

$post_id = $_POST['postId'] ?? null;

if (empty($post_id)) {
    header("Location: " . appendToUrl($redirect_url, 'error=missingid'));
    exit();
}

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