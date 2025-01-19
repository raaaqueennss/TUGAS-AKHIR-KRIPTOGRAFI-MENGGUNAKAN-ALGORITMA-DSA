<?php
session_start();
require '../Koneksi/Koneksi.php';


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Kunci</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assest/Pengguna.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .search-box {
            max-width: 300px;
        }

        .search-box .input-group-text {
            border-radius: 8px 0 0 8px;
        }

        .search-box .form-control {
            border-radius: 0 8px 8px 0;
        }

        .search-box .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .no-results-message {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto px-0 sidebar">
                <div class="website-name">
                    <h4>E-Document</h4>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Kelolahdoc.php" class="nav-link">
                            <i class="fas fa-file-alt me-2"></i> Kelola Dokumen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="TinjauanDoc.php" class="nav-link">
                            <i class="fas fa-search me-2"></i> Tinjauan Dokumen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="Pengguna.php" class="nav-link">
                            <i class="fas fa-users me-2"></i> Pengguna
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="ArsipDoc.php" class="nav-link">
                            <i class="fas fa-archive me-2"></i> Arsip Dokumen
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="ManagementKunci.php" class="nav-link">
                            <i class="fas fa-key me-2"></i> Management Kunci
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a href="../login/login.php" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <h2>Pengaturan Kunci DSA</h2>
                <p class="text-muted mb-4">Kelola kunci Digital Signature Algorithm (DSA) untuk tanda tangan digital dokumen.</p>

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['pesan']) && isset($_SESSION['tipe'])): ?>
                    <div class="alert alert-<?= $_SESSION['tipe'] ?> alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <?php if ($_SESSION['tipe'] == 'success'): ?>
                                <i class="fas fa-check-circle me-2"></i>
                            <?php elseif ($_SESSION['tipe'] == 'danger'): ?>
                                <i class="fas fa-exclamation-circle me-2"></i>
                            <?php elseif ($_SESSION['tipe'] == 'warning'): ?>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php else: ?>
                                <i class="fas fa-info-circle me-2"></i>
                            <?php endif; ?>
                            <div>
                                <?= $_SESSION['pesan'] ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    // Hapus pesan setelah ditampilkan
                    unset($_SESSION['pesan']);
                    unset($_SESSION['tipe']);
                    ?>
                <?php endif; ?>

                <!-- DSA Key Stats -->
                <div class="row mb-4">
                    <?php
                    // Query untuk mendapatkan statistik kunci
                    $stats = [
                        'total' => $koneksi->query("SELECT COUNT(*) as count FROM tabel_kunci")->fetch_assoc()['count'],
                        'aktif' => $koneksi->query("SELECT COUNT(*) as count FROM tabel_kunci WHERE status = 'aktif'")->fetch_assoc()['count'],
                        'kadaluarsa_soon' => $koneksi->query("SELECT COUNT(*) as count FROM tabel_kunci WHERE status = 'aktif' AND tanggal_kadaluarsa BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)")->fetch_assoc()['count'],
                        'nonaktif' => $koneksi->query("SELECT COUNT(*) as count FROM tabel_kunci WHERE status IN ('nonaktif', 'kadaluarsa')")->fetch_assoc()['count']
                    ];
                    ?>
                    <div class="col-md-3">
                        <div class="card key-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="key-icon bg-primary">
                                        <i class="fas fa-signature"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Total Kunci DSA</h6>
                                        <h3 class="mb-0"><?= $stats['total'] ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card key-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="key-icon bg-success">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Kunci Aktif</h6>
                                        <h3 class="mb-0"><?= $stats['aktif'] ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card key-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="key-icon bg-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Akan Kadaluarsa</h6>
                                        <h3 class="mb-0"><?= $stats['kadaluarsa_soon'] ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card key-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="key-icon bg-danger">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Kunci Nonaktif</h6>
                                        <h3 class="mb-0"><?= $stats['nonaktif'] ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahKunci">
                            <i class="fas fa-plus-circle me-2"></i>Buat Kunci DSA
                        </button>
                    </div>
                    <div class="search-box">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0"
                                placeholder="Cari kode, status, atau tanggal..."
                                aria-label="Cari kunci DSA">
                        </div>
                    </div>
                </div>

                <!-- DSA Key List -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kode Kunci</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Kadaluarsa</th>
                                        <th>Status</th>
                                        <th>Penggunaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM tabel_kunci ORDER BY tanggal_dibuat DESC";
                                    $result = $koneksi->query($query);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $max_usage = 1000;
                                            $usage_percent = ($row['jumlah_penggunaan'] / $max_usage) * 100;

                                            $status_class = match ($row['status']) {
                                                'aktif' => 'bg-success',
                                                'nonaktif' => 'bg-danger',
                                                'kadaluarsa' => 'bg-warning',
                                                default => 'bg-secondary'
                                            };
                                    ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-key text-primary me-2"></i>
                                                        <div>
                                                            <h6 class="mb-0"><?= htmlspecialchars($row['kode_kunci']) ?></h6>
                                                            <small class="text-muted"><?= $row['id_kunci'] ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= date('d M Y', strtotime($row['tanggal_dibuat'])) ?></td>
                                                <td>
                                                    <?php
                                                    $days_left = ceil((strtotime($row['tanggal_kadaluarsa']) - time()) / (60 * 60 * 24));
                                                    $badge_class = $days_left <= 30 ? 'bg-warning' : 'bg-info';
                                                    ?>
                                                    <div>
                                                        <?= date('d M Y', strtotime($row['tanggal_kadaluarsa'])) ?>
                                                        <span class="badge <?= $badge_class ?> ms-2">
                                                            <?= $days_left ?> hari lagi
                                                        </span>
                                                    </div>
                                                </td>
                                                <td><span class="badge <?= $status_class ?>"><?= ucfirst($row['status']) ?></span></td>
                                                <td>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: <?= $usage_percent ?>%"></div>
                                                    </div>
                                                    <small class="text-muted"><?= $row['jumlah_penggunaan'] ?> kali digunakan</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-info" title="Detail"
                                                            onclick="showKeyDetails('<?= $row['id_kunci'] ?>')">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning" title="Edit"
                                                            onclick="editKey('<?= $row['id_kunci'] ?>')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" title="Hapus"
                                                            onclick="deleteKey('<?= $row['id_kunci'] ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="no-data-message">
                                                    <i class="fas fa-key text-muted mb-3" style="font-size: 48px;"></i>
                                                    <h5 class="text-muted mb-2">Belum Ada Kunci DSA</h5>
                                                    <p class="text-muted mb-3">Klik tombol "Buat Kunci DSA" untuk membuat kunci baru</p>
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahKunci">
                                                        <i class="fas fa-plus-circle me-2"></i>Buat Kunci DSA
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add DSA Key Modal -->
    <div class="modal fade" id="modalTambahKunci" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Kunci DSA Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="./Proses-Page/Proses+Kunci.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kode Kunci</label>
                            <input type="text" name="kode_kunci" class="form-control" placeholder="Masukkan kode kunci" required>
                            <small class="text-muted">Contoh: KUNCI-001, DSA-2024-01, dll</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Masa Berlaku</label>
                            <select name="masa_berlaku" class="form-select" required>
                                <option value="365">1 Tahun</option>
                                <option value="730">2 Tahun</option>
                                <option value="1095">3 Tahun</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"
                                placeholder="Deskripsi penggunaan kunci"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Kunci DSA akan dibuat dengan panjang 2048-bit untuk keamanan optimal
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="buat_kunci_btn">
                            <i class="fas fa-key me-2"></i>Buat Kunci DSA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Kunci -->
    <div class="modal fade" id="keyDetailModal" tabindex="-1" aria-labelledby="keyDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="keyDetailModalLabel">
                        <i class="fas fa-key me-2"></i>Detail Kunci DSA
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">Informasi Dasar</h6>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">ID Kunci</small>
                                        <strong id="detail-id-kunci" class="d-block"></strong>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Kode Kunci</small>
                                        <strong id="detail-kode-kunci" class="d-block"></strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        <span id="detail-status-badge" class="badge bg-success"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">Masa Berlaku</h6>
                                    <div class="mb-2">
                                        <small class="text-muted d-block">Tanggal Dibuat</small>
                                        <strong id="detail-tanggal-dibuat" class="d-block"></strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Kadaluarsa</small>
                                        <strong id="detail-tanggal-kadaluarsa" class="d-block"></strong>
                                        <span id="detail-days-remaining" class="badge bg-info mt-1"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-3 text-muted">Deskripsi</h6>
                            <p id="detail-deskripsi" class="mb-0 text-secondary"></p>
                        </div>
                    </div>

                    <!-- Key Details -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">Public Key</h6>
                                    <div class="position-relative">
                                        <textarea class="form-control bg-light" id="detail-public-key" rows="3" readonly></textarea>
                                        <button class="btn btn-sm btn-primary position-absolute top-0 end-0 mt-2 me-2"
                                            onclick="copyToClipboard('detail-public-key')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-subtitle mb-3 text-muted">Private Key</h6>
                                    <div class="position-relative">
                                        <textarea class="form-control bg-light" id="detail-private-key" rows="3" readonly></textarea>
                                        <button class="btn btn-sm btn-primary position-absolute top-0 end-0 mt-2 me-2"
                                            onclick="copyToClipboard('detail-private-key')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadKeyDetails()">
                        <i class="fas fa-download me-2"></i>Download Info
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan modal edit sebelum penutup body -->
    <div class="modal fade" id="editKeyModal" tabindex="-1" aria-labelledby="editKeyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="editKeyModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Kunci DSA
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKeyForm" action="./Proses-Page/update_key.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_kunci" id="edit-id-kunci">
                        
                        <div class="mb-3">
                            <label class="form-label">Kode Kunci</label>
                            <input type="text" name="kode_kunci" id="edit-kode-kunci" 
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit-status" class="form-select" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="kadaluarsa">Kadaluarsa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Kadaluarsa</label>
                            <input type="date" name="tanggal_kadaluarsa" id="edit-tanggal-kadaluarsa" 
                                   class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="edit-deskripsi" class="form-control" 
                                    rows="3"></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Public key dan private key tidak dapat diubah untuk menjaga keamanan
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tambahkan modal konfirmasi delete sebelum penutup body -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin menghapus kunci ini?</p>
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Tindakan ini tidak dapat dibatalkan. Semua data terkait kunci ini akan dihapus secara permanen.
                    </div>
                    <!-- Info kunci yang akan dihapus -->
                    <div class="card border-0 bg-light mt-3">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted">Detail Kunci</h6>
                            <p class="mb-1"><strong>Kode:</strong> <span id="delete-kode-kunci"></span></p>
                            <p class="mb-1"><strong>Status:</strong> <span id="delete-status"></span></p>
                            <p class="mb-0"><strong>ID:</strong> <span id="delete-id-kunci"></span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-2"></i>Hapus Kunci
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pastikan modal dapat muncul tanpa error
        document.addEventListener('DOMContentLoaded', function() {
            var modalTambahKunci = new bootstrap.Modal(document.getElementById('modalTambahKunci'));

            document.querySelector('[data-bs-target="#modalTambahKunci"]').addEventListener('click', function() {
                modalTambahKunci.show();
            });
        });

        function showKeyDetails(id) {
            fetch('Proses-Page/get_key_details.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(id)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update informasi dasar
                        document.getElementById('detail-id-kunci').textContent = data.key.id_kunci;
                        document.getElementById('detail-kode-kunci').textContent = data.key.kode_kunci;

                        // Update status dengan warna yang sesuai
                        const statusBadge = document.getElementById('detail-status-badge');
                        statusBadge.textContent = data.key.status.toUpperCase();
                        statusBadge.className = 'badge ' + getStatusClass(data.key.status);

                        // Format dan update tanggal
                        const tanggalDibuat = new Date(data.key.tanggal_dibuat);
                        const tanggalKadaluarsa = new Date(data.key.tanggal_kadaluarsa);
                        document.getElementById('detail-tanggal-dibuat').textContent = formatDate(tanggalDibuat);
                        document.getElementById('detail-tanggal-kadaluarsa').textContent = formatDate(tanggalKadaluarsa);

                        // Hitung dan tampilkan sisa hari
                        const daysRemaining = Math.ceil((tanggalKadaluarsa - new Date()) / (1000 * 60 * 60 * 24));
                        document.getElementById('detail-days-remaining').textContent = `${daysRemaining} hari tersisa`;

                        // Update konten lainnya
                        document.getElementById('detail-deskripsi').textContent = data.key.deskripsi || 'Tidak ada deskripsi';
                        document.getElementById('detail-public-key').value = data.key.public_key;
                        document.getElementById('detail-private-key').value = data.key.private_key;

                        // Tampilkan modal
                        new bootstrap.Modal(document.getElementById('keyDetailModal')).show();
                    } else {
                        alert('Gagal mengambil detail kunci: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil detail kunci');
                });
        }

        function getStatusClass(status) {
            const statusClasses = {
                'aktif': 'bg-success',
                'nonaktif': 'bg-danger',
                'kadaluarsa': 'bg-warning'
            };
            return statusClasses[status] || 'bg-secondary';
        }

        function formatDate(date) {
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            return date.toLocaleDateString('id-ID', options);
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            document.execCommand('copy');

            // Tampilkan tooltip sukses
            const tooltip = document.createElement('div');
            tooltip.className = 'position-absolute top-0 end-0 mt-5 me-2 badge bg-success';
            tooltip.textContent = 'Tersalin!';
            element.parentElement.appendChild(tooltip);

            setTimeout(() => tooltip.remove(), 2000);
        }

        function downloadKeyDetails() {
            const keyInfo = {
                id: document.getElementById('detail-id-kunci').textContent,
                kode: document.getElementById('detail-kode-kunci').textContent,
                status: document.getElementById('detail-status-badge').textContent,
                tanggalDibuat: document.getElementById('detail-tanggal-dibuat').textContent,
                tanggalKadaluarsa: document.getElementById('detail-tanggal-kadaluarsa').textContent,
                deskripsi: document.getElementById('detail-deskripsi').textContent,
                publicKey: document.getElementById('detail-public-key').value,
                privateKey: document.getElementById('detail-private-key').value
            };

            const content = Object.entries(keyInfo)
                .map(([key, value]) => `${key}: ${value}`)
                .join('\n');

            const blob = new Blob([content], {
                type: 'text/plain'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `key-details-${keyInfo.id}.txt`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }

        function editKey(id) {
            // Ambil data kunci yang akan diedit
            fetch('Proses-Page/get_key_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Isi form dengan data yang ada
                    document.getElementById('edit-id-kunci').value = data.key.id_kunci;
                    document.getElementById('edit-kode-kunci').value = data.key.kode_kunci;
                    document.getElementById('edit-status').value = data.key.status;
                    
                    // Format tanggal untuk input date
                    const tanggalKadaluarsa = new Date(data.key.tanggal_kadaluarsa);
                    const formattedDate = tanggalKadaluarsa.toISOString().split('T')[0];
                    document.getElementById('edit-tanggal-kadaluarsa').value = formattedDate;
                    
                    document.getElementById('edit-deskripsi').value = data.key.deskripsi;

                    // Tampilkan modal
                    new bootstrap.Modal(document.getElementById('editKeyModal')).show();
                } else {
                    alert('Gagal mengambil data kunci: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data kunci');
            });
        }

        // Tambahkan event listener untuk form edit
        document.getElementById('editKeyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tutup modal
                    bootstrap.Modal.getInstance(document.getElementById('editKeyModal')).hide();
                    // Refresh halaman untuk menampilkan perubahan
                    location.reload();
                } else {
                    alert('Gagal mengupdate kunci: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate kunci');
            });
        });

        function deleteKey(id) {
            // Ambil data kunci yang akan dihapus
            fetch('Proses-Page/get_key_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Isi detail kunci di modal konfirmasi
                    document.getElementById('delete-id-kunci').textContent = data.key.id_kunci;
                    document.getElementById('delete-kode-kunci').textContent = data.key.kode_kunci;
                    document.getElementById('delete-status').textContent = data.key.status.toUpperCase();
                    
                    // Setup event handler untuk tombol konfirmasi
                    const confirmBtn = document.getElementById('confirmDeleteBtn');
                    confirmBtn.onclick = () => {
                        // Lakukan penghapusan dengan form submit
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = 'Proses-Page/delete_key.php';
                        
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'id';
                        input.value = id;
                        
                        form.appendChild(input);
                        document.body.appendChild(form);
                        
                        // Tutup modal sebelum submit
                        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
                        
                        form.submit();
                    };
                    
                    // Tampilkan modal konfirmasi
                    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
                } else {
                    alert('Gagal mengambil data kunci: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data kunci');
            });
        }

        function searchKeys() {
            const searchInput = document.querySelector('.search-box input');
            const tableRows = document.querySelectorAll('table tbody tr');
            const noDataRow = document.querySelector('.no-data-message')?.closest('tr');
            let hasResults = false;

            const searchTerm = searchInput.value.toLowerCase().trim();

            tableRows.forEach(row => {
                if (row === noDataRow) return;

                const kodeKunci = row.querySelector('h6')?.textContent.toLowerCase() || '';
                const idKunci = row.querySelector('small')?.textContent.toLowerCase() || '';
                const status = row.querySelector('.badge')?.textContent.toLowerCase() || '';
                const tanggalDibuat = row.querySelectorAll('td')[1]?.textContent.toLowerCase() || '';
                const tanggalKadaluarsa = row.querySelectorAll('td')[2]?.textContent.toLowerCase() || '';

                const matchFound = kodeKunci.includes(searchTerm) ||
                    idKunci.includes(searchTerm) ||
                    status.includes(searchTerm) ||
                    tanggalDibuat.includes(searchTerm) ||
                    tanggalKadaluarsa.includes(searchTerm);

                row.style.display = matchFound ? '' : 'none';
                if (matchFound) hasResults = true;
            });

            // Tampilkan pesan jika tidak ada hasil
            const existingNoResults = document.querySelector('.no-results-message');
            if (!hasResults && !existingNoResults) {
                const noResultsRow = document.createElement('tr');
                noResultsRow.className = 'no-results-message';
                noResultsRow.innerHTML = `
                    <td colspan="6" class="text-center py-4">
                        <div class="no-data-message">
                            <i class="fas fa-search text-muted mb-3" style="font-size: 48px;"></i>
                            <h5 class="text-muted mb-2">Tidak Ada Hasil</h5>
                            <p class="text-muted mb-0">Tidak ditemukan kunci DSA dengan kata kunci "${searchTerm}"</p>
                        </div>
                    </td>
                `;
                document.querySelector('table tbody').appendChild(noResultsRow);
            } else if (hasResults && existingNoResults) {
                existingNoResults.remove();
            }
        }

        // Tambahkan event listener untuk input pencarian
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-box input');

            // Debounce function untuk mengurangi frekuensi pencarian
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(searchKeys, 300);
            });
        });
    </script>

</body>

</html>