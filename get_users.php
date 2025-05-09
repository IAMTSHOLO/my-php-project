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


$sql = "SELECT * FROM users";

$result = $mysqli->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

foreach ($users as $user) {
    echo "User ID: " . $user['User_id'] . " | first_name: " . $user['last_name'] . " | email: " . $user['email'] . "\n";
}

$mysqli->close();
?>
