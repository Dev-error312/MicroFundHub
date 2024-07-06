<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['psw'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die(json_encode(array('success' => false, 'message' => 'Prepare statement failed: ' . $conn->error)));
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $hashedPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashedPassword)) {
        $_SESSION['userId'] = $userId;
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Invalid username or password.'));
    }

    $stmt->close();
}

$conn->close();
?>
