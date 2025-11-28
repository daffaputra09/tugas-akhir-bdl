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
    <?php elseif ($_GET['message'] == 'not_found'): ?>
        <div class="alert alert-error">Data yang Anda cari tidak ada di database.</div>
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
                    $no = 1;
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
                                                                                echo $row['nilai_huruf'] == 'A' ? '#10b981' : ($row['nilai_huruf'] == 'B' ? '#3b82f6' : ($row['nilai_huruf'] == 'C' ? '#f59e0b' : ($row['nilai_huruf'] == 'D' ? '#ef4444' : '#dc2626')));
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
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data nilai</h3>
            <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>