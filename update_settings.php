<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Ensure proper key name
$User_id = (int) trim($_POST['User_id'] ?? 0);
$language = trim($_POST['language'] ?? 'English');
$theme = trim($_POST['theme'] ?? 'Light');
$notifications = trim($_POST['notifications'] ?? 'On');

if ($User_id <= 0) {
    die("Error: User_id not provided or invalid.");
}

// Use prepared statement
$stmt = $mysqli->prepare("UPDATE user_settings SET language = ?, theme = ?, notifications = ? WHERE User_id = ?");
$stmt->bind_param("sssi", $language, $theme, $notifications, $User_id);
$success = $stmt->execute();

if ($success) {
    echo 'Settings updated successfully';
} else {
    echo 'ERROR: Update failed';
}
?>
