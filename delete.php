<?php
$servername = "localhost";
$username = "Tsholofelo";
$password = "IAMTSHOLO199925";
$dbname = "legalservices";

$mysqli = new mysqli($servername, $username, $password, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileName = $_POST['document_name'] ?? '';
    $category = $_POST['category'] ?? '';

    if (empty($fileName) || empty($category)) {
        echo 'Error: Missing parameters';
        exit;
    }

    // ❗️ FIX: Use correct column name `document_file_path`
    $stmt = $mysqli->prepare("SELECT document_file_path FROM documents WHERE document_name = ? AND category = ?");
    $stmt->bind_param("ss", $fileName, $category);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($filePath);

    if ($stmt->num_rows === 0) {
        echo 'Error: File not found in database';
        $stmt->close();
        exit;
    }

    $stmt->fetch();
    $stmt->close();

    if (!file_exists($filePath)) {
        echo 'Error: File not found on server';
        exit;
    }

    unlink($filePath);

    $stmt = $mysqli->prepare("DELETE FROM documents WHERE document_name = ? AND category = ?");
    $stmt->bind_param("ss", $fileName, $category);
    if ($stmt->execute()) {
        echo "Deleted \"$fileName\" from category \"$category\" and database.";
    } else {
        echo 'Error: Could not delete record from database';
    }
    $stmt->close();
} else {
    echo 'Error: Invalid request method';
}

$mysqli->close();
?>
