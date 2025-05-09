<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $User_id = isset($_POST['User_id']) ? $mysqli->real_escape_string($_POST['User_id']) : null;
    $id = isset($_POST['lawyer']) ? $mysqli->real_escape_string($_POST['lawyer']) : null;
    $legal_area = isset($_POST['legal_area']) ? $mysqli->real_escape_string($_POST['legal_area']) : null;
    $consultation_type = isset($_POST['consultation_type']) ? $mysqli->real_escape_string($_POST['consultation_type']) : null;
    $preferred_date = isset($_POST['preferred_date']) ? $mysqli->real_escape_string($_POST['preferred_date']) : null;
    $time_slot = isset($_POST['time_slot']) ? $mysqli->real_escape_string($_POST['time_slot']) : null;
    $phone_number = isset($_POST['phone_number']) ? $mysqli->real_escape_string($_POST['phone_number']) : null;

    // Validation: check required fields
    if (empty($User_id) || empty($id) || empty($legal_area) || empty($consultation_type) ||
        empty($preferred_date) || empty($time_slot) || empty($phone_number)) {
        echo "Error: All fields are required.";
        exit;
    }

    // Insert into database
    $stmt = $mysqli->prepare("INSERT INTO consultations (User_id, id, legal_area, consultation_type, preferred_date, time_slot, phone_number)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $User_id, $id, $legal_area, $consultation_type, $preferred_date, $time_slot, $phone_number);

    if ($stmt->execute()) {
        echo "Success: Your consultation has been booked!";
    } else {
        echo "Error: Failed to book consultation " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>
