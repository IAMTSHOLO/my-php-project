<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain"); // Explicitly send plain text

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Sanitize inputs
$sender_id = $mysqli->real_escape_string($_POST['sender_id']);
$receiver_id = $mysqli->real_escape_string($_POST['receiver_id']);
$message = $mysqli->real_escape_string($_POST['message']);

// Insert into database
$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$message')";

if ($mysqli->query($sql) === TRUE) {
    echo "Message sent successfully";
} else {
    echo "Failed to send message: " . $mysqli->error;
}

$mysqli->close();
?>
