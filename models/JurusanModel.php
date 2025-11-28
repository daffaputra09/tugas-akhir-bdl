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
        $this->conn->beginTransaction();
        try {
            $query = "INSERT INTO " . $this->table_name . "
                  (nama_jurusan, akreditasi)
                  VALUES (:nama_jurusan, :akreditasi)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
            $stmt->bindParam(":akreditasi", $data['akreditasi']);
            $stmt->execute();

            $this->conn->commit();

            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateJurusan($id, $data)
    {

        $this->conn->beginTransaction();
        try {
            $query = "UPDATE " . $this->table_name . "
                  SET nama_jurusan = :nama_jurusan,
                      akreditasi = :akreditasi
                  WHERE id_jurusan = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nama_jurusan", $data['nama_jurusan']);
            $stmt->bindParam(":akreditasi", $data['akreditasi']);
            $stmt->execute();

            $this->conn->commit();

            return true;
        } catch (Exception $th) {
            $this->conn->rollBack();
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
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();

            if ($e->getCode() === '23000') {
                return 'fk_error';
            }

            return $e->getMessage();
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
