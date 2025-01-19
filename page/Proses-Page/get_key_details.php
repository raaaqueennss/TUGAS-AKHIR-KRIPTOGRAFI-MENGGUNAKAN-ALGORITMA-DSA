<?php
session_start();
require '../../Koneksi/Koneksi.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => '', 'key' => null];
    
    if (!isset($_POST['id'])) {
        $_SESSION['pesan'] = 'ID kunci tidak ditemukan';
        $_SESSION['tipe'] = 'danger';
        $response['message'] = $_SESSION['pesan'];
        echo json_encode($response);
        exit;
    }
    
    $id = $_POST['id'];
    
    $query = "SELECT * FROM tabel_kunci WHERE id_kunci = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('s', $id);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $response['success'] = true;
            $response['key'] = $row;
        } else {
            $_SESSION['pesan'] = 'Data kunci tidak ditemukan';
            $_SESSION['tipe'] = 'warning';
            $response['message'] = $_SESSION['pesan'];
        }
    } else {
        $_SESSION['pesan'] = 'Gagal mengambil data kunci: ' . $koneksi->error;
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