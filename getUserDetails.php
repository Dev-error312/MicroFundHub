<?php
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(array('success' => false, 'message' => 'User not logged in'));
    exit;
}

$userId = $_SESSION['userId'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error));
    exit;
}

$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($userName, $userEmail);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($userName) {
    echo json_encode(array('success' => true, 'userName' => $userName, 'userEmail' => $userEmail));
} else {
    echo json_encode(array('success' => false, 'message' => 'User not found'));
}
?>
