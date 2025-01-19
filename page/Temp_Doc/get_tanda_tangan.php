<?php
include '../../Koneksi/Koneksi.php';

/**
 * Ambil data pengguna berdasarkan jabatan dan verifikasi tanda tangan.
 * 
 * @param string $jabatan
 * @param mysqli $koneksi
 * @return array|null Data pengguna termasuk tanda tangan, nama lengkap, alamat, created_at, dan status verifikasi tanda tangan.
 */
function getPenggunaByJabatan($jabatan, $koneksi) {
    $query = "SELECT nama_lengkap, jabatan, alamat, tanda_tangan, DATE_FORMAT(created_at, '%d %M %Y') AS created_at 
              FROM tabel_pengguna WHERE jabatan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $jabatan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Verifikasi apakah tanda tangan ada di folder ../../Package/File_Ens/
        $tandaTanganPath = '../../Package/File_Ens/' . $data['tanda_tangan'];
        $tandaTanganExists = file_exists($tandaTanganPath); // Menyimpan status verifikasi

        if ($tandaTanganExists) {
            $_SESSION['message'] = 'Tanda tangan cocok.';
        } else {
            $_SESSION['message'] = 'Tanda tangan tidak cocok.';
        }

        // Menambahkan status verifikasi ke dalam data pengguna
        $data['tanda_tangan_exists'] = $tandaTanganExists;

        $stmt->close();
        return $data;
    }

    $stmt->close();
    return null;
}
?>
