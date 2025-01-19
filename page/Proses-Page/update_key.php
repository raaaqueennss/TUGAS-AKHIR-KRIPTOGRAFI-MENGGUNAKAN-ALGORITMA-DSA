<?php
session_start();
require '../../Koneksi/Koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    // Validasi input
    if (!isset($_POST['id_kunci']) || !isset($_POST['kode_kunci']) || 
        !isset($_POST['status']) || !isset($_POST['tanggal_kadaluarsa'])) {
        $_SESSION['pesan'] = 'Data tidak lengkap';
        $_SESSION['tipe'] = 'danger';
        $response['message'] = $_SESSION['pesan'];
        echo json_encode($response);
        exit;
    }
    
    $id = $_POST['id_kunci'];
    $kode = $_POST['kode_kunci'];
    $status = $_POST['status'];
    $kadaluarsa = $_POST['tanggal_kadaluarsa'];
    $deskripsi = $_POST['deskripsi'] ?? '';
    
    // Validasi status
    $valid_statuses = ['aktif', 'nonaktif', 'kadaluarsa'];
    if (!in_array($status, $valid_statuses)) {
        $_SESSION['pesan'] = 'Status tidak valid';
        $_SESSION['tipe'] = 'warning';
        $response['message'] = $_SESSION['pesan'];
        echo json_encode($response);
        exit;
    }
    
    // Update kunci
    $query = "UPDATE tabel_kunci SET 
              kode_kunci = ?, 
              status = ?, 
              tanggal_kadaluarsa = ?,
              deskripsi = ?
              WHERE id_kunci = ?";
              
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('sssss', $kode, $status, $kadaluarsa, $deskripsi, $id);
    
    if ($stmt->execute()) {
        $_SESSION['pesan'] = 'Kunci berhasil diperbarui';
        $_SESSION['tipe'] = 'success';
        $response['success'] = true;
        $response['message'] = $_SESSION['pesan'];
    } else {
        $_SESSION['pesan'] = 'Gagal memperbarui kunci: ' . $koneksi->error;
        $_SESSION['tipe'] = 'danger';
        $response['message'] = $_SESSION['pesan'];
    }
    
    echo json_encode($response);
} else {
    http_response_code(405);
    $_SESSION['pesan'] = 'Method not allowed';
    $_SESSION['tipe'] = 'danger';
    echo json_encode(['success' => false, 'message' => $_SESSION['pesan']]);
} 