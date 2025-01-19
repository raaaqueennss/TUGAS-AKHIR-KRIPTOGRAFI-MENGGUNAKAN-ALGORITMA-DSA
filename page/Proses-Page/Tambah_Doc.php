<?php
include '../../Koneksi/Koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Data dari form bagian Kop
    $logo_instansi = $_FILES['logo_instansi']['name'];
    $nama_instansi = $_POST['nama_instansi'];
    $kecamatan_instansi = $_POST['kecamatan_instansi'];
    $desa_instansi = $_POST['desa_instansi'];
    $alamat_instansi = $_POST['alamat_instansi'];
    
    // Data dari form bagian Jenis Surat
    $jenis_surat = $_POST['jenis_surat'];
    $nomor_surat = $_POST['nomor_surat'];

    // Data dari bagian Konten Surat
    $judul = $_POST['judul'] ?? [];
    $sub_judul = isset($_POST['sub_judul']) ? $_POST['sub_judul'] : [];  // Pastikan sub_judul adalah array, tidak perlu explode
    $keterangan = $_POST['keterangan'] ?? [];

    // Generate ID untuk surat
    $id_surat = "SUR-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

    // Upload Logo
    $upload_dir = "../../Assest/img/Upload/";
    $upload_path = $upload_dir . basename($logo_instansi);
    if (!move_uploaded_file($_FILES['logo_instansi']['tmp_name'], $upload_path)) {
        $_SESSION['error_doc'] = "Gagal mengunggah logo instansi.";
        header("Location: ../Kelolahdoc.php");
        exit();
    }

    // Simpan data ke tabel surat
    $query_surat = "INSERT INTO surat (id_surat, logo_instansi, nama_instansi, kecamatan_instansi, desa_instansi, alamat_instansi, jenis_surat, nomor_surat, created_at) 
                    VALUES ('$id_surat', '$logo_instansi', '$nama_instansi', '$kecamatan_instansi', '$desa_instansi', '$alamat_instansi', '$jenis_surat', '$nomor_surat', CURRENT_TIMESTAMP)";
    
    if ($koneksi->query($query_surat)) {
        // Simpan konten surat
        $urutan = 1;
        
        // Menyimpan Judul dan Sub Judul
        foreach ($judul as $index => $item) {
            // Simpan Judul
            $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $tipe_konten = 'Judul';
            $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                             VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$item', $urutan, CURRENT_TIMESTAMP)";
            $koneksi->query($query_konten);
            $urutan++;

            // Simpan Sub Judul (jika ada sub judul)
            if (isset($sub_judul[$index])) {
                $sub_judul_items = explode(",", $sub_judul[$index]); // Memisahkan berdasarkan koma
                foreach ($sub_judul_items as $sub_item) {
                    $sub_item = trim($sub_item); // Menghapus spasi di awal dan akhir
                    $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                    $tipe_konten = 'Sub Judul';
                    $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                                     VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$sub_item', $urutan, CURRENT_TIMESTAMP)";
                    $koneksi->query($query_konten);
                    $urutan++;
                }
            }
        }

        // Menyimpan Keterangan
        foreach ($keterangan as $item) {
            $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $tipe_konten = 'Keterangan';
            $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                             VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$item', $urutan, CURRENT_TIMESTAMP)";
            $koneksi->query($query_konten);
            $urutan++;
        }

        $_SESSION['success_doc'] = "Surat berhasil ditambahkan.";
        header("Location: ../Kelolahdoc.php");
    } else {
        $_SESSION['error_doc'] = "Gagal menambahkan surat.";
        header("Location: ../Kelolahdoc.php");
    }
    exit();
}
?>
