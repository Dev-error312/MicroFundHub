<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(array('success' => false, 'message' => 'Please log in first.'));
    exit();
}

$userId = $_SESSION['userId'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error));
    exit();
}

// Retrieve form data
$loanAmount = $_POST['loanAmount'];
$purpose = $_POST['purpose'];
$doc = $_FILES['doc'];

// Allowed file types
$allowedTypes = array('image/png', 'image/jpeg', 'image/jpg', 'application/pdf');

// Validate file type
if (!in_array($doc['type'], $allowedTypes)) {
    echo json_encode(array('success' => false, 'message' => 'Invalid file type. Allowed types are PNG, JPG, JPEG, and PDF.'));
    exit();
}

// Define upload directory
$uploadDir = 'uploads/';
$uploadFile = $uploadDir . basename($doc['name']);

// Create upload directory if it doesn't exist
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Move the uploaded file to the upload directory
if (move_uploaded_file($doc['tmp_name'], $uploadFile)) {
    // Insert loan application into the database
    $sql = "INSERT INTO loans (userId, loanAmount, purpose, document) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(array('success' => false, 'message' => 'Prepare statement failed: ' . $conn->error));
        exit();
    }
    $stmt->bind_param("idss", $userId, $loanAmount, $purpose, $uploadFile);

    if ($stmt->execute()) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Execution failed: ' . $stmt->error));
    }

    $stmt->close();
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to upload document.'));
}

// Close the database connection
$conn->close();
?>
