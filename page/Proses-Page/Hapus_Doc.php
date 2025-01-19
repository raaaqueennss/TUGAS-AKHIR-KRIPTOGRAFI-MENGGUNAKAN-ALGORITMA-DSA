<?php
include '../../Koneksi/Koneksi.php';

session_start(); // Pastikan session sudah dimulai

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_dokumen = $_POST['id_dokumen'];

    try {
        // Query untuk menghapus dokumen berdasarkan id_dokumen
        $query = "DELETE FROM dokumen WHERE id_dokumen = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $id_dokumen);

        if ($stmt->execute()) {
            $_SESSION['success_doc'] = "Dokumen berhasil dihapus";
            header("Location: ../Kelolahdoc.php");
            exit();
        } else {
            throw new Exception("Gagal menghapus dokumen");
        }
    } catch (Exception $e) {
        $_SESSION['error_doc'] = $e->getMessage();
        header("Location: ../Kelolahdoc.php");
        exit();
    }
}
?>
