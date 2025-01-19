<?php
// Memasukkan file yang berisi fungsi query
include '../../Koneksi/Koneksi.php';
include './get_tanda_tangan.php';

$id_pengajuan = isset($_GET['id_pengajuan']) ? $koneksi->real_escape_string($_GET['id_pengajuan']) : null;

// Jika id_pengajuan tidak tersedia, hentikan eksekusi
if (!$id_pengajuan) {
    die('ID Pengajuan tidak valid');
}

// Jabatan yang akan dicari
$jabatan = 'KEPALA DESA';

// Memanggil fungsi untuk mendapatkan data pengguna berdasarkan jabatan
$dataPengguna = getPenggunaByJabatan($jabatan, $koneksi);

// Query untuk mendapatkan data pengajuan surat berdasarkan id_pengajuan
$sqlPengajuan = "SELECT * FROM pengajuan_surat WHERE id_pengajuan = '$id_pengajuan'";
$resultPengajuan = $koneksi->query($sqlPengajuan);

// Mengecek apakah data ditemukan
$dataPengajuan = $resultPengajuan->fetch_assoc();

// Siapkan data untuk ditampilkan
$imageBase64 = null;
$namaLengkap = $dataPengguna['nama_lengkap'] ?? null;
$alamat = $dataPengguna['alamat'] ?? null;
$createdAt = $dataPengguna['created_at'] ?? null;

// Menampilkan data pengajuan surat
$nama = $dataPengajuan['nama'] ?? null;
$tempatLahir = $dataPengajuan['tempat_lahir'] ?? null;
$tanggalLahir = $dataPengajuan['tanggal_lahir'] ?? null;
$nik = $dataPengajuan['nik'] ?? null;
$jenisKelamin = $dataPengajuan['jenis_kelamin'] ?? null;
$wargaNegara = $dataPengajuan['warga_negara'] ?? null;
$pekerjaan = $dataPengajuan['pekerjaan'] ?? null;
$alamatPengajuan = $dataPengajuan['alamat'] ?? null;
$keperluan = $dataPengajuan['keperluan'] ?? null;
$nomorTelepon = $dataPengajuan['nomor_telepon'] ?? null;
$createdAtPengajuan = $dataPengajuan['created_at'] ?? null;

// Mengambil jumlah pengajuan surat yang ada
$sqlJumlahPengajuan = "SELECT COUNT(*) AS jumlah FROM pengajuan_surat WHERE YEAR(created_at) = YEAR(CURRENT_DATE)";
$resultJumlahPengajuan = $koneksi->query($sqlJumlahPengajuan);
$dataJumlahPengajuan = $resultJumlahPengajuan->fetch_assoc();

// Mendapatkan bulan dan tahun saat ini
$bulan = date('m'); // Bulan saat ini
$tahun = date('Y'); // Tahun saat ini

// Menentukan nomor surat
$nomorSurat = $dataJumlahPengajuan['jumlah'] + 1 . " / DBL / KNB / " . $bulan . " / " . $tahun;

// Jika data tanda tangan ditemukan
if ($dataPengguna && $dataPengguna['tanda_tangan']) {
    $jsonData = file_get_contents($dataPengguna['tanda_tangan']);
    if ($jsonData !== false) {
        $data = json_decode($jsonData, true);
        if (isset($data['image'])) {
            $imageBase64 = $data['image'];
        }
    }
}
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Assest/ArsipDoc.css">
    <title>Template Surat</title>
    <style>
        .container {
            max-width: 900px !important;
            margin: 0 auto;
            padding: 20px 40px;
        }

        .kop-surat {
            padding: 20px 0;
            text-align: center;
            margin-bottom: 20px;
        }

        .content-wrapper {
            width: 100%;
            padding: 0;
        }

        .row {
            margin: 0;
            width: 100%;
        }

        .logo-container {
            padding-right: 0;
            text-align: right;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: -65px;
        }

        .instansi {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .desa {
            font-size: 18px;
            margin: 5px 0;
        }

        .garis-header {
            margin: 10px 0;
            width: 100%;

        }

        .garis-tebal {
            border-top: 3px solid #000;
            margin-bottom: 2px;
        }

        .garis-tipis {
            border-top: 1px solid #000;
        }

        .judul-surat {
            text-align: center;
            margin: 30px 0 10px 0;
            padding: 0;
        }

        .judul-surat h2 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .nomor-surat {
            text-align: center;
            margin-bottom: 30px;
        }

        .pembuka {
            text-align: left;
            margin: 30px 0;
            padding: 0;
        }

        .data-surat {
            margin: 20px 0;
            margin-left: 30px;
        }

        .data-row {
            display: grid;
            grid-template-columns: 180px 20px auto;
            margin-bottom: 10px;
            align-items: start;
        }

        .data-label {
            font-weight: normal;
        }

        .data-separator {
            text-align: center;
        }

        .data-value {
            text-align: left;
        }

        .keterangan {
            margin: 30px 0;
            margin-left: 30px;
            text-align: justify;
            line-height: 1.6;
        }

        .keterangan-text {
            margin-bottom: 20px;
        }

        .penutup {
            margin: 30px 0;
            margin-left: 30px;
            text-align: justify;
            line-height: 1.6;
        }

        .ttd-container {
            margin-top: 40px;
            margin-right: 30px;
            text-align: right;
        }

        .ttd-content {
            display: inline-block;
            text-align: left;
        }

        .ttd-tanggal {
            margin-bottom: 15px;
            text-align: center;
        }

        .ttd-jabatan {
            margin-bottom: 20px;
            /* Ruang untuk tanda tangan */
            text-align: center;
        }

        .ttd-tangan {
            text-align: center;
            /* Pusatkan konten di dalam div */
            margin: 10px 0;
        }

        .ttd-tangan img {
            max-width: 100%;
            /* Pastikan gambar tidak lebih besar dari lebar container */
            max-height: 150px;
            /* Batasi tinggi gambar menjadi maksimal 150px */
            height: auto;
            /* Sesuaikan tinggi secara proporsional */
            
            
        }


        .ttd-nama {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: center;
        }


        .alamat,
        .kontak {
            font-size: 14px;
            margin: 2px 0;
        }

        @media print {

            /* Pengaturan umum untuk print */
            body {
                margin: 0;
                padding: 0;
                background: #fff;
            }

            .container {
                max-width: 100% !important;
                width: 100%;
                padding: 0;
                margin: 0;
            }

            /* Memastikan konten muat dalam satu halaman */
            .content-wrapper {
                padding: 1.5cm 2cm;
                /* Margin print standar */
            }

            /* Pengaturan font untuk print */
            * {
                font-family: "Times New Roman", Times, serif;
                font-size: 12pt;
            }

            /* Pengaturan header */
            .instansi {
                font-size: 14pt;
            }

            .desa {
                font-size: 12pt;
            }

            .alamat,
            .kontak {
                font-size: 10pt;
            }

            /* Pengaturan garis */
            .garis-header {
                margin: 10px 0;
                width: 100%;
            }

            /* Pengaturan data surat */
            .data-row {
                margin-bottom: 8pt;
            }

            /* Pengaturan tanda tangan */
            .ttd-container {
                margin-top: 30pt;
            }

            /* Sembunyikan elemen yang tidak perlu di print */
            .no-print {
                display: none !important;
            }

            /* Hindari pemisahan konten */
            .keterangan,
            .penutup,
            .ttd-container {
                page-break-inside: avoid;
            }

            /* Pengaturan page break */
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="content-wrapper">
            <?php

            // Mengecek apakah ada pesan yang diset dalam session
            if (isset($_SESSION['message'])) {
                // Menentukan jenis alert berdasarkan pesan
                $alertClass = (strpos($_SESSION['message'], 'valid') !== false) ? 'alert-success' : 'alert-danger';
                $alertMessage = $_SESSION['message'];

                // Menampilkan alert
                echo "<div class='alert $alertClass alert-dismissible fade show' role='alert'>
            $alertMessage
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";

                // Menghapus pesan setelah ditampilkan
                unset($_SESSION['message']);
            }
            ?>
            <div class="kop-surat">
                <div class="row">
                    <div class="col-auto logo-container">
                        <img src="../../Assest/img/Lambang_Kabupaten_Enrekang.png" alt="Logo" class="logo rounded">
                    </div>
                    <div class="col">
                        <h1 class="instansi">PEMERINTAHAN DAERAH KABUPATEN ENREKANG</h1>
                        <p class="desa">KECAMATAN BUNGI</p>
                        <p class="desa">DESA BULO</p>
                        <p class="alamat">Alamat Jalan Poros Bulo Baruka, Kode Pos 91763</p>
                        <div class="garis-header">
                            <div class="garis-tebal"></div>
                            <div class="garis-tipis"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="judul-surat">
                <h2>SURAT KETERANGAN USAHA</h2>
                <div class="nomor-surat">
                    <p>Nomor Surat: <?php echo $nomorSurat; ?></p>
                </div>
            </div>

            <div class="pembuka">
                Yang bertanda tangan di bawah ini :
            </div>

            <div class="data-surat">
                <div class="data-row">
                    <div class="data-label">Nama</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?= htmlspecialchars($namaLengkap); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Jabatan</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?= htmlspecialchars($jabatan); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Alamat</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?= htmlspecialchars($alamat); ?></div>
                </div>
            </div>

            <div>
                Menerangkan dengan sesungguhnya bahwa :
            </div>

            <div class="data-surat">
                <div class="data-row">
                    <div class="data-label">Nama</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($nama); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Tempat/Tgl lahir</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($tempatLahir . ', ' . $tanggalLahir); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">NIK</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($nik); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Jenis Kelamin</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($jenisKelamin); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Warga Negara</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($wargaNegara); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Pekerjaan</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($pekerjaan); ?></div>
                </div>
                <div class="data-row">
                    <div class="data-label">Alamat</div>
                    <div class="data-separator">:</div>
                    <div class="data-value"><?php echo htmlspecialchars($alamatPengajuan); ?></div>
                </div>
            </div>



            <div class="keterangan">
                <div class="keterangan-text">
                    Yang bersangkutan di atas benar memiliki kegiatan usaha pengembangan, <?php echo htmlspecialchars($pekerjaan); ?>. di Dusun Bulo, Desa Bulo Kecamatan Bungin.
                    Karena itu yang bersangkutan diberikan Surat Keterangan Usaha Kecil Menengah (UKM) sebagai bahan pertimbangan untuk mendapatkan Bantuan Modal Usaha, Berupa Kredit Usaha Rakyat (KUR) dari Bank BRI.
                </div>
            </div>

            <div class="penutup">
                Demikian surat keterangan ini dibuat dengan sebenarnya dan diberikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.
            </div>

            <div class="ttd-container">
                <div class="ttd-content">
                    <div class="ttd-tanggal">
                        Enrekang, <?= htmlspecialchars($createdAt); ?>
                    </div>
                    <div class="ttd-jabatan">
                        <?= htmlspecialchars($jabatan); ?>
                    </div>
                    <div class="ttd-tangan">
                        <?php if ($imageBase64): ?>
                            <img src="data:image/png;base64,<?= htmlspecialchars($imageBase64); ?>" alt="Tanda Tangan <?= htmlspecialchars($jabatan); ?>">
                        <?php else: ?>
                            <p>Tanda tangan untuk jabatan <?= htmlspecialchars($jabatan); ?> tidak ditemukan.</p>
                        <?php endif; ?>
                    </div>
                    <div class="ttd-nama">
                        <?= htmlspecialchars($namaLengkap); ?>
                    </div>

                </div>
            </div>

            <!-- Konten surat akan ditambahkan di sini -->
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>