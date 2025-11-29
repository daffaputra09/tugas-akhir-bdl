<?php
class MatakuliahController
{
    private $model;

    public function __construct(MatakuliahModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 10;
            $offset = ($page - 1) * $per_page;
            
            $total_records = $this->model->countTotalMatakuliah();
            $total_pages = ceil($total_records / $per_page);
            
            $matakuliah = $this->model->getAllMatakuliah($per_page, $offset);
            if (!$matakuliah) {
                throw new Exception("Gagal mengambil data matakuliah");
            }
            include 'views/matakuliah_list.php';
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
                    'kode_mk' => $_POST['kode_mk'] ?? null,
                    'nama_mk' => $_POST['nama_mk'] ?? null,
                    'sks' => $_POST['sks'] ?? null,
                    'semester' => $_POST['semester'] ?? null,
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                ];

                // Validasi data
                if (empty($data['kode_mk'])) {
                    throw new Exception("Kode Matakuliah tidak boleh kosong");
                }
                if (empty($data['nama_mk'])) {
                    throw new Exception("Nama Matakuliah tidak boleh kosong");
                }
                if (empty($data['sks']) || !is_numeric($data['sks']) || $data['sks'] <= 0 || $data['sks'] > 24) {
                    throw new Exception("SKS harus angka antara 1-24");
                }
                if (empty($data['semester']) || !is_numeric($data['semester']) || $data['semester'] <= 0 || $data['semester'] > 8) {
                    throw new Exception("Semester harus angka antara 1-8");
                }

                if (!$this->model->createMatakuliah($data)) {
                    throw new Exception("Gagal menyimpan data matakuliah ke database");
                }
                
                header("Location: index.php?action=matakuliah_list&message=created");
                exit();
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }
        
        try {
            $jurusan_list = $this->model->getAllJurusan();
            if (!$jurusan_list) {
                throw new Exception("Gagal memuat data jurusan");
            }
            include 'views/matakuliah_form.php';
        } catch (Exception $e) {
            error_log("Error loading jurusan: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?action=matakuliah_list");
            exit();
        }

        if ($_POST) {
            try {
                $data = [
                    'kode_mk' => $_POST['kode_mk'] ?? null,
                    'nama_mk' => $_POST['nama_mk'] ?? null,
                    'sks' => $_POST['sks'] ?? null,
                    'semester' => $_POST['semester'] ?? null,
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                ];

                // Validasi data
                if (empty($data['kode_mk'])) {
                    throw new Exception("Kode Matakuliah tidak boleh kosong");
                }
                if (empty($data['nama_mk'])) {
                    throw new Exception("Nama Matakuliah tidak boleh kosong");
                }
                if (empty($data['sks']) || !is_numeric($data['sks']) || $data['sks'] <= 0 || $data['sks'] > 24) {
                    throw new Exception("SKS harus angka antara 1-24");
                }
                if (empty($data['semester']) || !is_numeric($data['semester']) || $data['semester'] <= 0 || $data['semester'] > 8) {
                    throw new Exception("Semester harus angka antara 1-8");
                }

                if (!$this->model->updateMatakuliah($id, $data)) {
                    throw new Exception("Gagal mengupdate data matakuliah");
                }
                
                header("Location: index.php?action=matakuliah_list&message=updated");
                exit();
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $matakuliah = $this->model->getMatakuliahById($id);
            if (!$matakuliah) {
                throw new Exception("Data matakuliah tidak ditemukan");
            }
            $jurusan_list = $this->model->getAllJurusan();
            if (!$jurusan_list) {
                throw new Exception("Gagal memuat data jurusan");
            }
            include 'views/matakuliah_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=matakuliah_list&message=search_error");
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

            $result = $this->model->deleteMatakuliah($id);
            
            if ($result === true) {
                header("Location: index.php?action=matakuliah_list&message=deleted");
            } elseif ($result === 'fk_error') {
                header("Location: index.php?action=matakuliah_list&message=fk_error");
            } else {
                throw new Exception("Gagal menghapus data matakuliah");
            }
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=matakuliah_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        $error = null;
        
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 10;
            $offset = ($page - 1) * $per_page;
            
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $keyword = $_GET['keyword'];
                $total_records = $this->model->countSearchMatakuliah($keyword);
                $total_pages = ceil($total_records / $per_page);
                
                $matakuliah = $this->model->searchMatakuliah($keyword, $per_page, $offset);
                if (!$matakuliah) {
                    throw new Exception("Pencarian gagal");
                }
            } else {
                $total_records = $this->model->countTotalMatakuliah();
                $total_pages = ceil($total_records / $per_page);
                
                $matakuliah = $this->model->getAllMatakuliah($per_page, $offset);
                if (!$matakuliah) {
                    throw new Exception("Gagal mengambil data matakuliah");
                }
            }
            include 'views/matakuliah_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }
}
?>