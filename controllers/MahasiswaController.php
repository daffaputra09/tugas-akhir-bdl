<?php
class MahasiswaController
{
    private $model;

    public function __construct(MahasiswaModel $model)
    {
        $this->model = $model;
    }

    public function list(): void
    {
        $mahasiswa = $this->model->getAllMahasiswa();
        include 'views/mahasiswa_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
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

            if ($this->model->createMahasiswa($data)) {
                header("Location: index.php?action=list&message=created");
                exit();
            } else {
                $error = "Gagal menambah mahasiswa";
            }
        }
        $jurusan_list = $this->model->getAllJurusan();
        $kelas_list = $this->model->getAllKelas();
        include 'views/mahasiswa_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
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

            if ($this->model->updateMahasiswa($id, $data)) {
                header("Location: index.php?action=list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate mahasiswa";
            }
        }

        $mahasiswa = $this->model->getMahasiswaById($id);
        $jurusan_list = $this->model->getAllJurusan();
        $kelas_list = $this->model->getAllKelas();
        include 'views/mahasiswa_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];
        if ($this->model->deleteMahasiswa($id)) {
            header("Location: index.php?action=list&message=deleted");
        } else {
            header("Location: index.php?action=list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $mahasiswa = $this->model->searchMahasiswa($_GET['keyword']);
        } else {
            $mahasiswa = $this->model->getAllMahasiswa();
        }
        include 'views/mahasiswa_list.php';
    }

    public function detail(){
        $nim = $_GET['nim'];
        $mahasiswa = $this->model->getMahasiswaByNIM($nim);
        include 'views/mahasiswa_detail.php';
    }

    public function getDashboardStats(){
        $stats = $this->model->getDashboardStats();
        return $stats;
    }
}
?>

