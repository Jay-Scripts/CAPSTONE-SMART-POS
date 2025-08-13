<?php

// ===== DATABASE CONNECTION (PDO only) =====
class Dbh
{
    private $host = "localhost";
    private $port = 3307;
    private $dbName = "smart_pos";
    private $username = "root";
    private $password = "";
    private $conn;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbName};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            header("Location: connectionLost.php");
            die();
        }
    }

    protected function getConnection()
    {
        return $this->conn;
    }
}
