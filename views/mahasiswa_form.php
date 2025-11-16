<?php
$page_title = isset($mahasiswa) ? "Edit Mahasiswa" : "Tambah Mahasiswa";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($mahasiswa) ? 'Edit Data Mahasiswa' : 'Tambah Data Mahasiswa'; ?></h2>
        <a href="index.php?action=list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            <div class="form-group">
                <label for="nim" class="form-label">NIM *</label>
                <input type="text" 
                       id="nim" 
                       name="nim" 
                       class="form-input" 
                       placeholder="Contoh: 2021010001"
                       value="<?php echo isset($mahasiswa) ? htmlspecialchars($mahasiswa['nim']) : ''; ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="nama_mahasiswa" class="form-label">Nama Lengkap *</label>
                <input type="text" 
                       id="nama_mahasiswa" 
                       name="nama_mahasiswa" 
                       class="form-input"
                       placeholder="Contoh: Ahmad Suryadi"
                       value="<?php echo isset($mahasiswa) ? htmlspecialchars($mahasiswa['nama_mahasiswa']) : ''; ?>" 
                       required>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-input"
                       placeholder="Contoh: ahmad@email.com"
                       value="<?php echo isset($mahasiswa) ? htmlspecialchars($mahasiswa['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" 
                       id="no_hp" 
                       name="no_hp" 
                       class="form-input"
                       placeholder="Contoh: 081234567890"
                       value="<?php echo isset($mahasiswa) ? htmlspecialchars($mahasiswa['no_hp']) : ''; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Jenis Kelamin *</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" 
                           name="jenis_kelamin" 
                           value="L" 
                           <?php echo (isset($mahasiswa) && $mahasiswa['jenis_kelamin'] == 'L') ? 'checked' : ''; ?>
                           required>
                    Laki-laki
                </label>
                <label class="radio-label">
                    <input type="radio" 
                           name="jenis_kelamin" 
                           value="P" 
                           <?php echo (isset($mahasiswa) && $mahasiswa['jenis_kelamin'] == 'P') ? 'checked' : ''; ?>
                           required>
                    Perempuan
                </label>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="id_jurusan" class="form-label">Jurusan *</label>
                <select id="id_jurusan" name="id_jurusan" class="form-select" required>
                    <option value="">-- Pilih Jurusan --</option>
                    <?php while ($jurusan = $jurusan_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $jurusan['id_jurusan']; ?>"
                                <?php echo (isset($mahasiswa) && $mahasiswa['id_jurusan'] == $jurusan['id_jurusan']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($jurusan['nama_jurusan']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_kelas" class="form-label">Kelas</label>
                <select id="id_kelas" name="id_kelas" class="form-select">
                    <option value="">-- Pilih Kelas --</option>
                    <?php while ($kelas = $kelas_list->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?php echo $kelas['id_kelas']; ?>"
                                <?php echo (isset($mahasiswa) && $mahasiswa['id_kelas'] == $kelas['id_kelas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                            <?php if ($kelas['nama_jurusan']): ?>
                                - <?php echo htmlspecialchars($kelas['nama_jurusan']); ?>
                            <?php endif; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="tahun_masuk" class="form-label">Tahun Masuk *</label>
                <input type="number" 
                       id="tahun_masuk" 
                       name="tahun_masuk" 
                       class="form-input"
                       placeholder="Contoh: 2021"
                       min="2000" 
                       max="<?php echo date('Y'); ?>"
                       value="<?php echo isset($mahasiswa) ? $mahasiswa['tahun_masuk'] : date('Y'); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="semester" class="form-label">Semester *</label>
                <input type="number" 
                       id="semester" 
                       name="semester" 
                       class="form-input"
                       placeholder="Contoh: 1"
                       min="1" 
                       max="14"
                       value="<?php echo isset($mahasiswa) ? $mahasiswa['semester'] : '1'; ?>" 
                       required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?php echo isset($mahasiswa) ? 'Update Data' : 'Simpan Data'; ?>
            </button>
            <a href="index.php?action=list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>
