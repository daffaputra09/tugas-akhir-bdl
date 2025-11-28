<?php
$page_title = "Data Jurusan";
include 'views/header.php';
?>

<?php if (isset($_GET['message'])): ?>
    <?php if ($_GET['message'] == 'created'): ?>
        <div class="alert alert-success">Data jurusan berhasil ditambahkan!</div>
    <?php elseif ($_GET['message'] == 'updated'): ?>
        <div class="alert alert-success">Data jurusan berhasil diupdate!</div>
    <?php elseif ($_GET['message'] == 'deleted'): ?>
        <div class="alert alert-success">Data jurusan berhasil dihapus!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data jurusan!</div>
    <?php elseif ($_GET['message'] == 'search_error'): ?>
        <div class="alert alert-error">Jurusan tidak ditemukan!</div>
    <?php elseif ($_GET['message'] == 'delete_error'): ?>
        <div class="alert alert-error">Gagal menghapus data jurusan!</div>
    <?php elseif ($_GET['message'] == 'not_found'): ?>
        <div class="alert alert-error">Data yang Anda cari tidak ada di database.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="content-box">
    <div class="content-header">
        <h2>Daftar Jurusan</h2>
        <a href="index.php?action=jurusan_create" class="btn btn-primary">Tambah Jurusan</a>
    </div>

    <div class="search-box">
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="jurusan_search">
            <input type="text" name="keyword" class="search-input" placeholder="Cari Nama Jurusan..."
                value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>
    </div>

    <?php if ($jurusan->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Akreditasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = $jurusan->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nama_jurusan']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['akreditasi']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=jurusan_edit&id=<?php echo $row['id_jurusan']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=jurusan_delete&id=<?php echo $row['id_jurusan']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <h3>Tidak ada data dosen</h3>
            <p>Silakan tambahkan data dosen baru</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'views/footer.php'; ?>