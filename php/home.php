<?php
session_start();
require_once 'db.php';
$isLoggedIn = isset($_SESSION['username']);

// query posts from database
$posts = [];
$sql = "SELECT p.id, p.title, p.content, p.topic, p.created_at, u.username, u.profile_picture 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();

// grab trending topics from the last 24 hours (limit of 5 topics in case we add more)
$trendingTopics = [];
$trendingSQL = "SELECT topic, COUNT(*) as post_count 
                FROM posts 
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
                GROUP BY topic 
                ORDER BY post_count DESC 
                LIMIT 5";

$trendingStmt = $conn->prepare($trendingSQL);
$trendingStmt->execute();
$trendingResult = $trendingStmt->get_result();
while ($row = $trendingResult->fetch_assoc()) {
    $trendingTopics[] = $row;
}
$trendingStmt->close();
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
                        <label><input type="checkbox" id="filter-posts" value="posts" checked>Posts</label>
                        <label><input type="checkbox" id="filter-users" value="users" checked>Users</label>
                    </div>
                </div>
                <form id="search-form" method="GET" action="" class="search-form" onsubmit="return handleSearch(event)">
                    <input type="text" name="search" id="search-input" placeholder="Search..." class="search-bar" 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <button type="submit" class="search-button">Search</button>
                </form>
            </div>
            <?php
            if (isset($_POST['accountButton'])) {
                header("Location: account_settings.php");
            }
            if (isset($_POST['adminButton'])) {
                header("Location: admin_page.php");
            }
            ?>
            <div class="right-container">
                <?php if ($isLoggedIn): ?>
                    <div class="login-container">
                        <?php if ($_SESSION['role'] ==="admin"): ?>
                            <form method="post">
                                <button type="submit" name="adminButton">Admin Page</button>
                            </form>
                        <?php endif; ?>
                    <div class="dropdown">
                        <button class="dropdown-button">Settings ▾</button>
                          <div class="dropdown-content" style="width: 110px;">
                            <form method="post">
                                <button type="submit" name="accountButton">Account</button>
                            </form>
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

    <div class="topic-filter-container">
        <h3>Filter by Topic:</h3>
        <div class="topic-buttons">
            <button class="topic-button active" onclick="filterPosts('all', event)">All Topics</button>
            <button class="topic-button" onclick="filterPosts('general', event)">General</button>
            <button class="topic-button" onclick="filterPosts('coursework', event)">Coursework</button>
            <button class="topic-button" onclick="filterPosts('politics', event)">Politics</button>
        </div>
    </div>

    <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href='home.php'"><strong>Home</strong></button>
        <button class="sidebar-button" onclick="window.location.href='profile.php'">My Profile</button>
    </div>

    <!-- trending topics sidebar -->
    <div class="trending-sidebar">
        <div class="trending-header">
            <h3>Trending Topics</h3>
            <span class="trending-subtitle">Last 24 hours</span>
        </div>
        <div class="trending-topics">
            <?php if (empty($trendingTopics)): ?>
                <p class="no-trending">No trending topics yet!</p>
            <?php else: ?>
                <?php foreach ($trendingTopics as $topic): ?>
                    <div class="trending-topic">
                        <span class="trending-topic-name"><?php echo htmlspecialchars(ucfirst($topic['topic'])); ?></span>
                        <span class="trending-topic-count"><?php echo $topic['post_count']; ?> posts</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
                        <label><input type="radio" name="postTopic" value="politics">Politics</label>
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
                <!--hidden form to give the redirect url to create_post -->
                <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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

                <!--hidden form to give the redirect url to create_post -->
                <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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
                        
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "admin"): ?>
                            <form method="post" onsubmit="return false;">
                            <button type="button" onclick="adminDeletePost(<?php echo $post['id']; ?>)">Admin: Delete Post</button>
                            </form>
                        <?php endif; ?>

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
                    <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <p class="post-date">Posted to <?php echo htmlspecialchars(ucfirst($post['topic']))?> on: <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>

                    <div class="post-icons">
                        <img src="../res/thumbs-up.png" alt="Thumbs Up" class="icon" onclick="handleLike(<?php echo $post['id']; ?>)">
                        <img src="../res/thumbs-down.png" alt="Thumbs Down" class="icon" onclick="handleDislike(<?php echo $post['id']; ?>)">
                        <img src="../res/comment-alt.png" alt="Comment" class="icon" onclick="handleComments(<?php echo $post['id']; ?>)">
                        <img src="../res/share.png" alt="Share" class="icon" onclick="handleShare(<?php echo $post['id']; ?>)">
                    </div>

                    <div id="comments-section-<?php echo $post['id']; ?>" class="comments-section" style="display: none;">
                        <div id="comments-container-<?php echo $post['id']; ?>" class="comments-container">
                            <p class="loading-comments">Loading comments...</p>
                        </div>

                        <?php if ($isLoggedIn): ?>
                        <form class="comment-form" action="add_comment.php" method="POST">
                            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                            <textarea name="comment" placeholder="Write a comment..." required></textarea>
                            <button type="submit" class="comment-submit-btn">Post Comment</button>
                        </form>
                        <?php else: ?>
                        <p class="login-to-comment">Please <a href="signin.php">sign in</a> to comment</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <script src="../scripts/home.js"></script>
</body>
</html>
