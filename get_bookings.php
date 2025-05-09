<?php
file_put_contents("hit_log.txt", "Script was hit\n", FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain");

// Database connection
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}






// Fetch all bookings (only date is needed)
$sql = "SELECT preferred_date FROM consultations";  // Make sure your table name and column match
$result = $mysqli->query($sql);

// Output plain text, each line is one date
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['preferred_date'] . "\n";  // Format: 2025-05-07
    }
} else {
    echo "";  // Return empty if no data
}

$mysqli->close();
?>
