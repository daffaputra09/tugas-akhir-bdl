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
        $dosen = $this->model->getAllDosen();
        include 'views/dosen_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
            $data = [
                'nip' => $_POST['nip'],
                'nama_dosen' => $_POST['nama_dosen'],
                'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'no_hp' => $_POST['no_hp'],
                'status_aktif' => !empty($_POST['status_aktif']) ? $_POST['status_aktif'] : 'Aktif',
            ];

            if ($this->model->createDosen($data)) {
                header("Location: index.php?action=dosen_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah dosen";
            }
        }
        $jurusan_list = $this->model->getAllJurusan();
        include 'views/dosen_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $data = [
                'nip' => $_POST['nip'],
                'nama_dosen' => $_POST['nama_dosen'],
                'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
                'email' => !empty($_POST['email']) ? $_POST['email'] : null,
                'no_hp' => $_POST['no_hp'],
                'status_aktif' => !empty($_POST['status_aktif']) ? $_POST['status_aktif'] : 'Aktif',
            ];

            if ($this->model->updateDosen($id, $data)) {
                header("Location: index.php?action=dosen_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate dosen. Silakan coba lagi.";
            }
        }

        $dosen = $this->model->getDosenById($id);

        if (!$dosen) {
            header("Location: index.php?action=dosen_list&message=not_found");
            exit();
        }

        $jurusan_list = $this->model->getAllJurusan();
        include 'views/dosen_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];

        $result = $this->model->deleteDosen($id);

        if ($result === true) {
            header("Location: index.php?action=dosen_list&message=deleted");
        } elseif ($result === 'fk_error') {
            header("Location: index.php?action=dosen_list&message=delete_fk_error");
        } else {
            header("Location: index.php?action=dosen_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $dosen = $this->model->searchDosen($_GET['keyword']);
        } else {
            $dosen = $this->model->getAllDosen();
        }
        include 'views/dosen_list.php';
    }
}
