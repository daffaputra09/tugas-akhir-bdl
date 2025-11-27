<?php
class NilaiModel
{
    private $conn;
    private $table_name = "nilai";
    private $mv_name = "mv_nilai_detail";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTipeNilaiOptions()
    {
        $query = "SELECT DISTINCT tipe_nilai FROM " . $this->mv_name . " WHERE tipe_nilai IS NOT NULL ORDER BY tipe_nilai ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getJurusanOptions()
    {
        $query = "SELECT DISTINCT id_jurusan_mk, nama_jurusan_mk FROM " . $this->mv_name . " 
                  WHERE id_jurusan_mk IS NOT NULL 
                  ORDER BY nama_jurusan_mk ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSemesterOptions()
    {
        $query = "SELECT DISTINCT semester_mk FROM " . $this->mv_name . " 
                  WHERE semester_mk IS NOT NULL 
                  ORDER BY semester_mk ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getAllNilai()
    {
        return $this->searchNilai('', '', '', '');
    }

    public function searchNilai($keyword = '', $filterTipe = '', $filterJurusan = '', $filterSemester = '')
    {
        $query = "SELECT * FROM " . $this->mv_name . " WHERE 1=1 ";

        if (!empty($keyword)) {
            $query .= " AND (nim ILIKE :keyword 
                        OR nama_mahasiswa ILIKE :keyword
                        OR kode_mk ILIKE :keyword
                        OR nama_mk ILIKE :keyword)";
        }

        if (!empty($filterTipe)) {
            $query .= " AND tipe_nilai = :tipe_nilai ";
        }

        if (!empty($filterJurusan)) {
            $query .= " AND id_jurusan_mk = :id_jurusan ";
        }

        if (!empty($filterSemester)) {
            $query .= " AND semester_mk = :semester ";
        }

        $query .= " ORDER BY tanggal_input DESC, nama_mahasiswa ASC";

        $stmt = $this->conn->prepare($query);

        if (!empty($keyword)) {
            $kw = "%{$keyword}%";
            $stmt->bindParam(":keyword", $kw);
        }

        if (!empty($filterTipe)) {
            $stmt->bindParam(":tipe_nilai", $filterTipe);
        }

        if (!empty($filterJurusan)) {
            $stmt->bindParam(":id_jurusan", $filterJurusan);
        }

        if (!empty($filterSemester)) {
            $stmt->bindParam(":semester", $filterSemester);
        }

        $stmt->execute();
        return $stmt;
    }

    public function refreshView()
    {
        try {
            $query = "REFRESH MATERIALIZED VIEW " . $this->mv_name;
            $stmt = $this->conn->prepare($query);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createNilai($data)
    {
        // Auto convert nilai_angka ke nilai_huruf
        $nilai_huruf = $this->convertNilaiHuruf($data['nilai_angka']);
        
        $query = "INSERT INTO " . $this->table_name . "
                  (id_mahasiswa, id_mk, nilai_angka, nilai_huruf, tipe_nilai, tanggal_input)
                  VALUES (:id_mahasiswa, :id_mk, :nilai_angka, :nilai_huruf, :tipe_nilai, :tanggal_input)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_mahasiswa", $data['id_mahasiswa']);
        $stmt->bindParam(":id_mk", $data['id_mk']);
        $stmt->bindParam(":nilai_angka", $data['nilai_angka']);
        $stmt->bindParam(":nilai_huruf", $nilai_huruf);
        $stmt->bindParam(":tipe_nilai", $data['tipe_nilai']);
        $stmt->bindParam(":tanggal_input", $data['tanggal_input']);
        
        if ($stmt->execute()) {
            // Refresh view setelah insert
            $this->refreshView();
            return true;
        }
        return false;
    }

    public function updateNilai($id, $data)
    {
        // Auto convert nilai_angka ke nilai_huruf
        $nilai_huruf = $this->convertNilaiHuruf($data['nilai_angka']);
        
        $query = "UPDATE " . $this->table_name . "
                  SET id_mahasiswa = :id_mahasiswa,
                      id_mk = :id_mk,
                      nilai_angka = :nilai_angka,
                      nilai_huruf = :nilai_huruf,
                      tipe_nilai = :tipe_nilai,
                      tanggal_input = :tanggal_input
                  WHERE id_nilai = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":id_mahasiswa", $data['id_mahasiswa']);
        $stmt->bindParam(":id_mk", $data['id_mk']);
        $stmt->bindParam(":nilai_angka", $data['nilai_angka']);
        $stmt->bindParam(":nilai_huruf", $nilai_huruf);
        $stmt->bindParam(":tipe_nilai", $data['tipe_nilai']);
        $stmt->bindParam(":tanggal_input", $data['tanggal_input']);
        
        if ($stmt->execute()) {
            // Refresh view setelah update
            $this->refreshView();
            return true;
        }
        return false;
    }

    public function deleteNilai($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_nilai = :id";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) {
                // Refresh view setelah delete
                $this->refreshView();
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getNilaiById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_nilai = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMahasiswaList()
    {
        $query = "SELECT id_mahasiswa, nim, nama_mahasiswa FROM mahasiswa ORDER BY nama_mahasiswa ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMatakuliahList()
    {
        $query = "SELECT id_mk, kode_mk, nama_mk FROM matakuliah ORDER BY kode_mk ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function convertNilaiHuruf($nilai_angka)
    {
        if ($nilai_angka >= 80) {
            return 'A';
        } elseif ($nilai_angka >= 70) {
            return 'B';
        } elseif ($nilai_angka >= 60) {
            return 'C';
        } elseif ($nilai_angka >= 50) {
            return 'D';
        } else {
            return 'E';
        }
    }
}
