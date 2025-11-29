<?php
$page_title = isset($nilai) ? "Edit Nilai" : "Tambah Nilai";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2><?php echo isset($nilai) ? 'Edit Data Nilai' : 'Tambah Data Nilai'; ?></h2>
        <a href="index.php?action=nilai_list" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <?php if (isset($_GET['load_mk']) && isset($_GET['id_mahasiswa'])): ?>
            <input type="hidden" name="selected_mahasiswa" value="<?php echo htmlspecialchars($_GET['id_mahasiswa']); ?>">
        <?php endif; ?>
        
        <div class="form-grid">
            <div class="form-group">
                <label for="id_mahasiswa" class="form-label">Mahasiswa *</label>
                <select id="id_mahasiswa" name="id_mahasiswa" class="form-select" required 
                        onchange="window.location.href='index.php?action=nilai_create&load_mk=1&id_mahasiswa=' + this.value">
                    <option value="" disabled <?php echo (!isset($_GET['id_mahasiswa']) && !isset($nilai)) ? 'selected' : ''; ?>>-- Pilih Mahasiswa --</option>
                    <?php foreach ($mahasiswaList as $mhs): ?>
                        <option value="<?php echo $mhs['id_mahasiswa']; ?>" 
                            <?php 
                            $selected = false;
                            if (isset($nilai) && $nilai['id_mahasiswa'] == $mhs['id_mahasiswa']) {
                                $selected = true;
                            } elseif (isset($_GET['id_mahasiswa']) && $_GET['id_mahasiswa'] == $mhs['id_mahasiswa']) {
                                $selected = true;
                            }
                            echo $selected ? 'selected' : ''; 
                            ?>>
                            <?php echo htmlspecialchars($mhs['nim'] . ' - ' . $mhs['nama_mahasiswa']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="id_mk" class="form-label">Mata Kuliah *</label>
                <select id="id_mk" name="id_mk" class="form-select" required 
                        <?php echo (empty($matakuliahList)) ? 'disabled' : ''; ?>>
                    <?php if (empty($matakuliahList)): ?>
                        <option value="" disabled selected>-- Pilih Mahasiswa Terlebih Dahulu --</option>
                    <?php else: ?>
                        <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                        <?php foreach ($matakuliahList as $mk): ?>
                            <option value="<?php echo $mk['id_mk']; ?>" 
                                <?php echo (isset($nilai) && $nilai['id_mk'] == $mk['id_mk']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($mk['kode_mk'] . ' - ' . $mk['nama_mk'] . ' (' . $mk['sks'] . ' SKS)'); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                    <?php if (empty($matakuliahList) && isset($_GET['id_mahasiswa'])): ?>
                        <span style="color: #dc2626;">⚠ Mahasiswa ini belum terdaftar di kelas atau tidak ada jadwal</span>
                    <?php else: ?>
                        Hanya matakuliah yang ada di jadwal kelas mahasiswa yang akan ditampilkan
                    <?php endif; ?>
                </small>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="nilai_angka" class="form-label">Nilai Angka *</label>
                <input type="number" id="nilai_angka" name="nilai_angka" class="form-input" 
                    placeholder="Contoh: 85"
                    value="<?php echo isset($nilai) ? $nilai['nilai_angka'] : ''; ?>" 
                    required min="0" max="100" oninput="updateNilaiHuruf(this.value)">
                <small style="color: #666; font-size: 12px;">Nilai huruf akan otomatis terisi berdasarkan nilai angka</small>
            </div>

            <div class="form-group">
                <label for="tipe_nilai" class="form-label">Tipe Nilai *</label>
                <select id="tipe_nilai" name="tipe_nilai" class="form-select" required>
                    <option value="" disabled selected>-- Pilih Tipe Nilai --</option>
                    <?php
                    $tipe_options = ['Tugas', 'UTS', 'UAS'];
                    foreach ($tipe_options as $tipe) {
                        $selected = (isset($nilai) && $nilai['tipe_nilai'] == $tipe) ? 'selected' : '';
                        echo "<option value='$tipe' $selected>$tipe</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="tanggal_input" class="form-label">Tanggal Input *</label>
            <input type="date" id="tanggal_input" name="tanggal_input" class="form-input" 
                value="<?php echo isset($nilai) ? $nilai['tanggal_input'] : date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group">
            <label class="form-label">Nilai Huruf (Otomatis)</label>
            <div id="nilai_huruf_display" style="padding: 10px; background-color: #f0f0f0; border-radius: 4px; font-weight: bold; font-size: 18px; color: #2563eb;">
                <?php echo isset($nilai) ? htmlspecialchars($nilai['nilai_huruf']) : '-'; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary"><?php echo isset($nilai) ? 'Update Data' : 'Simpan Data'; ?></button>
            <a href="index.php?action=nilai_list" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
function updateNilaiHuruf(nilai) {
    var huruf = '-';
    var color = '#666';
    
    if (nilai >= 80) {
        huruf = 'A';
        color = '#10b981';
    } else if (nilai >= 70) {
        huruf = 'B';
        color = '#3b82f6';
    } else if (nilai >= 60) {
        huruf = 'C';
        color = '#f59e0b';
    } else if (nilai >= 50) {
        huruf = 'D';
        color = '#ef4444';
    } else if (nilai >= 0) {
        huruf = 'E';
        color = '#dc2626';
    }
    
    document.getElementById('nilai_huruf_display').textContent = huruf;
    document.getElementById('nilai_huruf_display').style.color = color;
}
</script>

<?php include 'views/footer.php'; ?>

