<?php
// Connection.php
class Dbh
{
    private $host = "localhost";
    private $user = "root";
    private $pwd = "";
    private $dbName = "bais-system";
    private $conn = null;

    // Singleton instance
    private static $instance = null;

    private function __construct()
    {
        $this->conn = new mysqli($this->host, $this->user, $this->pwd, $this->dbName);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    // Get single instance
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Dbh();
        }
        return self::$instance->conn;
    }
}