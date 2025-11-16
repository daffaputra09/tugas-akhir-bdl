<?php
$page_title = isset($dosen) ? "Edit Dosen" : "Tambah Dosen";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($dosen) ? 'Edit Data Dosen' : 'Tambah Data Dosen'; ?></h2>
        <a href="index.php?action=dosen_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="nip" class="form-label">NIP *</label>
                <input type="text" id="nip" name="nip" class="form-input" placeholder="Contoh: 19801231 200501 1 001"
                       value="<?php echo isset($dosen) ? htmlspecialchars($dosen['nip']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="nama_dosen" class="form-label">Nama Dosen *</label>
                <input type="text" id="nama_dosen" name="nama_dosen" class="form-input" placeholder="Nama lengkap"
                       value="<?php echo isset($dosen) ? htmlspecialchars($dosen['nama_dosen']) : ''; ?>" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="email@kampus.ac.id"
                       value="<?php echo isset($dosen) ? htmlspecialchars($dosen['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="no_hp" class="form-label">No HP *</label>
                <input type="text" id="no_hp" name="no_hp" class="form-input" placeholder="081234567890"
                       value="<?php echo isset($dosen) ? htmlspecialchars($dosen['no_hp']) : ''; ?>" required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="id_jurusan" class="form-label">Jurusan</label>
                <select id="id_jurusan" name="id_jurusan" class="form-select">
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($jurusan = $jurusan_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $jurusan['id_jurusan']; ?>"
                            <?php echo (isset($dosen) && $dosen['id_jurusan'] == $jurusan['id_jurusan']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="status_aktif" class="form-label">Status</label>
                <select id="status_aktif" name="status_aktif" class="form-select">
                    <?php
                        $status = isset($dosen) ? $dosen['status_aktif'] : 'Aktif';
                    ?>
                    <option value="Aktif" <?php echo ($status == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Non Aktif" <?php echo ($status == 'Non Aktif') ? 'selected' : ''; ?>>Tidak  Aktif</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo isset($dosen) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=dosen_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>


