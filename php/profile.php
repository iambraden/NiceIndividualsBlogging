<?php
session_start();
require_once 'db.php';
$isLoggedIn = isset($_SESSION['username']);

if (!$isLoggedIn) {
    header('Location: signin.php');
    exit();
}

//moved above query to check session username against database
$username = $_SESSION['username'];

// query posts from database and display logged in user's posts
$posts = [];
$sql = "SELECT p.id, p.title, p.content, p.topic, p.created_at, u.username, u.profile_picture 
        FROM posts p 
        JOIN users u ON p.user_id = u.id 
        WHERE u.username = ?
        ORDER BY p.created_at DESC";
        
try {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error fetching posts: " . $e->getMessage());
}

// fetch user data from the database
$sql = "SELECT profile_picture FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();

// set default profile picture if none is uploaded
if (empty($profile_picture)) {
    $profile_picture = '../res/user.png';
} else {
    $profile_picture = '../upload/' . $profile_picture;
}

// display success or error messages
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success']);
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
    <header>
        <input type="hidden" id="profile-picture" value="<?php echo htmlspecialchars($profile_picture); ?>">
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
                <form id="search-form" method="GET" action="home.php" class="search-form">
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
                <div class="login-container">                        
                    <?php if ($_SESSION['role'] ==="admin"): ?>
                        <form method="post">
                            <button type="submit" name="adminButton">Admin Page</button>
                        </form>
                    <?php endif; ?>
                    <div class="dropdown">
                        <button class="dropdown-button">Settings ▾</button>
                        <div class="dropdown-content" style="width : 110px;">
                            <form method="post">
                                <button type="submit" name="accountButton">Account</button>
                            </form>
                            <br>
                            <button onclick="window.location.href='logout.php'">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="profileHead">
        <div class="profileHead-container">
            <h1 class="profileHeader">*Profile Header*</h1>
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="User Icon" class="user-icon-main">
            <h2 class="userName"><?php echo htmlspecialchars($username); ?></h2>
            <form action="update_profile_picture.php" method="post" enctype="multipart/form-data" class="profile-picture-form">
                <label for="profile-picture-upload" class="upload-label">
                    <span>Change Profile Picture</span>
                    <input type="file" id="profile-picture-upload" name="profile-picture" accept="image/jpeg, image/png, image/gif" style="display: none;" onchange="this.form.submit()">
                </label>
            </form>
            <?php if (!empty($success)): ?>
                <div id="success-message" class="success-message">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div id="error-message" class="error-message">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href='home.php'">Home</button>
        <button class="sidebar-button" onclick="window.location.href='profile.php'"><strong>My Profile</strong></button>
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


    <script src="../scripts/profile.js"></script>
</body>
</html>