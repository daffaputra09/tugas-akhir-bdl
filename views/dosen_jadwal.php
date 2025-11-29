<?php
$page_title = "Jadwal Mengajar Dosen";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <div>
            <h2>Jadwal Mengajar</h2>
            <p class="text-muted">
                Dosen: <strong><?php echo htmlspecialchars($dosenInfo['nama_dosen']); ?></strong> 
                (NIP: <?php echo htmlspecialchars($dosenInfo['nip']); ?>)
            </p>
        </div>
        <a href="index.php?action=dosen_list" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Kuliah</th>
                    <th>Ruangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($jadwal && $jadwal->rowCount() > 0):
                    $no = 1;
                    while ($row = $jadwal->fetch(PDO::FETCH_ASSOC)): 
                        // Format jam agar lebih rapi (menghilangkan detik jika ada)
                        $jam_mulai = date('H:i', strtotime($row['jam_mulai']));
                        $jam_selesai = date('H:i', strtotime($row['jam_selesai']));
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <span class="badge badge-info"><?php echo htmlspecialchars($row['hari']); ?></span>
                        </td>
                        <td><?php echo $jam_mulai . ' - ' . $jam_selesai; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama_mk']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['ruangan']); ?></td>
                    </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 20px;">
                            <em>Belum ada jadwal mengajar untuk dosen ini.</em>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'views/footer.php'; ?>