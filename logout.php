<?php
session_start();

// Enable error logging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug output
error_log("POST data: " . print_r($_POST, true));
error_log("SESSION data: " . print_r($_SESSION, true));

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

// Create a connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    error_log("Database connection failed: " . $mysqli->connect_error);
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if POST parameters were sent - primary method
if (isset($_POST['User_id']) && isset($_POST['session_id'])) {
    $User_id = $_POST['User_id'];
    $session_id = $_POST['session_id'];
    
    error_log("Using POST data: User_id=$User_id, session_id=$session_id");
    
    // Update the session table to mark as logged out
    $stmt = $mysqli->prepare("UPDATE sessions SET logout_time = NOW(), is_active = FALSE WHERE session_id = ? AND User_id = ?");
    $stmt->bind_param("si", $session_id, $User_id);
    $stmt->execute();
    
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        error_log("Session updated successfully in database");
        // Clear PHP session variables if they exist
        if (isset($_SESSION['User_id'])) {
            session_unset();
            session_destroy();
            error_log("PHP session destroyed");
        }
        echo "Logged out successfully";
    } else {
        error_log("No session found in database: User_id=$User_id, session_id=$session_id");
        echo "No active session found in database";
    }
    
    $stmt->close();
}
// Fallback to session variables
else if (isset($_SESSION['User_id']) && isset($_SESSION['session_id'])) {
    $User_id = $_SESSION['User_id'];
    $session_id = $_SESSION['session_id'];
    
    error_log("Using SESSION data: User_id=$User_id, session_id=$session_id");
    
    // Update the session table to mark as logged out
    $stmt = $mysqli->prepare("UPDATE sessions SET logout_time = NOW(), is_active = FALSE WHERE session_id = ? AND User_id = ?");
    $stmt->bind_param("si", $session_id, $User_id);
    $stmt->execute();
    
    // Destroy PHP session
    session_unset();
    session_destroy();
    error_log("PHP session destroyed");
    
    echo "Logged out successfully";
    $stmt->close();
} else {
    error_log("No session information provided in either POST or SESSION");
    echo "No session information provided";
}

$mysqli->close();
?>