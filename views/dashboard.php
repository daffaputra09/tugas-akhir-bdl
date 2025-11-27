<?php
$page_title = "Dashboard";
include 'views/header.php';
?>

<div class="main-content">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h2>Selamat Datang di Dashboard SIAKAD</h2>
        <p>Kelola data mahasiswa dengan mudah dan efisien</p>
    </div>

    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Mahasiswa</h3>
            <div class="number"><?php echo number_format($stats['total_mahasiswa']) ?></div>
        </div>
        <div class="card">
            <h3>Total Jurusan</h3>
            <div class="number"><?php echo number_format($stats['total_jurusan']) ?></div>
        </div>
        <div class="card">
            <h3>Total Dosen</h3>
            <div class="number"><?php echo number_format($stats['total_dosen']) ?></div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin: 2rem 0;">
        <div class="card">
            <h3>Mahasiswa Laki-laki</h3>
            <div class="number"><?php echo number_format($stats['total_laki']); ?></div>
        </div>
        <div class="card">
            <h3>Mahasiswa Perempuan</h3>
            <div class="number"><?php echo number_format($stats['total_perempuan']); ?></div>
        </div>  
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin: 2rem 0;">
        <div class="card">
            <h3>Nilai Terendah</h3>
            <div class="number"><?php echo number_format($stats['min_nilai']); ?></div>
        </div>
        <div class="card">
            <h3>Nilai Tertinggi</h3>
            <div class="number"><?php echo number_format($stats['max_nilai']); ?></div>
        </div>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin: 2rem 0;">
        <div class="card">
            <h3>Rata-rata Nilai Tugas</h3>
            <div class="number"><?php echo number_format($stats['avg_tugas'],2,',','.'); ?></div>
        </div>
        <div class="card">
            <h3>Rata-rata Nilai UTS</h3>
            <div class="number"><?php echo number_format($stats['avg_uts'],2,',','.'); ?></div>
        </div>
        <div class="card">
            <h3>Rata-rata Nilai UAS</h3>
            <div class="number"><?php echo number_format($stats['avg_uas'],2,',','.'); ?></div>
        </div>
    </div>
    <div class="card">
        <h3>Rata-rata Nilai</h3>
        <div class="number"><?php echo number_format($stats['avg_per_mahasiswa'],2,',','.'); ?></div>
    </div>
</div>