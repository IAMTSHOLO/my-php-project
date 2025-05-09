<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain"); // Explicitly send plain text

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


// Check if GET parameters are set
if (isset($_GET['User_id']) && isset($_GET['id'])) {
    $User_id = $_GET['User_id'];
    $id = $_GET['id'];

    // Sanitize input to prevent SQL injection (using prepared statements)
    $stmt = $mysqli->prepare("SELECT messages.*, users.first_name AS sender_first_name FROM messages
                              JOIN users ON messages.sender_id = users.User_id
                              WHERE (messages.sender_id = ? AND messages.receiver_id = ?)
                              OR (messages.sender_id = ? AND messages.receiver_id = ?)
                              ORDER BY messages.timestamp ASC");

    // Bind parameters and execute
    $stmt->bind_param("iiii", $User_id, $id, $id, $User_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are messages
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo $row['sender_first_name'] . ": " . $row['message'] . "\n";  // Format message output as plain text
        }
    } else {
        echo "No messages found.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Missing parameters. Please provide 'User_id' and 'id'.\n";
}

// Close database connection
$mysqli->close();
?>
