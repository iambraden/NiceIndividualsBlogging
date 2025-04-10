<?php
session_start();
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/admin_page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
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
        <h2>Change a User's Role</h2>
        <div class="form-container">
            <?php if (!empty($error)): ?>
                <div class="error-messages">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form action="change_user_role.php" method="post">
                <div class="form-group">
                    <label for="selectUsername">User to change by username</label>
                    <input type="text" id="selectUsername" name="selectUsername" placeholder="Enter username">
                    <p class="or-text">or</p>
                </div>

            

                <div class="form-group">
                    <label for="selectUserByEmail">User to change by email</label>
                    <input type="text" id="selectUserByEmail" name="selectUserByEmail" placeholder="Enter email">
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" id="user" name="user_role" value="user"
                            <?php if (isset($_POST['user_role']) && $_POST['user_role'] == "user") echo "checked"; ?>>
                            User
                        </label>
                        <label>
                            <input type="radio" id="admin" name="user_role" value="admin"
                            <?php if (isset($_POST['user_role']) && $_POST['user_role'] == "admin") echo "checked"; ?>>
                            Admin
                        </label>
                        <label>
                            <input type="radio" id="banned" name="user_role" value="banned"
                            <?php if (isset($_POST['user_role']) && $_POST['user_role'] == "banned") echo "checked"; ?>>
                            Ban User
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-button" name="role">Change Role</button>
            </form>
        </div>
    </div>
</body>
<script src="../scripts/admin_page.js"></script>
</html>