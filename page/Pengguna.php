<?php
session_start();
// Memasukkan koneksi ke database
require '../Koneksi/Koneksi.php';

// Query untuk mengambil data kunci
$queryKunci = "SELECT id_kunci, kode_kunci FROM tabel_kunci WHERE status = 'AKTIF'";
$resultKunci = mysqli_query($koneksi, $queryKunci);

// Mengecek apakah query berhasil
if (!$resultKunci) {
    echo "Error (Kunci): " . mysqli_error($koneksi);
    exit;
}

// Query untuk mengambil data pengguna
$queryPengguna = "SELECT id_pengguna, foto_profil, nama_lengkap, email, jabatan, no_hp, level_akses, status, tanda_tangan FROM tabel_pengguna";
$resultPengguna = $koneksi->query($queryPengguna);

// Mengecek apakah query berhasil
if (!$resultPengguna) {
    echo "Error (Pengguna): " . mysqli_error($koneksi);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengguna</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assest/Pengguna.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                <h2>Daftar Pengguna</h2>
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success_pengguna']) || isset($_SESSION['error_pengguna'])): ?>
                    <?php
                    $alert_message = '';
                    $alert_type = '';
                    $alert_icon = '';

                    // Menentukan jenis pesan dan icon sesuai dengan session
                    if (isset($_SESSION['success_pengguna'])) {
                        $alert_message = $_SESSION['success_pengguna'];
                        $alert_type = 'success';
                        $alert_icon = 'check-circle';
                    } elseif (isset($_SESSION['error_pengguna'])) {
                        $alert_message = $_SESSION['error_pengguna'];
                        $alert_type = 'danger';
                        $alert_icon = 'exclamation-circle';
                    } elseif (isset($_SESSION['warning_pengguna'])) {
                        $alert_message = $_SESSION['warning_pengguna'];
                        $alert_type = 'warning';
                        $alert_icon = 'exclamation-triangle';
                    } else {
                        $alert_message = $_SESSION['info_pengguna'];
                        $alert_type = 'info';
                        $alert_icon = 'info-circle';
                    }
                    ?>
                    <div class="alert alert-<?= $alert_type ?> alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-<?= $alert_icon ?> me-2"></i>
                            <div>
                                <?= $alert_message ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php
                    // Hapus pesan setelah ditampilkan
                    unset($_SESSION['success_pengguna']);
                    unset($_SESSION['error_pengguna']);
                    unset($_SESSION['warning_pengguna']);
                    unset($_SESSION['info_pengguna']);
                    ?>
                <?php endif; ?>

                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" placeholder="Cari nama/jabatan...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Semua Jabatan</option>
                                    <option value="kepala_desa">Kepala Desa</option>
                                    <option value="sekretaris">Sekretaris Desa</option>
                                    <option value="bendahara">Bendahara</option>
                                    <option value="kaur">Kepala Urusan</option>
                                    <option value="kadus">Kepala Dusun</option>
                                    <option value="staff">Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                    <option value="pending">Menunggu Verifikasi</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-user-plus me-2"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="card">
                    <div class="card-body">
                        <h4>Data Pengguna</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>No. HP</th>
                                        <th>Status</th>
                                        <th>Akses</th>
                                        <th>Tanda Tangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($resultPengguna && $resultPengguna->num_rows > 0) : ?>
                                        <?php while ($user = $resultPengguna->fetch_assoc()) : ?>
                                            <tr>
                                                <td>
                                                    <img src="<?= htmlspecialchars($user['foto_profil']); ?>" alt="<?= htmlspecialchars($user['nama_lengkap']); ?>" width="50" height="50">
                                                </td>
                                                <td>
                                                    <div class="user-info">
                                                        <h6 class="mb-0"><?= htmlspecialchars($user['nama_lengkap']); ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($user['email']); ?></small>
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($user['jabatan']); ?></td>
                                                <td><?= htmlspecialchars($user['no_hp']); ?></td>
                                                <td>
                                                    <span class="badge <?= $user['status'] === 'Aktif' ? 'bg-success' : 'bg-danger'; ?>">
                                                        <?= htmlspecialchars($user['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $user['level_akses'] === 'Admin' ? 'bg-primary' : 'bg-info'; ?>">
                                                        <?= htmlspecialchars($user['level_akses']); ?>
                                                    </span>
                                                </td>
                                                <td>
    <?php
    try {
        if (!empty($user['tanda_tangan'])) {
            $path = "../Package/File_Ens/" . $user['tanda_tangan'];
            
            if (file_exists($path)) {
                $signatureData = json_decode(file_get_contents($path), true);
                if ($signatureData && isset($signatureData['image'])) {
                    echo '<img src="data:image/png;base64,' . $signatureData['image'] . '" alt="Tanda Tangan" width="100">';
                } else {
                    echo 'Format tanda tangan tidak valid';
                }
            } else {
                echo 'File tanda tangan tidak ditemukan';
            }
        } else {
            echo 'Tanda tangan tidak tersedia';
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    ?>
</td>


                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-info" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" title="Nonaktifkan">
                                                            <i class="fas fa-user-slash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada data pengguna.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengguna Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form action="./Proses-Page/Tambah_Pengguna.php" method="POST" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Foto Profil</label>
                                    <input type="file" class="form-control" name="foto_profil" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" placeholder="Masukkan nama lengkap" name="nama_lengkap" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Masukkan email" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jabatan</label>
                                    <select class="form-select" name="jabatan" required>
                                        <option value="">Pilih Jabatan</option>
                                        <option value="KEPALA DESA">Kepala Desa</option>
                                        <option value="SEKRETARIS">Sekretaris Desa</option>
                                        <option value="BENDAHARA">Bendahara</option>
                                        <option value="KAUR">Kepala Urusan</option>
                                        <option value="KADUS">Kepala Dusun</option>
                                        <option value="STAFF">Staff</option>
                                        <option value="MASYARAKAT">Masyarakat</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. HP</label>
                                    <input type="tel" class="form-control" placeholder="Masukkan nomor HP" name="no_hp" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Level Akses</label>
                                    <select class="form-select" name="level_akses" required>
                                        <option value="">Pilih Level Akses</option>
                                        <option value="ADMIN">Admin</option>
                                        <option value="STAFF">Staff</option>
                                        <option value="USER">User</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="AKTIF">Aktif</option>
                                        <option value="TIDAK_AKTIF">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" rows="3" placeholder="Masukkan alamat lengkap" name="alamat" required></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Tanda Tangan</label>
                                    <input type="file" class="form-control" name="tanda_tangan" required>
                                    <small class="text-muted">Unggah file tanda tangan dalam format PNG atau JPG.</small>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Pilih Kode Kunci</label>
                                    <select class="form-select" name="kode_kunci" required>
                                        <option value="">Pilih Kode Kunci</option>
                                        <?php while ($option = $resultKunci->fetch_assoc()) : ?>
                                            <option value="<?= htmlspecialchars($option['kode_kunci']); ?>">
                                                <?= htmlspecialchars($option['kode_kunci']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>