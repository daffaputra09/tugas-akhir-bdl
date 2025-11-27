<?php
class Database
{
    // Informasi koneksi database - GANTI dengan settingan lokal Anda
    private $host = "localhost";
    private $port = "5430";
    private $db_name = "db_akademik_kampus";
    private $username = "postgres";
    private $password = "12345678"; // Default username PostgreSQL
    // Ganti dengan password PostgreSQL Anda
    public $conn;
    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port .
                ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error koneksi database: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
