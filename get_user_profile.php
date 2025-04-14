<?php
header("Content-Type: text/plain");

// DB config
$host = "localhost";
$dbname = "legalservices";
$username = "root";
$password = "";

$mysqli  = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    echo "error=Connection failed";
    exit();
}

// Check for user_id
if (!isset($_GET['User_id'])) {
    echo "error=Missing User_id";
    exit();
}

$User_id = intval($_GET['User_id']);

// Query for both Clients and Lawyers (using a UNION to join)
$sql = "
    SELECT 
        u.User_id, 
        u.first_name, 
        u.last_name, 
        u.email, 
        u.contact_details, 
        u.physical_address, 
        u.role, 
        l.expertise
    FROM users u 
    LEFT JOIN lawyers l ON u.User_id = l.User_id
    WHERE u.User_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $User_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    // Handle dynamic output based on user role
    foreach ($user as $key => $value) {
        if ($key === 'expertise' && empty($value)) {
            // Do not show expertise for clients (if it's empty for the user)
            continue;
        }
        echo "$key=$value\n";
    }
} else {
    echo "error=User not found";
    exit();
}

$stmt->close();
$mysqli->close();
?>
