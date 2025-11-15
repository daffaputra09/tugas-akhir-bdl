<?php
class Database
{
    // Informasi koneksi database - GANTI dengan settingan lokal Anda
    private $host = "localhost";
    private $port = "5434";
    private $db_name = "db_akademik_kampus";
    private $username = "postgres";
    private $password = "password"; // Default username PostgreSQL
    // Ganti dengan password PostgreSQL Anda
    public $conn;
    // Method untuk mendapatkan koneksi
    public function getConnection()
    {
        $this->conn = null;
        try {
            // Membuat koneksi PDO ke PostgreSQL
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port .
                ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Set error mode ke exception untuk handling error yang lebih baik
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Koneksi database berhasil!";
        } catch (PDOException $exception) {
            echo "Error koneksi database: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
