<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input
    $User_id = $mysqli->real_escape_string($_POST['User_id']);
    $id = $mysqli->real_escape_string($_POST['id']);
    $legal_area = $mysqli->real_escape_string($_POST['legal_area']);
    $consultation_type = $mysqli->real_escape_string($_POST['consultation_type']);
    $preferred_date = $mysqli->real_escape_string($_POST['preferred_date']);
    $time_slot = $mysqli->real_escape_string($_POST['time_slot']);
    $phone_number = $mysqli->real_escape_string($_POST['phone_number']);

    // Use prepared statements for safety
    $stmt = $mysqli->prepare("INSERT INTO consultations (User_id, id, legal_area, consultation_type, preferred_date, time_slot, phone_number)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $User_id, $id, $legal_area, $consultation_type, $preferred_date, $time_slot, $phone_number);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}

?>
