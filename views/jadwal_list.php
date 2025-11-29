<?php
$page_title = "Data Jadwal Kuliah";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data jurusan berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data jurusan berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data jurusan berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'refreshed'): ?>
        <div class="alert alert-info">Data jadwal berhasil diperbarui (Refresh View)!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data jurusan!</div>
    <?php elseif ($_GET['message'] == 'search_error'): ?>
        <div class="alert alert-error">Jurusan tidak ditemukan!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data jurusan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data jadwal berhasil diupdate!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Jadwal Kuliah</h2>
        <div class="btn-group">
            <a href="index.php?action=jadwal_refresh" class="btn btn-success" style="margin-right: 5px;">
                Refresh Data
            </a>

            <a href="index.php?action=jadwal_create" class="btn btn-primary">Tambah Jadwal</a>
        </div>
    </div>

    <div class="search-box" style="margin-bottom: 20px;">
        <form method="GET" action="index.php" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%;">
            <input type="hidden" name="action" value="jadwal_search">

            <input type="text" name="keyword" class="search-input" placeholder="Cari MK, Dosen, atau Kelas..." style="flex: 2;"
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">

            <select name="filter_hari" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Semua Hari --</option>
                <?php foreach ($listHari as $hari): ?>
                    <option value="<?php echo $hari; ?>"
                        <?php echo (isset($_GET['filter_hari']) && $_GET['filter_hari'] == $hari) ? 'selected' : ''; ?>>
                        <?php echo $hari; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_tahun" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Semua Tahun --</option>
                <?php foreach ($listTahun as $tahun): ?>
                    <option value="<?php echo $tahun; ?>"
                        <?php echo (isset($_GET['filter_tahun']) && $_GET['filter_tahun'] == $tahun) ? 'selected' : ''; ?>>
                        <?php echo $tahun; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 8px 15px; cursor: pointer;">Filter</button>

            <?php if (isset($_GET['keyword']) || isset($_GET['filter_hari']) || isset($_GET['filter_tahun'])): ?>
                <a href="index.php?action=jadwal_list" class="btn btn-warning" style="padding: 8px 15px; text-decoration:none; line-height:1.2;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($jadwal->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu & TA</th>
                        <th>Mata Kuliah (SKS)</th>
                        <th>Kelas / Ruang</th>
                        <th>Dosen Pengampu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no = isset($page) ? (($page - 1) * 10 + 1) : 1;
                    while ($row = $jadwal->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>

                            <td>
                                <strong><?php echo htmlspecialchars($row['hari']); ?></strong><br>
                                <small>
                                    <?php
                                    echo substr($row['jam_mulai'], 0, 5) . ' - ' . substr($row['jam_selesai'], 0, 5);
                                    ?>
                                </small><br>
                                <span class="badge-ta" style="font-size: 0.8em; color: #666;">
                                    <?php echo htmlspecialchars($row['tahun_akademik']); ?>
                                </span>
                            </td>

                            <td>
                                <strong><?php echo htmlspecialchars($row['nama_mk']); ?></strong>
                                <br>
                                <small>(<?php echo htmlspecialchars($row['sks']); ?> SKS)</small>
                            </td>

                            <td>
                                Kelas: <strong><?php echo htmlspecialchars($row['nama_kelas']); ?></strong>
                                <br>
                                Ruang: <?php echo htmlspecialchars($row['ruangan']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['nama_dosen']); ?>
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=jadwal_edit&id=<?php echo $row['id_jadwal']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=jadwal_delete&id=<?php echo $row['id_jadwal']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal kuliah ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <?php 
        if (isset($total_pages) && $total_pages > 1): 
            $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $action = isset($_GET['action']) ? $_GET['action'] : 'jadwal_list';
            $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
            $filter_hari = isset($_GET['filter_hari']) ? htmlspecialchars($_GET['filter_hari']) : '';
            $filter_tahun = isset($_GET['filter_tahun']) ? htmlspecialchars($_GET['filter_tahun']) : '';
            
            echo '<div class="pagination">';

            if ($current_page > 1) {
                $first_params = ['action' => $action, 'page' => 1];
                if (!empty($keyword)) $first_params['keyword'] = $keyword;
                if (!empty($filter_hari)) $first_params['filter_hari'] = $filter_hari;
                if (!empty($filter_tahun)) $first_params['filter_tahun'] = $filter_tahun;
                echo '<a href="index.php?' . http_build_query($first_params) . '" class="pagination-btn">« Pertama</a>';
            }

            if ($current_page > 1) {
                $prev_params = ['action' => $action, 'page' => $current_page - 1];
                if (!empty($keyword)) $prev_params['keyword'] = $keyword;
                if (!empty($filter_hari)) $prev_params['filter_hari'] = $filter_hari;
                if (!empty($filter_tahun)) $prev_params['filter_tahun'] = $filter_tahun;
                echo '<a href="index.php?' . http_build_query($prev_params) . '" class="pagination-btn">‹ Sebelumnya</a>';
            }
            
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            for ($i = $start_page; $i <= $end_page; $i++) {
                $page_params = ['action' => $action, 'page' => $i];
                if (!empty($keyword)) $page_params['keyword'] = $keyword;
                if (!empty($filter_hari)) $page_params['filter_hari'] = $filter_hari;
                if (!empty($filter_tahun)) $page_params['filter_tahun'] = $filter_tahun;
                $active_class = ($i == $current_page) ? 'active' : '';
                echo '<a href="index.php?' . http_build_query($page_params) . '" class="pagination-btn ' . $active_class . '">' . $i . '</a>';
            }

            if ($current_page < $total_pages) {
                $next_params = ['action' => $action, 'page' => $current_page + 1];
                if (!empty($keyword)) $next_params['keyword'] = $keyword;
                if (!empty($filter_hari)) $next_params['filter_hari'] = $filter_hari;
                if (!empty($filter_tahun)) $next_params['filter_tahun'] = $filter_tahun;
                echo '<a href="index.php?' . http_build_query($next_params) . '" class="pagination-btn">Selanjutnya ›</a>';
            }

            if ($current_page < $total_pages) {
                $last_params = ['action' => $action, 'page' => $total_pages];
                if (!empty($keyword)) $last_params['keyword'] = $keyword;
                if (!empty($filter_hari)) $last_params['filter_hari'] = $filter_hari;
                if (!empty($filter_tahun)) $last_params['filter_tahun'] = $filter_tahun;
                echo '<a href="index.php?' . http_build_query($last_params) . '" class="pagination-btn">Terakhir »</a>';
            }
            echo '</div>';
            echo '<div class="pagination-info">Menampilkan halaman ' . $current_page . ' dari ' . $total_pages . ' (Total: ' . $total_records . ' data)</div>';
        endif; 
        ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data jadwal</h3>
            <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>