<?php
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "smartpos";
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Log the error message or handle it as needed
    error_log($e->getMessage());
    // Jump to connectionLost.php
    header('Location: connectionLost.php');
    exit(); // Ensure no further code is executed
}
