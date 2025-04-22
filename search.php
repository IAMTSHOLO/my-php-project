<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain"); // Explicitly send plain text

$searchType = $_GET['type'] ?? '';  // "document" or "lawyer"
$keyword = $_GET['q'] ?? '';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$keyword = $mysqli->real_escape_string($keyword); // prevent SQL injection

if ($searchType === 'document') {
    $sql = "SELECT document_name, category 
            FROM documents 
            WHERE document_name LIKE '%$keyword%' 
               OR category LIKE '%$keyword%'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Document: " . $row['document_name'] . " (" . $row['category'] . ")\n";
        }
    } else {
        echo "No matching documents found.";
    }

} else if ($searchType === 'lawyer') {
    // Join lawyers with users to get name and expertise
    $sql = "SELECT u.first_name, u.last_name, l.expertise, l.years_of_experience 
            FROM lawyers l
            JOIN users u ON l.User_id = u.User_id
            WHERE u.first_name LIKE '%$keyword%' 
               OR u.last_name LIKE '%$keyword%' 
               OR l.expertise LIKE '%$keyword%'";

    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Lawyer: " . $row['first_name'] . " " . $row['last_name'] . " - " 
                 . $row['expertise'] . " (" . $row['years_of_experience'] . " years)\n";
        }
    } else {
        echo "No matching lawyers found.";
    }

} else {
    echo "Invalid search type.";
}

$mysqli->close();
?>
