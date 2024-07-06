<?php
session_start();

if (!isset($_SESSION['userId'])) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Please log in first.'));
    exit();
}

$userId = $_SESSION['userId'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error));
    exit();
}

$loanId = $_POST['loanId'];
$amount = $_POST['amount'];
$paymentMethod = $_POST['paymentMethod'];

if (empty($loanId) || empty($amount) || empty($paymentMethod)) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'All fields are required.'));
    exit();
}

$sql = "INSERT INTO repayments (loanId, userId, amount, paymentMethod, paymentDate) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $loanId, $userId, $amount, $paymentMethod);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'message' => 'Payment processed successfully!'));
} else {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'message' => 'Failed to process payment: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
