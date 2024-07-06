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

$sql = "SELECT loanId, loanAmount, dueDate FROM loans WHERE userId = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$loans = array();
while ($row = $result->fetch_assoc()) {
    $loans[] = $row;
}

header('Content-Type: application/json');
echo json_encode(array('success' => true, 'loans' => $loans));

$stmt->close();
$conn->close();
?>
