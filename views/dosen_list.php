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
        <form method="GET" action="index.php">
            <input type="hidden" name="action" value="dosen_search">
            <input type="text" name="keyword" class="search-input" placeholder="Cari NIP, Nama, atau Email..."
                   value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
        </form>
    </div>

    <?php if ($dosen->rowCount() > 0): ?>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama Dosen</th>
                        <th>Jurusan</th>
                        <th>Email</th>
                        <th>No HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $dosen->fetch(PDO::FETCH_ASSOC)): 
                    ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['nip']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['nama_dosen']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_jurusan'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['email'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
                            <td><?php echo htmlspecialchars($row['status_aktif']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="index.php?action=dosen_edit&id=<?php echo $row['id_dosen']; ?>" class="btn btn-edit">Edit</a>
                                    <a href="index.php?action=dosen_delete&id=<?php echo $row['id_dosen']; ?>" class="btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')">Hapus</a>
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


