<?php
include '../../Koneksi/Koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Data dari form bagian Kop
    $id_surat = $_POST['id_surat'];
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
    $sub_judul = $_POST['sub_judul'] ?? [];
    $keterangan = $_POST['keterangan'] ?? [];

    // Proses upload logo jika ada file baru
    if (!empty($logo_instansi)) {
        $upload_dir = "../../Assest/img/Upload/";
        $upload_path = $upload_dir . basename($logo_instansi);
        if (!move_uploaded_file($_FILES['logo_instansi']['tmp_name'], $upload_path)) {
            $_SESSION['error_doc'] = "Gagal mengunggah logo instansi.";
            header("Location: ../Kelolahdoc.php");
            exit();
        }
        $logo_query = "logo_instansi = '$logo_instansi',";
    } else {
        $logo_query = "";
    }

    // Update data di tabel surat
    $query_surat = "UPDATE surat SET 
                    $logo_query
                    nama_instansi = '$nama_instansi',
                    kecamatan_instansi = '$kecamatan_instansi',
                    desa_instansi = '$desa_instansi',
                    alamat_instansi = '$alamat_instansi',
                    jenis_surat = '$jenis_surat',
                    nomor_surat = '$nomor_surat',
                    updated_at = CURRENT_TIMESTAMP 
                    WHERE id_surat = '$id_surat'";

    if ($koneksi->query($query_surat)) {
        // Hapus konten surat lama
        $query_delete_konten = "DELETE FROM konten_surat WHERE id_surat = '$id_surat'";
        $koneksi->query($query_delete_konten);

        // Simpan konten surat baru
        $urutan = 1;

        foreach ($judul as $item) {
            $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $tipe_konten = 'Judul';
            $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                             VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$item', $urutan, CURRENT_TIMESTAMP)";
            $koneksi->query($query_konten);
            $urutan++;
        }

        foreach ($sub_judul as $item) {
            $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $tipe_konten = 'Sub Judul';
            $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                             VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$item', $urutan, CURRENT_TIMESTAMP)";
            $koneksi->query($query_konten);
            $urutan++;
        }

        foreach ($keterangan as $item) {
            $id_konten = "KNT-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            $tipe_konten = 'Keterangan';
            $query_konten = "INSERT INTO konten_surat (id_konten, id_surat, tipe_konten, isi_konten, urutan, created_at) 
                             VALUES ('$id_konten', '$id_surat', '$tipe_konten', '$item', $urutan, CURRENT_TIMESTAMP)";
            $koneksi->query($query_konten);
            $urutan++;
        }

        $_SESSION['success_doc'] = "Surat berhasil diperbarui.";
        header("Location: ../Kelolahdoc.php");
    } else {
        $_SESSION['error_doc'] = "Gagal memperbarui surat.";
        header("Location: ../Kelolahdoc.php");
    }
    exit();
}
?>
