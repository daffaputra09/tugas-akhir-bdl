<?php
$page_title = "Detail Mahasiswa";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Detail Mahasiswa</h2>
        <a href="index.php?action=list" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="content">
        <!-- Bagian Kiri - Foto -->
        <div class="photo-section">
            <div class="content-box" style="min-width: 300px; display: flex; justify-content: center; align-items: center; height: 300px; width: 300px;">
                <?php if (!empty($mahasiswa['foto']) && file_exists($mahasiswa['foto'])): ?>
                    <img src="<?= htmlspecialchars($mahasiswa['foto']) ?>" 
                         alt="Foto <?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?>" 
                         style="max-width: 100%; max-height: 100%; border-radius: 5px; object-fit: cover;">
                <?php else: ?>
                    <div class="photo-placeholder" style="width: 200px; height: 200px; background-color: #f0f0f0; border-radius: 5px; display: flex; align-items: center; justify-content: center; font-size: 48px; color: #999;">
                        👤
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bagian Kanan - Detail -->
        <div class="details-section">
            <div class="detail-title"><?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></div>
            <div class="detail-subtitle">NIM: <?= htmlspecialchars($mahasiswa['nim']) ?></div>

            <div class="detail-group">
                <div class="detail-label">Jenis Kelamin</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['jenis_kelamin']) ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Email</div>
                <div class="detail-value">
                    <?php if (!empty($mahasiswa['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($mahasiswa['email']) ?>" style="color: #667eea; text-decoration: none;">
                            <?= htmlspecialchars($mahasiswa['email']) ?>
                        </a>
                    <?php else: ?>
                        <span class="empty">Tidak ada email</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">No. HP</div>
                <div class="detail-value <?= empty($mahasiswa['no_hp']) ? 'empty' : '' ?>">
                    <?= !empty($mahasiswa['no_hp']) ? htmlspecialchars($mahasiswa['no_hp']) : 'Tidak ada nomor HP' ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Jurusan</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['jurusan']) ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Kelas</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['kelas']) ?></div>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <div class="info-card-label">Tahun Masuk</div>
                    <div class="info-card-value"><?= htmlspecialchars($mahasiswa['tahun_masuk']) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-card-label">Semester</div>
                    <div class="info-card-value"><?= htmlspecialchars($mahasiswa['semester']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Akademik -->
<?php if ($statistik && $statistik['total_mk_diambil'] > 0): ?>
<div class="content-box" style="margin-top: 20px;">
    <h3 style="margin-bottom: 20px; color: #031C30;">📊 Statistik Akademik</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-left: 4px solid #2563eb; padding: 20px; border-radius: 6px;">
            <div style="font-size: 13px; color: #666; margin-bottom: 8px;">IPK</div>
            <div style="font-size: 28px; font-weight: bold; color: #031C30;"><?= number_format($statistik['ipk'], 2) ?></div>
        </div>
        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-left: 4px solid #10b981; padding: 20px; border-radius: 6px;">
            <div style="font-size: 13px; color: #666; margin-bottom: 8px;">Total SKS</div>
            <div style="font-size: 28px; font-weight: bold; color: #031C30;"><?= $statistik['total_sks_diambil'] ?></div>
        </div>
        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-left: 4px solid #f59e0b; padding: 20px; border-radius: 6px;">
            <div style="font-size: 13px; color: #666; margin-bottom: 8px;">Mata Kuliah</div>
            <div style="font-size: 28px; font-weight: bold; color: #031C30;"><?= $statistik['total_mk_diambil'] ?></div>
        </div>
        <div style="background: #f8f9fa; border: 1px solid #e0e0e0; border-left: 4px solid #8b5cf6; padding: 20px; border-radius: 6px;">
            <div style="font-size: 13px; color: #666; margin-bottom: 8px;">Lulus</div>
            <div style="font-size: 28px; font-weight: bold; color: #031C30;"><?= $statistik['mk_lulus'] ?></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- KHS (Kartu Hasil Studi) -->
<?php if (!empty($khs_grouped)): ?>
<div class="content-box" style="margin-top: 20px;">
    <h3 style="margin-bottom: 20px; color: #031C30;">📋 Kartu Hasil Studi (KHS)</h3>
    
    <?php foreach ($khs_grouped as $semester => $data): ?>
    <div style="margin-bottom: 30px;">
        <h4 style="background: #031C30; color: white; padding: 10px 15px; border-radius: 5px; margin-bottom: 10px;">
            Semester <?= $semester ?>
        </h4>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode MK</th>
                        <th width="40%">Nama Mata Kuliah</th>
                        <th width="10%">SKS</th>
                        <th width="10%">Nilai</th>
                        <th width="10%">Huruf</th>
                        <th width="10%">Mutu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    foreach ($data['matakuliah'] as $mk): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($mk['kode_mk']) ?></strong></td>
                        <td><?= htmlspecialchars($mk['nama_mk']) ?></td>
                        <td style="text-align: center;"><?= $mk['sks'] ?></td>
                        <td style="text-align: center;"><?= $mk['nilai_angka'] ?></td>
                        <td style="text-align: center;">
                            <?php
                            $badgeColor = 'badge-secondary';
                            if ($mk['nilai_huruf'] == 'A') $badgeColor = 'badge-success';
                            elseif ($mk['nilai_huruf'] == 'B') $badgeColor = 'badge-info';
                            elseif ($mk['nilai_huruf'] == 'C') $badgeColor = 'badge-warning';
                            elseif ($mk['nilai_huruf'] == 'D') $badgeColor = 'badge-danger';
                            elseif ($mk['nilai_huruf'] == 'E') $badgeColor = 'badge-dark';
                            ?>
                            <span class="badge <?= $badgeColor ?>"><?= $mk['nilai_huruf'] ?></span>
                        </td>
                        <td style="text-align: center;"><?= number_format($mk['mutu'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td colspan="3" style="text-align: right; padding-right: 15px;">Total Semester <?= $semester ?>:</td>
                        <td style="text-align: center;"><?= $data['total_sks'] ?></td>
                        <td colspan="2" style="text-align: right; padding-right: 15px;">IPS:</td>
                        <td style="text-align: center;">
                            <?php 
                            $ips = $data['total_sks'] > 0 ? $data['total_mutu'] / $data['total_sks'] : 0;
                            $ipsColor = $ips >= 3.0 ? '#10b981' : ($ips >= 2.0 ? '#f59e0b' : '#ef4444');
                            ?>
                            <span style="color: <?= $ipsColor ?>; font-size: 16px;">
                                <?= number_format($ips, 2) ?>
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Transkrip Nilai Lengkap -->
<?php if (!empty($transkrip)): ?>
<div class="content-box" style="margin-top: 20px;">
    <h3 style="margin-bottom: 20px; color: #031C30;">📝 Transkrip Nilai Lengkap</h3>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Kode MK</th>
                    <th width="30%">Nama Mata Kuliah</th>
                    <th width="8%">Semester</th>
                    <th width="8%">SKS</th>
                    <th width="12%">Tipe</th>
                    <th width="10%">Nilai</th>
                    <th width="10%">Huruf</th>
                    <th width="15%">Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = isset($transkrip_page) ? (($transkrip_page - 1) * 10 + 1) : 1;
                foreach ($transkrip as $row): 
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= htmlspecialchars($row['kode_mk']) ?></strong></td>
                    <td><?= htmlspecialchars($row['nama_mk']) ?></td>
                    <td style="text-align: center;"><?= $row['semester_mk'] ?></td>
                    <td style="text-align: center;"><?= $row['sks'] ?></td>
                    <td>
                        <?php
                        $tipeBadge = 'badge-secondary';
                        if ($row['tipe_nilai'] == 'UAS') $tipeBadge = 'badge-danger';
                        elseif ($row['tipe_nilai'] == 'UTS') $tipeBadge = 'badge-warning';
                        elseif ($row['tipe_nilai'] == 'Tugas') $tipeBadge = 'badge-info';
                        ?>
                        <span class="badge <?= $tipeBadge ?>"><?= htmlspecialchars($row['tipe_nilai']) ?></span>
                    </td>
                    <td style="text-align: center;"><?= $row['nilai_angka'] ?></td>
                    <td style="text-align: center;">
                        <?php
                        $badgeColor = 'badge-secondary';
                        if ($row['nilai_huruf'] == 'A') $badgeColor = 'badge-success';
                        elseif ($row['nilai_huruf'] == 'B') $badgeColor = 'badge-info';
                        elseif ($row['nilai_huruf'] == 'C') $badgeColor = 'badge-warning';
                        elseif ($row['nilai_huruf'] == 'D') $badgeColor = 'badge-danger';
                        elseif ($row['nilai_huruf'] == 'E') $badgeColor = 'badge-dark';
                        ?>
                        <span class="badge <?= $badgeColor ?>"><?= $row['nilai_huruf'] ?></span>
                    </td>
                    <td><?= date('d M Y', strtotime($row['tanggal_input'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php
    if (isset($transkrip_total_pages) && $transkrip_total_pages > 1):
        $current_transkrip_page = isset($_GET['transkrip_page']) ? (int)$_GET['transkrip_page'] : 1;
        $detail_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        echo '<div class="pagination" style="margin-top: 20px;">';
        
        if ($current_transkrip_page > 1) {
            $first_params = ['action' => 'detail', 'id' => $detail_id, 'transkrip_page' => 1];
            echo '<a href="index.php?' . http_build_query($first_params) . '" class="pagination-btn">« Pertama</a>';
        }
        
        if ($current_transkrip_page > 1) {
            $prev_params = ['action' => 'detail', 'id' => $detail_id, 'transkrip_page' => $current_transkrip_page - 1];
            echo '<a href="index.php?' . http_build_query($prev_params) . '" class="pagination-btn">‹ Sebelumnya</a>';
        }
        
        $start_page = max(1, $current_transkrip_page - 2);
        $end_page = min($transkrip_total_pages, $current_transkrip_page + 2);
        for ($i = $start_page; $i <= $end_page; $i++) {
            $page_params = ['action' => 'detail', 'id' => $detail_id, 'transkrip_page' => $i];
            $active_class = ($i == $current_transkrip_page) ? 'active' : '';
            echo '<a href="index.php?' . http_build_query($page_params) . '" class="pagination-btn ' . $active_class . '">' . $i . '</a>';
        }
        
        if ($current_transkrip_page < $transkrip_total_pages) {
            $next_params = ['action' => 'detail', 'id' => $detail_id, 'transkrip_page' => $current_transkrip_page + 1];
            echo '<a href="index.php?' . http_build_query($next_params) . '" class="pagination-btn">Selanjutnya ›</a>';
        }
        
        if ($current_transkrip_page < $transkrip_total_pages) {
            $last_params = ['action' => 'detail', 'id' => $detail_id, 'transkrip_page' => $transkrip_total_pages];
            echo '<a href="index.php?' . http_build_query($last_params) . '" class="pagination-btn">Terakhir »</a>';
        }
        
        echo '</div>';
        echo '<div class="pagination-info">Menampilkan halaman ' . $current_transkrip_page . ' dari ' . $transkrip_total_pages . ' (Total: ' . $transkrip_total . ' data)</div>';
    endif;
    ?>
</div>
<?php else: ?>
<div class="content-box" style="margin-top: 20px;">
    <div class="empty-state">
        <h3>Belum ada data nilai</h3>
        <p>Mahasiswa ini belum memiliki nilai yang terinput</p>
    </div>
</div>
<?php endif; ?>

<?php include 'views/footer.php'; ?>