<?php
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "legalservices"; // Your database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve parameters from POST
    $fileName = $_POST['fileName'] ?? '';
    $category = $_POST['category'] ?? '';

    if (empty($fileName) || empty($category)) {
        echo 'Error: Missing parameters';
        exit;
    }

    // Query to find the file's path in the database
    $stmt = $mysqli->prepare("SELECT file_path FROM documents WHERE file_name = ? AND category = ?");
    $stmt->bind_param("ss", $fileName, $category);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($filePath);
    
    if ($stmt->num_rows === 0) {
        echo 'Error: File not found in database';
        exit;
    }

    // Get the file path
    $stmt->fetch();
    $stmt->close();

    // Delete the file from the server
    if (file_exists($filePath)) {
        unlink($filePath);
    } else {
        echo 'Error: File not found on server';
        exit;
    }

    // Delete the record from the database
    $stmt = $mysqli->prepare("DELETE FROM documents WHERE file_name = ? AND category = ?");
    $stmt->bind_param("ss", $fileName, $category);
    if ($stmt->execute()) {
        echo "Deleted \"$fileName\" from category \"$category\" and database.";
    } else {
        echo 'Error: Could not delete record from database';
    }
    $stmt->close();

} else {
    echo 'Error: Invalid request method';
}

// Close database connection
$mysqli->close();
?>
