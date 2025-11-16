<?php
$page_title = isset($kelas) ? "Edit Kelas" : "Tambah Kelas";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($kelas) ? 'Edit Data Kelas' : 'Tambah Data Kelas'; ?></h2>
        <a href="index.php?action=kelas_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_kelas" class="form-label">Nama Kelas *</label>
                <input type="text" id="nama_kelas" name="nama_kelas" class="form-input" placeholder="Contoh: TI 1A"
                    value="<?php echo isset($kelas) ? htmlspecialchars($kelas['nama_kelas']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="id_jurusan" class="form-label">Kelas *</label>
                <select id="id_jurusan" name="id_jurusan" class="form-input" required>
                    <option value="" selected disabled>-- Pilih Kelas --</option>
                    <?php if ($jurusan_list->rowCount() > 0): ?>
                        <?php while ($j = $jurusan_list->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php
                            $selected = isset($kelas) && $kelas['id_jurusan'] == $j['id_jurusan'] ? 'selected' : '';
                            ?>
                            <option value="<?= $j['id_jurusan'] ?>" <?= $selected ?>>
                                <?= htmlspecialchars($j['nama_jurusan']) ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>

                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo isset($kelas) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=kelas_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>