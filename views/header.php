<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akademik Kampus</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
</head>

<body>
    <div class="dashboard-layout">
        <!-- sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1>SIAKAD</h1>
                <p>Sistem Akademik Kampus</p>
            </div>
            
            <nav class="sidebar-menu">
                <a href="index.php?action=list" class="menu-link">Data Mahasiswa</a>
                <a href="index.php?action=dosen_list" class="menu-link">Data Dosen</a>
            </nav>
        </aside>

        <!-- wrapper -->
        <div class="main-wrapper">
            <header class="top-navbar">
            <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'SIAKAD'; ?></h1>                <div class="user-info">
                    <span>Admin</span>
                </div>
            </header>
            
            <main class="main-content">
