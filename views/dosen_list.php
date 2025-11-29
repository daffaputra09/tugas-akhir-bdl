<?php
$page_title = "Data Dosen";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data dosen berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data dosen berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data dosen berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data dosen!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Dosen</h2>
        <a href="index.php?action=dosen_create" class="btn btn-primary">Tambah Dosen</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php" style="display: flex; gap: 20px; width: 100%;">

            <input type="hidden" name="action" value="dosen_search">

            <input type="text" name="keyword" class="search-input" placeholder="Cari NIP, Nama..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>"
                style="flex: 1; padding: 10px;">

            <select name="status" class="form-control" style="width: 200px; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">
                <option value="">-- Semua Status --</option>
                <?php
                $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
                $opsi_status = ['Aktif', 'Tidak Aktif'];

                foreach ($opsi_status as $st) {
                    $selected = ($status_filter == $st) ? 'selected' : '';
                    echo "<option value='$st' $selected>$st</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">Cari</button>

            <?php if (isset($_GET['keyword']) || isset($_GET['status'])): ?>
                <a href="index.php?action=dosen_list" class="btn btn-secondary" style="background:#6c757d; color:white; padding:10px 20px; text-decoration:none; border-radius:4px; display: flex; align-items: center;">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($dosen->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">NIP</th>
                        <th width="20%">Nama Dosen</th>
                        <th width="15%">Jurusan</th>
                        <th width="15%">Email</th>
                        <th width="10%">No HP</th>
                        <th width="10%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = isset($page) ? (($page - 1) * 10 + 1) : 1;
                    while ($row = $dosen->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nip']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_jurusan'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>

                            <td>
                                <?php
                                $status_bersih = strtolower(trim($row['status_aktif']));

                                $next_status = ($status_bersih == 'aktif') ? 'Tidak Aktif' : 'Aktif';

                                if ($status_bersih == 'aktif') {
                                    $badgeClass = 'badge-success';
                                    $confirmMsg = "Non-aktifkan dosen ini?";
                                } else {
                                    $badgeClass = 'badge-secondary';
                                    $confirmMsg = "Aktifkan kembali dosen ini?";
                                }
                                ?>

                                <a href="index.php?action=dosen_toggle_status&id=<?php echo $row['id_dosen']; ?>&status=<?php echo $next_status; ?>"
                                    style="text-decoration: none;"
                                    onclick="return confirm('<?php echo $confirmMsg; ?>')">

                                    <span class="badge <?php echo $badgeClass; ?>" style="cursor: pointer;" title="Klik untuk ubah status">
                                        <?php echo htmlspecialchars($row['status_aktif']); ?>
                                    </span>
                                </a>
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=dosen_jadwal&id=<?php echo $row['id_dosen']; ?>" class="btn btn-primary" title="Lihat Jadwal">Jadwal</a>
                                    <a href="index.php?action=dosen_edit&id=<?php echo $row['id_dosen']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=dosen_delete&id=<?php echo $row['id_dosen']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')">Hapus</a>
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
            $action = isset($_GET['action']) ? $_GET['action'] : 'dosen_list';

            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $status = isset($_GET['status']) ? $_GET['status'] : '';

            echo '<div class="pagination">';

            $buildLink = function ($pg) use ($action, $keyword, $status) {
                $params = ['action' => $action, 'page' => $pg];
                if (!empty($keyword)) $params['keyword'] = $keyword;
                if (!empty($status)) $params['status'] = $status;
                return 'index.php?' . http_build_query($params);
            };

            if ($current_page > 1) {
                echo '<a href="' . $buildLink(1) . '" class="pagination-btn">« Pertama</a>';
                echo '<a href="' . $buildLink($current_page - 1) . '" class="pagination-btn">‹ Sebelumnya</a>';
            }

            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            for ($i = $start_page; $i <= $end_page; $i++) {
                $active_class = ($i == $current_page) ? 'active' : '';
                echo '<a href="' . $buildLink($i) . '" class="pagination-btn ' . $active_class . '">' . $i . '</a>';
            }

            if ($current_page < $total_pages) {
                echo '<a href="' . $buildLink($current_page + 1) . '" class="pagination-btn">Selanjutnya ›</a>';
                echo '<a href="' . $buildLink($total_pages) . '" class="pagination-btn">Terakhir »</a>';
            }
            echo '</div>';
            echo '<div class="pagination-info">Menampilkan halaman ' . $current_page . ' dari ' . $total_pages . ' (Total: ' . $total_records . ' data)</div>';
        endif;
        ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data dosen</h3>
            <p>Silakan tambahkan data dosen baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>