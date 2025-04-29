<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

// Get POST data safely
$sender_id = isset($_POST['sender_id']) ? intval($_POST['sender_id']) : null;
$receiver_id = isset($_POST['receiver_id']) ? intval($_POST['receiver_id']) : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;

if ($sender_id === null || $receiver_id === null || $message === null) {
    die("Missing sender_id, receiver_id, or message.");
}

// DB connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if receiver_id exists in the lawyers table
$checkReceiver = $mysqli->prepare("SELECT id FROM lawyers WHERE id = ?");
$checkReceiver->bind_param("i", $receiver_id);
$checkReceiver->execute();
$checkReceiverResult = $checkReceiver->get_result();

if ($checkReceiverResult->num_rows > 0) {
    // Prepare and insert message
    $stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);

    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message: " . $stmt->error;
    }
} else {
    echo "Receiver ID not found in lawyers table.";
}

?>
