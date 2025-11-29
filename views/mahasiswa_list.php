<?php
$page_title = "Data Mahasiswa";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">
            Data mahasiswa berhasil ditambahkan!
        </div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">
            Data mahasiswa berhasil diupdate!
        </div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">
            Data mahasiswa berhasil dihapus!
        </div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">
            Gagal menghapus data mahasiswa!
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Mahasiswa</h2>
        <a href="index.php?action=create" class="btn btn-primary">Tambah Mahasiswa</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="search">
            <input type="text" name="keyword" class="search-input"
                placeholder="Cari berdasarkan NIM, Nama atau Email..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>
    </div>

    <?php if ($mahasiswa->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%">Foto</th>
                        <th width="15%">NIM</th>
                        <th width="25%">Nama Mahasiswa</th>
                        <th width="20%">Jurusan & Kelas</th>
                        <th width="10%">L/P</th>
                        <th width="10%">IPK</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = isset($page) ? (($page - 1) * 10 + 1) : 1;
                    while ($row = $mahasiswa->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if (!empty($row['foto']) && file_exists($row['foto'])): ?>
                                    <img src="<?php echo htmlspecialchars($row['foto']); ?>"
                                        alt="Foto"
                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd;">
                                <?php else: ?>
                                    <div style="width: 40px; height: 40px; background-color: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #aaa; font-size: 10px;">
                                        No IMG
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['nim']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama_mahasiswa']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($row['nama_jurusan'] ?? '-'); ?></div>
                                <small style="color: #666; font-size: 12px;"><?php echo htmlspecialchars($row['nama_kelas'] ?? '-'); ?></small>
                            </td>
                            <td>
                                <?php if ($row['jenis_kelamin'] == 'L'): ?>
                                    <span class="badge badge-male">L</span>
                                <?php else: ?>
                                    <span class="badge badge-female">P</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $ipk = $row['ipk'] ?? 0;
                                $badgeColor = $ipk >= 3.00 ? 'badge-success' : ($ipk >= 2.00 ? 'badge-warning' : 'badge-danger');
                                ?>
                                <span class="badge <?= $badgeColor ?>"><?= number_format($ipk, 2) ?></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=detail&id=<?php echo $row['id_mahasiswa']; ?>"
                                        class="btn btn-view" title="Lihat Detail">Detail</a>

                                    <a href="index.php?action=edit&id=<?php echo $row['id_mahasiswa']; ?>"
                                        class="btn btn-edit" title="Edit">Edit</a>

                                    <a href="index.php?action=delete&id=<?php echo $row['id_mahasiswa']; ?>"
                                        class="btn btn-delete"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')" title="Hapus">Hapus</a>
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
            $action = isset($_GET['action']) ? $_GET['action'] : 'list';
            $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';

            echo '<div class="pagination">';

            if ($current_page > 1) {
                $first_params = ['action' => $action, 'page' => 1];
                if (!empty($keyword)) $first_params['keyword'] = $keyword;
                echo '<a href="index.php?' . http_build_query($first_params) . '" class="pagination-btn">« Pertama</a>';
            }

            if ($current_page > 1) {
                $prev_params = ['action' => $action, 'page' => $current_page - 1];
                if (!empty($keyword)) $prev_params['keyword'] = $keyword;
                echo '<a href="index.php?' . http_build_query($prev_params) . '" class="pagination-btn">‹ Sebelumnya</a>';
            }

            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            for ($i = $start_page; $i <= $end_page; $i++) {
                $page_params = ['action' => $action, 'page' => $i];
                if (!empty($keyword)) $page_params['keyword'] = $keyword;
                $active_class = ($i == $current_page) ? 'active' : '';
                echo '<a href="index.php?' . http_build_query($page_params) . '" class="pagination-btn ' . $active_class . '">' . $i . '</a>';
            }

            if ($current_page < $total_pages) {
                $next_params = ['action' => $action, 'page' => $current_page + 1];
                if (!empty($keyword)) $next_params['keyword'] = $keyword;
                echo '<a href="index.php?' . http_build_query($next_params) . '" class="pagination-btn">Selanjutnya ›</a>';
            }

            if ($current_page < $total_pages) {
                $last_params = ['action' => $action, 'page' => $total_pages];
                if (!empty($keyword)) $last_params['keyword'] = $keyword;
                echo '<a href="index.php?' . http_build_query($last_params) . '" class="pagination-btn">Terakhir »</a>';
            }
            echo '</div>';
            echo '<div class="pagination-info">Menampilkan halaman ' . $current_page . ' dari ' . $total_pages . ' (Total: ' . (isset($total_records) ? $total_records : '0') . ' data)</div>';
        endif;
        ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data mahasiswa</h3>
            <p>Silakan tambahkan data mahasiswa baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>