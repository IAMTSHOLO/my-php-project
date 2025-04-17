<?php
session_start();

// Check if session is active
if (isset($_SESSION['User_id']) && isset($_SESSION['Session_id'])) {
    // Database connection setup
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "legalservices";

    // Create a connection
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $user_id = $_SESSION['User_id'];
    $session_id = $_SESSION['Session_id'];

    // Update the session table to mark as logged out
    $stmt = $mysqli->prepare("UPDATE sessions SET logout_time = NOW(), is_active = FALSE WHERE session_id = ? AND User_id = ?");
    $stmt->bind_param("si", $session_id, $user_id);
    $stmt->execute();
    $stmt->close();

    // Destroy PHP session
    session_unset();
    session_destroy();

    echo "Logout successful";
} else {
    echo "No active session found";
}
?>
