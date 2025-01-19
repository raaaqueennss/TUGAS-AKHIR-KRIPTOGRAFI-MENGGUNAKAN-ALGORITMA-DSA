<?php
// Mengatur parameter database
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "tugaskripto_dsa";

// Membuat koneksi ke database MySQL menggunakan MySQLi
$koneksi = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Mengecek apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
} else {
   
}
?>
