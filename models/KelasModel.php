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
        try {
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
        } catch (PDOException $e) {
            error_log("Error getAllKelas: " . $e->getMessage());
            return false;
        }
    }

    public function createKelas($data)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . "
                        (nama_kelas, id_jurusan)
                      VALUES 
                        (:nama_kelas, :id_jurusan)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nama_kelas", $data['nama_kelas']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createKelas: " . $e->getMessage());
            return false;
        }
    }

    public function updateKelas($id, $data)
    {
        try {
            $this->conn->beginTransaction();
            
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
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateKelas: " . $e->getMessage());
            return false;
        }
    }

    public function deleteKelas($id)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id_kelas = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->getCode() == '23000') {
                error_log("Error deleteKelas: Foreign Key Constraint - " . $e->getMessage());
                return 'fk_error';
            } else {
                error_log("Error deleteKelas: " . $e->getMessage());
                return false;
            }
        }
    }

    public function getKelasById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_kelas = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getKelasById: " . $e->getMessage());
            return false;
        }
    }

    public function searchKelas($keyword)
    {
        try {
            $query = "SELECT 
                        k.id_kelas, 
                        k.nama_kelas, 
                        k.id_jurusan, 
                        j.nama_jurusan 
                      FROM 
                        " . $this->table_name . " k
                        LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                      WHERE 
                        k.nama_kelas ILIKE :keyword OR j.nama_jurusan ILIKE :keyword
                      ORDER BY 
                        k.nama_kelas";

            $stmt = $this->conn->prepare($query);
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchKelas: " . $e->getMessage());
            return false;
        }
    }
}
?>