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
        $matakuliah = $this->model->getAllMatakuliah();
        include 'views/matakuliah_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
            $data = [
                'kode_mk' => $_POST['kode_mk'],
                'nama_mk' => $_POST['nama_mk'],
                'sks' => $_POST['sks'],
                'semester' => $_POST['semester'],
                'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
            ];

            if ($this->model->createMatakuliah($data)) {
                header("Location: index.php?action=matakuliah_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah matakuliah. Silakan coba lagi.";
            }
        }
        $jurusan_list = $this->model->getAllJurusan();
        include 'views/matakuliah_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $data = [
                'kode_mk' => $_POST['kode_mk'],
                'nama_mk' => $_POST['nama_mk'],
                'sks' => $_POST['sks'],
                'semester' => $_POST['semester'],
                'id_jurusan' => !empty($_POST['id_jurusan']) ? $_POST['id_jurusan'] : null,
            ];

            if ($this->model->updateMatakuliah($id, $data)) {
                header("Location: index.php?action=matakuliah_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate matakuliah";
            }
        }

        $matakuliah = $this->model->getMatakuliahById($id);

        if (!$matakuliah) {
            header("Location: index.php?action=matakuliah_list&message=not_found");
            exit();
        }

        $jurusan_list = $this->model->getAllJurusan();
        include 'views/matakuliah_form.php';
    }

    public function delete(): void
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $result = $this->model->deleteMatakuliah($id);

            if ($result === true) {
                header("Location: index.php?action=matakuliah_list&message=deleted");
            } elseif ($result === 'fk_error') {
                header("Location: index.php?action=matakuliah_list&message=delete_fk_error");
            } else {
                header("Location: index.php?action=matakuliah_list&message=delete_error");
            }
        } else {
            header("Location: index.php?action=matakuliah_list");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $matakuliah = $this->model->searchMatakuliah($_GET['keyword']);
        } else {
            $matakuliah = $this->model->getAllMatakuliah();
        }
        include 'views/matakuliah_list.php';
    }
}
