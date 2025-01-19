<?php
session_start();
require '../Koneksi/Koneksi.php';
// Initialize variables for filtering
$statusFilter = isset($_POST['status']) ? trim($_POST['status']) : '';
$departmentFilter = isset($_POST['department']) ? trim($_POST['department']) : '';
$searchQuery = isset($_POST['search']) ? trim($_POST['search']) : '';
// Pagination variables
$limit = 10; // Number of results per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$page = max($page, 1); // Ensure page is at least 1
$offset = ($page - 1) * $limit; // Calculate offset for SQL query
// Base SQL query with placeholders for filters
$sql = "SELECT * FROM pengajuan_surat WHERE 1=1";
$params = [];
// Apply filters if provided
if ($statusFilter) {
    $sql .= " AND status = ?";
    $params[] = $statusFilter;
}
if ($departmentFilter) {
    $sql .= " AND department = ?";
    $params[] = $departmentFilter;
}
if ($searchQuery) {
    $sql .= " AND (nama LIKE ? OR keperluan LIKE ?)";
    $params[] = "%$searchQuery%";
    $params[] = "%$searchQuery%";
}
// Get total number of records for pagination
$stmt = $koneksi->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
}
$stmt->execute();
$totalRecordsResult = $stmt->get_result();
$totalRecords = $totalRecordsResult->num_rows; // Total records found
$totalPages = ceil($totalRecords / $limit); // Total pages
$stmt->close();
// Modify query to include LIMIT and OFFSET for pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
// Execute the query with pagination
$stmt = $koneksi->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params) - 2) . "ii", ...$params);
$stmt->execute();
$result = $stmt->get_result();
// Ambil pesan dari session
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
// Hapus pesan setelah ditampilkan
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinjauan Dokument</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assest/TinjauanDoc.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="container-fluid">
    <?php
if (isset($_SESSION['message'])) {
    // Menampilkan pesan verifikasi tanda tangan
    echo "<div id='message'>{$_SESSION['message']}</div>";
    unset($_SESSION['message']); // Menghapus pesan setelah ditampilkan
}
?>

<script>
// Menampilkan pesan selama 5 detik
setTimeout(function() {
    var messageElement = document.getElementById('message');
    if (messageElement) {
        messageElement.style.display = 'none'; // Menyembunyikan pesan setelah 5 detik
    }
}, 5000); // 5000 milidetik = 5 detik
</script>

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
            <div class="main-content">
                <h2>Tinjauan Dokumen</h2>
                <!-- Filter Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pengaju/dokumen..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                                </div>
                                <!-- Filter Button -->
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Terapkan Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Review List -->
                <div class="card">
                    <div class="card-body">
                        <div class="review-list">
                            <?php if ($result && $result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <div class="review-item mb-3">
                                        <div class="row align-items-center">
                                            <!-- Document Info -->
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="review-icon">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($row['keperluan']); ?></h6>
                                                        <p class="mb-0 text-muted">
                                                            <small>
                                                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($row['nama']); ?>
                                                                <span class="mx-2">•</span>
                                                                <i class="fas fa-calendar me-2"></i><?php echo date('d M Y', strtotime($row['created_at'])); ?>
                                                            </small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Priority Badge -->
                                            <div class="col-md-2 text-center">
                                                <span class="priority-badge high">Prioritas Tinggi</span>
                                            </div>
                                            <!-- Action Buttons -->
                                            <div class="col-md-2">
                                                <div class="btn-group">
                                                    <a href="./Temp_Doc/Temp_Surat.php?id_pengajuan=<?php echo htmlspecialchars($row['id_pengajuan']); ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-success" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <a href="./Temp_Doc/get_tanda_tangan.php"
                                                        class="btn btn-sm btn-warning"
                                                        title="Verifikasi Tanda Tangan">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center">Tidak ada data yang ditemukan.</p>
                            <?php endif; ?>
                        </div>
                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <?php $koneksi->close(); ?>
        </div>
        <!-- Bootstrap 5 JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        </script>
</body>

</html>