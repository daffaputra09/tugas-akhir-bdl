<?php
class MatakuliahModel
{
    private $conn;
    private $table_name = "matakuliah";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllMatakuliah()
    {
        try {
            $query = "SELECT 
                        m.id_mk,
                        m.kode_mk,
                        m.nama_mk,
                        m.sks,
                        m.semester,
                        m.id_jurusan,
                        j.nama_jurusan
                      FROM 
                        " . $this->table_name . " m
                        LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                      ORDER BY 
                        m.kode_mk";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getAllMatakuliah: " . $e->getMessage());
            return false;
        }
    }

    public function createMatakuliah($data)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . "
                      (kode_mk, nama_mk, sks, semester, id_jurusan)
                      VALUES (:kode_mk, :nama_mk, :sks, :semester, :id_jurusan)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":kode_mk", $data['kode_mk']);
            $stmt->bindParam(":nama_mk", $data['nama_mk']);
            $stmt->bindParam(":sks", $data['sks']);
            $stmt->bindParam(":semester", $data['semester']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createMatakuliah: " . $e->getMessage());
            return false;
        }
    }

    public function updateMatakuliah($id, $data)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . "
                      SET kode_mk = :kode_mk,
                          nama_mk = :nama_mk,
                          sks = :sks,
                          semester = :semester,
                          id_jurusan = :id_jurusan
                      WHERE id_mk = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":kode_mk", $data['kode_mk']);
            $stmt->bindParam(":nama_mk", $data['nama_mk']);
            $stmt->bindParam(":sks", $data['sks']);
            $stmt->bindParam(":semester", $data['semester']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateMatakuliah: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMatakuliah($id)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id_mk = :id";
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
                error_log("Error deleteMatakuliah: Foreign Key Constraint - " . $e->getMessage());
                return 'fk_error';
            } else {
                error_log("Error deleteMatakuliah: " . $e->getMessage());
                return false;
            }
        }
    }

    public function getMatakuliahById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_mk = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getMatakuliahById: " . $e->getMessage());
            return false;
        }
    }

    public function getAllJurusan()
    {
        try {
            $query = "SELECT id_jurusan, nama_jurusan FROM jurusan ORDER BY nama_jurusan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getAllJurusan: " . $e->getMessage());
            return false;
        }
    }

    public function searchMatakuliah($keyword)
    {
        try {
            $query = "SELECT 
                        m.id_mk,
                        m.kode_mk,
                        m.nama_mk,
                        m.sks,
                        m.semester,
                        m.id_jurusan,
                        j.nama_jurusan
                      FROM 
                        " . $this->table_name . " m
                        LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                      WHERE 
                        m.kode_mk ILIKE :keyword 
                        OR m.nama_mk ILIKE :keyword
                        OR j.nama_jurusan ILIKE :keyword
                      ORDER BY 
                        m.kode_mk";
            $stmt = $this->conn->prepare($query);
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchMatakuliah: " . $e->getMessage());
            return false;
        }
    }
}
?>