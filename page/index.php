<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assest/Index.css">
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
                <h2>Selamat Datang di Dashboard</h2>
                
                <!-- Statistics Cards -->
                <div class="row stats-cards mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Total Surat</h6>
                                        <h3 class="card-title mb-0">248</h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Surat Masuk</h6>
                                        <h3 class="card-title mb-0">125</h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Surat Keluar</h6>
                                        <h3 class="card-title mb-0">123</h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-paper-plane"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-subtitle mb-2">Arsip Digital</h6>
                                        <h3 class="card-title mb-0">89</h3>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-archive"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Documents & Quick Actions -->
                <div class="row">
                    <!-- Recent Documents -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Dokumen Terbaru</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No. Surat</th>
                                                <th>Jenis</th>
                                                <th>Perihal</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2024/03/001</td>
                                                <td>Surat Masuk</td>
                                                <td>Undangan Musyawarah Desa</td>
                                                <td>15 Mar 2024</td>
                                                <td><span class="badge bg-success">Selesai</span></td>
                                            </tr>
                                            <tr>
                                                <td>2024/03/002</td>
                                                <td>Surat Keluar</td>
                                                <td>SK Pengangkatan Perangkat</td>
                                                <td>14 Mar 2024</td>
                                                <td><span class="badge bg-warning">Proses</span></td>
                                            </tr>
                                            <tr>
                                                <td>2024/03/003</td>
                                                <td>Surat Masuk</td>
                                                <td>Laporan Keuangan Desa</td>
                                                <td>13 Mar 2024</td>
                                                <td><span class="badge bg-info">Diterima</span></td>
                                            </tr>
                                            <tr>
                                                <td>2024/03/004</td>
                                                <td>Surat Keluar</td>
                                                <td>Surat Keterangan Domisili</td>
                                                <td>13 Mar 2024</td>
                                                <td><span class="badge bg-success">Selesai</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Aksi Cepat</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-action">
                                        <i class="fas fa-plus-circle me-2"></i>Buat Surat Baru
                                    </button>
                                    <button class="btn btn-info btn-action">
                                        <i class="fas fa-upload me-2"></i>Unggah Dokumen
                                    </button>
                                    <button class="btn btn-success btn-action">
                                        <i class="fas fa-file-signature me-2"></i>Verifikasi Surat
                                    </button>
                                    <button class="btn btn-warning btn-action">
                                        <i class="fas fa-search me-2"></i>Cari Arsip
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notifikasi Terbaru</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="notification-list">
                                    <div class="notification-item p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon bg-primary">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0">Surat baru memerlukan verifikasi</p>
                                                <small class="text-muted">5 menit yang lalu</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notification-item p-3 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon bg-success">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0">Dokumen telah disetujui</p>
                                                <small class="text-muted">1 jam yang lalu</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="notification-item p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon bg-warning">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div class="ms-3">
                                                <p class="mb-0">Pengingat: Batas waktu verifikasi</p>
                                                <small class="text-muted">2 jam yang lalu</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>