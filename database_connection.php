<?php
var_dump($_POST);
$servername = "localhost";
$username = "root";    // Default XAMPP MySQL username
$password = "";        // Default XAMPP MySQL password (empty)
$dbname = "legalservices"; // Replace with your database name

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    
    die(json_encode([
        "message" => "Connection failed",
        "error" => $mysqli->connect_error
    ]));
}
?>
