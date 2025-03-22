<?php
session_start();
require_once 'db.php';
$isLoggedIn = isset($_SESSION['username']);

// Query posts from database
$posts = [];
$sql = "SELECT p.id, p.title, p.content, p.topic, p.created_at, u.username, u.profile_picture 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC";
        
try {
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
} catch (Exception $e) {
    error_log("Error fetching posts: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../css/home.css">
</head>
<body>
    <header>
        <h1>CampusConnect</h1>
        <div class="header-container">
            <div class="center-container">
                <div class="dropdown">
                    <button class="dropdown-button">Filter ▾</button>
                    <div class="dropdown-content">
                        <label><input type="checkbox" value="posts" checked> Posts</label>
                        <label><input type="checkbox" value="profile" checked> Profiles</label>
                        <label><input type="checkbox" value="option3" checked> Option 3</label>
                    </div>
                </div>
                <input type="text" placeholder="Search..." class="search-bar">
            </div>

            <div class="right-container">
                <?php if ($isLoggedIn): ?>
                    <div class="login-container">
                        <div class="dropdown">
                            <button class="dropdown-button">Settings ▾</button>
                            <div class="dropdown-content" style="width: 110px;">
                                <button>Account</button>
                                <br>
                                <button onclick="window.location.href='logout.php'">Logout</button>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="login-container">
                        <img src="../res/user.png" alt="User Icon" class="user-icon">
                        <button class="login-button" onclick="window.location.href='signin.php'">Sign in</button>
                    </div>
                    <button class="signup-button" onclick="window.location.href='signup.php'">Sign up</button>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href='home.php'"><strong>Home</strong></button>
        <button class="sidebar-button" onclick="window.location.href='profile.php'">My Profile</button>
    </div>

    <?php if ($isLoggedIn): ?>
    <button class="post-button" onclick="openForm()">Create Post</button>
    <div class="post-popup" id="postForm" style="display: none;">
        <div class="form-container">
            <h3>Create Post</h3>
            <form action="create_post.php" method="POST" class="postForm-container">
                <div class="form-group">
                    <label for="postTitle"><b>Title</b></label>
                    <input type="text" placeholder="Title your post" name="postTitle" required>
                </div>
                
                <div class="form-group">
                    <div class="topic-rg">
                        <p><b>Topic</b></p>
                        <label><input type="radio" name="postTopic" value="general" checked>General</label>
                        <label><input type="radio" name="postTopic" value="coursework">Coursework</label>
                        <label><input type="radio" name="postTopic" value="politice">Politics</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="postBody"><b>Content</b></label>
                    <textarea placeholder="Enter your post content" name="postBody" rows="6" required></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="submit-post-button">Submit Post</button>
                    <button type="button" class="cancel-post-button" onclick="closeForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <div class="post-popup" id="editPostForm" style="display: none;">
        <div class="form-container">
            <h3>Edit Post</h3>
            <form action="edit_post.php" method="POST" class="postForm-container">
                <input type="hidden" id="edit-post-id" name="postId">
                <div class="form-group">
                    <label for="editPostTitle"><b>Title</b></label>
                    <input type="text" placeholder="Title your post" name="postTitle" id="editPostTitle" required>
                </div>

                <div class="form-group">
                    <label for="editPostBody"><b>Content</b></label>
                    <textarea placeholder="Enter your post content" name="postBody" id="editPostBody" rows="6" required></textarea>
                </div>

                <div class="form-buttons">
                    <button type="submit" class="submit-post-button">Update Post</button>
                    <button type="button" class="cancel-post-button" onclick="closeEditForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div class="posts-container">
        <?php if (empty($posts)): ?>
            <div class="post">
                <p>No posts available yet. Be the first to create a post!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-header">
                        <?php 
                        $profilePic = !empty($post['profile_picture']) 
                            ? '../upload/' . $post['profile_picture'] 
                            : '../res/user.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="User Icon" class="user-icon">
                        <span class="username"><?php echo htmlspecialchars($post['username']); ?></span>
                        
                        <?php if ($isLoggedIn && $_SESSION['username'] === $post['username']): ?>
                        <div class="post-options">
                            <button class="post-options-btn">⋮</button>
                            <div class="post-options-dropdown">
                                <button onclick="editPost(<?php echo $post['id']; ?>)">Edit</button>
                                <button onclick="deletePost(<?php echo $post['id']; ?>)">Delete</button>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <p class="post-date">Posted to <?php echo htmlspecialchars(ucfirst($post['topic']))?> on: <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="../scripts/home.js"></script>
</body>
</html>
