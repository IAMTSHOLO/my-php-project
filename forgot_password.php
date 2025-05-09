<?php
// forgot_password.php

// Database connection
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

if (empty($email) || empty($new_password)) {
    echo "Email and new password are required.";
    exit;
}

// Optional but recommended: Hash the password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Check if the user exists
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    // Update the user's password
    $update = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    if ($mysqli->query($update)) {
        echo "Password has been successfully reset.";
    } else {
        echo "Error updating password. Please try again.";
    }
} else {
    echo "No account found with that email.";
}

$mysqli->close();
?>
