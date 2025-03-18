<?php
require_once 'db.php';

$firstname = $lastname = $email = $username = $password = $profile_picture = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate mandatory fields
    if (empty($firstname)) {
        $errors[] = 'First Name is required.';
    }
    if (empty($lastname)) {
        $errors[] = 'Last Name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    // Check if email or username already exists
    $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $email, $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = 'Email or username already exists.';
    }
    $stmt->close();

    // Handle file upload (pfp)
    if (isset($_FILES['profile-picture']) && $_FILES['profile-picture']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['profile-picture'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_type = $file['type'];

        // Validate file type/size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Only JPG, PNG, and GIF files are allowed.';
        }
        if ($file_size > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = 'File size must be less than 2MB.';
        }

        // Move uploaded file to the uploads directory
        if (empty($errors)) {
            $upload_dir = '../upload/';
            $profile_picture = uniqid() . '_' . basename($file_name);
            move_uploaded_file($file_tmp, $upload_dir . $profile_picture);
        }
    }

    // If no errors insert into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (firstname, lastname, email, username, password, profile_picture, role)
                VALUES (?, ?, ?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssss', $firstname, $lastname, $email, $username, $hashed_password, $profile_picture);

        if ($stmt->execute()) {
            // home.php after successful signup
            header('Location: home.php');
            exit();
        } else {
            // Log database errors
            error_log('Database error: ' . $stmt->error);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/signup.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>
        <div class="form-container">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="signup.php" method="post" enctype="multipart/form-data">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <label for="profile-picture">Profile Picture</label>
                <input type="file" id="profile-picture" name="profile-picture">
                <button type="submit" class="submit-button">Sign Up</button>
            </form>
            <p>Already have an account? <a href="signin.php">Sign in</a></p>
        </div>
    </div>

    <script src="../scripts/signup.js"></script>
</body>
</html>