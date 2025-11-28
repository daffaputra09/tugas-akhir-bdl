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
        try {
            $query = "SELECT id_jurusan, nama_jurusan, akreditasi FROM " . $this->table_name . " ORDER BY nama_jurusan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getAllJurusan: " . $e->getMessage());
            return false;
        }
    }

    public function createJurusan($data)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO " . $this->table_name . "
                      (nama_jurusan, akreditasi)
                      VALUES (:nama_jurusan, :akreditasi)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
            $stmt->bindParam(":akreditasi", $data['akreditasi']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createJurusan: " . $e->getMessage());
            return false;
        }
    }

    public function updateJurusan($id, $data)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE " . $this->table_name . "
                      SET nama_jurusan = :nama_jurusan,
                          akreditasi = :akreditasi
                      WHERE id_jurusan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
            $stmt->bindParam(":akreditasi", $data['akreditasi']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateJurusan: " . $e->getMessage());
            return false;
        }
    }

    public function deleteJurusan($id)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM " . $this->table_name . " WHERE id_jurusan = :id";
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
                error_log("Error deleteJurusan: Foreign Key Constraint - " . $e->getMessage());
                return 'fk_error';
            } else {
                error_log("Error deleteJurusan: " . $e->getMessage());
                return false;
            }
        }
    }

    public function getJurusanById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_jurusan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getJurusanById: " . $e->getMessage());
            return false;
        }
    }

    public function searchJurusan($keyword)
    {
        try {
            $query = "SELECT id_jurusan, nama_jurusan, akreditasi FROM " . $this->table_name . " WHERE nama_jurusan ILIKE :keyword ORDER BY nama_jurusan DESC";
            $stmt = $this->conn->prepare($query);
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchJurusan: " . $e->getMessage());
            return false;
        }
    }
}
?>