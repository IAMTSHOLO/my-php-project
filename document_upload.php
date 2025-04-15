<?php
$host = "localhost";
$username = "root";
$password = ""; // Change this if you set a MySQL password
$database = "legalservices"; // Replace with your actual DB name

// Create DB connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check DB connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// File upload handling
$targetDir = "uploads/";
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (isset($_FILES['document']) && isset($_POST['User_id'])) {
    $userId = $_POST['User_id'];
    $category = $_POST['category'] ?? 'Uncategorized';

    $fileName = basename($_FILES["document"]["name"]);
    $fileTmpPath = $_FILES["document"]["tmp_name"];
    $fileSize = $_FILES["document"]["size"];
    $fileType = $_FILES["document"]["type"];
    $uploadDate = date("Y-m-d H:i:s");
    $filePath = $targetDir . $fileName;

    // Move file to uploads/ directory
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        $sql = "INSERT INTO documents (
                    document_name,
                    document_type,
                    upload_date,
                    last_modified_date,
                    status,
                    document_size,
                    document_file_path,
                    User_id,
                    category
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $mysqli->prepare($sql);
        $status = 'Active'; // Default document status
        $lastModified = $uploadDate;

        $stmt->bind_param(
            "sssssssis",
            $fileName,
            $fileType,
            $uploadDate,
            $lastModified,
            $status,
            $fileSize,
            $filePath,
            $userId,
            $category
        );

        if ($stmt->execute()) {
            echo "Document uploaded and saved successfully!";
        } else {
            echo "Database error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file or user ID received.";
}

$mysqli->close();
?>
