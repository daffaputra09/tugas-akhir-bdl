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
        $listHari = $this->model->getHariOptions();
        $listTahun = $this->model->getTahunOptions();

        $jadwal = $this->model->getAllJadwal();
        include 'views/jadwal_list.php';
    }

    public function search(): void
    {
        $listHari = $this->model->getHariOptions();
        $listTahun = $this->model->getTahunOptions();

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $filterHari = isset($_GET['filter_hari']) ? $_GET['filter_hari'] : '';
        $filterTahun = isset($_GET['filter_tahun']) ? $_GET['filter_tahun'] : '';

        $jadwal = $this->model->searchJadwal($keyword, $filterHari, $filterTahun);

        include 'views/jadwal_list.php';
    }

    public function refresh(): void
    {
        if ($this->model->refreshView()) {
            header("Location: index.php?action=jadwal_list&message=refreshed");
        } else {
            header("Location: index.php?action=jadwal_list&message=refresh_error");
        }
        exit;
    }

    public function create(): void
    {
        // 1. Handle Submit Form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_mk' => $_POST['id_mk'],
                'id_dosen' => $_POST['id_dosen'],
                'id_kelas' => $_POST['id_kelas'],
                'hari' => $_POST['hari'],
                'jam_mulai' => $_POST['jam_mulai'],
                'jam_selesai' => $_POST['jam_selesai'],
                'ruangan' => $_POST['ruangan'],
                'tahun_akademik' => $_POST['tahun_akademik']
            ];

            if ($this->model->createJadwal($data)) {
                // Refresh View agar data tampil
                $this->model->refreshView();
                header("Location: index.php?action=jadwal_list&message=created");
                exit;
            } else {
                $error = "Gagal menyimpan data.";
            }
        }

        // 2. Siapkan Data Dropdown untuk View
        $dosenList = $this->model->getDosenList();
        $mkList = $this->model->getMatkulList();
        $kelasList = $this->model->getKelasList();

        // 3. Load View
        include 'views/jadwal_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=jadwal_list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_mk' => $_POST['id_mk'],
                'id_dosen' => $_POST['id_dosen'],
                'id_kelas' => $_POST['id_kelas'],
                'hari' => $_POST['hari'],
                'jam_mulai' => $_POST['jam_mulai'],
                'jam_selesai' => $_POST['jam_selesai'],
                'ruangan' => $_POST['ruangan'],
                'tahun_akademik' => $_POST['tahun_akademik']
            ];

            if ($this->model->updateJadwal($id, $data)) {
                $this->model->refreshView();
                header("Location: index.php?action=jadwal_list&message=updated");
                exit;
            } else {
                $error = "Gagal mengupdate data.";
            }
        }

        $jadwal = $this->model->getJadwalById($id);
        
        $dosenList = $this->model->getDosenList();
        $mkList = $this->model->getMatkulList();
        $kelasList = $this->model->getKelasList();

        if (!$jadwal) {
            header("Location: index.php?action=jadwal_list&message=search_error");
            exit;
        }

        include 'views/jadwal_form.php';
    }

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if ($this->model->deleteJadwal($id)) {
                
                $this->model->refreshView();

                header("Location: index.php?action=jadwal_list&message=deleted");
            } else {
                header("Location: index.php?action=jadwal_list&message=delete_error");
            }
        } else {
            header("Location: index.php?action=jadwal_list");
        }
        exit;
    }
}
