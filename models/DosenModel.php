<?php
class DosenModel
{
    private $conn;
    private $table_name = "dosen";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllDosen($limit = null, $offset = null)
    {
        try {
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

            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->conn->prepare($query);

            if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getAllDosen: " . $e->getMessage());
            return false;
        }
    }

    public function countTotalDosen()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error countTotalDosen: " . $e->getMessage());
            return 0;
        }
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

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createDosen: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatusDosen($id_dosen, $status)
    {
        try {
            $query = "CALL public.update_status_dosen(:id_dosen, :status)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_dosen", $id_dosen);
            $stmt->bindParam(":status", $status);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updateStatusDosen: " . $e->getMessage());
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

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateDosen: " . $e->getMessage());
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

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleteDosen: " . $e->getMessage());
            return false;
        }
    }

    public function getDosenById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_dosen = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getDosenById: " . $e->getMessage());
            return false;
        }
    }

    public function searchDosen($keyword, $status = '', $limit = null, $offset = null)
    {
        try {
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
                      WHERE (d.nip ILIKE :keyword
                          OR d.nama_dosen ILIKE :keyword
                          OR d.email ILIKE :keyword)";

            if (!empty($status)) {
                $query .= " AND d.status_aktif = :status";
            }

            $query .= " ORDER BY d.id_dosen DESC";

            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->conn->prepare($query);
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);

            if (!empty($status)) {
                $stmt->bindParam(":status", $status);
            }

            if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchDosen: " . $e->getMessage());
            return false;
        }
    }

    public function countSearchDosen($keyword, $status = '')
    {
        try {
            $query = "SELECT COUNT(*) as total 
                      FROM " . $this->table_name . " d
                      WHERE (d.nip ILIKE :keyword
                          OR d.nama_dosen ILIKE :keyword
                          OR d.email ILIKE :keyword)";

            if (!empty($status)) {
                $query .= " AND d.status_aktif = :status";
            }

            $stmt = $this->conn->prepare($query);
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);
            
            if (!empty($status)) {
                $stmt->bindParam(":status", $status);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error countSearchDosen: " . $e->getMessage());
            return 0;
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

    public function getJadwalDosen($id_dosen)
    {
        try {
            $query = "SELECT * FROM public.get_jadwal_dosen(:id_dosen)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_dosen", $id_dosen);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getJadwalDosen: " . $e->getMessage());
            return false;
        }
    }
}
