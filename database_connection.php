<?php
var_dump($_POST);
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

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
