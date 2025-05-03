<?php
file_put_contents("hit_log.txt", "Script was hit\n", FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain");

// Database connection
$servername = "localhost";
$username = "root"; // Change this to your DB username
$password = ""; // Change this to your DB password
$dbname = "legalservices"; // Your database name

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get the consultation ID from the request
if (isset($_POST['consultation_id'])) {
    $consultation_id = $_POST['consultation_id'];

    // Prepare SQL to delete the consultation
    $sql = "DELETE FROM consultations WHERE consultation_id = ?";

    // Prepare statement
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $consultation_id);  // "i" means the parameter is an integer
        
        // Execute the query
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                echo "success";  // Successfully deleted
            } else {
                echo "no_rows_deleted";  // No rows deleted (could be invalid ID)
            }
        } else {
            echo "error_executing_query";    // Error executing the SQL query
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "error_preparing_statement";  // Failed to prepare the SQL statement
    }
} else {
    echo "consultation_id_not_set";  // Consultation ID is not set
}

// Close the database connection
$mysqli->close();
?>
