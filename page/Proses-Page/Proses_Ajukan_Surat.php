<?php
session_start();

// Include your database connection file
include '../../Koneksi/Koneksi.php'; // Ensure this file has your database connection code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture form data
    $id_pengajuan = uniqid(); // Generate a unique ID for the application
    $id_surat = $_POST['jenisSurat'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempatLahir']);
    $tanggal_lahir = $_POST['tanggalLahir'];
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $jenis_kelamin = $_POST['jenisKelamin'];
    $warga_negara = mysqli_real_escape_string($koneksi, $_POST['wargaNegara']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $nomor_telepon = mysqli_real_escape_string($koneksi, $_POST['nomorTelepon']); // Capture phone number from input

    // Prepare the SQL query
    $query = "INSERT INTO pengajuan_surat (id_pengajuan, id_surat, nama, tempat_lahir, tanggal_lahir, nik, jenis_kelamin, warga_negara, pekerjaan, alamat, keperluan, nomor_telepon)
              VALUES ('$id_pengajuan', '$id_surat', '$nama', '$tempat_lahir', '$tanggal_lahir', '$nik', '$jenis_kelamin', '$warga_negara', '$pekerjaan', '$alamat', '$keperluan', '$nomor_telepon')";

    // Execute the query
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['message'] = 'Pengajuan surat berhasil dikirim!';
        $_SESSION['message_type'] = 'success';
        $message = "Selamat Datang\n";
        $message .= "Kantor Desa Bulo\n";
        $message .= "Kecamatan Bungin, Kabupaten Enrekang\n";
        $message .= "----------------------------\n";
        $message .= "Pengajuan Surat Anda\n\n";
        $message .= "ID Pengajuan: $id_pengajuan\n";
        $message .= "Nama: $nama\n";
        $message .= "Tempat Lahir: $tempat_lahir\n";
        $message .= "Tanggal Lahir: $tanggal_lahir\n";
        $message .= "NIK: $nik\n";
        $message .= "Jenis Kelamin: $jenis_kelamin\n";
        $message .= "Warga Negara: $warga_negara\n";
        $message .= "Pekerjaan: $pekerjaan\n";
        $message .= "Alamat: $alamat\n";
        $message .= "Keperluan: $keperluan\n";
        $message .= "Nomor Telepon: $nomor_telepon\n";

        // Send the message using cURL to Fonnte API
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target' => "$nomor_telepon|Fonnte|Admin", // Adjusted target format
                'message' => $message,
                'countryCode' => '62', // Assuming this is the country code for Indonesia
                // Removed unnecessary fields
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: oqUHRaMFU2SBeHswEF7f' // Your actual token
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
            echo $error_msg; // Handle the error message
        } else {
            echo $response; // Log or handle the response
        }

    } else {
        // Store error message in session
        $_SESSION['message'] = 'Error: ' . mysqli_error($koneksi);
        $_SESSION['message_type'] = 'error'; 
    }

    // Close the database connection
    mysqli_close($koneksi);

    // Redirect to the index page
    header("Location: ../../index-Pengguna.php");
    exit();
} else {
    // Redirect back if the request method is not POST
    header("Location: ../../index-Pengguna.php");
    exit();
}
?>
