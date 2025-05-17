<?php
header('Content-Type: text/plain');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug: print all GET data
var_dump($_GET);

$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}



// Get the lawyer_id from the query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Join lawyers with users table to get the full name
    $sql = "SELECT u.first_name, u.last_name 
            FROM lawyers l 
            JOIN users u ON l.User_id = u.User_id 
            WHERE l.id = ? 
            LIMIT 1";

    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name );

    if ($stmt->fetch()) {
        // Output plain text full name
        echo $first_name . ' ' . $last_name ;
    } else {
        echo 'Not found';
    }

    $stmt->close();
} else {
    echo 'Invalid ID';
}

$mysqli->close();
?>
