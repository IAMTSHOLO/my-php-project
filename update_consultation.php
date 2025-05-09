<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=UTF-8');

$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";// Your database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


// Make sure DB connection is here

header("Content-Type: application/x-www-form-urlencoded");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $consultation_id = $_POST['consultation_id'] ?? null;
    $legal_area = $_POST['legal_area'] ?? null;
    $id = $_POST['lawyer'] ?? null;
    $consultation_type = $_POST['consultation_type'] ?? null;
    $preferred_date = $_POST['preferred_date'] ?? null;
    $time_slot = $_POST['time_slot'] ?? null;
    $phone_number = $_POST['phone_number'] ?? null;

    if ($consultation_id && $legal_area && $id && $consultation_type && $preferred_date && $time_slot && $phone_number) {
        $stmt = $mysqli->prepare("UPDATE consultations SET legal_area = ?, id = ?, consultation_type = ?, preferred_date = ?, time_slot = ?, phone_number = ? WHERE consultation_id = ?");
        $stmt->bind_param("ssssssi", $legal_area, $id, $consultation_type, $preferred_date, $time_slot, $phone_number, $consultation_id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error_updating";
        }

        $stmt->close();
    } else {
        echo "missing_fields";
    }
} else {
    echo "invalid_method";
}

$mysqli->close();
?>
