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
                <?php
                $listMenu = [
                    [
                        'url' => 'action=list',
                        'label' => 'Data Mahasiswa'
                    ],
                    [
                        'url' => 'action=dosen_list',
                        'label' => 'Data Dosen'
                    ],
                    [
                        'url' => 'action=jurusan_list',
                        'label' => 'Data Jurusan'
                    ],
                    [
                        'url' => 'action=kelas_list',
                        'label' => 'Data Kelas'
                    ],
                    [
                        'url' => 'action=jadwal_list',
                        'label' => 'Data Jadwal'
                    ]
                ];
                foreach ($listMenu as $menu) {
                    echo '<a href="index.php?' . $menu['url'] . '" class="menu-link">' . $menu['label'] . '</a>' . "\n";
                }
                ?>
            </nav>
        </aside>

        <!-- wrapper -->
        <div class="main-wrapper">
            <header class="top-navbar">
                <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'SIAKAD'; ?></h1>
                <div class="user-info">
                    <span>Admin</span>
                </div>
            </header>

            <main class="main-content">