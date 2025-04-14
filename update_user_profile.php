<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: text/plain");

// DB config
$host = "localhost";
$dbname = "legalservices";
$username = "root";
$password = "";

$mysqli  = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    echo "error=Connection failed";
    exit();
}

// Check required POST fields
if (
    !isset($_POST['User_id']) || 
    !isset($_POST['first_name']) ||
    !isset($_POST['last_name']) ||  
    !isset($_POST['email']) || 
    !isset($_POST['contact_details']) || 
    !isset($_POST['physical_address'])
) {
    echo "error=Missing fields";
    exit();
}

$User_id = intval($_POST['User_id']);
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$contact_details = $_POST['contact_details'];
$physical_address = $_POST['physical_address'];

// Update the common user fields
$sql = "UPDATE users SET first_name=?, last_name=?, email=?, contact_details=?, physical_address=? WHERE User_id=?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssssi", $first_name, $last_name, $email, $contact_details, $physical_address, $User_id);

if (!$stmt->execute()) {
    echo "error=Update failed (user)";
    $stmt->close();
    $mysqli->close();
    exit();
}
$stmt->close();

// Check if Qualifications is provided (indicating a lawyer)
if (isset($_POST['expertise'])) {
    $expertise = $_POST['expertise'];
    
    // Check if the user exists in the lawyers table
    $checkSql = "SELECT Lawyer_id FROM lawyers WHERE User_id = ?";
    $checkStmt = $mysqli->prepare($checkSql);
    $checkStmt->bind_param("i", $User_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Update existing lawyer qualifications
        $updateLawyerSql = "UPDATE lawyers SET expertise = ? WHERE User_id = ?";
        $updateLawyerStmt = $mysqli->prepare($updateLawyerSql);
        $updateLawyerStmt->bind_param("si", $expertise, $User_id);
        $updateLawyerStmt->execute();
        $updateLawyerStmt->close();
    }
    $checkStmt->close();
}

echo "success";

$mysqli->close();
?>
