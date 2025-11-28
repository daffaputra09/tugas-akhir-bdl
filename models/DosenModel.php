<?php
class DosenModel
{
    private $conn;
    private $table_name = "dosen";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllDosen()
    {
        $query = "SELECT 
                    d.id_dosen,
                    d.nip,
                    d.nama_dosen,
                    d.email,
                    d.no_hp,
                    d.status_aktif,
                    d.id_jurusan,
                    j.nama_jurusan
                  FROM " . $this->table_name . " d
                  LEFT JOIN jurusan j ON d.id_jurusan = j.id_jurusan
                  ORDER BY d.id_dosen DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function createDosen($data)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . "
                      (nip, nama_dosen, id_jurusan, email, no_hp, status_aktif)
                      VALUES (:nip, :nama_dosen, :id_jurusan, :email, :no_hp, :status_aktif)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nip", $data['nip']);
            $stmt->bindParam(":nama_dosen", $data['nama_dosen']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":no_hp", $data['no_hp']);
            $stmt->bindParam(":status_aktif", $data['status_aktif']);

            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function updateDosen($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            $query = "UPDATE " . $this->table_name . "
                      SET nip = :nip,
                          nama_dosen = :nama_dosen,
                          id_jurusan = :id_jurusan,
                          email = :email,
                          no_hp = :no_hp,
                          status_aktif = :status_aktif
                      WHERE id_dosen = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nip", $data['nip']);
            $stmt->bindParam(":nama_dosen", $data['nama_dosen']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":no_hp", $data['no_hp']);
            $stmt->bindParam(":status_aktif", $data['status_aktif']);

            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function deleteDosen($id)
    {
        try {
            $this->conn->beginTransaction();

            $query = "DELETE FROM " . $this->table_name . " WHERE id_dosen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();

            if ($e->getCode() == '23000') {
                return 'fk_error';
            } else {
                return false;
            }
        }
    }

    public function getDosenById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_dosen = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchDosen($keyword)
    {
        $query = "SELECT 
                    d.id_dosen,
                    d.nip,
                    d.nama_dosen,
                    d.email,
                    d.no_hp,
                    d.status_aktif,
                    j.nama_jurusan
                  FROM " . $this->table_name . " d
                  LEFT JOIN jurusan j ON d.id_jurusan = j.id_jurusan
                  WHERE d.nip ILIKE :keyword
                     OR d.nama_dosen ILIKE :keyword
                     OR d.email ILIKE :keyword
                  ORDER BY d.id_dosen DESC";
        $stmt = $this->conn->prepare($query);
        $kw = "%{$keyword}%";
        $stmt->bindParam(":keyword", $kw);
        $stmt->execute();
        return $stmt;
    }

    public function getAllJurusan()
    {
        $query = "SELECT id_jurusan, nama_jurusan FROM jurusan ORDER BY nama_jurusan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
