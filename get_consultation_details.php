<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain");  // Change to plain text for easy display

$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the consultation ID from the request
$consultation_id = $_GET['consultation_id'];
error_log("Consultation ID: " . $consultation_id);


// Prepare SQL query to retrieve consultation details
$query = "SELECT consultations.*, 
                 CONCAT(users.first_name, ' ', users.last_name) AS lawyer_name,
                 users.contact_details
          FROM consultations
          JOIN lawyers ON consultations.id = lawyers.id
          JOIN users ON lawyers.User_id = users.User_id
          WHERE consultations.consultation_id = ?";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('i', $consultation_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if consultation details were found
if ($result->num_rows > 0) {
    $consultation = $result->fetch_assoc();
    
    // Output the details as plaintext
    echo "Lawyer Name: " . $consultation['lawyer_name'] . "\n";
    echo "Consultation Type: " . $consultation['consultation_type'] . "\n";
    echo "Phone Number: " . $consultation['contact_details'] . "\n";
    echo "Legal Area: " . $consultation['legal_area'] . "\n";
    echo "Time Slot: " . $consultation['time_slot'] . "\n";
    echo "Preferred Date: " . $consultation['preferred_date'] . "\n";
} else {
    // If no consultation found, output an error message
    echo "Error: Consultation not found\n";
}

$stmt->close();
$mysqli->close();
?>
