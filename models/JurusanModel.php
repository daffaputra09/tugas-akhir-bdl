<?php
class JurusanModel
{
    private $conn;
    private $table_name = "jurusan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllJurusan()
    {
        $query = "SELECT id_jurusan, nama_jurusan, akreditasi FROM  $this->table_name  ORDER BY nama_jurusan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function createJurusan($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                  (nama_jurusan, akreditasi)
                  VALUES (:nama_jurusan, :akreditasi)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
        $stmt->bindParam(":akreditasi", $data['akreditasi']);
        return $stmt->execute();
    }

    public function updateJurusan($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET nama_jurusan = :nama_jurusan,
                      akreditasi = :akreditasi
                  WHERE id_jurusan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
        $stmt->bindParam(":akreditasi", $data['akreditasi']);
        return $stmt->execute();
    }

    public function deleteJurusan($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_jurusan = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                return 'fk_error';
            } else {
                return false;
            }
        }
    }

    public function getJurusanById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_jurusan = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchJurusan($keyword)
    {
        $query = "SELECT id_jurusan, nama_jurusan, akreditasi FROM jurusan WHERE nama_jurusan ILIKE :keyword ORDER BY nama_jurusan DESC";
        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
