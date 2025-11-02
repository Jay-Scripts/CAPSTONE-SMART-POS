<?php
$host = "localhost";
$port = 3307;
$dbName = "sampol";
$username = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbName;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // For JS heartbeat check
    if (isset($_GET['check'])) {
        echo "Connected";
        exit;
    }
} catch (PDOException $e) {
    if (isset($_GET['check'])) {
        echo "FAIL";
        exit;
    }

    // For debugging (remove in production)
    die("DB Connection failed: " . $e->getMessage());
}
