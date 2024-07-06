<?php
session_start();

if (!isset($_SESSION['userId'])) {
    $_SESSION['error'] = "Please log in first.";
    header("Location: login.html");
    exit();
}
?>