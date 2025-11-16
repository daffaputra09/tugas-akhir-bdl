<?php
class KelasModel
{
    private $conn;
    private $table_name = "kelas";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllKelas()
    {
        $query = "SELECT 
                    k.id_kelas, 
                    k.nama_kelas, 
                    k.id_jurusan, 
                    j.nama_jurusan 
                  FROM 
                    " . $this->table_name . " k
                    LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                  ORDER BY 
                    k.nama_kelas";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function createKelas($data)
    {
        $query = "INSERT INTO " . $this->table_name . "
                    (nama_kelas, id_jurusan)
                  VALUES 
                    (:nama_kelas, :id_jurusan)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama_kelas", $data['nama_kelas']);
        $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
        return $stmt->execute();
    }

    public function updateKelas($id, $data)
    {
        $query = "UPDATE " . $this->table_name . "
                  SET 
                    nama_kelas = :nama_kelas,
                    id_jurusan = :id_jurusan
                  WHERE 
                    id_kelas = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama_kelas", $data['nama_kelas']);
        $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
        return $stmt->execute();
    }

    public function deleteKelas($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_kelas = :id";

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

    public function getKelasById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_kelas = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchKelas($keyword)
    {
        // Query diubah untuk JOIN dan search di k.nama_kelas
        $query = "SELECT 
                    k.id_kelas, 
                    k.nama_kelas, 
                    k.id_jurusan, 
                    j.nama_jurusan 
                  FROM 
                    " . $this->table_name . " k
                    LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                  WHERE 
                    k.nama_kelas ILIKE :keyword OR J.nama_jurusan ILIKE :keyword
                  ORDER BY 
                    k.nama_kelas";

        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }
}
