<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

require_once 'db.php'; // Include the database connection

$username = $_SESSION['username'];

// Fetch user data from the database
$sql = "SELECT profile_picture FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();

// Set default profile picture if none is uploaded
if (empty($profile_picture)) {
    $profile_picture = '../res/user.png';
}else{
    $profile_picture = '../upload/' . $profile_picture;
}
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
                        <label><input type="checkbox" value="posts" checked> Posts</label>
                        <label><input type="checkbox" value="profile" checked> Profiles</label>
                        <label><input type="checkbox" value="option3" checked> Option 3</label>
                    </div>
                </div>
                <input type="text" placeholder="Search..." class="search-bar">
            </div>

            <div class="right-container">
                <div class="login-container">
                    <div class="dropdown">
                        <button class="dropdown-button">Settings ▾</button>
                        <div class="dropdown-content" style="width : 110px;">
                            <button>Account</button>
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
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="User Icon" class="user-icon">
            <h2 class="userName"><?php echo htmlspecialchars($username); ?></h2>
        </div>
    </section>
    <div class="sidebar">
        <button class="sidebar-button" onclick="window.location.href='home.php'">Home</button>
        <button class="sidebar-button" onclick="window.location.href='profile.php'"><strong>My Profile</strong></button>
    </div>
    <div class="posts-container">
        <div class="post">
            <div class="post-header">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <h2>Post Title 1</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
        <div class="post">
            <div class="post-header">
                <span class="username"><?php echo htmlspecialchars($username); ?></span>
            </div>
            <h2>Post Title 2</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam.</p>
        </div>
    </div>
    <script src="../scripts/profile.js"></script>
</body>
</html>