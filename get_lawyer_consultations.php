<?php
$mysqli = new mysqli("localhost", "root", "", "legal_app");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lawyer_id = $_POST['lawyer_id'];
    $sql = "SELECT * FROM consultations WHERE lawyer_id = '$lawyer_id' ORDER BY preferred_date DESC";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['id'] . "|" . $row['legal_area'] . "|" . $row['consultation_type'] . "|" .
                 $row['preferred_date'] . "|" . $row['time_slot'] . "|" . $row['phone_number'] . "|" . 
                 $row['status'] . "\n";
        }
    } else {
        echo "No consultations";
    }
}

$mysqli->close();
?>
