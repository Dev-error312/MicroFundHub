<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "microfundhub";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error)));
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$username = $_POST['username'];
$password = password_hash($_POST['psw'], PASSWORD_BCRYPT);

$sql = "INSERT INTO users (name, email, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $name, $email, $phone, $address, $username, $password);

if ($stmt->execute()) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error: ' . $stmt->error));
}

$stmt->close();
$conn->close();
?>
