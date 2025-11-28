<?php
class MahasiswaModel
{
    private $conn;
    private $table_name = "mahasiswa";

    // Constructor
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // METHOD 1: Read semua mahasiswa dengan join ke tabel jurusan dan kelas
    public function getAllMahasiswa()
    {
        $query = "SELECT 
                    m.id_mahasiswa,
                    m.nim,
                    m.nama_mahasiswa,
                    m.email,
                    m.jenis_kelamin,
                    m.no_hp,
                    m.tahun_masuk,
                    m.semester,
                    m.foto,
                    j.nama_jurusan,
                    k.nama_kelas,
                    m.id_jurusan,
                    m.id_kelas
                  FROM " . $this->table_name . " m
                  LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                  LEFT JOIN kelas k ON m.id_kelas = k.id_kelas
                  ORDER BY m.id_mahasiswa DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 2: Create mahasiswa baru
    public function createMahasiswa($data)
    {
        try {
            $this->conn->beginTransaction();

            $query = "INSERT INTO " . $this->table_name . " 
                (nim, nama_mahasiswa, id_jurusan, tahun_masuk, email, jenis_kelamin, no_hp, semester, id_kelas, foto)
                VALUES (:nim, :nama_mahasiswa, :id_jurusan, :tahun_masuk, :email, :jenis_kelamin, :no_hp, :semester, :id_kelas, :foto)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":nim", $data['nim']);
            $stmt->bindParam(":nama_mahasiswa", $data['nama_mahasiswa']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            $stmt->bindParam(":tahun_masuk", $data['tahun_masuk']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
            $stmt->bindParam(":no_hp", $data['no_hp']);
            $stmt->bindParam(":semester", $data['semester']);
            $stmt->bindParam(":id_kelas", $data['id_kelas']);
            $stmt->bindParam(":foto", $data['foto']);

            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // METHOD 3: Update mahasiswa
    public function updateMahasiswa($id, $data)
    {
        try {
            $this->conn->beginTransaction();

            // Cek apakah ada update foto atau tidak
            if (isset($data['foto']) && !empty($data['foto'])) {
                $query = "UPDATE " . $this->table_name . "
                    SET nim = :nim, 
                        nama_mahasiswa = :nama_mahasiswa,
                        id_jurusan = :id_jurusan,
                        tahun_masuk = :tahun_masuk,
                        email = :email, 
                        jenis_kelamin = :jenis_kelamin,
                        no_hp = :no_hp, 
                        semester = :semester,
                        id_kelas = :id_kelas,
                        foto = :foto
                    WHERE id_mahasiswa = :id";
            } else {
                $query = "UPDATE " . $this->table_name . "
                    SET nim = :nim, 
                        nama_mahasiswa = :nama_mahasiswa,
                        id_jurusan = :id_jurusan,
                        tahun_masuk = :tahun_masuk,
                        email = :email, 
                        jenis_kelamin = :jenis_kelamin,
                        no_hp = :no_hp, 
                        semester = :semester,
                        id_kelas = :id_kelas
                    WHERE id_mahasiswa = :id";
            }

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":nim", $data['nim']);
            $stmt->bindParam(":nama_mahasiswa", $data['nama_mahasiswa']);
            $stmt->bindParam(":id_jurusan", $data['id_jurusan']);
            $stmt->bindParam(":tahun_masuk", $data['tahun_masuk']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
            $stmt->bindParam(":no_hp", $data['no_hp']);
            $stmt->bindParam(":semester", $data['semester']);
            $stmt->bindParam(":id_kelas", $data['id_kelas']);

            if (isset($data['foto']) && !empty($data['foto'])) {
                $stmt->bindParam(":foto", $data['foto']);
            }

            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // METHOD 4: Delete mahasiswa
    public function deleteMahasiswa($id) {
        try {
            $this->conn->beginTransaction();

            $mahasiswa = $this->getMahasiswaById($id);
            $foto_path = $mahasiswa['foto'] ?? null;
        
            $query = "DELETE FROM " . $this->table_name . " WHERE id_mahasiswa = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $this->conn->commit();

            if ($foto_path && file_exists($foto_path)) {
                unlink($foto_path);
            }
            
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

    // METHOD 5: Get single mahasiswa by ID
    public function getMahasiswaById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_mahasiswa = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 6: Get all jurusan untuk dropdown
    public function getAllJurusan()
    {
        $query = "SELECT id_jurusan, nama_jurusan FROM jurusan ORDER BY nama_jurusan";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 7: Get all kelas untuk dropdown
    public function getAllKelas()
    {
        $query = "SELECT k.id_kelas, k.nama_kelas, j.nama_jurusan 
                  FROM kelas k
                  LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                  ORDER BY k.nama_kelas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 8: Get dashboard statistics
    public function getDashboardStats()
    {
        $query = "SELECT 
                    *
                  FROM vw_dashboard";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // METHOD 9: Get mahasiswa per jurusan
    public function getMahasiswaPerJurusan()
    {
        $query = "SELECT 
                    j.nama_jurusan,
                    COUNT(m.id_mahasiswa) as total_mahasiswa
                  FROM jurusan j
                  LEFT JOIN mahasiswa m ON j.id_jurusan = m.id_jurusan
                  GROUP BY j.id_jurusan, j.nama_jurusan
                  ORDER BY total_mahasiswa DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 10: Get mahasiswa per angkatan
    public function getMahasiswaPerAngkatan()
    {
        $query = "SELECT 
                    tahun_masuk,
                    COUNT(*) as total_mahasiswa
                  FROM " . $this->table_name . "
                  GROUP BY tahun_masuk
                  ORDER BY tahun_masuk DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 11: Get mahasiswa per semester
    public function getMahasiswaPerSemester()
    {
        $query = "SELECT 
                    semester,
                    COUNT(*) as total_mahasiswa
                  FROM " . $this->table_name . "
                  GROUP BY semester
                  ORDER BY semester";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 12: Search mahasiswa
    public function searchMahasiswa($keyword)
    {
        $query = "SELECT 
                    m.id_mahasiswa,
                    m.nim,
                    m.nama_mahasiswa,
                    m.email,
                    m.jenis_kelamin,
                    m.no_hp,
                    m.tahun_masuk,
                    m.semester,
                    m.foto,
                    j.nama_jurusan,
                    k.nama_kelas
                  FROM " . $this->table_name . " m
                  LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                  LEFT JOIN kelas k ON m.id_kelas = k.id_kelas
                  WHERE m.nim LIKE :keyword 
                     OR m.nama_mahasiswa LIKE :keyword
                     OR m.email LIKE :keyword
                  ORDER BY m.id_mahasiswa DESC";
        $stmt = $this->conn->prepare($query);
        $keyword = "%{$keyword}%";
        $stmt->bindParam(":keyword", $keyword);
        $stmt->execute();
        return $stmt;
    }

    // METHOD 13: Detail mahasiswa by NIM
    public function getMahasiswaByNIM($nim)
    {
        $query = "SELECT 
                    *
                  FROM vw_detail_mahasiswa m
                  WHERE m.nim = :nim";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nim", $nim);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
