<?php
include '../../Koneksi/Koneksi.php';
session_start();

function generateIdPengguna()
{
    return "USR-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validation code
        $requiredFields = ['nama_lengkap', 'email', 'jabatan', 'no_hp', 'level_akses', 'status', 'alamat', 'kode_kunci'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Field $field wajib diisi!");
            }
        }

        // File validation
        if (!isset($_FILES['foto_profil']) || $_FILES['foto_profil']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("Foto profil wajib diunggah!");
        }

        if (!isset($_FILES['tanda_tangan']) || $_FILES['tanda_tangan']['error'] === UPLOAD_ERR_NO_FILE) {
            throw new Exception("Tanda tangan wajib diunggah!");
        }

        // Handle profile photo upload
        if ($_FILES['foto_profil']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = "../../Assest/img/Upload/";
            $uploadFotoProfil = $uploadDir . basename($_FILES['foto_profil']['name']);
            if (!move_uploaded_file($_FILES['foto_profil']['tmp_name'], $uploadFotoProfil)) {
                throw new Exception("Gagal mengunggah foto profil.");
            }
        } else {
            throw new Exception("Terjadi kesalahan saat mengunggah foto profil: " . $_FILES['foto_profil']['error']);
        }

        // Modified signature handling and encryption
        if ($_FILES['tanda_tangan']['error'] === UPLOAD_ERR_OK) {
            $tandaTanganPath = $_FILES['tanda_tangan']['tmp_name'];
            if (!file_exists($tandaTanganPath)) {
                throw new Exception("Tanda tangan tidak ditemukan.");
            }

            // Get private key
            $kodeKunci = $_POST['kode_kunci'];
            $queryKunci = "SELECT private_key FROM tabel_kunci WHERE kode_kunci = ? AND status = 'AKTIF'";
            $stmtKunci = $koneksi->prepare($queryKunci);
            $stmtKunci->bind_param('s', $kodeKunci);
            $stmtKunci->execute();
            $resultKunci = $stmtKunci->get_result();

            if ($resultKunci && $resultKunci->num_rows > 0) {
                $rowKunci = $resultKunci->fetch_assoc();
                $privateKey = trim($rowKunci['private_key']);

                // Read image file and convert to base64
                $imageData = file_get_contents($tandaTanganPath);
                if ($imageData === false) {
                    throw new Exception("Gagal membaca file tanda tangan.");
                }
                
                // Convert image to base64
                $base64TandaTangan = base64_encode($imageData);
                
                // Create a unique filename for the encrypted signature
                $encryptedTandaTanganFile = "../../Package/File_Ens/" . uniqid("tanda_tangan_", true) . ".sig";

                // Create signature using private key with SHA256
                $binary_signature = '';
                if (!openssl_sign($base64TandaTangan, $binary_signature, $privateKey, OPENSSL_ALGO_SHA256)) {
                    $error = openssl_error_string();
                    throw new Exception("Gagal membuat tanda tangan digital: " . ($error ? $error : "Unknown error"));
                }
                
                // Save both the base64 image and signature
                $data_to_save = [
                    'image' => $base64TandaTangan,
                    'signature' => base64_encode($binary_signature)
                ];
                
                // Save as JSON
                if (file_put_contents($encryptedTandaTanganFile, json_encode($data_to_save)) === false) {
                    throw new Exception("Gagal menyimpan tanda tangan yang terenkripsi.");
                }
            } else {
                throw new Exception("Kode Kunci tidak ditemukan atau sudah tidak aktif.");
            }
            $stmtKunci->close();
        } else {
            throw new Exception("Terjadi kesalahan saat mengunggah tanda tangan: " . $_FILES['tanda_tangan']['error']);
        }

        // Generate ID Pengguna
        $idPengguna = generateIdPengguna();

        // Insert into database
        $stmt = $koneksi->prepare("INSERT INTO tabel_pengguna (id_pengguna, foto_profil, nama_lengkap, email, jabatan, no_hp, level_akses, status, alamat, tanda_tangan, kode_kunci, created_at, updated_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");

        if (!$stmt) {
            throw new Exception("Gagal mempersiapkan query: " . $koneksi->error);
        }

        $stmt->bind_param(
            'sssssssssss',
            $idPengguna,
            $uploadFotoProfil,
            $_POST['nama_lengkap'],
            $_POST['email'],
            $_POST['jabatan'],
            $_POST['no_hp'],
            $_POST['level_akses'],
            $_POST['status'],
            $_POST['alamat'],
            $encryptedTandaTanganFile,
            $kodeKunci
        );

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data pengguna: " . $stmt->error);
        }

        $_SESSION['success_pengguna'] = "Pengguna berhasil ditambahkan.";
        header("Location: ../Pengguna.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_pengguna'] = $e->getMessage();
        header("Location: ../Pengguna.php");
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($koneksi)) $koneksi->close();
    }
}