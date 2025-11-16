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
        $jurusan = $this->model->getAllJurusan();
        include 'views/jurusan_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
            $data = [
                'nama_jurusan' => $_POST['nama_jurusan'],
                'akreditasi' => $_POST['akreditasi'],
            ];

            if ($this->model->createJurusan($data)) {
                header("Location: index.php?action=jurusan_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah jurusan";
            }
        }
        $jurusan_list = $this->model->getAllJurusan();
        include 'views/jurusan_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $data = [
                'nama_jurusan' => $_POST['nama_jurusan'],
                'akreditasi' => $_POST['akreditasi'],
            ];

            if ($this->model->updateJurusan($id, $data)) {
                header("Location: index.php?action=jurusan_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate jurusan";
            }
        }

        $jurusan = $this->model->getJurusanById($id);
        $jurusan_list = $this->model->getAllJurusan();
        include 'views/jurusan_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];
        if ($this->model->deleteJurusan($id)) {
            header("Location: index.php?action=jurusan_list&message=deleted");
        } else {
            header("Location: index.php?action=jurusan_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $jurusan = $this->model->searchJurusan($_GET['keyword']);
        } else {
            $jurusan = $this->model->getAllJurusan();
        }
        include 'views/jurusan_list.php';
    }
}
?>

