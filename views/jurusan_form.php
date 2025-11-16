<?php
$page_title = isset($jurusan) ? "Edit Jurusan" : "Tambah Jurusan";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($jurusan) ? 'Edit Data Jurusan' : 'Tambah Data Jurusan'; ?></h2>
        <a href="index.php?action=jurusan_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="nama_jurusan" class="form-label">Nama Jurusan *</label>
                <input type="text" id="nama_jurusan" name="nama_jurusan" class="form-input" placeholder="Contoh: Sistem Informasi dan Bisnis"
                    value="<?php echo isset($jurusan) ? htmlspecialchars($jurusan['nama_jurusan']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="akreditasi" class="form-label">Akreditasi *</label>
                <?php
                $akreditasi_options = ['A', 'B', 'C', 'D', 'E'];
                echo '<select id="akreditasi" name="akreditasi" class="form-input" required>';
                echo '<option value="" selected disabled>-- Pilih Akreditasi --</option>';
                foreach ($akreditasi_options as $option) {
                    $selected = isset($jurusan) && $jurusan['akreditasi'] === $option ? 'selected' : '';
                    echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                }
                echo '</select>';
                ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo isset($jurusan) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=jurusan_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>