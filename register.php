<?php
// Database connection setup (adjust your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "legalservices";  // Name of your database

// Create a connection
$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assigning input data to variables
    $first_name = $_POST['first_name'] ?? null;
    $last_name = $_POST['last_name'] ?? null;
    $gender = $_POST['gender'] ?? null;
    $dob = $_POST['dob'] ?? null;
    $physical_address = $_POST['physical_address'] ?? null;
    $contact_details = $_POST['contact_details'] ?? null;
    $nationality = $_POST['nationality'] ?? null;
    $national_id = $_POST['national_id'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);  // Hash the password before storing it
    $role = $_POST['role'] ?? 'client';  // Default to 'client' if no role is provided

    // Lawyer-specific fields
    $years_of_experience = $_POST['years_of_experience'] ?? null;
    $expertise = $_POST['expertise'] ?? null;

    // Begin transaction
    $mysqli->begin_transaction();

    try {
        // Insert common user data into users table
        $stmt = $mysqli->prepare("INSERT INTO users 
            (first_name, last_name, gender, dob, physical_address, contact_details, nationality, national_id, email, password, role)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $first_name, $last_name, $gender, $dob, $physical_address, $contact_details, $nationality, $national_id, $email, $hashedPassword, $role);
        $stmt->execute();

        // Get the ID of the inserted user
        $User_id = $mysqli->insert_id;

        // If the role is lawyer, insert into the lawyers table
        if ($role === 'lawyer') {
            // Insert lawyer-specific data into lawyers table
            $stmt2 = $mysqli->prepare("INSERT INTO lawyers (User_id, years_of_experience, expertise) VALUES (?, ?, ?)");
            $stmt2->bind_param("iis", $User_id, $years_of_experience, $expertise);
            $stmt2->execute();
        }

        // Commit transaction
        $mysqli->commit();

        // Return plain text message
        echo "Registration successful";

    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        $mysqli->rollback();
        echo "Registration failed: " . $e->getMessage();
    }

    // Close the database connection
    $mysqli->close();
} else {
    echo "Invalid request method";
}
?>
