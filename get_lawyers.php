<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "legalservices"; // Your database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Query to fetch lawyer names and IDs by joining lawyers with users table
    $sql = "
        SELECT l.id, u.first_name, u.last_name
        FROM lawyers l
        JOIN users u ON l.User_id = u.User_id
    "; 

    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // Output each lawyer as an <option> tag
        while($row = $result->fetch_assoc()) {
            // Output the <option> tag for each lawyer
            echo "<option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";

        }
    } else {
        // If no lawyers are found, show an empty option
        echo "<option value=''>No lawyers found</option>";
    }

    $mysqli->close();
}

?>
