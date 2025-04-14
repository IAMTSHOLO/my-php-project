<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";

// Create a connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Validate incoming POST data
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute SQL statement
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Compare hashed password from DB
        if (password_verify($password, $user['password'])) {
         // SUCCESS: Return a simple success message with user ID
           echo "Login successful|" . $user['User_id'];
            exit();

        } else {
            // Wrong password
            echo "Invalid email or password";
            exit();
        }
    } else {
        // User not found
        echo "Invalid email or password";
        exit();
    }

    $stmt->close();
} else {
    // Missing required fields
    echo "Email and password are required";
    exit();
}

$mysqli->close();
?>
