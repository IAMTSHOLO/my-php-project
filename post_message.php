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

// DB connection
$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Assuming you're preparing the message insert query
$stmt = $mysqli->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

// Check if receiver_id exists in the lawyers table
$checkReceiver = $mysqli->prepare("SELECT id FROM lawyers WHERE id = ?");
$checkReceiver->bind_param("i", $receiver_id);
$checkReceiver->execute();
$checkReceiverResult = $checkReceiver->get_result();

if ($checkReceiverResult->num_rows > 0) {
    // Proceed with inserting the message
    if ($stmt->execute()) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message.";
    }
} else {
    echo "Receiver ID not found in lawyers table.";
}

?>
