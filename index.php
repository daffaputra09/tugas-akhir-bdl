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
require_once 'models/JadwalModel.php';
require_once 'controllers/JadwalController.php';
require_once 'models/MatakuliahModel.php';
require_once 'controllers/MatakuliahController.php';
require_once 'models/NilaiModel.php';
require_once 'controllers/NilaiController.php';

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
$jadwal = new JadwalModel($db);
$jadwalController = new JadwalController($jadwal);
$matakuliahModel = new MatakuliahModel($db);
$matakuliahController = new MatakuliahController($matakuliahModel);
$nilaiModel = new NilaiModel($db);
$nilaiController = new NilaiController($nilaiModel);


$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch ($action) {
    case 'dashboard':
        $stats = $controller->getDashboardStats();
        include 'views/dashboard.php';
        break;
        
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
        
    case 'detail':
        $controller->detail();
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
    case 'dosen_jadwal':
        $dosenController->jadwal();
        break;
    case 'dosen_toggle_status':
        $dosenController->toggleStatus();
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

    // Jadwal Routes
    case 'jadwal_list':
        $jadwalController->list();
        break;
    case 'jadwal_create':
        $jadwalController->create();
        break;
    case 'jadwal_edit':
        $jadwalController->edit();
        break;
    case 'jadwal_delete':
        $jadwalController->delete();
        break;
    case 'jadwal_refresh':
        $jadwalController->refresh();
        break;
    case 'jadwal_search':
        $jadwalController->search();
        break;

    // Matakuliah Routes
    case 'matakuliah_list':
        $matakuliahController->list();
        break;
    case 'matakuliah_create':
        $matakuliahController->create();
        break;
    case 'matakuliah_edit':
        $matakuliahController->edit();
        break;
    case 'matakuliah_delete':
        $matakuliahController->delete();
        break;
    case 'matakuliah_search':
        $matakuliahController->search();
        break;

    // Nilai Routes
    case 'nilai_list':
        $nilaiController->list();
        break;
    case 'nilai_create':
        $nilaiController->create();
        break;
    case 'nilai_edit':
        $nilaiController->edit();
        break;
    case 'nilai_delete':
        $nilaiController->delete();
        break;
    case 'nilai_search':
        $nilaiController->search();
        break;
    case 'nilai_refresh':
        $nilaiController->refresh();
        break;

    default:
        $controller->list();
        break;
}
