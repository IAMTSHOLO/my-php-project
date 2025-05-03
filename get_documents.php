<?php
header('Content-Type: text/plain');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug: print all GET data
var_dump($_GET);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$User_id = isset($_GET['User_id']) ? $_GET['User_id'] : null;
if (!$User_id) {
    echo "ERROR: Missing User_id";
    exit;
}

$sql = "SELECT category, document_name FROM documents WHERE User_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $User_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No documents found for User_id: $User_id";
} else {
    while ($row = $result->fetch_assoc()) {
        echo $row['category'] . "|" . $row['document_name'] . "\n";
    }
}

$mysqli->close();
?>
