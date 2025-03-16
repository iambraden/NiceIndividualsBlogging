<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
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

    <div class="posts-container">
        <div class="post">
            <div class="post-header">
                <span class="username">Jane Doe1</span>
            </div>
            <h2>Post Title 1</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
        <div class="post">
            <div class="post-header">
                <span class="username">Jane Doe2</span>
            </div>
            <h2>Post Title 2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
        <div class="post">
            <div class="post-header">
                <span class="username">Jane Doe3</span>
            </div>
            <h2>Post Title 3</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
    </div>

    <script src="../scripts/home.js"></script>
</body>
</html>
