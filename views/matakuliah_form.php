<?php
$page_title = isset($matakuliah) ? "Edit Matakuliah" : "Tambah Matakuliah";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($matakuliah) ? 'Edit Data Matakuliah' : 'Tambah Data Matakuliah'; ?></h2>
        <a href="index.php?action=matakuliah_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="kode_mk" class="form-label">Kode Matakuliah *</label>
                <input type="text" id="kode_mk" name="kode_mk" class="form-input" placeholder="Contoh: SI101"
                    value="<?php echo isset($matakuliah) ? htmlspecialchars($matakuliah['kode_mk']) : ''; ?>" required maxlength="10">
            </div>

            <div class="form-group">
                <label for="nama_mk" class="form-label">Nama Matakuliah *</label>
                <input type="text" id="nama_mk" name="nama_mk" class="form-input" placeholder="Contoh: Algoritma dan Struktur Data"
                    value="<?php echo isset($matakuliah) ? htmlspecialchars($matakuliah['nama_mk']) : ''; ?>" required maxlength="100">
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="sks" class="form-label">SKS *</label>
                <input type="number" id="sks" name="sks" class="form-input" placeholder="Contoh: 3"
                    value="<?php echo isset($matakuliah) ? $matakuliah['sks'] : ''; ?>" required min="1" max="6">
            </div>

            <div class="form-group">
                <label for="semester" class="form-label">Semester *</label>
                <input type="number" id="semester" name="semester" class="form-input" placeholder="Contoh: 1"
                    value="<?php echo isset($matakuliah) ? $matakuliah['semester'] : ''; ?>" required min="1" max="14">
            </div>
        </div>

        <div class="form-group">
            <label for="id_jurusan" class="form-label">Jurusan</label>
            <select id="id_jurusan" name="id_jurusan" class="form-select">
                <option value="">-- Pilih Jurusan --</option>
                <?php while ($jurusan = $jurusan_list->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?php echo $jurusan['id_jurusan']; ?>"
                            <?php echo (isset($matakuliah) && $matakuliah['id_jurusan'] == $jurusan['id_jurusan']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo isset($matakuliah) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=matakuliah_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>

