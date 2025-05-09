<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $User_id = $_POST['User_id'] ?? '';
    $language = $_POST['language'] ?? 'English';
    $theme = $_POST['theme'] ?? 'Light';
    $notifications = $_POST['notifications'] ?? 'On';

    if (empty($User_id)) {
        echo "User ID is missing";
        exit;
    }

    // Check if record already exists
    $checkSql = "SELECT * FROM user_settings WHERE User_id = '$User_id'";
    $checkResult = mysqli_query($mysqli, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "Settings already exist for this user.";
    } else {
        $sql = "INSERT INTO user_settings (User_id, language, theme, notifications)
                VALUES ('$User_id', '$language', '$theme', '$notifications')";
        if (mysqli_query($mysqli, $sql)) {
            echo "Settings inserted successfully";
        } else {
            echo "Insert failed: " . mysqli_error($mysqli);
        }
    }
} else {
    echo "Invalid request method";
}
?>
