<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Dokument</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Assest/ArsipDoc.css">
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
                        <a href="#" class="nav-link">
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
                <h2>Arsip Dokumen</h2>

                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" placeholder="Cari dokumen...">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option value="">Semua Kategori</option>
                                    <option value="surat_keluar">Surat Keluar</option>
                                    <option value="surat_masuk">Surat Masuk</option>
                                    <option value="sk">Surat Keputusan</option>
                                    <option value="perdes">Peraturan Desa</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Tahun</option>
                                    <option value="2024">2024</option>
                                    <option value="2023">2023</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Status</option>
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Tidak Aktif</option>
                                    <option value="expired">Kadaluarsa</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Archive Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card archive-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="archive-icon bg-primary">
                                        <i class="fas fa-file-archive"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Total Arsip</h6>
                                        <h3 class="mb-0">1,234</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card archive-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="archive-icon bg-success">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Terenkripsi</h6>
                                        <h3 class="mb-0">856</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card archive-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="archive-icon bg-warning">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Masa Berlaku</h6>
                                        <h3 class="mb-0">45</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card archive-stat">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="archive-icon bg-danger">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="mb-1">Kadaluarsa</h6>
                                        <h3 class="mb-0">12</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Archive List -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Arsip</th>
                                        <th>Nama Dokumen</th>
                                        <th>Kategori</th>
                                        <th>Tanggal Arsip</th>
                                        <th>Status</th>
                                        <th>Keamanan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>ARC/2024/001</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <div>
                                                    <h6 class="mb-0">Laporan Keuangan 2023</h6>
                                                    <small class="text-muted">2.5 MB</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Laporan</td>
                                        <td>15 Mar 2024</td>
                                        <td><span class="badge bg-success">Aktif</span></td>
                                        <td><span class="security-badge high">Tinggi</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" title="Unduh">
                                                    <i class="fas fa-download"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning" title="Enkripsi">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- More archive rows... -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>