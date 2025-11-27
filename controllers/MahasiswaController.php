<?php
class MahasiswaController
{
    private $model;
    private $upload_dir = 'uploads/foto_mahasiswa/';
    private $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    private $max_size = 2097152; // 2MB in bytes

    public function __construct(MahasiswaModel $model)
    {
        $this->model = $model;
        // Buat folder upload jika belum ada
        if (!file_exists($this->upload_dir)) {
            mkdir($this->upload_dir, 0755, true);
        }
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
                'id_kelas' => !empty($_POST['id_kelas']) ? $_POST['id_kelas'] : null,
                'foto' => null
            ];

            // Handle file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $upload_result = $this->handleFileUpload($_FILES['foto'], $_POST['nim']);
                if ($upload_result['success']) {
                    $data['foto'] = $upload_result['path'];
                } else {
                    $error = $upload_result['error'];
                }
            }

            if (!isset($error) && $this->model->createMahasiswa($data)) {
                header("Location: index.php?action=list&message=created");
                exit();
            } else {
                $error = $error ?? "Gagal menambah mahasiswa";
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

            // Handle file upload jika ada file baru
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                $upload_result = $this->handleFileUpload($_FILES['foto'], $_POST['nim'], $id);
                if ($upload_result['success']) {
                    $data['foto'] = $upload_result['path'];
                } else {
                    $error = $upload_result['error'];
                }
            }

            if (!isset($error) && $this->model->updateMahasiswa($id, $data)) {
                header("Location: index.php?action=list&message=updated");
                exit();
            } else {
                $error = $error ?? "Gagal mengupdate mahasiswa";
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

    private function handleFileUpload($file, $nim, $id = null)
    {
        // Validasi tipe file
        if (!in_array($file['type'], $this->allowed_types)) {
            return ['success' => false, 'error' => 'Format file tidak didukung. Hanya JPG, PNG, dan GIF yang diperbolehkan.'];
        }

        // Validasi ukuran file
        if ($file['size'] > $this->max_size) {
            return ['success' => false, 'error' => 'Ukuran file terlalu besar. Maksimal 2MB.'];
        }

        // Generate nama file unik
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = 'mahasiswa_' . $nim . '_' . time() . '.' . $file_extension;
        $target_path = $this->upload_dir . $file_name;

        // Hapus foto lama jika edit
        if ($id) {
            $mahasiswa = $this->model->getMahasiswaById($id);
            if ($mahasiswa && !empty($mahasiswa['foto']) && file_exists($mahasiswa['foto'])) {
                unlink($mahasiswa['foto']);
            }
        }

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return ['success' => true, 'path' => $target_path];
        } else {
            return ['success' => false, 'error' => 'Gagal mengupload file.'];
        }
    }
}
?>
