<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

// Create connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get and sanitize input values
    $User_id = isset($_POST['User_id']) ? $mysqli->real_escape_string($_POST['User_id']) : null;
    $lawyer_id = isset($_POST['lawyer']) ? $mysqli->real_escape_string($_POST['lawyer']) : null;
    // Debugging: Check if lawyer_id is set
      var_dump($lawyer_id); // This will print the lawyer ID value
    $legal_area = isset($_POST['legal_area']) ? $mysqli->real_escape_string($_POST['legal_area']) : null;
    $consultation_type = isset($_POST['consultation_type']) ? $mysqli->real_escape_string($_POST['consultation_type']) : null;
    $preferred_date = isset($_POST['preferred_date']) ? $mysqli->real_escape_string($_POST['preferred_date']) : null;
    $time_slot = isset($_POST['time_slot']) ? $mysqli->real_escape_string($_POST['time_slot']) : null;
    $phone_number = isset($_POST['phone_number']) ? $mysqli->real_escape_string($_POST['phone_number']) : null;

    // Ensure lawyer_id is not empty
    if (empty($lawyer_id)) {
        die("Error: Lawyer ID is missing. Please select a lawyer.");
    }

    // Prepare and execute the SQL query
    $stmt = $mysqli->prepare("INSERT INTO consultations (User_id, lawyer_id, legal_area, consultation_type, preferred_date, time_slot, phone_number)
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssss", $User_id, $lawyer_id, $legal_area, $consultation_type, $preferred_date, $time_slot, $phone_number);

    if ($stmt->execute()) {
        echo "Success: Your consultation has been booked!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
}

?>
