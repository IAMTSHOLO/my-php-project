<?php
session_start();

// Enable error logging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

// Create connection
$mysqli= new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Debug log
    error_log("Login attempt for email: $email");
    
    // Prepare SQL statement to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT User_id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"]; // Assuming the password is hashed in the database
        $User_id = $row["User_id"];
        
        // Verify password (either using password_verify if hashed, or direct comparison if not)
        // In production, always use password_verify with properly hashed passwords!
        if (password_verify($password, $hashed_password) || $password == $hashed_password) {
            // Generate a session ID
            $session_id = md5(uniqid() . $User_id . time());
            
            // Store session in database
            $stmt = $mysqli->prepare("INSERT INTO sessions (User_id, session_id, login_time, is_active) VALUES (?, ?, NOW(), TRUE)");
            $stmt->bind_param("is", $User_id, $session_id);
            $stmt->execute();
            
            // Store in PHP session
            $_SESSION['User_id'] = $User_id;
            $_SESSION['session_id'] = $session_id;
            
            // Debug log
            error_log("Login successful for user ID: $User_id, session_id : $session_id");
            
            // Return success with user ID and session ID
            echo "Login successful|$User_id|$session_id";
        } else {
            // Debug log
            error_log("Password verification failed for email: $email");
            echo "Invalid email or password";
        }
    } else {
        // Debug log
        error_log("No user found with email: $email");
        echo "Invalid email or password";
    }
    
    $stmt->close();
}

$mysqli->close();
?>