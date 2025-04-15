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

$User_id = $_POST['User_id'] ?? '';

if (empty($User_id)) {
    echo 'ERROR: User ID is missing';
    exit;
}

// Fixing the query binding to match only one parameter
$stmt = $mysqli->prepare("SELECT language, theme, notifications FROM user_settings WHERE User_id = ?");
$stmt->bind_param("s", $User_id); // We need to pass only one string parameter
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo $row['language'] . ',' . $row['theme'] . ',' . $row['notifications'];
} else {
    echo 'ERROR: No settings found';
}
?>
