<?php
require_once 'config/database.php';
require_once 'models/MahasiswaModel.php';
require_once 'controllers/MahasiswaController.php';
require_once 'models/DosenModel.php';
require_once 'controllers/DosenController.php';

// db
$database = new Database();
$db = $database->getConnection();
$mahasiswaModel = new MahasiswaModel($db);
$controller = new MahasiswaController($mahasiswaModel);
$dosenModel = new DosenModel($db);
$dosenController = new DosenController($dosenModel);

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

    default:
        $controller->list();
        break;
}
?>
