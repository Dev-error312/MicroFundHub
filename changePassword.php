<?php
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(array('success' => false, 'message' => 'User not logged in'));
    exit;
}

$userId = $_SESSION['userId'];
$currentPassword = $_POST['currentPassword'];
$newPassword = $_POST['newPassword'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error));
    exit;
}

$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($hashedPassword);
$stmt->fetch();
$stmt->close();

if (!password_verify($currentPassword, $hashedPassword)) {
    echo json_encode(array('success' => false, 'message' => 'Current password is incorrect'));
    exit;
}

$newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
$sql = "UPDATE users SET password = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $newHashedPassword, $userId);

if ($stmt->execute()) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to change password: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
