<?php
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
            // Make sure headers not sent yet
            if (!headers_sent()) {
                header("Location: connectionLost.php");
                exit;  // ✅ stop execution right after redirect
            } else {
                // Fallback if headers already sent
                echo "<script>window.location.href='connectionLost.php';</script>";
                exit;
            }
        }
    }

    protected function getConnection()
    {
        return $this->conn;
    }
}
