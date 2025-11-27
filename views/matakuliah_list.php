<?php
$page_title = "Data Matakuliah";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data matakuliah berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data matakuliah berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data matakuliah berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data matakuliah!</div>
    <?php elseif ($_GET['message'] == 'fk_error'): ?>
        <div class="alert alert-error">Tidak dapat menghapus matakuliah karena masih digunakan di data lain!</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Matakuliah</h2>
        <a href="index.php?action=matakuliah_create" class="btn btn-primary">Tambah Matakuliah</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="matakuliah_search">
            <input type="text" name="keyword" class="search-input" placeholder="Cari berdasarkan Kode, Nama, atau Jurusan..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>
    </div>

    <?php if ($matakuliah->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Nama Matakuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $matakuliah->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['kode_mk']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                            <td><?php echo htmlspecialchars($row['sks']); ?></td>
                            <td><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_jurusan'] ?? '-'); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=matakuliah_edit&id=<?php echo $row['id_mk']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=matakuliah_delete&id=<?php echo $row['id_mk']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus matakuliah ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data matakuliah</h3>
            <p>Silakan tambahkan data matakuliah baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>

