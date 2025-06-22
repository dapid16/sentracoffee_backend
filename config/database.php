<?php
class Database {
    private $host = "localhost";
    private $db_name = "sentracoffee_db"; // PASTIKAN NAMA INI SAMA PERSIS DENGAN DB LO
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                                 $this->username,
                                 $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // TIDAK ADA ECHO ATAU PRINT DI SINI!
            throw new Exception("Database connection error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>