<?php
// Database connection
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "legalservices"; // Your database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get data from the request
$User_id = $_POST['User_id'] ?? null;
$filename = $_POST['filename'] ?? null;

if (!$User_id || !$filename) {
    echo "Error: Missing parameters!";
    exit();
}

// Path to the uploaded documents folder
$uploadDir = 'uploads/'; // Make sure the path is correct based on where you store your documents

// Delete the file from the server
$filePath = $uploadDir . $filename;

if (file_exists($filePath)) {
    // Delete the file
    if (!unlink($filePath)) {
        echo "Error: Could not delete the file.";
        exit();
    }
} else {
    echo "Error: File does not exist.";
    exit();
}

// Remove the file record from the database
$sql = "DELETE FROM documents WHERE User_id = ? AND filename = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $User_id, $filename);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "success";
} else {
    echo "Error: Failed to delete document record from the database.";
}

$stmt->close();
$mysqli->close();
?>
