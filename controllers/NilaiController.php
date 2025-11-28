<?php
class NilaiController
{
    private $model;

    public function __construct(NilaiModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        try {
            $listTipe = $this->model->getTipeNilaiOptions();
            $listJurusan = $this->model->getJurusanOptions();
            $listSemester = $this->model->getSemesterOptions();

            $nilai = $this->model->getAllNilai();
            if (!$nilai) {
                throw new Exception("Gagal mengambil data nilai");
            }
            include 'views/nilai_list.php';
        } catch (Exception $e) {
            error_log("Error in list: " . $e->getMessage());
            $error = $e->getMessage();
            include 'views/error.php';
        }
    }

    public function create(): void
    {
        $error = null;
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = [
                    'id_mahasiswa' => $_POST['id_mahasiswa'],
                    'id_mk' => $_POST['id_mk'],
                    'nilai_angka' => $_POST['nilai_angka'],
                    'tipe_nilai' => $_POST['tipe_nilai'],
                    'tanggal_input' => $_POST['tanggal_input'] ?? date('Y-m-d'),
                ];

                // Validasi data
                if (empty($data['id_mahasiswa'])) {
                    throw new Exception("Mahasiswa harus dipilih");
                }
                if (empty($data['id_mk'])) {
                    throw new Exception("Matakuliah harus dipilih");
                }
                if (empty($data['nilai_angka']) || $data['nilai_angka'] < 0 || $data['nilai_angka'] > 100) {
                    throw new Exception("Nilai angka harus antara 0-100");
                }
                if (empty($data['tipe_nilai'])) {
                    throw new Exception("Tipe nilai harus dipilih");
                }

                if (!$this->model->createNilai($data)) {
                    throw new Exception("Gagal menyimpan data nilai");
                }
                
                header("Location: index.php?action=nilai_list&message=created");
                exit();
            } catch (Exception $e) {
                error_log("Error in create: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $mahasiswaList = $this->model->getMahasiswaList();
            $matakuliahList = $this->model->getMatakuliahList();
            include 'views/nilai_form.php';
        } catch (Exception $e) {
            error_log("Error loading dropdown: " . $e->getMessage());
            $error = "Gagal memuat data referensi";
            include 'views/error.php';
        }
    }

    public function edit(): void
    {
        $error = null;
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            header("Location: index.php?action=nilai_list");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = [
                    'id_mahasiswa' => $_POST['id_mahasiswa'],
                    'id_mk' => $_POST['id_mk'],
                    'nilai_angka' => $_POST['nilai_angka'],
                    'tipe_nilai' => $_POST['tipe_nilai'],
                    'tanggal_input' => $_POST['tanggal_input'],
                ];

                // Validasi data
                if (empty($data['id_mahasiswa'])) {
                    throw new Exception("Mahasiswa harus dipilih");
                }
                if (empty($data['id_mk'])) {
                    throw new Exception("Matakuliah harus dipilih");
                }
                if (empty($data['nilai_angka']) || $data['nilai_angka'] < 0 || $data['nilai_angka'] > 100) {
                    throw new Exception("Nilai angka harus antara 0-100");
                }
                if (empty($data['tipe_nilai'])) {
                    throw new Exception("Tipe nilai harus dipilih");
                }

                if (!$this->model->updateNilai($id, $data)) {
                    throw new Exception("Gagal mengupdate data nilai");
                }
                
                header("Location: index.php?action=nilai_list&message=updated");
                exit();
            } catch (Exception $e) {
                error_log("Error in edit: " . $e->getMessage());
                $error = $e->getMessage();
            }
        }

        try {
            $nilai = $this->model->getNilaiById($id);
            if (!$nilai) {
                throw new Exception("Data nilai tidak ditemukan");
            }

            $mahasiswaList = $this->model->getMahasiswaList();
            $matakuliahList = $this->model->getMatakuliahList();
            include 'views/nilai_form.php';
        } catch (Exception $e) {
            error_log("Error loading edit data: " . $e->getMessage());
            header("Location: index.php?action=nilai_list&message=search_error");
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

            if (!$this->model->deleteNilai($id)) {
                throw new Exception("Gagal menghapus data nilai");
            }

            header("Location: index.php?action=nilai_list&message=deleted");
        } catch (Exception $e) {
            error_log("Error in delete: " . $e->getMessage());
            header("Location: index.php?action=nilai_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        $error = null;
        
        try {
            $listTipe = $this->model->getTipeNilaiOptions();
            $listJurusan = $this->model->getJurusanOptions();
            $listSemester = $this->model->getSemesterOptions();

            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $filterTipe = isset($_GET['filter_tipe']) ? $_GET['filter_tipe'] : '';
            $filterJurusan = isset($_GET['filter_jurusan']) ? $_GET['filter_jurusan'] : '';
            $filterSemester = isset($_GET['filter_semester']) ? $_GET['filter_semester'] : '';

            $nilai = $this->model->searchNilai($keyword, $filterTipe, $filterJurusan, $filterSemester);
            if (!$nilai) {
                throw new Exception("Pencarian gagal");
            }
            
            include 'views/nilai_list.php';
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
            header("Location: index.php?action=nilai_list&message=refreshed");
        } catch (Exception $e) {
            error_log("Error in refresh: " . $e->getMessage());
            header("Location: index.php?action=nilai_list&message=refresh_error");
        }
        exit();
    }
}
?>