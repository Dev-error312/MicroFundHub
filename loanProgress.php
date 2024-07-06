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

// Retrieve loans for the logged-in user
$sql = "SELECT loanAmount, purpose, status, DATE_FORMAT(date, '%Y-%m-%d') AS date FROM loans WHERE userId = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(array('success' => false, 'message' => 'Prepare statement failed: ' . $conn->error));
    exit();
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$loans = array();
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

echo json_encode(array('success' => true, 'loans' => $loans));

$stmt->close();
$conn->close();
?>
