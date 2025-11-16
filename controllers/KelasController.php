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
        $kelas = $this->kelasModel->getAllKelas();
        include 'views/kelas_list.php';
    }

    public function create(): void
    {
        if ($_POST) {
            $data = [
                'nama_kelas' => $_POST['nama_kelas'],
                'id_jurusan' => $_POST['id_jurusan'],
            ];

            if ($this->kelasModel->createKelas($data)) {
                header("Location: index.php?action=kelas_list&message=created");
                exit();
            } else {
                $error = "Gagal menambah kelas";
            }
        }

        $jurusan_list = $this->jurusanModel->getAllJurusan();
        include 'views/kelas_form.php';
    }

    public function edit(): void
    {
        $id = $_GET['id'];

        if ($_POST) {
            $data = [
                'nama_kelas' => $_POST['nama_kelas'],
                'id_jurusan' => $_POST['id_jurusan'],
            ];

            if ($this->kelasModel->updateKelas($id, $data)) {
                header("Location: index.php?action=kelas_list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate kelas";
            }
        }

        $kelas = $this->kelasModel->getKelasById($id);
        $jurusan_list = $this->jurusanModel->getAllJurusan();
        include 'views/kelas_form.php';
    }

    public function delete(): void
    {
        $id = $_GET['id'];
        
        $result = $this->kelasModel->deleteKelas($id);

        if ($result === true) {
            header("Location: index.php?action=kelas_list&message=deleted");
        } elseif ($result === 'fk_error') {
            header("Location: index.php?action=kelas_list&message=fk_error");
        } else {
            header("Location: index.php?action=kelas_list&message=delete_error");
        }
        exit();
    }

    public function search(): void
    {
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $kelas = $this->kelasModel->searchKelas($_GET['keyword']);
        } else {
            $kelas = $this->kelasModel->getAllKelas();
        }
        include 'views/kelas_list.php';
    }
}
?>