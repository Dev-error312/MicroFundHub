<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    echo json_encode(array('success' => false, 'message' => 'Please log in first.'));
    exit();
}

$userId = $_SESSION['userId'];
$message = trim($_POST['message']);

if (empty($message)) {
    echo json_encode(array('success' => false, 'message' => 'Feedback message cannot be empty.'));
    exit();
}

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

// Insert feedback into the database
$sql = "INSERT INTO feedback (userId, message) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Prepare statement failed: ' . $conn->error));
    exit();
}

$stmt->bind_param("is", $userId, $message);

if ($stmt->execute()) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Execution failed: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
