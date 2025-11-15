<?php
require_once 'config/database.php';
require_once 'models/MahasiswaModel.php';

// db
$database = new Database();
$db = $database->getConnection();
$mahasiswaModel = new MahasiswaModel($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'list':
        $mahasiswa = $mahasiswaModel->getAllMahasiswa();
        include 'views/mahasiswa_list.php';
        break;

    case 'create':
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
            
            if ($mahasiswaModel->createMahasiswa($data)) {
                header("Location: index.php?action=list&message=created");
                exit();
            } else {
                $error = "Gagal menambah mahasiswa";
            }
        }
        include 'views/mahasiswa_form.php';
        break;

    case 'edit':
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
            
            if ($mahasiswaModel->updateMahasiswa($id, $data)) {
                header("Location: index.php?action=list&message=updated");
                exit();
            } else {
                $error = "Gagal mengupdate mahasiswa";
            }
        }
        
        $mahasiswa = $mahasiswaModel->getMahasiswaById($id);
        include 'views/mahasiswa_form.php';
        break;

    case 'delete':
        $id = $_GET['id'];
        if ($mahasiswaModel->deleteMahasiswa($id)) {
            header("Location: index.php?action=list&message=deleted");
        } else {
            header("Location: index.php?action=list&message=delete_error");
        }
        exit();
        break;

    case 'search':
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            $mahasiswa = $mahasiswaModel->searchMahasiswa($_GET['keyword']);
        } else {
            $mahasiswa = $mahasiswaModel->getAllMahasiswa();
        }
        include 'views/mahasiswa_list.php';
        break;

    default:
        $mahasiswa = $mahasiswaModel->getAllMahasiswa();
        include 'views/mahasiswa_list.php';
        break;
}
?>
