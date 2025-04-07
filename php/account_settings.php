<?php
session_start();
$error = $_SESSION['error'] ?? '';
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
        <h2>Account Settings</h2>
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="change_username.php" method="post" enctype="multipart/form-data">
                <label for="oldUsername">Current Username</label>
                <input type="text" id="oldUsername" name="oldUsername" required>
                <label for="newUsername">New Username</label>
                <input type="text" id="newUsername" name="newUsername" required>
                <button type="submit" class="submit-button">Change Username</button>
            </form>
        </div>
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="change_email.php" method="post" enctype="multipart/form-data">
                <label for="oldEmail">Current Email</label>
                <input type="text" id="oldEmail" name="oldEmail" required>
                <label for="newEmail">New Email</label>
                <input type="text" id="newEmail" name="newEmail" required>
                <button type="submit" class="submit-button">Change Email</button>
            </form>
        </div>
    <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="change_password.php" method="post" enctype="multipart/form-data">
                <label for="oldPassword">Current Password</label>
                <input type="password" id="oldPassword" name="oldPassword" required>
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" required>
                <button type="submit" class="submit-button">Change Password</button>
            </form>
        </div>
    </div>

    <script src="../scripts/account_settings_username.js"></script>
    <script src="../scripts/account_settings_email.js"></script>
    <script src="../scripts/account_settings_password.js"></script>
</body>
</html>