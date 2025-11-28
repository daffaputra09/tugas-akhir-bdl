<?php
class MahasiswaController
{
    private $model;
    private $upload_dir = 'uploads/foto_mahasiswa/';
    private $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    private $max_size = 2097152; 

    public function __construct(MahasiswaModel $model)
    {
        $this->model = $model;
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0755, true);
        }
    }

    public function list(): void
    {
        try {
            $mahasiswa = $this->model->getAllMahasiswa();
            if (!$mahasiswa) {
                throw new Exception("Gagal mengambil data mahasiswa");
            }
            include 'views/mahasiswa_list.php';
        } catch (Exception $e) {
            error_log("Error in list: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function create(): void
    {
        $error = null;
        
        if ($_POST) {
            try {
                $data = [
                    'nim' => $_POST['nim'],
                    'nama_mahasiswa' => $_POST['nama_mahasiswa'],
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                    'tahun_masuk' => $_POST['tahun_masuk'],
                    'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                    'jenis_kelamin' => $_POST['jenis_kelamin'],
                    'no_hp' => !empty($_POST['no_hp']) ? $_POST['no_hp'] : null,
                    'semester' => $_POST['semester'],
                    'id_kelas' => !empty($_POST['id_kelas']) ? $_POST['id_kelas'] : null,
                    'foto' => null
                ];

                // Validasi data required
                if (empty($data['nim']) || empty($data['nama_mahasiswa']) || empty($data['tahun_masuk']) || empty($data['jenis_kelamin']) || empty($data['semester'])) {
                    throw new Exception("NIM, Nama Mahasiswa, Tahun Masuk, Jenis Kelamin, dan Semester harus diisi");
                }

                // Handle file upload
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    $upload_result = $this->handleFileUpload($_FILES['foto'], $_POST['nim']);
                    if (!$upload_result['success']) {
                        throw new Exception($upload_result['error']);
                    }
                    $data['foto'] = $upload_result['path'];
                }

                if (!$this->model->createMahasiswa($data)) {
                    throw new Exception("Gagal menyimpan data mahasiswa ke database");
                }
                
                header("Location: index.php?action=list&message=created");
                exit();
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }
        
        try {
            $jurusan_list = $this->model->getAllJurusan();
            $kelas_list = $this->model->getAllKelas();
            include 'views/mahasiswa_form.php';
        } catch (Exception $e) {
            error_log("Error loading dropdowns: " . $e->getMessage());
            $error = "Gagal memuat data referensi";
            include 'views/error.php';
        }
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?action=list");
            exit();
        }

        if ($_POST) {
            try {
                $data = [
                    'nim' => $_POST['nim'],
                    'nama_mahasiswa' => $_POST['nama_mahasiswa'],
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                    'tahun_masuk' => $_POST['tahun_masuk'],
                    'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                    'jenis_kelamin' => $_POST['jenis_kelamin'],
                    'no_hp' => !empty($_POST['no_hp']) ? $_POST['no_hp'] : null,
                    'semester' => $_POST['semester'],
                    'id_kelas' => !empty($_POST['id_kelas']) ? $_POST['id_kelas'] : null
                ];

                // Validasi data required
                if (empty($data['nim']) || empty($data['nama_mahasiswa']) || empty($data['tahun_masuk']) || empty($data['jenis_kelamin']) || empty($data['semester'])) {
                    throw new Exception("NIM, Nama Mahasiswa, Tahun Masuk, Jenis Kelamin, dan Semester harus diisi");
                }

                // Handle file upload jika ada file baru
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    $upload_result = $this->handleFileUpload($_FILES['foto'], $_POST['nim'], $id);
                    if (!$upload_result['success']) {
                        throw new Exception($upload_result['error']);
                    }
                    $data['foto'] = $upload_result['path'];
                }

                if (!$this->model->updateMahasiswa($id, $data)) {
                    throw new Exception("Gagal mengupdate data mahasiswa");
                }
                
                header("Location: index.php?action=list&message=updated");
                exit();
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $mahasiswa = $this->model->getMahasiswaById($id);
            if (!$mahasiswa) {
                throw new Exception("Data mahasiswa tidak ditemukan");
            }
            $jurusan_list = $this->model->getAllJurusan();
            $kelas_list = $this->model->getAllKelas();
            include 'views/mahasiswa_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=list&message=search_error");
            exit();
        }
    }

    public function delete(): void
    {
        try {
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception("ID tidak valid");
            }

            if (!$this->model->deleteMahasiswa($id)) {
                throw new Exception("Gagal menghapus data mahasiswa");
            }
            
            header("Location: index.php?action=list&message=deleted");
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        $error = null;
        
        try {
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $mahasiswa = $this->model->searchMahasiswa($_GET['keyword']);
                if (!$mahasiswa) {
                    throw new Exception("Pencarian gagal");
                }
            } else {
                $mahasiswa = $this->model->getAllMahasiswa();
                if (!$mahasiswa) {
                    throw new Exception("Gagal mengambil data mahasiswa");
                }
            }
            include 'views/mahasiswa_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function detail(): void
    {
        try {
            $nim = $_GET['nim'] ?? null;
            
            if (!$nim) {
                throw new Exception("NIM tidak valid");
            }

            $mahasiswa = $this->model->getMahasiswaByNIM($nim);
            if (!$mahasiswa) {
                throw new Exception("Data mahasiswa tidak ditemukan");
            }
            
            include 'views/mahasiswa_detail.php';
        } catch (Exception $e) {
            error_log("Error in detail: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function getDashboardStats()
    {
        try {
            $stats = $this->model->getDashboardStats();
            if (!$stats) {
                throw new Exception("Gagal mengambil statistik dashboard");
            }
            return $stats;
        } catch (Exception $e) {
            error_log("Error in getDashboardStats: " . $e->getMessage());
            return null;
        }
    }

    private function handleFileUpload($file, $nim, $id = null)
    {
        try {
            // Validasi tipe file
            if (!in_array($file['type'], $this->allowed_types)) {
                throw new Exception("Format file tidak didukung. Hanya JPG, PNG, dan GIF yang diperbolehkan");
            }

            // Validasi ukuran file
            if ($file['size'] > $this->max_size) {
                throw new Exception("Ukuran file terlalu besar. Maksimal 2MB");
            }

            // Generate nama file unik
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $file_name = 'mahasiswa_' . $nim . '_' . time() . '.' . $file_extension;
            $target_path = $this->upload_dir . $file_name;

            // Hapus foto lama jika edit
            if ($id) {
                $mahasiswa = $this->model->getMahasiswaById($id);
                if ($mahasiswa && !empty($mahasiswa['foto']) && file_exists($mahasiswa['foto'])) {
                    if (!unlink($mahasiswa['foto'])) {
                        throw new Exception("Gagal menghapus file foto lama");
                    }
                }
            }

            // Upload file
            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                throw new Exception("Gagal mengupload file");
            }

            return ['success' => true, 'path' => $target_path];
        } catch (Exception $e) {
            error_log("Error in handleFileUpload: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
?>