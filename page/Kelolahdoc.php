<?php
session_start();
require '../Koneksi/Koneksi.php';

// Tentukan jumlah data per halaman
$perPage = 10;
// Tentukan halaman saat ini
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Hitung offset untuk LIMIT
$start = ($page - 1) * $perPage;

// Ambil data pencarian dan filter
$searchKeyword = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";
$filterJenisSurat = isset($_GET['jenis_surat']) && $_GET['jenis_surat'] != '' ? $_GET['jenis_surat'] : null;

// Query utama untuk gabungkan tabel surat dan konten_surat
$query = "
    SELECT s.id_surat, 
           s.logo_instansi, 
           s.nama_instansi, 
           s.kecamatan_instansi, 
           s.desa_instansi,
           s.alamat_instansi, 
           s.jenis_surat, 
           s.nomor_surat, 
           s.created_at AS surat_created_at,
           ks.tipe_konten, 
           ks.isi_konten, 
           ks.urutan
    FROM surat s
    LEFT JOIN konten_surat ks ON s.id_surat = ks.id_surat
    WHERE s.nama_instansi LIKE '$searchKeyword'";

// Tambahkan filter jenis_surat jika ada
if ($filterJenisSurat) {
    $query .= " AND s.jenis_surat = '$filterJenisSurat'";
}

// Tambahkan paginasi
$query .= " LIMIT $start, $perPage";

$result = $koneksi->query($query);

// Mengelompokkan konten berdasarkan id_surat
$suratData = [];
while ($row = $result->fetch_assoc()) {
    $id_surat = $row['id_surat'];

    if (!isset($suratData[$id_surat])) {
        // Menambahkan data surat utama ke array
        $suratData[$id_surat] = [
            'id_surat' => $row['id_surat'],
            'logo_instansi' => $row['logo_instansi'],
            'nama_instansi' => $row['nama_instansi'],
            'kecamatan_instansi' => $row['kecamatan_instansi'],
            'desa_instansi' => $row['desa_instansi'],
            'alamat_instansi' => $row['alamat_instansi'],
            'jenis_surat' => $row['jenis_surat'],
            'nomor_surat' => $row['nomor_surat'],
            'surat_created_at' => $row['surat_created_at'],
            'konten' => [] // Menyimpan konten terkait
        ];
    }

    // Menambahkan konten ke dalam array konten surat
    $suratData[$id_surat]['konten'][] = [
        'tipe_konten' => $row['tipe_konten'],
        'isi_konten' => $row['isi_konten'],
        'urutan' => $row['urutan']
    ];
}

// Hitung total data berdasarkan pencarian dan filter
$countQuery = "
    SELECT COUNT(DISTINCT s.id_surat) as total
    FROM surat s
    LEFT JOIN konten_surat ks ON s.id_surat = ks.id_surat
    WHERE s.nama_instansi LIKE '$searchKeyword'";
if ($filterJenisSurat) {
    $countQuery .= " AND s.jenis_surat = '$filterJenisSurat'";
}
$countResult = $koneksi->query($countQuery);
$totalData = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalData / $perPage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Dokument</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assest/KelolahDoc.css">
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
                <h2>Kelola Dokumen</h2>
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success_doc'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div>
                                <?= $_SESSION['success_doc'] ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success_doc']); ?>
                <?php elseif (isset($_SESSION['error_doc'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <div>
                                <?= $_SESSION['error_doc'] ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['error_doc']); ?>
                <?php endif; ?>
                <!-- Search and Filter -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Cari dokumen input -->
                            <div class="col-md-4">
                                <form method="get" action="">
                                    <input type="text" class="form-control" placeholder="Cari dokumen..." id="searchDokumen" name="search" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                            </div>

                            <!-- Filter Jenis Dokumen -->
                            <div class="col-md-3">
                                <select class="form-select" id="jenisDokumen" name="jenis_surat">
                                    <option value="">Semua Jenis</option>
                                    <?php
                                    // Ambil jenis surat dari database jika diperlukan
                                    $resultJenisDoc = $koneksi->query("SELECT DISTINCT jenis_surat FROM surat");
                                    if ($resultJenisDoc->num_rows > 0) {
                                        while ($row = $resultJenisDoc->fetch_assoc()) {
                                            $selected = isset($_GET['jenis_surat']) && $_GET['jenis_surat'] == $row['jenis_surat'] ? 'selected' : '';
                                            echo '<option value="' . $row['jenis_surat'] . '" ' . $selected . '>' . ucfirst($row['jenis_surat']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Tidak ada jenis dokumen</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Tombol Cari -->
                            <div class="col-md-2">
                                <button class="btn btn-secondary w-100" type="submit">
                                    <i class="fas fa-search me-2"></i>Cari
                                </button>
                            </div>
                            </form>

                            <!-- Tombol Tambah Dokumen -->
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                    <i class="fas fa-plus me-2"></i>Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabel Dokumen -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID Surat</th>
                                        <th>Logo Instansi</th>
                                        <th>Nama Instansi</th>
                                        <th>Kecamatan</th>
                                        <th>Desa</th>
                                        <th>Alamat</th>
                                        <th>Jenis Surat</th>
                                        <th>Nomor Surat</th>
                                        <th>Tipe Konten</th>
                                        <th>Isi Konten</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($suratData as $data): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($data['id_surat']) ?></td>
                                            <td><img src="<?= htmlspecialchars($data['logo_instansi']) ?>" alt="Logo" height="50"></td>
                                            <td><?= htmlspecialchars($data['nama_instansi']) ?></td>
                                            <td><?= htmlspecialchars($data['kecamatan_instansi']) ?></td>
                                            <td><?= htmlspecialchars($data['desa_instansi']) ?></td>
                                            <td><?= htmlspecialchars($data['alamat_instansi']) ?></td>
                                            <td><?= htmlspecialchars($data['jenis_surat']) ?></td>
                                            <td><?= htmlspecialchars($data['nomor_surat']) ?></td>
                                            <td>
                                                <?php
                                                foreach ($data['konten'] as $konten) {
                                                    echo htmlspecialchars($konten['tipe_konten']) . "<br>";
                                                    echo '<hr>';  // Menambahkan garis pemisah setelah setiap tipe konten
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                foreach ($data['konten'] as $konten) {
                                                    echo htmlspecialchars($konten['isi_konten']) . "<br>";
                                                    echo '<hr>';  // Menambahkan garis pemisah setelah setiap isi konten
                                                }
                                                ?>
                                            </td>
                                            <td><?= htmlspecialchars($data['surat_created_at']) ?></td>
                                            <td>
                                                <a href="./Temp_Doc/Temp_Surat.php?id_surat=<?= htmlspecialchars($data['id_surat']) ?>"
                                                    class="btn btn-info btn-sm" target="_blank" aria-label="View Surat">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <!-- Tombol Edit -->
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editSuratModal"
                                                    onclick="setEditModalData(<?= htmlspecialchars(json_encode($data)) ?>)" aria-label="Edit Surat">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <!-- Tombol Hapus -->
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteSuratModal"
                                                    onclick="deleteSurat('<?= htmlspecialchars($data['id_surat']) ?>', '<?= htmlspecialchars($data['nama_instansi']) ?>')" aria-label="Hapus Surat">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginasi -->
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>&jenis_surat=<?= isset($_GET['jenis_surat']) ? htmlspecialchars($_GET['jenis_surat']) : '' ?>&page=<?= $page - 1 ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                        <a class="page-link" href="?search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>&jenis_surat=<?= isset($_GET['jenis_surat']) ? htmlspecialchars($_GET['jenis_surat']) : '' ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= ($page == $totalPages) ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?search=<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>&jenis_surat=<?= isset($_GET['jenis_surat']) ? htmlspecialchars($_GET['jenis_surat']) : '' ?>&page=<?= $page + 1 ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>



            </div>
        </div>
    </div>


  <!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Surat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="Proses-Page/Tambah_Doc.php" enctype="multipart/form-data">
                    <!-- Form Bagian Kop -->
                    <h6 class="fw-bold">Bagian Kop</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Logo</label>
                            <input type="file" name="logo_instansi" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Instansi</label>
                            <input type="text" name="nama_instansi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan Instansi</label>
                            <input type="text" name="kecamatan_instansi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Desa Instansi</label>
                            <input type="text" name="desa_instansi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Instansi</label>
                            <textarea name="alamat_instansi" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Form Bagian Jenis Surat -->
                    <h6 class="fw-bold">Jenis Surat</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Surat</label>
                            <select name="jenis_surat" class="form-select" required>
                                <option value="">Pilih Jenis Surat</option>
                                <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                                <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Surat</label>
                            <input type="text" name="nomor_surat" class="form-control" required>
                        </div>
                    </div>

                    <!-- Form Bagian Tambah Judul/Sub Judul/Keterangan -->
                    <h6 class="fw-bold">Bagian Konten Surat</h6>
                    <div id="konten-surat"></div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="addJudul">Tambah Judul</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="addSubJudul" disabled>Tambah Sub Judul</button>
                        <button type="button" class="btn btn-outline-success btn-sm" id="addKeterangan">Tambah Keterangan</button>
                    </div>

                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div class="modal fade" id="editSuratModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="Proses-Page/Edit_Doc.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_surat" id="editIdSurat">

                    <!-- Form Bagian Kop -->
                    <h6 class="fw-bold">Bagian Kop</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Upload Logo (Opsional)</label>
                            <input type="file" name="logo_instansi" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Instansi</label>
                            <input type="text" name="nama_instansi" id="editNamaInstansi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kecamatan Instansi</label>
                            <input type="text" name="kecamatan_instansi" id="editKecamatanInstansi" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Desa Instansi</label>
                            <input type="text" name="desa_instansi" id="editDesaInstansi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Instansi</label>
                            <textarea name="alamat_instansi" id="editAlamatInstansi" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>

                    <!-- Form Bagian Jenis Surat -->
                    <h6 class="fw-bold">Jenis Surat</h6>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Surat</label>
                            <select name="jenis_surat" id="editJenisSurat" class="form-select" required>
                                <option value="">Pilih Jenis Surat</option>
                                <option value="Surat Keterangan Usaha">Surat Keterangan Usaha</option>
                                <option value="Surat Keterangan Tidak Mampu">Surat Keterangan Tidak Mampu</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nomor Surat</label>
                            <input type="text" name="nomor_surat" id="editNomorSurat" class="form-control" required>
                        </div>
                    </div>

                    <!-- Form Bagian Konten Surat -->
                    <h6 class="fw-bold">Bagian Konten Surat</h6>
                    <div id="editKontenSurat"></div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Delete Document Modal -->
    <div class="modal fade" id="deleteDocumentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus dokumen ini?</p>
                    <p class="text-danger"><strong>Dokumen akan ditampilkan di sini</strong></p>
                </div>
                <form method="post" action="Proses-Page/Hapus_Doc.php">
                    <input type="hidden" name="id_dokumen" id="delete-id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('searchBtn');

            if (searchBtn) {
                searchBtn.addEventListener('click', function() {
                    const search = document.getElementById('searchDokumen').value;
                    const jenis = document.getElementById('jenisDokumen').value;

                    // Update the form action and trigger the form submission
                    const form = document.querySelector('form');
                    form.action = `?search=${encodeURIComponent(search)}&jenis_surat=${encodeURIComponent(jenis)}`;
                    form.submit(); // This will submit the form
                });
            }
        });

        function setEditModalData(data) {
    // Isi data modal dengan informasi dari baris tabel
    document.getElementById('editIdSurat').value = data.id_surat;
    document.getElementById('editNamaInstansi').value = data.nama_instansi;
    document.getElementById('editKecamatanInstansi').value = data.kecamatan_instansi;
    document.getElementById('editDesaInstansi').value = data.desa_instansi;
    document.getElementById('editAlamatInstansi').value = data.alamat_instansi;
    document.getElementById('editJenisSurat').value = data.jenis_surat;
    document.getElementById('editNomorSurat').value = data.nomor_surat;

    // Kosongkan div konten surat terlebih dahulu
    const kontenSuratDiv = document.getElementById('editKontenSurat');
    kontenSuratDiv.innerHTML = '';

    // Tambahkan konten surat ke dalam modal
    data.konten.forEach((konten, index) => {
        const kontenGroup = document.createElement('div');
        kontenGroup.classList.add('mb-3');

        const tipeKontenLabel = document.createElement('label');
        tipeKontenLabel.classList.add('form-label');
        tipeKontenLabel.textContent = `Tipe Konten ${index + 1}`;
        kontenGroup.appendChild(tipeKontenLabel);

        const tipeKontenInput = document.createElement('input');
        tipeKontenInput.type = 'text';
        tipeKontenInput.name = `konten[${index}][tipe_konten]`;
        tipeKontenInput.classList.add('form-control');
        tipeKontenInput.value = konten.tipe_konten;
        kontenGroup.appendChild(tipeKontenInput);

        const isiKontenLabel = document.createElement('label');
        isiKontenLabel.classList.add('form-label', 'mt-2');
        isiKontenLabel.textContent = `Isi Konten ${index + 1}`;
        kontenGroup.appendChild(isiKontenLabel);

        const isiKontenTextarea = document.createElement('textarea');
        isiKontenTextarea.name = `konten[${index}][isi_konten]`;
        isiKontenTextarea.classList.add('form-control');
        isiKontenTextarea.rows = 2;
        isiKontenTextarea.textContent = konten.isi_konten;
        kontenGroup.appendChild(isiKontenTextarea);

        kontenSuratDiv.appendChild(kontenGroup);
    });
}



document.addEventListener("DOMContentLoaded", function () {
    const kontenSurat = document.getElementById("konten-surat");

    // Check if the buttons exist before attaching event listeners
    const addJudulBtn = document.getElementById("addJudul");
    const addSubJudulBtn = document.getElementById("addSubJudul");
    const addKeteranganBtn = document.getElementById("addKeterangan");

    if (addJudulBtn) {
        addJudulBtn.addEventListener("click", () => {
            const judulInput = document.createElement("div");
            judulInput.classList.add("mb-3");
            judulInput.innerHTML = `
                <label class="form-label">Judul</label>
                <input type="text" name="judul[]" class="form-control" placeholder="Contoh: Yang bertanda tangan di bawah ini" required>
            `;
            kontenSurat.appendChild(judulInput);
        });
    }

    if (addSubJudulBtn) {
        addSubJudulBtn.addEventListener("click", () => {
            const subJudulInput = document.createElement("div");
            subJudulInput.classList.add("mb-3");
            subJudulInput.innerHTML = `
                <label class="form-label">Sub Judul</label>
                <textarea name="sub_judul[]" class="form-control" rows="3" placeholder="Masukkan setiap item pada baris baru" required></textarea>
            `;
            kontenSurat.appendChild(subJudulInput);
        });
    }

    if (addKeteranganBtn) {
        addKeteranganBtn.addEventListener("click", () => {
            const keteranganInput = document.createElement("div");
            keteranganInput.classList.add("mb-3");
            keteranganInput.innerHTML = `
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan[]" class="form-control" rows="3" placeholder="Contoh: Yang bersangkutan adalah pemilik usaha..." required></textarea>
            `;
            kontenSurat.appendChild(keteranganInput);
        });
    }
});

    </script>
</body>

</html>