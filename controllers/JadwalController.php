<?php
class JadwalController
{
    private $model;

    public function __construct(JadwalModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 10;
            $offset = ($page - 1) * $per_page;
            
            $listHari = $this->model->getHariOptions();
            $listTahun = $this->model->getTahunOptions();

            $total_records = $this->model->countSearchJadwal('', '', '');
            $total_pages = ceil($total_records / $per_page);
            
            $jadwal = $this->model->searchJadwal('', '', '', $per_page, $offset);
            if (!$jadwal) {
                throw new Exception("Gagal mengambil data jadwal");
            }
            include 'views/jadwal_list.php';
        } catch (Exception $e) {
            error_log("Error in list: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function search(): void
    {
        $error = null;
        
        try {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $per_page = 10;
            $offset = ($page - 1) * $per_page;
            
            $listHari = $this->model->getHariOptions();
            $listTahun = $this->model->getTahunOptions();

            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $filterHari = isset($_GET['filter_hari']) ? $_GET['filter_hari'] : '';
            $filterTahun = isset($_GET['filter_tahun']) ? $_GET['filter_tahun'] : '';

            $total_records = $this->model->countSearchJadwal($keyword, $filterHari, $filterTahun);
            $total_pages = ceil($total_records / $per_page);

            $jadwal = $this->model->searchJadwal($keyword, $filterHari, $filterTahun, $per_page, $offset);
            if (!$jadwal) {
                throw new Exception("Pencarian gagal");
            }

            include 'views/jadwal_list.php';
        } catch (Exception $e) {
            error_log("Error in search: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function refresh(): void
    {
        try {
            if (!$this->model->refreshView()) {
                throw new Exception("Gagal refresh materialized view");
            }
            header("Location: index.php?action=jadwal_list&message=refreshed");
        } catch (Exception $e) {
            error_log("Error in refresh: " . $e->getMessage());
            header("Location: index.php?action=jadwal_list&message=refresh_error");
        }
        exit;
    }

    public function create(): void
    {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = [
                    'id_mk' => $_POST['id_mk'] ?? null,
                    'id_dosen' => $_POST['id_dosen'] ?? null,
                    'id_kelas' => $_POST['id_kelas'] ?? null,
                    'hari' => $_POST['hari'] ?? null,
                    'jam_mulai' => $_POST['jam_mulai'] ?? null,
                    'jam_selesai' => $_POST['jam_selesai'] ?? null,
                    'ruangan' => $_POST['ruangan'] ?? null,
                    'tahun_akademik' => $_POST['tahun_akademik'] ?? null
                ];

                // Validasi data
                if (empty($data['id_mk']) || empty($data['id_dosen']) || empty($data['id_kelas'])) {
                    throw new Exception("Matakuliah, Dosen, dan Kelas harus dipilih");
                }
                if (empty($data['hari'])) {
                    throw new Exception("Hari harus dipilih");
                }
                if (empty($data['jam_mulai']) || empty($data['jam_selesai'])) {
                    throw new Exception("Jam mulai dan jam selesai harus diisi");
                }
                if (strtotime($data['jam_mulai']) >= strtotime($data['jam_selesai'])) {
                    throw new Exception("Jam mulai harus lebih kecil dari jam selesai");
                }
                if (empty($data['ruangan'])) {
                    throw new Exception("Ruangan harus diisi");
                }
                if (empty($data['tahun_akademik'])) {
                    throw new Exception("Tahun akademik harus diisi");
                }

                if (!$this->model->createJadwal($data)) {
                    throw new Exception("Gagal menyimpan data jadwal ke database");
                }

                $this->model->refreshView();
                header("Location: index.php?action=jadwal_list&message=created");
                exit;
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $dosenList = $this->model->getDosenList();
            $mkList = $this->model->getMatkulList();
            $kelasList = $this->model->getKelasList();
            if (!$dosenList || !$mkList || !$kelasList) {
                throw new Exception("Gagal memuat data referensi");
            }
            include 'views/jadwal_form.php';
        } catch (Exception $e) {
            error_log("Error loading dropdown: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header("Location: index.php?action=jadwal_list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = [
                    'id_mk' => $_POST['id_mk'] ?? null,
                    'id_dosen' => $_POST['id_dosen'] ?? null,
                    'id_kelas' => $_POST['id_kelas'] ?? null,
                    'hari' => $_POST['hari'] ?? null,
                    'jam_mulai' => $_POST['jam_mulai'] ?? null,
                    'jam_selesai' => $_POST['jam_selesai'] ?? null,
                    'ruangan' => $_POST['ruangan'] ?? null,
                    'tahun_akademik' => $_POST['tahun_akademik'] ?? null
                ];

                // Validasi data
                if (empty($data['id_mk']) || empty($data['id_dosen']) || empty($data['id_kelas'])) {
                    throw new Exception("Matakuliah, Dosen, dan Kelas harus dipilih");
                }
                if (empty($data['hari'])) {
                    throw new Exception("Hari harus dipilih");
                }
                if (empty($data['jam_mulai']) || empty($data['jam_selesai'])) {
                    throw new Exception("Jam mulai dan jam selesai harus diisi");
                }
                if (strtotime($data['jam_mulai']) >= strtotime($data['jam_selesai'])) {
                    throw new Exception("Jam mulai harus lebih kecil dari jam selesai");
                }
                if (empty($data['ruangan'])) {
                    throw new Exception("Ruangan harus diisi");
                }
                if (empty($data['tahun_akademik'])) {
                    throw new Exception("Tahun akademik harus diisi");
                }

                if (!$this->model->updateJadwal($id, $data)) {
                    throw new Exception("Gagal mengupdate data jadwal");
                }

                $this->model->refreshView();
                header("Location: index.php?action=jadwal_list&message=updated");
                exit;
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $jadwal = $this->model->getJadwalById($id);
            if (!$jadwal) {
                throw new Exception("Data jadwal tidak ditemukan");
            }
            
            $dosenList = $this->model->getDosenList();
            $mkList = $this->model->getMatkulList();
            $kelasList = $this->model->getKelasList();

            include 'views/jadwal_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=jadwal_list&message=search_error");
            exit;
        }
    }

    public function delete(): void
    {
        try {
            $id = $_GET['id'] ?? null;

            if (!$id) {
                throw new Exception("ID tidak valid");
            }

            if (!$this->model->deleteJadwal($id)) {
                throw new Exception("Gagal menghapus data jadwal");
            }

            $this->model->refreshView();
            header("Location: index.php?action=jadwal_list&message=deleted");
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=jadwal_list&message=delete_error");
        }
        exit;
    }
}
?>