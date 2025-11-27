<?php
$page_title = "Detail Mahasiswa";
include 'views/header.php';
?>

<div class="content-box">
    <div class="content-header">
        <h2>Detail Mahasiswa</h2>
        <a href="index.php?action=list" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="content">
        <!-- Bagian Kiri - Foto -->
        <div class="photo-section">
            <div class="content-box" style="min-width: 300px; display: flex; justify-content: center; align-items: center; height: 300px; width: 300px;">
                <?php if (!empty($mahasiswa['foto']) && file_exists('uploads/' . $mahasiswa['foto'])): ?>
                    <img src="uploads/<?= htmlspecialchars($mahasiswa['foto']) ?>" alt="Foto Mahasiswa">
                <?php else: ?>
                    <div class="photo-placeholder">👤</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bagian Kanan - Detail -->
        <div class="details-section">
            <div class="detail-title"><?= htmlspecialchars($mahasiswa['nama_mahasiswa']) ?></div>
            <div class="detail-subtitle">NIM: <?= htmlspecialchars($mahasiswa['nim']) ?></div>

            <div class="detail-group">
                <div class="detail-label">Jenis Kelamin</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['jenis_kelamin']) ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Email</div>
                <div class="detail-value">
                    <?php if (!empty($mahasiswa['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($mahasiswa['email']) ?>" style="color: #667eea; text-decoration: none;">
                            <?= htmlspecialchars($mahasiswa['email']) ?>
                        </a>
                    <?php else: ?>
                        <span class="empty">Tidak ada email</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">No. HP</div>
                <div class="detail-value <?= empty($mahasiswa['no_hp']) ? 'empty' : '' ?>">
                    <?= !empty($mahasiswa['no_hp']) ? htmlspecialchars($mahasiswa['no_hp']) : 'Tidak ada nomor HP' ?>
                </div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Jurusan</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['jurusan']) ?></div>
            </div>

            <div class="detail-group">
                <div class="detail-label">Kelas</div>
                <div class="detail-value"><?= htmlspecialchars($mahasiswa['kelas']) ?></div>
            </div>

            <div class="info-cards">
                <div class="info-card">
                    <div class="info-card-label">Tahun Masuk</div>
                    <div class="info-card-value"><?= htmlspecialchars($mahasiswa['tahun_masuk']) ?></div>
                </div>

                <div class="info-card">
                    <div class="info-card-label">Semester</div>
                    <div class="info-card-value"><?= htmlspecialchars($mahasiswa['semester']) ?></div>
                </div>
            </div>
        </div>
    </div>
</div>