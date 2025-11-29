<?php
class JadwalModel
{
    private $conn;
    private $table_name = "mv_jadwal_kelas";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getHariOptions()
    {
        try {
            $query = "SELECT DISTINCT hari FROM " . $this->table_name . " ORDER BY hari DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getHariOptions: " . $e->getMessage());
            return array();
        }
    }

    public function getTahunOptions()
    {
        try {
            $query = "SELECT DISTINCT tahun_akademik FROM " . $this->table_name . " ORDER BY tahun_akademik ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Error getTahunOptions: " . $e->getMessage());
            return array();
        }
    }

    public function getAllJadwal()
    {
        return $this->searchJadwal('', '', '');
    }

    public function searchJadwal($keyword, $filterHari = '', $filterTahun = '', $limit = null, $offset = null)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1 ";

            if (!empty($keyword)) {
                $query .= " AND (nama_mk ILIKE :keyword 
                            OR nama_dosen ILIKE :keyword 
                            OR nama_kelas ILIKE :keyword)";
            }

            if (!empty($filterHari)) {
                $query .= " AND hari = :hari ";
            }

            if (!empty($filterTahun)) {
                $query .= " AND tahun_akademik = :tahun_akademik ";
            }

            $query .= " ORDER BY hari DESC, jam_mulai ASC";
            
            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->conn->prepare($query);

            if (!empty($keyword)) {
                $kw = "%{$keyword}%";
                $stmt->bindParam(":keyword", $kw);
            }

            if (!empty($filterHari)) {
                $stmt->bindParam(":hari", $filterHari);
            }

            if (!empty($filterTahun)) {
                $stmt->bindParam(":tahun_akademik", $filterTahun);
            }
            
            if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchJadwal: " . $e->getMessage());
            return false;
        }
    }

    public function countSearchJadwal($keyword, $filterHari = '', $filterTahun = '')
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE 1=1 ";

            if (!empty($keyword)) {
                $query .= " AND (nama_mk ILIKE :keyword 
                            OR nama_dosen ILIKE :keyword 
                            OR nama_kelas ILIKE :keyword)";
            }

            if (!empty($filterHari)) {
                $query .= " AND hari = :hari ";
            }

            if (!empty($filterTahun)) {
                $query .= " AND tahun_akademik = :tahun_akademik ";
            }

            $stmt = $this->conn->prepare($query);

            if (!empty($keyword)) {
                $kw = "%{$keyword}%";
                $stmt->bindParam(":keyword", $kw);
            }

            if (!empty($filterHari)) {
                $stmt->bindParam(":hari", $filterHari);
            }

            if (!empty($filterTahun)) {
                $stmt->bindParam(":tahun_akademik", $filterTahun);
            }

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error countSearchJadwal: " . $e->getMessage());
            return 0;
        }
    }

    public function refreshView()
    {
        try {
            $query = "REFRESH MATERIALIZED VIEW mv_jadwal_kelas";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error refreshView: " . $e->getMessage());
            return false;
        }
    }

    public function deleteJadwal($id)
    {
        try {
            $this->conn->beginTransaction();
            
            $query = "DELETE FROM jadwal_kuliah WHERE id_jadwal = :id";
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
            error_log("Error deleteJadwal: " . $e->getMessage());
            return false;
        }
    }

    public function getDosenList() {
        try {
            $query = "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getDosenList: " . $e->getMessage());
            return array();
        }
    }

    public function getMatkulList() {
        try {
            $query = "SELECT id_mk, kode_mk, nama_mk, sks FROM matakuliah ORDER BY kode_mk ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getMatkulList: " . $e->getMessage());
            return array();
        }
    }

    public function getKelasList() {
        try {
            $query = "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getKelasList: " . $e->getMessage());
            return array();
        }
    }

    public function createJadwal($data) {
        try {
            $this->conn->beginTransaction();
            
            $query = "INSERT INTO jadwal_kuliah (id_mk, id_dosen, id_kelas, hari, jam_mulai, jam_selesai, ruangan, tahun_akademik) 
                      VALUES (:id_mk, :id_dosen, :id_kelas, :hari, :jam_mulai, :jam_selesai, :ruangan, :tahun_akademik)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id_mk", $data['id_mk']);
            $stmt->bindParam(":id_dosen", $data['id_dosen']);
            $stmt->bindParam(":id_kelas", $data['id_kelas']);
            $stmt->bindParam(":hari", $data['hari']);
            $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
            $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
            $stmt->bindParam(":ruangan", $data['ruangan']);
            $stmt->bindParam(":tahun_akademik", $data['tahun_akademik']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createJadwal: " . $e->getMessage());
            return false;
        }
    }

    public function getJadwalById($id) {
        try {
            $query = "SELECT * FROM jadwal_kuliah WHERE id_jadwal = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getJadwalById: " . $e->getMessage());
            return false;
        }
    }

    public function updateJadwal($id, $data) {
        try {
            $this->conn->beginTransaction();
            
            $query = "UPDATE jadwal_kuliah SET 
                        id_mk = :id_mk, 
                        id_dosen = :id_dosen, 
                        id_kelas = :id_kelas, 
                        hari = :hari, 
                        jam_mulai = :jam_mulai, 
                        jam_selesai = :jam_selesai, 
                        ruangan = :ruangan, 
                        tahun_akademik = :tahun_akademik
                      WHERE id_jadwal = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":id_mk", $data['id_mk']);
            $stmt->bindParam(":id_dosen", $data['id_dosen']);
            $stmt->bindParam(":id_kelas", $data['id_kelas']);
            $stmt->bindParam(":hari", $data['hari']);
            $stmt->bindParam(":jam_mulai", $data['jam_mulai']);
            $stmt->bindParam(":jam_selesai", $data['jam_selesai']);
            $stmt->bindParam(":ruangan", $data['ruangan']);
            $stmt->bindParam(":tahun_akademik", $data['tahun_akademik']);
            
            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateJadwal: " . $e->getMessage());
            return false;
        }
    }
}
?>