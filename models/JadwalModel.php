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
        $query = "SELECT DISTINCT hari FROM " . $this->table_name . " ORDER BY hari DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getTahunOptions()
    {
        $query = "SELECT DISTINCT tahun_akademik FROM " . $this->table_name . " ORDER BY tahun_akademik ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllJadwal()
    {
        return $this->searchJadwal('', '', '');
    }

    public function searchJadwal($keyword, $filterHari = '', $filterTahun = '')
    {
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
        return $stmt;
    }

    public function refreshView()
    {
        try {
            $query = "REFRESH MATERIALIZED VIEW mv_jadwal_kelas";
            $stmt = $this->conn->prepare($query);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteJadwal($id)
    {
        $query = "DELETE FROM jadwal_kuliah WHERE id_jadwal = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getDosenList() {
        $query = "SELECT id_dosen, nama_dosen FROM dosen ORDER BY nama_dosen ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMatkulList() {
        $query = "SELECT id_mk, kode_mk, nama_mk, sks FROM matakuliah ORDER BY kode_mk ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKelasList() {
        $query = "SELECT id_kelas, nama_kelas FROM kelas ORDER BY nama_kelas ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createJadwal($data) {
        $query = "INSERT INTO jadwal_kuliah (id_mk, id_dosen, id_kelas, hari, jam_mulai, jam_selesai, ruangan, tahun_akademik) 
                  VALUES (:id_mk, :id_dosen, :id_kelas, :hari, :jam_mulai, :jam_selesai, :ruangan, :tahun_akademik)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            ':id_mk' => $data['id_mk'],
            ':id_dosen' => $data['id_dosen'],
            ':id_kelas' => $data['id_kelas'],
            ':hari' => $data['hari'],
            ':jam_mulai' => $data['jam_mulai'],
            ':jam_selesai' => $data['jam_selesai'],
            ':ruangan' => $data['ruangan'],
            ':tahun_akademik' => $data['tahun_akademik']
        ]);
    }

    public function getJadwalById($id) {
        $query = "SELECT * FROM jadwal_kuliah WHERE id_jadwal = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateJadwal($id, $data) {
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
        
        $data['id'] = $id;
        return $stmt->execute($data);
    }
}
