<?php
// Menentukan judul halaman
$page_title = isset($jadwal) ? "Edit Jadwal Kuliah" : "Tambah Jadwal Kuliah";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($jadwal) ? 'Edit Data Jadwal' : 'Tambah Data Jadwal'; ?></h2>
        <a href="index.php?action=jadwal_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-grid">
            
            <div class="form-group">
                <label for="tahun_akademik" class="form-label">Tahun Akademik *</label>
                <input type="text" id="tahun_akademik" name="tahun_akademik" class="form-input" 
                    placeholder="Cth: 2024/2025 Ganjil"
                    value="<?php echo isset($jadwal) ? htmlspecialchars($jadwal['tahun_akademik']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="hari" class="form-label">Hari *</label>
                <select id="hari" name="hari" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Hari --</option>
                    <?php
                    $hari_options = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    foreach ($hari_options as $h) {
                        $selected = (isset($jadwal) && $jadwal['hari'] == $h) ? 'selected' : '';
                        echo "<option value='$h' $selected>$h</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_mk" class="form-label">Mata Kuliah *</label>
                <select id="id_mk" name="id_mk" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                    <?php foreach ($mkList as $mk): ?>
                        <option value="<?php echo $mk['id_mk']; ?>" 
                            <?php echo (isset($jadwal) && $jadwal['id_mk'] == $mk['id_mk']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($mk['nama_mk']) . " (" . $mk['sks'] . " SKS)"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_dosen" class="form-label">Dosen Pengampu *</label>
                <select id="id_dosen" name="id_dosen" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Dosen --</option>
                    <?php foreach ($dosenList as $dosen): ?>
                        <option value="<?php echo $dosen['id_dosen']; ?>" 
                            <?php echo (isset($jadwal) && $jadwal['id_dosen'] == $dosen['id_dosen']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dosen['nama_dosen']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_kelas" class="form-label">Kelas *</label>
                <select id="id_kelas" name="id_kelas" class="form-input" required>
                    <option value="" disabled selected>-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $kelas): ?>
                        <option value="<?php echo $kelas['id_kelas']; ?>" 
                            <?php echo (isset($jadwal) && $jadwal['id_kelas'] == $kelas['id_kelas']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($kelas['nama_kelas']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="ruangan" class="form-label">Ruangan *</label>
                <input type="text" id="ruangan" name="ruangan" class="form-input" 
                    placeholder="Cth: H.3.1"
                    value="<?php echo isset($jadwal) ? htmlspecialchars($jadwal['ruangan']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="jam_mulai" class="form-label">Jam Mulai *</label>
                <input type="time" id="jam_mulai" name="jam_mulai" class="form-input" 
                    value="<?php echo isset($jadwal) ? $jadwal['jam_mulai'] : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="jam_selesai" class="form-label">Jam Selesai *</label>
                <input type="time" id="jam_selesai" name="jam_selesai" class="form-input" 
                    value="<?php echo isset($jadwal) ? $jadwal['jam_selesai'] : ''; ?>" required>
            </div>

        </div>

        <div class="form-actions" style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary"><?php echo isset($jadwal) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=jadwal_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<?php include 'views/footer.php'; ?>