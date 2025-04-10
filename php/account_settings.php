<?php
session_start();
require_once 'db.php';

// Fetch user statistics
$userId = $_SESSION['user_id']; // Assuming user ID is stored in session
$stats = [
    'posts' => 0,
    'comments' => 0,
];

if ($userId) {
    // Query to fetch statistics
    $queryPosts = "SELECT COUNT(*) AS total_posts FROM posts WHERE user_id = ?";
    $queryComments = "SELECT COUNT(*) AS total_comments FROM comments WHERE user_id = ?";

    $stmt = $conn->prepare($queryPosts);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['posts'] = $result->fetch_assoc()['total_posts'];

    $stmt = $conn->prepare($queryComments);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stats['comments'] = $result->fetch_assoc()['total_comments'];
}


$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success']);
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/account_settings.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
</head>
<body>
    <header>
        <div class="header-container">
            <h1 class="header-title">CampusConnect</h1>
            <div class="center-container">
                <button class="back-button" onclick="window.location.href='home.php'">Home</button>
            </div>
        </div>
    </header>

    <div class="body-container">
        <div class="side-tab">
            <button onclick="showSection('settings')">Account Settings</button>
            <button onclick="showSection('statistics')">Profile Statistics</button>
        </div>

        <div id="settings-section" class="content-section">
            <h2>Account Settings</h2>
            <div class="form-container">
                <?php if (!empty($success)): ?>
                    <div id="success-message" class="success-message">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div id="error-message" class="error-message">
                        <?php foreach ((array) $error as $err): ?>
                            <?php echo htmlspecialchars($err); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form id="usernameForm" action="change_username.php" method="post" enctype="multipart/form-data">
                    <label for="oldUsername">Current Username</label>
                    <input type="text" id="oldUsername" name="oldUsername" required>
                    <label for="newUsername">New Username</label>
                    <input type="text" id="newUsername" name="newUsername" required>
                    <button type="submit" class="submit-button">Change Username</button>
                </form>
            </div>
            <div class="form-container">
                <form id="emailForm" action="change_email.php" method="post" enctype="multipart/form-data">
                    <label for="oldEmail">Current Email</label>
                    <input type="text" id="oldEmail" name="oldEmail" required>
                    <label for="newEmail">New Email</label>
                    <input type="text" id="newEmail" name="newEmail" required>
                    <button type="submit" class="submit-button">Change Email</button>
                </form>
            </div>
            <div class="form-container">
                <form id="passwordForm" action="change_password.php" method="post" enctype="multipart/form-data">
                    <label for="oldPassword">Current Password</label>
                    <input type="password" id="oldPassword" name="oldPassword" required>
                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                    <button type="submit" class="submit-button">Change Password</button>
                </form>
            </div>
        </div>

        <div id="statistics-section" class="content-section" style="display: none;">
            <h2>Profile Statistics</h2>
            <div class="statistics-container">
                <div class="stat-card">
                    <h3>Total Posts</h3>
                    <p><?php echo $stats['posts']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Comments</h3>
                    <p><?php echo $stats['comments']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="../scripts/account_settings_username.js"></script>
    <script src="../scripts/account_settings_email.js"></script>
    <script src="../scripts/account_settings_password.js"></script>
    <script>
        function showSection(section) {
            document.getElementById('settings-section').style.display = section === 'settings' ? 'block' : 'none';
            document.getElementById('statistics-section').style.display = section === 'statistics' ? 'block' : 'none';
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0'; 
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 500);
                }, 3000);
            }

            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.opacity = '0'; 
                    setTimeout(() => {
                        errorMessage.style.display = 'none'; 
                    }, 500);
                }, 3000); 
            }
        });
    </script>
</body>
</html>