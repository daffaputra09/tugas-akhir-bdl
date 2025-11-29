<?php
$page_title = "Dashboard";
include 'views/header.php';
?>

<div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2>Selamat Datang di Dashboard SIAKAD</h2>
        <p>Kelola data akademik dengan mudah dan efisien</p>
    </div>

    <!-- Stats Cards - Primary -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Mahasiswa</div>
                <div class="stat-value"><?php echo number_format($stats['total_mahasiswa']) ?></div>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="7" r="4"></circle>
                    <path d="M6 21v-2a4 4 0 0 1 4-4h4a4 4 0 0 1 4 4v2"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Dosen</div>
                <div class="stat-value"><?php echo number_format($stats['total_dosen']) ?></div>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                    <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                    <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Jurusan</div>
                <div class="stat-value"><?php echo number_format($stats['total_jurusan']) ?></div>
            </div>
        </div>
    </div>

    <!-- Gender Stats -->
    <div class="stats-grid stats-grid-2">
        <div class="stat-card stat-male">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="10" cy="8" r="4"></circle>
                    <path d="M10.5 15H8a4 4 0 0 0-4 4v2h11v-2a4 4 0 0 0-.5-2"></path>
                    <path d="M19 8v6m3-3h-6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Mahasiswa Laki-laki</div>
                <div class="stat-value"><?php echo number_format($stats['total_laki']); ?></div>
            </div>
        </div>

        <div class="stat-card stat-female">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"></circle>
                    <path d="M15 21v-4"></path>
                    <path d="M9 21v-4"></path>
                    <path d="M12 13v8"></path>
                    <path d="M12 13c-2.5 0-4.5 1.5-4.5 3.5V21h9v-4.5c0-2-2-3.5-4.5-3.5z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Mahasiswa Perempuan</div>
                <div class="stat-value"><?php echo number_format($stats['total_perempuan']); ?></div>
            </div>
        </div>
    </div>

    <!-- Nilai Stats -->
    <div class="stats-grid stats-grid-2">
        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                    <path d="M3.6 9h16.8a1 1 0 0 1 .95 1.316l-2.4 7.2A2 2 0 0 1 17.05 19H6.95a2 2 0 0 1-1.9-1.484l-2.4-7.2A1 1 0 0 1 3.6 9z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Nilai Terendah</div>
                <div class="stat-value"><?php echo number_format($stats['min_nilai']); ?></div>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Nilai Tertinggi</div>
                <div class="stat-value"><?php echo number_format($stats['max_nilai']); ?></div>
            </div>
        </div>
    </div>

    <!-- Rata-rata Nilai -->
    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M12 18v-6"></path>
                    <path d="M9 15h6"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rata-rata Nilai Tugas</div>
                <div class="stat-value"><?php echo number_format($stats['avg_tugas'], 2, ',', '.'); ?></div>
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <path d="M14 2v6h6"></path>
                    <path d="M9 13h6"></path>
                    <path d="M9 17h3"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rata-rata Nilai UTS</div>
                <div class="stat-value"><?php echo number_format($stats['avg_uts'], 2, ',', '.'); ?></div>
            </div>
        </div>

        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10 12 2 2 10l10 8Z"></path>
                    <path d="M6 12v5c0 .6.4 1.1 1 1.4l5 2.6 5-2.6c.6-.3 1-.8 1-1.4v-5"></path>
                </svg>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rata-rata Nilai UAS</div>
                <div class="stat-value"><?php echo number_format($stats['avg_uas'], 2, ',', '.'); ?></div>
            </div>
        </div>
    </div>

    <!-- Highlight Card -->
    <div class="stat-card-highlight">
        <div class="stat-icon-lg">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 3h18v18H3z"></path>
                <path d="M8 12h8"></path>
                <path d="M12 8v8"></path>
            </svg>
        </div>
        <div class="stat-content-lg">
            <div class="stat-label-lg">Rata-rata Nilai Keseluruhan</div>
            <div class="stat-value-lg"><?php echo number_format($stats['avg_per_mahasiswa'], 2, ',', '.'); ?></div>
            <div class="stat-subtitle">Berdasarkan semua mahasiswa aktif</div>
        </div>
    </div>
</div>