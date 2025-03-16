<?php
session_start();

//Database not set up yet. Hardcoded user and password.
$validUser = [
    "testUser" => "testPassword",
    "testUser2" => "testPassword2" 
];

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

//Check if user is valid
if(isset($validUser[$username]) && $validUser[$username] == $password){
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    header('Location: ../profile.html');
    exit();
}else{
    header('Location: ../signin.html');
    exit();
}
?>