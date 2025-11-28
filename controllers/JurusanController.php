<?php
class JurusanController
{
    private $model;

    public function __construct(JurusanModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        try {
            $jurusan = $this->model->getAllJurusan();
            if (!$jurusan) {
                throw new Exception("Gagal mengambil data jurusan");
            }
            include 'views/jurusan_list.php';
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
                    'nama_jurusan' => $_POST['nama_jurusan'] ?? null,
                    'akreditasi' => $_POST['akreditasi'] ?? null,
                ];

                // Validasi data
                if (empty($data['nama_jurusan'])) {
                    throw new Exception("Nama Jurusan tidak boleh kosong");
                }
                if (empty($data['akreditasi'])) {
                    throw new Exception("Akreditasi tidak boleh kosong");
                }
                if (!in_array($data['akreditasi'], ['A', 'B', 'C'])) {
                    throw new Exception("Akreditasi harus A, B, atau C");
                }

                if (!$this->model->createJurusan($data)) {
                    throw new Exception("Gagal menyimpan data jurusan ke database");
                }
                
                header("Location: index.php?action=jurusan_list&message=created");
                exit();
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }
        
        include 'views/jurusan_form.php';
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: index.php?action=jurusan_list");
            exit();
        }

        if ($_POST) {
            try {
                $data = [
                    'nama_jurusan' => $_POST['nama_jurusan'] ?? null,
                    'akreditasi' => $_POST['akreditasi'] ?? null,
                ];

                // Validasi data
                if (empty($data['nama_jurusan'])) {
                    throw new Exception("Nama Jurusan tidak boleh kosong");
                }
                if (empty($data['akreditasi'])) {
                    throw new Exception("Akreditasi tidak boleh kosong");
                }
                if (!in_array($data['akreditasi'], ['A', 'B', 'C'])) {
                    throw new Exception("Akreditasi harus A, B, atau C");
                }

                if (!$this->model->updateJurusan($id, $data)) {
                    throw new Exception("Gagal mengupdate data jurusan");
                }
                
                header("Location: index.php?action=jurusan_list&message=updated");
                exit();
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $jurusan = $this->model->getJurusanById($id);
            if (!$jurusan) {
                throw new Exception("Data jurusan tidak ditemukan");
            }
            include 'views/jurusan_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=jurusan_list&message=search_error");
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

            $result = $this->model->deleteJurusan($id);
            
            if ($result === true) {
                header("Location: index.php?action=jurusan_list&message=deleted");
            } elseif ($result === 'fk_error') {
                header("Location: index.php?action=jurusan_list&message=fk_error");
            } else {
                throw new Exception("Gagal menghapus data jurusan");
            }
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=jurusan_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        $error = null;
        
        try {
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $jurusan = $this->model->searchJurusan($_GET['keyword']);
                if (!$jurusan) {
                    throw new Exception("Pencarian gagal");
                }
            } else {
                $jurusan = $this->model->getAllJurusan();
                if (!$jurusan) {
                    throw new Exception("Gagal mengambil data jurusan");
                }
            }
            include 'views/jurusan_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }
}
?>