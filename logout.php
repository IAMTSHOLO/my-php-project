<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

session_start();

// Check if a session exists (i.e., if the user is logged in)
if (isset($_SESSION['User_id'])) {
    session_unset();
    session_destroy();
    echo 'Logged out successfully';
} else {
    echo 'No active session found';
}

?>
