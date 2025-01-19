-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 19 Jan 2025 pada 14.00
-- Versi server: 8.0.30
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tugaskripto_dsa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `konten_surat`
--

CREATE TABLE `konten_surat` (
  `id_konten` varchar(50) NOT NULL,
  `id_surat` varchar(50) NOT NULL,
  `tipe_konten` enum('Judul','Sub Judul','Keterangan') NOT NULL,
  `isi_konten` text NOT NULL,
  `urutan` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `konten_surat`
--

INSERT INTO `konten_surat` (`id_konten`, `id_surat`, `tipe_konten`, `isi_konten`, `urutan`, `created_at`) VALUES
('KNT-09380CFA', 'SUR-84B0942F', 'Sub Judul', 'Jabatan : Kepala Desa Bulo', 5, '2025-01-05 12:34:43'),
('KNT-28D65F45', 'SUR-84B0942F', 'Keterangan', 'Yang bersangkutan di atas benar memiliki kegiatan usaha pengembangan, Nama Usaha. di Dusun Bulo, Desa Bulo Kecamatan Bungin.\r\nKarena itu yang bersangkutan diberikan Surat Keterangan Usaha Kecil Menengah (UKM) sebagai bahan pertimbangan untuk mendapatkan Bantuan Modal Usaha, Berupa Kredit Usaha Rakyat (KUR) dari Bank BRI.', 6, '2025-01-05 12:34:43'),
('KNT-45E51718', 'SUR-84B0942F', 'Sub Judul', 'SE', 3, '2025-01-05 12:34:43'),
('KNT-7E5C0CCF', 'SUR-84B0942F', 'Judul', 'Yang Bertanda Tangan Di Bawah Ini :', 1, '2025-01-05 12:34:43'),
('KNT-9ED3616C', 'SUR-84B0942F', 'Judul', 'Menerangkan Bahwa Sesungguhnya', 4, '2025-01-05 12:34:43'),
('KNT-E4984976', 'SUR-84B0942F', 'Sub Judul', 'Nama : Wahyu', 2, '2025-01-05 12:34:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_penggunaan_kunci`
--

CREATE TABLE `log_penggunaan_kunci` (
  `id_log` int NOT NULL,
  `id_kunci` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_penggunaan` datetime DEFAULT CURRENT_TIMESTAMP,
  `tipe_penggunaan` enum('tanda_tangan','verifikasi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dokumen` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_operasi` enum('sukses','gagal') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan_error` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id_pengajuan` varchar(50) NOT NULL,
  `id_surat` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `nik` varchar(20) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `warga_negara` varchar(255) NOT NULL DEFAULT 'Indonesia',
  `pekerjaan` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `keperluan` text NOT NULL,
  `nomor_telepon` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id_pengajuan`, `id_surat`, `nama`, `tempat_lahir`, `tanggal_lahir`, `nik`, `jenis_kelamin`, `warga_negara`, `pekerjaan`, `alamat`, `keperluan`, `nomor_telepon`, `created_at`) VALUES
('678a59bed6a48', 'SUR-84B0942F', 'Khalis', 'Enrekang', '2025-01-12', '17287182178', 'L', 'Indonesia', 'petani', 'bulo', 'usaha bawangg', '089513062824', '2025-01-17 13:23:10'),
('678cd57c24679', 'SUR-84B0942F', 'Khalis', 'bulo', '2001-09-16', '17287182178', 'L', 'Indonesia', 'petani', 'bulo', 'modal bawang', '089513062824', '2025-01-19 10:35:40'),
('678cda0b4888a', 'SUR-84B0942F', 'alifia', 'Enrekang', '2025-01-13', '17287182178', 'P', 'Indonesia', 'petani', 'bulo', 'usahaa', '087740059666', '2025-01-19 10:55:07'),
('678ce4e5f1a4e', 'SUR-84B0942F', 'alifia', 'bulo', '2004-05-14', '17287182178', 'P', 'Indonesia', 'petani', 'buloo', 'modal bawang', '087740059666', '2025-01-19 11:41:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `surat`
--

CREATE TABLE `surat` (
  `id_surat` varchar(50) NOT NULL,
  `logo_instansi` varchar(255) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `kecamatan_instansi` varchar(255) NOT NULL,
  `desa_instansi` varchar(255) NOT NULL,
  `alamat_instansi` text NOT NULL,
  `jenis_surat` enum('Surat Keterangan Usaha','Surat Keterangan Tidak Mampu') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `surat`
--

INSERT INTO `surat` (`id_surat`, `logo_instansi`, `nama_instansi`, `kecamatan_instansi`, `desa_instansi`, `alamat_instansi`, `jenis_surat`, `nomor_surat`, `created_at`) VALUES
('SUR-84B0942F', 'Lambang_Kabupaten_Enrekang.png', 'PEMERINTAHAN KABUPATEN ENREKANG', 'KECEMATAN BUNGIN', 'DESA BULO', 's', 'Surat Keterangan Usaha', '/ DBL / KNB / ', '2025-01-05 12:34:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_kunci`
--

CREATE TABLE `tabel_kunci` (
  `id_kunci` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_kunci` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `private_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_dibuat` datetime NOT NULL,
  `tanggal_kadaluarsa` datetime NOT NULL,
  `status` enum('aktif','nonaktif','kadaluarsa') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jumlah_penggunaan` int DEFAULT '0',
  `terakhir_digunakan` datetime DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tabel_kunci`
--

INSERT INTO `tabel_kunci` (`id_kunci`, `kode_kunci`, `private_key`, `public_key`, `tanggal_dibuat`, `tanggal_kadaluarsa`, `status`, `deskripsi`, `jumlah_penggunaan`, `terakhir_digunakan`, `jabatan`, `created_at`, `updated_at`) VALUES
('DSA-20250119-1D8C4C9D', 'wahyu-001', '-----BEGIN PRIVATE KEY-----\r\nMIICXQIBADCCAjUGByqGSM44BAEwggIoAoIBAQCPXcIao1pXWxF1YV7Q64GYn1WU\r\nR9oyAWgexksafEQJ3dAy5x5HZhg8/T7dqSOHcrYUN9cK2wLR2C4m6kGb8wnwUqnx\r\nCRjHyC5JMOZL8G6YR+OFBDbjgE45vcAlW11Ch9FWoSJxnjm1iN+DD4NKovsjpOj4\r\nS9kW1exs+4yEZnGVij6xWy/w+NzM9W3pOHRKg0C+u87g8Duvy/oplyQWpDd0eBvD\r\nBU9k0LsWpLTssbR2NaGuw+KoMnizeD8iYbvvKRO+VDR0vICc/jSKNZI5CTteMIgj\r\nvJfBc8o3ESOq5uLlbLNrOqLSf6LVxp7pDAmxAGdLTMXEz34YAZqNRjSO+r+5Ah0A\r\n6IuzuPjGriiuXaTc1VMO2LXprx8wFAYomJ/eowKCAQBgP1uamKx0qbFeLmZznNy3\r\nG2GXUDJ8qUPwVcFw0B64da3GNjBffiM7KD0OSR2EnS3YC4FWH+G3ePRKR4//Jv8R\r\nC4fsi0FWKOd+HypX0FVlok34Ybfz4xTNKUGZmue9E8Ie0LMGpPyq2a8wnHq1vOMY\r\nbbILyr9wSAKQeWWAWtTPVlzIAKkg57EenbrVKpM9M3KrQk7d3B44VS7Adfn0O2dc\r\nneOfaVZ2Op2k9K/NCNENAAB9rrc+plLIPpMRItx4lrbmuC0MR2+jGFE+QCWiGKwr\r\nIDlS2WVUuLZMxv6ZMYq+B70KXOvi6YfdJ5cSytwS8t0mSHusg8aFjvVOh307bL39\r\nBB8CHQC1GlmxhAWb9ZdYWcYoBEEJi9IDTGfBqzMpcW0l\r\n-----END PRIVATE KEY-----\r\n', '-----BEGIN PUBLIC KEY-----\r\nMIIDQjCCAjUGByqGSM44BAEwggIoAoIBAQCPXcIao1pXWxF1YV7Q64GYn1WUR9oy\r\nAWgexksafEQJ3dAy5x5HZhg8/T7dqSOHcrYUN9cK2wLR2C4m6kGb8wnwUqnxCRjH\r\nyC5JMOZL8G6YR+OFBDbjgE45vcAlW11Ch9FWoSJxnjm1iN+DD4NKovsjpOj4S9kW\r\n1exs+4yEZnGVij6xWy/w+NzM9W3pOHRKg0C+u87g8Duvy/oplyQWpDd0eBvDBU9k\r\n0LsWpLTssbR2NaGuw+KoMnizeD8iYbvvKRO+VDR0vICc/jSKNZI5CTteMIgjvJfB\r\nc8o3ESOq5uLlbLNrOqLSf6LVxp7pDAmxAGdLTMXEz34YAZqNRjSO+r+5Ah0A6Iuz\r\nuPjGriiuXaTc1VMO2LXprx8wFAYomJ/eowKCAQBgP1uamKx0qbFeLmZznNy3G2GX\r\nUDJ8qUPwVcFw0B64da3GNjBffiM7KD0OSR2EnS3YC4FWH+G3ePRKR4//Jv8RC4fs\r\ni0FWKOd+HypX0FVlok34Ybfz4xTNKUGZmue9E8Ie0LMGpPyq2a8wnHq1vOMYbbIL\r\nyr9wSAKQeWWAWtTPVlzIAKkg57EenbrVKpM9M3KrQk7d3B44VS7Adfn0O2dcneOf\r\naVZ2Op2k9K/NCNENAAB9rrc+plLIPpMRItx4lrbmuC0MR2+jGFE+QCWiGKwrIDlS\r\n2WVUuLZMxv6ZMYq+B70KXOvi6YfdJ5cSytwS8t0mSHusg8aFjvVOh307bL39A4IB\r\nBQACggEACeswDmcLcJ8Xk7GcBzcDMuiCr+1ggGALz8lumh6B20lwjm3JjPhrRFp0\r\nHiUzs/n/P59l+BmZ24etxTFPSkChON7DOoLP7kTo6HCZ+ztAkshVEcwnMeGIUU83\r\nOZT71pjdaL9weIC8NHLRntBGbk1+HdMMfhaMpwrChdqMj3wM7LTp1HGhieHbS64D\r\naUHM3QsVNAqk1xA2rY6M1Dfy0t5f/WtG5ez07DOIJEEniVqa7hCt1720w0pCmk1e\r\n1mPlRkUb3C8N0Vc4UaFAnpD6BLF8z9R/0HXc4HQKCQnOdi/1kwVMJ4lzxcHQKDff\r\nVXNt6n7fr3TIdrFVeHvPWPALoYC8Hg==\r\n-----END PUBLIC KEY-----\r\n', '2025-01-19 11:38:18', '2027-01-19 11:38:18', 'aktif', 'kepala desa', 0, NULL, 'NULL', '2025-01-19 11:38:18', '2025-01-19 11:38:18');

--
-- Trigger `tabel_kunci`
--
DELIMITER $$
CREATE TRIGGER `before_kunci_update` BEFORE UPDATE ON `tabel_kunci` FOR EACH ROW BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_kunci` BEFORE UPDATE ON `tabel_kunci` FOR EACH ROW BEGIN
    -- Update status menjadi kadaluarsa jika sudah melewati tanggal kadaluarsa
    IF NEW.tanggal_kadaluarsa < NOW() AND NEW.status = 'aktif' THEN
        SET NEW.status = 'kadaluarsa';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_pengguna`
--

CREATE TABLE `tabel_pengguna` (
  `id_pengguna` varchar(50) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `jabatan` enum('KEPALA DESA','SEKRETARIS','BENDAHARA','KAUR','KADUS','STAFF','MASYARAKAT') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `level_akses` enum('ADMIN','STAFF','USER') NOT NULL,
  `status` enum('AKTIF','TIDAK_AKTIF') DEFAULT 'AKTIF',
  `alamat` text,
  `tanda_tangan` blob,
  `kode_kunci` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `tabel_pengguna`
--

INSERT INTO `tabel_pengguna` (`id_pengguna`, `foto_profil`, `nama_lengkap`, `email`, `jabatan`, `no_hp`, `level_akses`, `status`, `alamat`, `tanda_tangan`, `kode_kunci`, `created_at`, `updated_at`) VALUES
('USR-C2E1CD24', '../../Assest/img/Upload/wahyuu.jpg', 'wahyu', 'wahyu@gmail.com', 'KEPALA DESA', '0895401407239', 'ADMIN', 'AKTIF', 'bulo', 0x2e2e2f2e2e2f5061636b6167652f46696c655f456e732f74616e64615f74616e67616e5f36373863653437666364653966382e33353535373034382e736967, 'wahyu-001', '2025-01-19 11:39:43', '2025-01-19 11:39:43');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `konten_surat`
--
ALTER TABLE `konten_surat`
  ADD PRIMARY KEY (`id_konten`),
  ADD KEY `id_surat` (`id_surat`);

--
-- Indeks untuk tabel `log_penggunaan_kunci`
--
ALTER TABLE `log_penggunaan_kunci`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_kunci` (`id_kunci`);

--
-- Indeks untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `id_surat` (`id_surat`);

--
-- Indeks untuk tabel `surat`
--
ALTER TABLE `surat`
  ADD PRIMARY KEY (`id_surat`);

--
-- Indeks untuk tabel `tabel_kunci`
--
ALTER TABLE `tabel_kunci`
  ADD PRIMARY KEY (`id_kunci`),
  ADD KEY `idx_kode_kunci` (`kode_kunci`);

--
-- Indeks untuk tabel `tabel_pengguna`
--
ALTER TABLE `tabel_pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `log_penggunaan_kunci`
--
ALTER TABLE `log_penggunaan_kunci`
  MODIFY `id_log` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `konten_surat`
--
ALTER TABLE `konten_surat`
  ADD CONSTRAINT `konten_surat_ibfk_1` FOREIGN KEY (`id_surat`) REFERENCES `surat` (`id_surat`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_penggunaan_kunci`
--
ALTER TABLE `log_penggunaan_kunci`
  ADD CONSTRAINT `log_penggunaan_kunci_ibfk_1` FOREIGN KEY (`id_kunci`) REFERENCES `tabel_kunci` (`id_kunci`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`id_surat`) REFERENCES `surat` (`id_surat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
