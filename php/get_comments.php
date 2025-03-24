<?php
session_start();
require_once 'db.php';


$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

if ($post_id <= 0) {

    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}


$comments = [];
$sql = "SELECT c.id, c.content, c.created_at, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at ASC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    error_log("Fetching comments for post_id: $post_id");
    
    while ($row = $result->fetch_assoc()) {
        $row['created_at'] = date('M d, Y g:i A', strtotime($row['created_at']));
        $comments[] = $row;
        error_log("Found comment: " . json_encode($row));
    }
    
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching comments: " . $e->getMessage());
}

header('Content-Type: application/json');
echo json_encode($comments);
exit();
?>