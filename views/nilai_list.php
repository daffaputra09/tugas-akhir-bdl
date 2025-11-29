<?php
$page_title = "Data Nilai";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data nilai berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data nilai berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data nilai berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'refreshed'): ?>
        <div class="alert alert-info">Data nilai berhasil diperbarui (Refresh View)!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data nilai!</div>
    <?php elseif ($_GET['message'] == 'refresh_error'): ?>
        <div class="alert alert-error">Gagal refresh data nilai!</div>
    <?php elseif ($_GET['message'] == 'search_error'): ?>
        <div class="alert alert-error">Data nilai tidak ditemukan!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Nilai</h2>
        <div class="btn-group">
            <a href="index.php?action=nilai_refresh" class="btn btn-success" style="margin-right: 5px;">
                Refresh Data
            </a>
            <a href="index.php?action=nilai_create" class="btn btn-primary">Tambah Nilai</a>
        </div>
    </div>

    <div class="search-box" style="margin-bottom: 20px;">
        <form method="GET" action="index.php" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%;">
            <input type="hidden" name="action" value="nilai_search">

            <input type="text" name="keyword" class="search-input" placeholder="Cari berdasarkan NIM, Nama Mahasiswa, Kode MK, atau Nama MK..." style="flex: 2;"
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">

            <select name="filter_tipe" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Semua Tipe --</option>
                <?php foreach ($listTipe as $tipe): ?>
                    <option value="<?php echo $tipe; ?>"
                        <?php echo (isset($_GET['filter_tipe']) && $_GET['filter_tipe'] == $tipe) ? 'selected' : ''; ?>>
                        <?php echo $tipe; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_jurusan" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Semua Jurusan --</option>
                <?php foreach ($listJurusan as $jurusan): ?>
                    <option value="<?php echo $jurusan['id_jurusan_mk']; ?>"
                        <?php echo (isset($_GET['filter_jurusan']) && $_GET['filter_jurusan'] == $jurusan['id_jurusan_mk']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($jurusan['nama_jurusan_mk']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="filter_semester" class="form-control" style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">-- Semua Semester --</option>
                <?php foreach ($listSemester as $semester): ?>
                    <option value="<?php echo $semester; ?>"
                        <?php echo (isset($_GET['filter_semester']) && $_GET['filter_semester'] == $semester) ? 'selected' : ''; ?>>
                        Semester <?php echo $semester; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 8px 15px; cursor: pointer;">Filter</button>

            <?php if (isset($_GET['keyword']) || isset($_GET['filter_tipe']) || isset($_GET['filter_jurusan']) || isset($_GET['filter_semester'])): ?>
                <a href="index.php?action=nilai_list" class="btn btn-warning" style="padding: 8px 15px; text-decoration:none; line-height:1.2;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($nilai->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Jurusan</th>
                        <th>Semester</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Tipe Nilai</th>
                        <th>Tanggal Input</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $no = isset($page) ? (($page - 1) * 10 + 1) : 1;
                    while ($row = $nilai->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></strong><br>
                                <small>NIM: <?php echo htmlspecialchars($row['nim']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nama_mk']); ?></strong><br>
                                <small><?php echo htmlspecialchars($row['kode_mk']); ?> (<?php echo htmlspecialchars($row['sks']); ?> SKS)</small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['nama_jurusan_mk'] ?? '-'); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['semester_mk'] ?? '-'); ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nilai_angka']); ?></strong>
                            </td>
                            <td>
                                <span class="badge" style="background-color: <?php 
                                    echo $row['nilai_huruf'] == 'A' ? '#10b981' : 
                                        ($row['nilai_huruf'] == 'B' ? '#3b82f6' : 
                                        ($row['nilai_huruf'] == 'C' ? '#f59e0b' : 
                                        ($row['nilai_huruf'] == 'D' ? '#ef4444' : '#dc2626'))); 
                                    ?>; color: white; padding: 4px 8px; border-radius: 3px;">
                                    <?php echo htmlspecialchars($row['nilai_huruf']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['tipe_nilai']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal_input'])); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=nilai_edit&id=<?php echo $row['id_nilai']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=nilai_delete&id=<?php echo $row['id_nilai']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">Hapus</a>
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
            $action = isset($_GET['action']) ? $_GET['action'] : 'nilai_list';
            $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
            $filter_tipe = isset($_GET['filter_tipe']) ? htmlspecialchars($_GET['filter_tipe']) : '';
            $filter_jurusan = isset($_GET['filter_jurusan']) ? htmlspecialchars($_GET['filter_jurusan']) : '';
            $filter_semester = isset($_GET['filter_semester']) ? htmlspecialchars($_GET['filter_semester']) : '';
            
            echo '<div class="pagination">';

            if ($current_page > 1) {
                $first_params = ['action' => $action, 'page' => 1];
                if (!empty($keyword)) $first_params['keyword'] = $keyword;
                if (!empty($filter_tipe)) $first_params['filter_tipe'] = $filter_tipe;
                if (!empty($filter_jurusan)) $first_params['filter_jurusan'] = $filter_jurusan;
                if (!empty($filter_semester)) $first_params['filter_semester'] = $filter_semester;
                echo '<a href="index.php?' . http_build_query($first_params) . '" class="pagination-btn">« Pertama</a>';
            }

            if ($current_page > 1) {
                $prev_params = ['action' => $action, 'page' => $current_page - 1];
                if (!empty($keyword)) $prev_params['keyword'] = $keyword;
                if (!empty($filter_tipe)) $prev_params['filter_tipe'] = $filter_tipe;
                if (!empty($filter_jurusan)) $prev_params['filter_jurusan'] = $filter_jurusan;
                if (!empty($filter_semester)) $prev_params['filter_semester'] = $filter_semester;
                echo '<a href="index.php?' . http_build_query($prev_params) . '" class="pagination-btn">‹ Sebelumnya</a>';
            }
            
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            for ($i = $start_page; $i <= $end_page; $i++) {
                $page_params = ['action' => $action, 'page' => $i];
                if (!empty($keyword)) $page_params['keyword'] = $keyword;
                if (!empty($filter_tipe)) $page_params['filter_tipe'] = $filter_tipe;
                if (!empty($filter_jurusan)) $page_params['filter_jurusan'] = $filter_jurusan;
                if (!empty($filter_semester)) $page_params['filter_semester'] = $filter_semester;
                $active_class = ($i == $current_page) ? 'active' : '';
                echo '<a href="index.php?' . http_build_query($page_params) . '" class="pagination-btn ' . $active_class . '">' . $i . '</a>';
            }

            if ($current_page < $total_pages) {
                $next_params = ['action' => $action, 'page' => $current_page + 1];
                if (!empty($keyword)) $next_params['keyword'] = $keyword;
                if (!empty($filter_tipe)) $next_params['filter_tipe'] = $filter_tipe;
                if (!empty($filter_jurusan)) $next_params['filter_jurusan'] = $filter_jurusan;
                if (!empty($filter_semester)) $next_params['filter_semester'] = $filter_semester;
                echo '<a href="index.php?' . http_build_query($next_params) . '" class="pagination-btn">Selanjutnya ›</a>';
            }

            if ($current_page < $total_pages) {
                $last_params = ['action' => $action, 'page' => $total_pages];
                if (!empty($keyword)) $last_params['keyword'] = $keyword;
                if (!empty($filter_tipe)) $last_params['filter_tipe'] = $filter_tipe;
                if (!empty($filter_jurusan)) $last_params['filter_jurusan'] = $filter_jurusan;
                if (!empty($filter_semester)) $last_params['filter_semester'] = $filter_semester;
                echo '<a href="index.php?' . http_build_query($last_params) . '" class="pagination-btn">Terakhir »</a>';
            }
            echo '</div>';
            echo '<div class="pagination-info">Menampilkan halaman ' . $current_page . ' dari ' . $total_pages . ' (Total: ' . $total_records . ' data)</div>';
        endif; 
        ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data nilai</h3>
            <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>
