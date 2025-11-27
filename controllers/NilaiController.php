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
        $listTipe = $this->model->getTipeNilaiOptions();
        $listJurusan = $this->model->getJurusanOptions();
        $listSemester = $this->model->getSemesterOptions();

        $nilai = $this->model->getAllNilai();
        include 'views/nilai_list.php';
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_mahasiswa' => $_POST['id_mahasiswa'],
                'id_mk' => $_POST['id_mk'],
                'nilai_angka' => $_POST['nilai_angka'],
                'tipe_nilai' => $_POST['tipe_nilai'],
                'tanggal_input' => $_POST['tanggal_input'] ?? date('Y-m-d'),
            ];

            if ($this->model->createNilai($data)) {
                header("Location: index.php?action=nilai_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah nilai";
            }
        }

        $mahasiswaList = $this->model->getMahasiswaList();
        $matakuliahList = $this->model->getMatakuliahList();
        include 'views/nilai_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=nilai_list");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id_mahasiswa' => $_POST['id_mahasiswa'],
                'id_mk' => $_POST['id_mk'],
                'nilai_angka' => $_POST['nilai_angka'],
                'tipe_nilai' => $_POST['tipe_nilai'],
                'tanggal_input' => $_POST['tanggal_input'],
            ];

            if ($this->model->updateNilai($id, $data)) {
                header("Location: index.php?action=nilai_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate nilai";
            }
        }

        $nilai = $this->model->getNilaiById($id);
        
        if (!$nilai) {
            header("Location: index.php?action=nilai_list&message=search_error");
            exit();
        }

        $mahasiswaList = $this->model->getMahasiswaList();
        $matakuliahList = $this->model->getMatakuliahList();
        include 'views/nilai_form.php';
    }

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            if ($this->model->deleteNilai($id)) {
                header("Location: index.php?action=nilai_list&message=deleted");
            } else {
                header("Location: index.php?action=nilai_list&message=delete_error");
            }
        } else {
            header("Location: index.php?action=nilai_list");
        }
        exit();
    }

    public function search(): void
    {
        $listTipe = $this->model->getTipeNilaiOptions();
        $listJurusan = $this->model->getJurusanOptions();
        $listSemester = $this->model->getSemesterOptions();

        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $filterTipe = isset($_GET['filter_tipe']) ? $_GET['filter_tipe'] : '';
        $filterJurusan = isset($_GET['filter_jurusan']) ? $_GET['filter_jurusan'] : '';
        $filterSemester = isset($_GET['filter_semester']) ? $_GET['filter_semester'] : '';

        $nilai = $this->model->searchNilai($keyword, $filterTipe, $filterJurusan, $filterSemester);
        include 'views/nilai_list.php';
    }

    public function refresh(): void
    {
        if ($this->model->refreshView()) {
            header("Location: index.php?action=nilai_list&message=refreshed");
        } else {
            header("Location: index.php?action=nilai_list&message=refresh_error");
        }
        exit();
    }
}
?>
