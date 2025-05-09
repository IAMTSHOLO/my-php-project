<?php
// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


// Log all incoming requests for debugging 
file_put_contents("hit_log.txt", "Script was hit at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
file_put_contents("hit_log.txt", "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Set content type
header("Content-Type: text/plain");

// Check if message_id is provided
if (isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];
    file_put_contents("hit_log.txt", "Received message_id: $message_id\n", FILE_APPEND);
    
    // Database connection
    $mysqli = new mysqli("localhost", "root", "", "legalservices");
    if ($mysqli->connect_error) {
        $error = "Connection failed: " . $mysqli->connect_error;
        file_put_contents("hit_log.txt", $error . "\n", FILE_APPEND);
        die($error);
    }
    
    // Convert message_id to integer since the database column is an integer type
    $message_id = (int)$message_id;
    
    // Check if the message exists before trying to delete
    $check_sql = "SELECT * FROM messages WHERE message_id = $message_id";
    $check_result = $mysqli->query($check_sql);
    
    if ($check_result && $check_result->num_rows > 0) {
        // Message exists, proceed with deletion
        $row = $check_result->fetch_assoc();
        file_put_contents("hit_log.txt", "Found message with ID $message_id: " . print_r($row, true) . "\n", FILE_APPEND);
        
        $sql = "DELETE FROM messages WHERE message_id = $message_id";
        $result = $mysqli->query($sql);
        
        if ($result === TRUE) {
            $response = "Message deleted successfully";
            file_put_contents("hit_log.txt", $response . "\n", FILE_APPEND);
            echo $response;
        } else {
            $error = "Error deleting message: " . $mysqli->error;
            file_put_contents("hit_log.txt", $error . "\n", FILE_APPEND);
            echo $error;
        }
    } else {
        // Message doesn't exist
        $error = "Message not found with ID: $message_id";
        file_put_contents("hit_log.txt", $error . "\n", FILE_APPEND);
        echo $error;
    }
    
    $mysqli->close();
} else {
    $error = "Missing message_id parameter";
    file_put_contents("hit_log.txt", $error . ". POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
    echo $error;
}