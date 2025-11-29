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
                function render_menu_icon($name)
                {
                    $icons = [
                        'dashboard' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="11" width="7" height="10" rx="1"></rect><rect x="3" y="15" width="7" height="6" rx="1"></rect></svg>',
                        'mahasiswa' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="3"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                        'dosen' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="7" r="4"></circle><path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path></svg>',
                        'jurusan' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg>',
                        'kelas' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="13" rx="2"></rect><path d="M3 17l4 3h10l4-3"></path></svg>',
                        'jadwal' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4"></path><path d="M8 2v4"></path><path d="M3 10h18"></path><path d="M16 13h-4v4"></path></svg>',
                        'matakuliah' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-graduation-cap-icon lucide-graduation-cap"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>',
                        'nilai' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-list-ordered-icon lucide-list-ordered"><path d="M11 5h10"/><path d="M11 12h10"/><path d="M11 19h10"/><path d="M4 4h1v5"/><path d="M4 9h2"/><path d="M6.5 20H3.4c0-1 2.6-1.925 2.6-3.5a1.5 1.5 0 0 0-2.6-1.02"/></svg>',
                    ];

                    return isset($icons[$name]) ? $icons[$name] : $icons['dashboard'];
                }

                $listMenu = [
                    [
                        'url' => 'action=dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'dashboard',
                    ],
                    [
                        'url' => 'action=list',
                        'label' => 'Data Mahasiswa',
                        'icon' => 'mahasiswa',
                    ],
                    [
                        'url' => 'action=dosen_list',
                        'label' => 'Data Dosen',
                        'icon' => 'dosen',
                    ],
                    [
                        'url' => 'action=jurusan_list',
                        'label' => 'Data Jurusan',
                        'icon' => 'jurusan',
                    ],
                    [
                        'url' => 'action=kelas_list',
                        'label' => 'Data Kelas',
                        'icon' => 'kelas',
                    ],
                    [
                        'url' => 'action=jadwal_list',
                        'label' => 'Data Jadwal',
                        'icon' => 'jadwal',
                    ],
                    [
                        'url' => 'action=matakuliah_list',
                        'label' => 'Data Matakuliah',
                        'icon' => 'matakuliah',
                    ],
                    [
                        'url' => 'action=nilai_list',
                        'label' => 'Data Nilai',
                        'icon' => 'nilai',
                    ],
                ];

                $currentAction = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

                foreach ($listMenu as $menu) {
                    $menuAction = '';
                    if (preg_match('/action=([^&]+)/', $menu['url'], $matches)) {
                        $menuAction = $matches[1];
                    }
                    
                    $isActive = ($menuAction === $currentAction);
                    $classes = 'menu-link' . ($isActive ? ' active' : '');
                    echo '<a href="index.php?' . $menu['url'] . '" class="' . $classes . '">';
                    echo '<span class="menu-icon">' . render_menu_icon($menu['icon']) . '</span>';
                    echo '<span class="menu-text">' . htmlspecialchars($menu['label']) . '</span>';
                    echo '</a>' . "\n";
                }
                ?>
            </nav>
        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="main-wrapper">
            <header class="top-navbar">
                <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <h1 class="page-title"><?php echo isset($page_title) ? $page_title : 'SIAKAD'; ?></h1>
                <div class="user-info">
                    <span>Admin</span>
                </div>
        </header>

        <main class="main-content">