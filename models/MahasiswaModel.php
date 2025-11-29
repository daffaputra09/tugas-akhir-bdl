<?php
class MahasiswaModel
{
    private $conn;
    private $table_name = "mahasiswa";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllMahasiswa($limit = null, $offset = null)
    {
        try {
            $query = "SELECT 
                    m.id_mahasiswa,
                    m.nim,
                    m.nama_mahasiswa,
                    m.jenis_kelamin,
                    m.foto,
                    m.email,
                    j.nama_jurusan,
                    k.nama_kelas,
                    public.hitung_ipk(m.id_mahasiswa) as ipk
                  FROM " . $this->table_name . " m
                  LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                  LEFT JOIN kelas k ON m.id_kelas = k.id_kelas
                  ORDER BY m.nama_mahasiswa ASC";

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
            error_log("Error getAllMahasiswa: " . $e->getMessage());
            return false;
        }
    }

    public function countTotalMahasiswa()
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error countTotalMahasiswa: " . $e->getMessage());
            return 0;
        }
    }

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

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error createMahasiswa: " . $e->getMessage());
            return false;
        }
    }

    public function updateMahasiswa($id, $data)
    {
        try {
            $this->conn->beginTransaction();

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

            if ($stmt->execute()) {
                $this->conn->commit();
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error updateMahasiswa: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMahasiswa($id)
    {
        try {
            $this->conn->beginTransaction();

            $mahasiswa = $this->getMahasiswaById($id);
            $foto_path = $mahasiswa['foto'] ?? null;

            $query = "DELETE FROM " . $this->table_name . " WHERE id_mahasiswa = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                $this->conn->commit();

                // Hapus file foto setelah commit berhasil
                if ($foto_path && file_exists($foto_path)) {
                    unlink($foto_path);
                }
                return true;
            }
            $this->conn->rollBack();
            return false;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error deleteMahasiswa: " . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_mahasiswa = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getMahasiswaById: " . $e->getMessage());
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

    public function getAllKelas()
    {
        try {
            $query = "SELECT k.id_kelas, k.nama_kelas, j.nama_jurusan 
                      FROM kelas k
                      LEFT JOIN jurusan j ON k.id_jurusan = j.id_jurusan
                      ORDER BY k.nama_kelas";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getAllKelas: " . $e->getMessage());
            return false;
        }
    }

    public function getDashboardStats()
    {
        try {
            $query = "SELECT * FROM vw_dashboard";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getDashboardStats: " . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaPerJurusan()
    {
        try {
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
        } catch (PDOException $e) {
            error_log("Error getMahasiswaPerJurusan: " . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaPerAngkatan()
    {
        try {
            $query = "SELECT 
                        tahun_masuk,
                        COUNT(*) as total_mahasiswa
                      FROM " . $this->table_name . "
                      GROUP BY tahun_masuk
                      ORDER BY tahun_masuk DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getMahasiswaPerAngkatan: " . $e->getMessage());
            return false;
        }
    }

    public function getMahasiswaPerSemester()
    {
        try {
            $query = "SELECT 
                        semester,
                        COUNT(*) as total_mahasiswa
                      FROM " . $this->table_name . "
                      GROUP BY semester
                      ORDER BY semester";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error getMahasiswaPerSemester: " . $e->getMessage());
            return false;
        }
    }

    public function searchMahasiswa($keyword, $limit = null, $offset = null)
    {
        try {
            $query = "SELECT 
                        m.id_mahasiswa,
                        m.nim,
                        m.nama_mahasiswa,
                        m.jenis_kelamin,
                        m.foto,
                        m.email,
                        j.nama_jurusan,
                        k.nama_kelas,
                        public.hitung_ipk(m.id_mahasiswa) as ipk
                      FROM " . $this->table_name . " m
                      LEFT JOIN jurusan j ON m.id_jurusan = j.id_jurusan
                      LEFT JOIN kelas k ON m.id_kelas = k.id_kelas
                      WHERE m.nim LIKE :keyword 
                         OR m.nama_mahasiswa LIKE :keyword
                         OR m.email LIKE :keyword
                      ORDER BY m.nama_mahasiswa ASC";

            if ($limit !== null && $offset !== null) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $this->conn->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(":keyword", $keyword);

            if ($limit !== null && $offset !== null) {
                $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            error_log("Error searchMahasiswa: " . $e->getMessage());
            return false;
        }
    }

    public function countSearchMahasiswa($keyword)
    {
        try {
            $query = "SELECT COUNT(*) as total 
                      FROM " . $this->table_name . " m
                      WHERE m.nim LIKE :keyword 
                         OR m.nama_mahasiswa LIKE :keyword
                         OR m.email LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $keyword = "%{$keyword}%";
            $stmt->bindParam(":keyword", $keyword);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error countSearchMahasiswa: " . $e->getMessage());
            return 0;
        }
    }

    public function getMahasiswaByNIM($nim)
    {
        try {
            $query = "SELECT * FROM vw_detail_mahasiswa m WHERE m.nim = :nim";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nim", $nim);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getMahasiswaByNIM: " . $e->getMessage());
            return false;
        }
    }
}
