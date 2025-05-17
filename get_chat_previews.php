<?php
file_put_contents("hit_log.txt", "Script was hit\n", FILE_APPEND);

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: text/plain");

// Database connection
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}


$User_id = isset($_GET['User_id ']) ? $_GET['User_id '] : null;

if (!$User_id ) {
    die("Error: User ID is required");
}

$sql = "
SELECT 
    l.id,
    CONCAT(l.first_name, ' ', l.last_name) as lawyer_name,
    m.message as last_message,
    m.timestamp as last_message_time,
    (SELECT COUNT(*) FROM messages 
     WHERE sender_id = l.id 
     AND receiver_id = ? 
     AND is_read = 0) as unread_count
FROM messages m
JOIN lawyers l ON (m.sender_id = l.id OR m.receiver_id = l.id)
WHERE (m.sender_id = ? OR m.receiver_id = ?)
AND (
    m.id = (
        SELECT MAX(id) 
        FROM messages 
        WHERE (sender_id = l.id AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = l.id)
    )
)
GROUP BY l.id
ORDER BY m.timestamp DESC
";

$stmt = $mysqli ->prepare($sql);
if (!$stmt) {
    die("Error: Query preparation failed: " . $mysqli ->error);
}

$stmt->bind_param("iiiii", $User_id , $User_id , $User_id , $User_id , $User_id );

if (!$stmt->execute()) {
    die("Error: Query execution failed: " . $stmt->error);
}

$result = $stmt->get_result();

header('Content-Type: text/plain');

while ($row = $result->fetch_assoc()) {
    $time = date('Y-m-d H:i', strtotime($row['last_message_time']));
    $unread = (int)$row['unread_count'];
    echo "Lawyer: {$row['lawyer_name']} | Last: {$row['last_message']} | Time: {$time} | Unread: {$unread}\n";
}

$stmt->close();
$mysqli ->close();
?>
