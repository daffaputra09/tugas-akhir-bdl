<?php
require_once 'config/database.php';
require_once 'models/MahasiswaModel.php';
require_once 'controllers/MahasiswaController.php';
require_once 'models/DosenModel.php';
require_once 'controllers/DosenController.php';
require_once 'models/JurusanModel.php';
require_once 'controllers/JurusanController.php';
require_once 'models/KelasModel.php';
require_once 'controllers/KelasController.php';

// db
$database = new Database();
$db = $database->getConnection();
$mahasiswaModel = new MahasiswaModel($db);
$controller = new MahasiswaController($mahasiswaModel);
$dosenModel = new DosenModel($db);
$dosenController = new DosenController($dosenModel);
$jurusan = new JurusanModel($db);
$jurusanController = new JurusanController($jurusan);
$kelas = new KelasModel($db);
$kelasController = new KelasController($kelas, $jurusan);

$action = isset($_GET['action']) ? $_GET['action'] : 'list';

switch ($action) {
    case 'list':
        $controller->list();
        break;

    case 'create':
        $controller->create();
        break;

    case 'edit':
        $controller->edit();
        break;

    case 'delete':
        $controller->delete();
        break;

    case 'search':
        $controller->search();
        break;

    // Dosen routes
    case 'dosen_list':
        $dosenController->list();
        break;
    case 'dosen_create':
        $dosenController->create();
        break;
    case 'dosen_edit':
        $dosenController->edit();
        break;
    case 'dosen_delete':
        $dosenController->delete();
        break;
    case 'dosen_search':
        $dosenController->search();
        break;

    // Jurusan routes
    case 'jurusan_list':
        $jurusanController->list();
        break;
    case 'jurusan_create':
        $jurusanController->create();
        break;
    case 'jurusan_edit':
        $jurusanController->edit();
        break;
    case 'jurusan_delete':
        $jurusanController->delete();
        break;
    case 'jurusan_search':
        $jurusanController->search();
        break;

    // Kelas Routes
    case 'kelas_list':
        $kelasController->list();
        break;
    case 'kelas_create':
        $kelasController->create();
        break;
    case 'kelas_edit':
        $kelasController->edit();
        break;
    case 'kelas_delete':
        $kelasController->delete();
        break;
    case 'kelas_search':
        $kelasController->search();
        break;

    default:
        $controller->list();
        break;
}
