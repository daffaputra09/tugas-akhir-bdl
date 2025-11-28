<?php
class KelasController
{
    private $kelasModel;
    private $jurusanModel;

    public function __construct(KelasModel $kelasModel, JurusanModel $jurusanModel)
    {
        $this->kelasModel = $kelasModel;
        $this->jurusanModel = $jurusanModel;
    }

    public function list(): void
    {
        try {
            $kelas = $this->kelasModel->getAllKelas();
            if (!$kelas) {
                throw new Exception("Gagal mengambil data kelas");
            }
            include 'views/kelas_list.php';
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
                    'nama_kelas' => $_POST['nama_kelas'] ?? null,
                    'id_jurusan' => $_POST['id_jurusan'] ?? null,
                ];

                // Validasi data
                if (empty($data['nama_kelas'])) {
                    throw new Exception("Nama Kelas tidak boleh kosong");
                }
                if (empty($data['id_jurusan'])) {
                    throw new Exception("Jurusan harus dipilih");
                }

                if (!$this->kelasModel->createKelas($data)) {
                    throw new Exception("Gagal menyimpan data kelas ke database");
                }
                
                header("Location: index.php?action=kelas_list&message=created");
                exit();
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $jurusan_list = $this->jurusanModel->getAllJurusan();
            if (!$jurusan_list) {
                throw new Exception("Gagal memuat data jurusan");
            }
            include 'views/kelas_form.php';
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
            header("Location: index.php?action=kelas_list");
            exit();
        }

        if ($_POST) {
            try {
                $data = [
                    'nama_kelas' => $_POST['nama_kelas'] ?? null,
                    'id_jurusan' => $_POST['id_jurusan'] ?? null,
                ];

                // Validasi data
                if (empty($data['nama_kelas'])) {
                    throw new Exception("Nama Kelas tidak boleh kosong");
                }
                if (empty($data['id_jurusan'])) {
                    throw new Exception("Jurusan harus dipilih");
                }

                if (!$this->kelasModel->updateKelas($id, $data)) {
                    throw new Exception("Gagal mengupdate data kelas");
                }
                
                header("Location: index.php?action=kelas_list&message=updated");
                exit();
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $kelas = $this->kelasModel->getKelasById($id);
            if (!$kelas) {
                throw new Exception("Data kelas tidak ditemukan");
            }
            $jurusan_list = $this->jurusanModel->getAllJurusan();
            if (!$jurusan_list) {
                throw new Exception("Gagal memuat data jurusan");
            }
            include 'views/kelas_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=kelas_list&message=search_error");
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

            $result = $this->kelasModel->deleteKelas($id);

            if ($result === true) {
                header("Location: index.php?action=kelas_list&message=deleted");
            } elseif ($result === 'fk_error') {
                header("Location: index.php?action=kelas_list&message=fk_error");
            } else {
                throw new Exception("Gagal menghapus data kelas");
            }
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=kelas_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        $error = null;
        
        try {
            if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                $kelas = $this->kelasModel->searchKelas($_GET['keyword']);
                if (!$kelas) {
                    throw new Exception("Pencarian gagal");
                }
            } else {
                $kelas = $this->kelasModel->getAllKelas();
                if (!$kelas) {
                    throw new Exception("Gagal mengambil data kelas");
                }
            }
            include 'views/kelas_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }
}
?>