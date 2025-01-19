<?php
// Mulai session
session_start();
require '../../Koneksi/Koneksi.php';
// Tentukan folder untuk menyimpan kunci DSA
define('KUNCI_DSA_FOLDER', '../../Package/Kunci/');
// Pastikan folder untuk menyimpan kunci ada
if (!is_dir(KUNCI_DSA_FOLDER)) {
    mkdir(KUNCI_DSA_FOLDER, 0777, true);
}
// Periksa apakah tombol submit diklik
if (isset($_POST['buat_kunci_btn'])) {
    try {
        // Validasi input dengan htmlspecialchars
        $kode_kunci = htmlspecialchars(trim($_POST['kode_kunci']));
        $masa_berlaku = filter_input(INPUT_POST, 'masa_berlaku', FILTER_VALIDATE_INT);
        $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
        // Generate id_kunci yang unik
        $timestamp = time();
        $random = bin2hex(random_bytes(4));
        $id_kunci = "DSA-" . date('Ymd', $timestamp) . "-" . strtoupper($random);
        // Tentukan file kunci
        $paramFile = KUNCI_DSA_FOLDER . $id_kunci . '_param.pem';
        $privateKeyFile = KUNCI_DSA_FOLDER . $id_kunci . '_private.pem';
        $publicKeyFile = KUNCI_DSA_FOLDER . $id_kunci . '_public.pem';
        // Generate DSA parameters first
        $command = "openssl dsaparam -out " . escapeshellarg($paramFile) . " 2048";
        exec($command, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Gagal membuat parameter DSA.");
        }
        // Generate private key using the parameters
        $command = "openssl gendsa -out " . escapeshellarg($privateKeyFile) . " " . escapeshellarg($paramFile);
        exec($command, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Gagal membuat private key DSA.");
        }
        // Generate public key
        $command = "openssl dsa -in " . escapeshellarg($privateKeyFile) . " -pubout -out " . escapeshellarg($publicKeyFile);
        exec($command, $output, $returnCode);
        if ($returnCode !== 0) {
            throw new Exception("Gagal membuat public key DSA.");
        }
        // Baca isi kunci
        $privateKeyPem = file_get_contents($privateKeyFile);
        $publicKeyPem = file_get_contents($publicKeyFile);
        // Hapus file parameter sementara
        unlink($paramFile);
        // Hitung tanggal kadaluarsa
        $tanggal_dibuat = date('Y-m-d H:i:s');
        $tanggal_kadaluarsa = date('Y-m-d H:i:s', strtotime("+$masa_berlaku days"));
        $jabatan = "NULL";
        // Prepare statement
        $stmt = $koneksi->prepare("INSERT INTO tabel_kunci (
            id_kunci, 
            kode_kunci, 
            private_key, 
            public_key,
            jabatan,
            tanggal_dibuat,
            tanggal_kadaluarsa,
            status,
            deskripsi
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'aktif', ?)");
        $stmt->bind_param("ssssssss", 
            $id_kunci,
            $kode_kunci,
            $privateKeyPem,
            $publicKeyPem,
            $jabatan,
            $tanggal_dibuat,
            $tanggal_kadaluarsa,
            $deskripsi
        );
        if ($stmt->execute()) {
            $_SESSION['pesan'] = "Kunci DSA berhasil dibuat dengan ID: $id_kunci";
            $_SESSION['tipe'] = "success";
        } else {
            throw new Exception("Gagal menyimpan data kunci ke database.");
        }
    } catch (Exception $e) {
        $_SESSION['pesan'] = "Error: " . $e->getMessage();
        $_SESSION['tipe'] = "danger";
        // Hapus file yang mungkin sudah dibuat jika terjadi error
        foreach ([$paramFile, $privateKeyFile, $publicKeyFile] as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
    // Redirect kembali ke halaman management kunci
    header("Location: ../ManagementKunci.php");
    exit;
}
// Jika akses langsung ke file ini
header("Location: ../ManagementKunci.php");
exit;
?>
