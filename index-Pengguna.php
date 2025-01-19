<?php
session_start();
require './Koneksi/Koneksi.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./Assest/index-Pengguna.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Selamat Datang, Di Kantor Desa Bulo</title>
</head>

<body>
    <!-- Header Section -->
    <header class="bg-white shadow-sm">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <img src="./Assest/img/Lambang_Kabupaten_Enrekang.png" alt="Logo Enrekang" height="60">
                        <div class="ms-3">
                            <h1 class="h4 mb-0">Kantor Desa Bulo</h1>
                            <p class="mb-0 text-muted">Kecamatan Bungin, Kabupaten Enrekang</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        <div class="contact-info text-end">
                            <p class="mb-0"><i class="fas fa-phone me-2"></i>0822116888927</p>
                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>CWQ7+48C, Bulo, Bungin, Enrekang Regency, South Sulawesi 91761</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- Hero Section -->
    <section class="hero-section position-relative">

        <?php


        // Check if there's a message in the session
        if (isset($_SESSION['message'])) {
            // Determine the alert type based on the message type
            $alertType = ($_SESSION['message_type'] == 'success') ? 'success' : 'danger';

            // Display the message
            echo '<div class="alert alert-' . $alertType . ' alert-dismissible fade show" role="alert">';
            echo htmlspecialchars($_SESSION['message']);
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            echo '</div>';

            // Unset the message after displaying
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="display-4 mb-4">Selamat Datang di Portal Layanan Desa Bulo</h2>
                    <p class="lead mb-4">Melayani kebutuhan administrasi masyarakat Desa Bulo, Kecamatan Bungin dengan profesional dan terpercaya</p>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalPengajuanSurat">
                        <i class="fas fa-file-alt me-2"></i>Ajukan Surat Sekarang
                    </button>
                    <br>
                    <br>
                    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ceksurat">
                        <i class="fas fa-file-alt me-2"></i>Cek Surat
                    </button>

                </div>


                <div class="col-md-6">
                    <img src="./Assest/img/Lambang_Kabupaten_Enrekang.png" alt="Kantor Desa Bulo" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="bg-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title"><i class="fas fa-map-marker-alt text-primary me-2"></i>Lokasi Kami</h3>
                            <div class="ratio ratio-16x9 mt-3">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d378347.46412679815!2d119.82678777090928!3d-3.539766643611498!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d943dff1405d061%3A0x8845405f83c3f5b5!2sKantor%20Desa%20Bulo%2C%20Kecamatan%20Bungin%2C%20Kabupaten%20Enrekang!5e0!3m2!1sen!2sid!4v1737099737965!5m2!1sen!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                            <p class="mt-3 mb-0">CWQ7+48C, Bulo, Bungin, Kabupaten Enrekang, Sulawesi Selatan 91761</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="card-title"><i class="fas fa-clock text-primary me-2"></i>Jam Operasional</h3>
                            <ul class="list-unstyled mt-3">
                                <li class="mb-2"><i class="fas fa-calendar-day me-2"></i>Senin - Jumat</li>
                                <li class="mb-2"><i class="fas fa-clock me-2"></i>08:00 - 15:30 WITA</li>
                                <li class="mb-2"><i class="fas fa-ban me-2"></i>Sabtu & Minggu Tutup</li>
                                <li><i class="fas fa-info-circle me-2"></i>Hari Libur Nasional Tutup</li>
                            </ul>
                            <div class="social-media mt-4">
                                <h5>Ikuti Kami</h5>
                                <div class="d-flex gap-3 mt-2">
                                    <a href="#" class="text-primary fs-4"><i class="fab fa-facebook"></i></a>
                                    <a href="#" class="text-info fs-4"><i class="fab fa-twitter"></i></a>
                                    <a href="#" class="text-danger fs-4"><i class="fab fa-instagram"></i></a>
                                    <a href="https://wa.me/+6222116888927" class="text-success fs-4"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   <!-- Modal Pengajuan Surat -->
<div class="modal fade" id="modalPengajuanSurat" tabindex="-1" aria-labelledby="modalPengajuanSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPengajuanSuratLabel">Form Pengajuan Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPengajuanSurat" method="POST" action="./page/Proses-Page/Proses_Ajukan_Surat.php">
                    <div class="mb-3">
                        <label for="jenisSurat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="jenisSurat" name="jenisSurat" required>
                            <option value="">Pilih Jenis Surat</option>
                            <?php
                            // Query untuk mengambil data unik dari tabel surat
                            $query = "SELECT MIN(id_surat) AS id_surat, jenis_surat FROM surat GROUP BY jenis_surat";
                            $result = mysqli_query($koneksi, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['id_surat'] . '">' . htmlspecialchars($row['jenis_surat']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Form fields for other inputs -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="tempatLahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempatLahir" name="tempatLahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggalLahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggalLahir" name="tanggalLahir" required>
                    </div>
                    <div class="mb-3">
                        <label for="nik" class="form-label">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" required>
                    </div>
                    <div class="mb-3">
                        <label for="jenisKelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenisKelamin" name="jenisKelamin" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="wargaNegara" class="form-label">Warga Negara</label>
                        <input type="text" class="form-control" id="wargaNegara" name="wargaNegara" value="Indonesia" required>
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan</label>
                        <textarea class="form-control" id="keperluan" name="keperluan" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="nomorTelepon" class="form-label">Nomor Telepon</label>
                        <input type="text" class="form-control" id="nomorTelepon" name="nomorTelepon" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Kantor Desa Bulo. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>