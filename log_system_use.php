<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain"); // Explicitly send plain text

$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Sanitize inputs
$system_name = $_POST['system_name'] ?? '';
$system_type = $_POST['system_type'] ?? '';
$api_endpoint = $_POST['api_endpoint'] ?? '';
$User_id = $_POST['User_id'] ?? '';  // Use lowercase to match Insomnia keys

// Validate required fields
if (empty($system_name) || empty($system_type) || empty($api_endpoint) || empty($User_id)) {
    die("Missing required fields");
}

// Check if the system already exists
$stmt = $mysqli->prepare("SELECT id FROM external_system WHERE api_endpoint = ? AND User_id = ?");
$stmt->bind_param("si", $api_endpoint, $User_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Insert new system with hardcoded values for status, supported_features, and last_sync_date
    $insert = $mysqli->prepare("INSERT INTO external_system (system_name, system_type, api_endpoint, status, supported_features, last_sync_date, User_id) VALUES (?, ?, ?, 'active', '', NOW(), ?)");
    $insert->bind_param("sssi", $system_name, $system_type, $api_endpoint, $User_id);
    
    if ($insert->execute()) {
        echo "System successfully logged.";
    } else {
        echo "Failed to insert system: " . $insert->error;
    }
} else {
    // System exists — update last_sync_date
    $update = $mysqli->prepare("UPDATE external_system SET last_sync_date = NOW() WHERE api_endpoint = ? AND User_id = ?");
    $update->bind_param("si", $api_endpoint, $User_id);
    
    if ($update->execute()) {
        echo "System already exists. Sync date updated.";
    } else {
        echo "System exists but failed to update sync date: " . $update->error;
    }
}

$mysqli->close();
?>
