<?php
class DosenController
{
    private $model;

    public function __construct(DosenModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 10;
            $offset = ($page - 1) * $per_page;
            
            $total_records = $this->model->countTotalDosen();
            $total_pages = ceil($total_records / $per_page);
            
            $dosen = $this->model->getAllDosen($per_page, $offset);
            include 'views/dosen_list.php';
        } catch (Exception $e) {
            error_log("Error in list: " . $e->getMessage());
            $error = "Gagal mengambil data dosen";
            include 'views/error.php';
        }
    }

    public function create(): void
    {
        $error = null;
        
        if ($_POST) {
            try {
                $data = [
                    'nip' => $_POST['nip'],
                    'nama_dosen' => $_POST['nama_dosen'],
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                    'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                    'no_hp' => $_POST['no_hp'],
                    'status_aktif' => !empty($_POST['status_aktif']) ? $_POST['status_aktif'] : 'Aktif',
                ];

                // Validasi data
                if (empty($data['nip']) || empty($data['nama_dosen']) || empty($data['no_hp'])) {
                    throw new Exception("NIP, Nama Dosen, dan No HP tidak boleh kosong");
                }

                if ($this->model->createDosen($data)) {
                    header("Location: index.php?action=dosen_list&message=created");
                    exit();
                } else {
                    throw new Exception("Gagal menyimpan data dosen ke database");
                }
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }
        
        try {
            $jurusan_list = $this->model->getAllJurusan();
            include 'views/dosen_form.php';
        } catch (Exception $e) {
            error_log("Error loading jurusan: " . $e->getMessage());
            $error = "Gagal memuat data jurusan";
            include 'views/error.php';
        }
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?action=dosen_list");
            exit();
        }

        if ($_POST) {
            try {
                $data = [
                    'nip' => $_POST['nip'],
                    'nama_dosen' => $_POST['nama_dosen'],
                    'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                    'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                    'no_hp' => $_POST['no_hp'],
                    'status_aktif' => !empty($_POST['status_aktif']) ? $_POST['status_aktif'] : 'Aktif',
                ];

                // Validasi data
                if (empty($data['nip']) || empty($data['nama_dosen']) || empty($data['no_hp'])) {
                    throw new Exception("NIP, Nama Dosen, dan No HP tidak boleh kosong");
                }

                if ($this->model->updateDosen($id, $data)) {
                    header("Location: index.php?action=dosen_list&message=updated");
                    exit();
                } else {
                    throw new Exception("Gagal mengupdate data dosen");
                }
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $dosen = $this->model->getDosenById($id);
            if (!$dosen) {
                throw new Exception("Data dosen tidak ditemukan");
            }
            $jurusan_list = $this->model->getAllJurusan();
            include 'views/dosen_form.php';
        } catch (Exception $e) {
            error_log("Error loading data: " . $e->getMessage());
            header("Location: index.php?action=dosen_list&message=search_error");
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

            if ($this->model->deleteDosen($id)) {
                header("Location: index.php?action=dosen_list&message=deleted");
            } else {
                throw new Exception("Gagal menghapus data dosen");
            }
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=dosen_list&message=delete_error");
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
                $total_records = $this->model->countSearchDosen($keyword);
                $total_pages = ceil($total_records / $per_page);
                
                $dosen = $this->model->searchDosen($keyword, $per_page, $offset);
                if (!$dosen) {
                    throw new Exception("Gagal melakukan pencarian");
                }
            } else {
                $total_records = $this->model->countTotalDosen();
                $total_pages = ceil($total_records / $per_page);
                
                $dosen = $this->model->getAllDosen($per_page, $offset);
                if (!$dosen) {
                    throw new Exception("Gagal mengambil data dosen");
                }
            }
            include 'views/dosen_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }
}
?>