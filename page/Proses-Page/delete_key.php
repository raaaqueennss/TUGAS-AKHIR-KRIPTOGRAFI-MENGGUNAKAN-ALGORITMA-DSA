<?php
session_start();
require '../../Koneksi/Koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['id'])) {
            $_SESSION['pesan'] = 'ID kunci tidak ditemukan';
            $_SESSION['tipe'] = 'danger';
            header('Location: ../ManagementKunci.php');
            exit;
        }
        
        $id = $_POST['id'];
        
        // Cek penggunaan kunci
        $check_query = "SELECT jumlah_penggunaan FROM tabel_kunci WHERE id_kunci = ?";
        $check_stmt = $koneksi->prepare($check_query);
        if (!$check_stmt) {
            throw new Exception('Database error: ' . $koneksi->error);
        }
        
        $check_stmt->bind_param('s', $id);
        if (!$check_stmt->execute()) {
            throw new Exception('Database error: ' . $check_stmt->error);
        }
        
        $result = $check_stmt->get_result();
        
        if ($result->num_rows === 0) {
            $_SESSION['pesan'] = 'Kunci tidak ditemukan';
            $_SESSION['tipe'] = 'danger';
            header('Location: ../ManagementKunci.php');
            exit;
        }
        
        $usage_count = $result->fetch_assoc()['jumlah_penggunaan'];
        
        if ($usage_count > 0) {
            $_SESSION['pesan'] = 'Kunci tidak dapat dihapus karena sudah digunakan sebanyak ' . $usage_count . ' kali';
            $_SESSION['tipe'] = 'warning';
            header('Location: ../ManagementKunci.php');
            exit;
        }
        
        // Hapus kunci
        $query = "DELETE FROM tabel_kunci WHERE id_kunci = ? AND jumlah_penggunaan = 0";
        $stmt = $koneksi->prepare($query);
        if (!$stmt) {
            throw new Exception('Database error: ' . $koneksi->error);
        }
        
        $stmt->bind_param('s', $id);
        if (!$stmt->execute()) {
            throw new Exception('Database error: ' . $stmt->error);
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception('Kunci tidak dapat dihapus');
        }
        
        $_SESSION['pesan'] = 'Kunci berhasil dihapus';
        $_SESSION['tipe'] = 'success';
        
    } catch (Exception $e) {
        $_SESSION['pesan'] = $e->getMessage();
        $_SESSION['tipe'] = 'danger';
    }
    
} else {
    $_SESSION['pesan'] = 'Method not allowed';
    $_SESSION['tipe'] = 'danger';
}

header('Location: ../ManagementKunci.php');
exit; 