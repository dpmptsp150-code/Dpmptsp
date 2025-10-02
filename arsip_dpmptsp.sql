-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Okt 2025 pada 05.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arsip_dpmptsp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bentuk_izin`
--

CREATE TABLE `bentuk_izin` (
  `id` int(11) NOT NULL,
  `jenis_izin_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bentuk_izin`
--

INSERT INTO `bentuk_izin` (`id`, `jenis_izin_id`, `nama`, `deskripsi`, `created_at`) VALUES
(7, 1, 'IPSD', 'Izin Pembangunan Sekolah Dasar', '2025-09-18 06:22:58'),
(8, 3, 'ITAK', 'Izin Trayek Angkutan Kota', '2025-09-18 06:23:35'),
(9, 4, 'SKPRI', 'Surat Keterangan Pendirian Rumah Ibadah', '2025-09-22 00:12:25'),
(10, 4, 'SRO', 'Surat Rekomendasi Ormas', '2025-09-22 00:12:37'),
(11, 2, 'IPA', 'Izin Pemasangan Apotek', '2025-09-22 00:13:04'),
(12, 2, 'ITO', 'Izin Toko Obat', '2025-09-22 00:13:15'),
(13, 2, 'IZINMENDIRIKANKLINIK', 'Izin Mendirikan Klinik', '2025-09-22 00:13:41'),
(14, 2, 'OPERASIONALKLINIK', 'Izin Operasional Klinik', '2025-09-22 00:13:55'),
(15, 2, 'OPERASIONALKLINIKKECANTIKAN', 'Izin Operasional Klinik Kecantikan', '2025-09-22 00:14:31'),
(16, 2, 'OPTIK', 'Surat Izin Penyelenggaraan Optikal', '2025-09-22 00:14:51'),
(17, 2, 'SIKBIDAN', 'Surat Izin Kerja Bidan', '2025-09-22 00:15:05'),
(18, 2, 'SIKPERAWAT', 'Surat Izin Kerja Perawat', '2025-09-22 00:15:23'),
(19, 2, 'SIKTGZI', 'Izin Kerja Tenaga Kerja Gizi', '2025-09-22 00:15:42'),
(20, 2, 'SIPA', 'Surat Izin Praktek Apoteker', '2025-09-22 00:16:06'),
(21, 2, 'SIPBIDAN', 'Surat Izin Praktek Bidan', '2025-09-22 00:16:41'),
(22, 2, 'SIPDRG', 'Surat Izin Praktek Dokter Gigi', '2025-09-22 00:16:52'),
(23, 2, 'SIPP', 'Surat Izin Praktik Perawat', '2025-09-22 00:17:04'),
(24, 2, 'SIPUMUM', 'Surat Izin Praktek Dokter Umum', '2025-09-22 00:17:23'),
(25, 2, 'STPT', 'Izin Penyehat Tradisional', '2025-09-22 00:17:32'),
(26, 2, 'IUSKH', 'Izin Usaha Sarana Kesehatan Hewan', '2025-09-22 00:17:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokumen`
--

CREATE TABLE `dokumen` (
  `id` int(11) NOT NULL,
  `nama_pemilik` varchar(100) DEFAULT NULL,
  `nama_perusahaan` varchar(150) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `tahun` varchar(4) DEFAULT NULL,
  `nomor_surat` varchar(100) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `jenis_izin_id` int(11) DEFAULT NULL,
  `bentuk_izin_id` int(11) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `tgl_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `drive_file_id` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokumen`
--

INSERT INTO `dokumen` (`id`, `nama_pemilik`, `nama_perusahaan`, `tanggal`, `tahun`, `nomor_surat`, `kategori`, `jenis_izin_id`, `bentuk_izin_id`, `file`, `file_path`, `tgl_upload`, `drive_file_id`) VALUES
(23, 'king', 'ptr', NULL, '2016', '55545684856546', '', NULL, NULL, 'uploads/1758686686_logo3.png', NULL, '2025-09-24 04:04:46', NULL),
(24, 'ronie pethan', 'decull pt barca indo', NULL, '2023', '200546465626', '', NULL, NULL, 'uploads/1759286149_peta-OpenStreetMap-Peta.com.png', NULL, '2025-10-01 02:35:49', NULL),
(25, 'ronie pethan', 'decull pt barca indo', NULL, '2023', '200546465626', '', NULL, NULL, 'uploads/1759288000_peta-OpenStreetMap-Peta.com.png', NULL, '2025-10-01 03:06:40', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_izin`
--

CREATE TABLE `jenis_izin` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_izin`
--

INSERT INTO `jenis_izin` (`id`, `nama`, `deskripsi`, `created_at`) VALUES
(1, 'Pendidikan', 'Perizinan Bidang Pendidikan', '2025-09-18 06:09:37'),
(2, 'Kesehatan', 'Perizinan Bidang Kesehatan', '2025-09-18 06:10:05'),
(3, 'Trayek', 'Izin Trayek / Usaha Angkutan Kota', '2025-09-18 06:10:27'),
(4, 'Keagamaan', 'Perizinan Bidang Keagamaan', '2025-09-22 00:11:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `role`, `created_at`) VALUES
(1, 'devweb', '$2y$10$IMleI6oPVX31ez8zL7661eXiVRiAHDE29JIWPEoJtfIx0piNK0LS.', 'Pengembang Web', 'admin', '2025-09-23 02:54:33'),
(2, 'adminarsip', '$2y$10$GhuA9q.zWe/.3q.Fni/2S.ge2gOzvzy9VZEOji/ufquftySsN/ToW', 'Admin Arsip', 'admin', '2025-09-23 02:54:33'),
(3, 'user1', '$2y$10$Z9fmx4ufL2oVUPiH2tYH3uablZNgSJajbSM9lyU7Cdp85H1Uxp0mm', 'User 1', 'user', '2025-09-23 02:54:33'),
(4, 'user2', '$2y$10$YfuvMy0dIAxsekkVw6nY2.pjQ3OEATYA08aiMCdiI8JA7DvNwfHMi', 'User 2', 'user', '2025-09-23 02:54:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bentuk_izin`
--
ALTER TABLE `bentuk_izin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jenis_izin` (`jenis_izin_id`);

--
-- Indeks untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_dokumen_jenisizin` (`jenis_izin_id`),
  ADD KEY `fk_dokumen_bentukizin` (`bentuk_izin_id`);

--
-- Indeks untuk tabel `jenis_izin`
--
ALTER TABLE `jenis_izin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bentuk_izin`
--
ALTER TABLE `bentuk_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `jenis_izin`
--
ALTER TABLE `jenis_izin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bentuk_izin`
--
ALTER TABLE `bentuk_izin`
  ADD CONSTRAINT `fk_jenis_izin` FOREIGN KEY (`jenis_izin_id`) REFERENCES `jenis_izin` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `dokumen`
--
ALTER TABLE `dokumen`
  ADD CONSTRAINT `fk_dokumen_bentukizin` FOREIGN KEY (`bentuk_izin_id`) REFERENCES `bentuk_izin` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dokumen_jenisizin` FOREIGN KEY (`jenis_izin_id`) REFERENCES `jenis_izin` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
