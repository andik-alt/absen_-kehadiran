-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2026 at 04:43 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_attend`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `peserta_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `waktu_checkin` timestamp NULL DEFAULT current_timestamp(),
  `waktu_hadir` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `nama_event` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(150) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `nama_event`, `tanggal`, `lokasi`, `deskripsi`, `created_at`) VALUES
(2, 'RAPAT ANEV PROGRAM DAN ANGGARAN POLRES SINGKAWANG T.A.2026', '2026-11-11', 'AULA SAR POLRES SINGKAWANG', '', '2026-11-10 08:22:51');

-- --------------------------------------------------------

--
-- Table structure for table `peserta`
--

CREATE TABLE `peserta` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `pangkat` varchar(100) DEFAULT NULL,
  `qr_token` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `jabatan` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peserta`
--

INSERT INTO `peserta` (`id`, `event_id`, `nama`, `pangkat`, `qr_token`, `created_at`, `jabatan`) VALUES
(3, 2, 'RIKO NUSWANTORO', 'BRIPKA', 'cdc807b24877bf1bfbbcc69b28d12160', '2026-11-10 08:26:01', 'PS. PAURMIN'),
(4, 2, 'YURIO RAMADHANI', 'BRIPTU', '58fe0e13a7ef8e9891208cecd28791c7', '2026-11-10 08:26:42', 'OPERATOR ANGGARAN'),
(5, 2, 'ENDANG DWI LESTARI', 'BRIPKA', '9d7882b0600bacb6d1127a4f6ca59f8f', '2026-11-10 08:27:14', 'PAURMINTU'),
(6, 2, 'IBNU MAULANA', 'BRIPDA', '7d7de509e64fe529d5356850740f6d84', '2026-11-10 08:27:42', 'OPERATOR ANGGARAN'),
(7, 2, 'M. ZULKARNAIN', 'BRIPDA', '58a21ad43f3288b86b355cf21ab85ac5', '2026-11-10 08:28:14', 'PS PAURMIN'),
(8, 2, 'SRI NURINDAH APRIANI', 'BRIGPOL', '91b636660e4a72f8ca00f14ee1086348', '2026-11-10 08:29:34', 'OPERATOR ANGGARAN'),
(11, 2, 'RUSMONO', 'AIPTU', '61c8c487a2da91e5f79d92bf340d152c', '2026-11-10 08:31:40', 'KAURMINTU'),
(12, 2, 'BIMMO SHENO KRISFIANTO,S.H', 'BRIGPOL', '3c8694981e861f8490d0d31eb5be28ef', '2026-11-11 01:13:53', 'OPERATOR ANGGARAN'),
(13, 2, 'DARU PURWANINGSIH', 'PENDA', '4cafab98f9435097bb9d43da20221c91', '2026-11-11 01:14:26', 'PS. KAURMINTU'),
(14, 2, 'DEDY CANDRA ANGGARA', 'AIPDA', 'b01d2cf76cdf70060a47efeb52e148d7', '2026-11-11 01:28:13', 'OPERATOR ANGGARAN'),
(15, 2, 'YULIANA', 'PENATA', 'bcf8f473ef358e910b68d2dc35fa752c', '2026-11-11 01:28:45', 'PS KAURMINTU'),
(16, 2, 'ANKHASA DWI ARJUNA', 'BRIPDA', 'bf331dbc9daee3efccdf305371ff7d19', '2026-11-11 01:29:28', 'OPERATOR ANGGARAN'),
(17, 2, 'JONI', 'AIPDA', '814c038f7aae92459f7a6be67760e8c9', '2026-11-11 01:30:37', 'PS KAURMINTU'),
(18, 2, 'MARLINA YULIANTI', 'BRIGPOL', '44d38588b622d52c0d827508b8f8d514', '2026-11-11 01:31:53', 'OPERATOR ANGGARAN'),
(19, 2, 'HERMANTO', 'AIPTU', 'cff5f711c0a3db14e6a2afaa5ea80889', '2026-11-11 01:32:22', 'PS. KAURMINTU'),
(20, 2, 'BAMBANG PRAMONO, S.H.', 'BRIPKA', 'd7b1fdfb882a1e6a1d745e91d9625ac9', '2026-11-11 01:32:52', 'OPERATOR ANGGARAN'),
(21, 2, 'BERTHYN PASURUAN', 'AIPTU', '08caed9684166d0807803a5e96385dcd', '2026-11-11 01:33:22', 'KAURMINTU'),
(22, 2, 'YENI RIYANI', 'BRIGPOL', '2b303203e28efa7178749e15ff366599', '2026-11-11 01:33:54', 'OPERATOR ANGGARAN'),
(23, 2, 'LILI YULIANA', '', '2012236d0f7e8382248e82a3b0b0d4d0', '2026-11-11 01:34:32', 'OPERATOR ANGGARAN'),
(24, 2, 'MALKALIANA MALINDA', 'BRIGPOL', '9754dfcd9cd843aa63afb24fc68cc5c5', '2026-11-11 01:35:36', 'OPERATOR ANGGARAN'),
(25, 2, 'NADIA SEPTIANTI,S.H.', 'BRIGPOL', 'df20eef447554a4ef291d0137114da62', '2026-11-11 01:36:15', 'OPERATOR ANGGARAN'),
(26, 2, 'RATTI ASSYIFA, S.H.', 'BRIGPOL', '5c77cbcd8b85fbf2d907f582b1a96c79', '2026-11-11 01:36:38', 'OPERATOR ANGGARAN'),
(27, 2, 'ABDUL AZIZ', 'BRIPTU', 'acae0735a679420f89c7ad5b4772935f', '2026-11-11 01:37:08', 'OPERATOR ANGGARAN'),
(28, 2, 'NIKA SURTANIA, S.H.', 'BRIGPOL', 'e192ca09e870c5e04b54292e7b7d0ade', '2026-11-11 01:37:37', 'OPERATOR ANGGARAN'),
(29, 2, 'ISWANDI', 'AIPDA', '282e28a822b4b06f7454f2456ff61a36', '2026-11-11 01:38:03', 'OPERATOR ANGGARAN'),
(30, 2, 'SUMARSI WALUYOJATI, A.Md.Kep.', 'BRIPKA', '452295d1fcc817adcfe8e56a606b8716', '2026-11-11 01:38:26', 'OPERATOR ANGGARAN'),
(31, 2, 'UMIATI', 'PENDA TK.I', 'f1c18083aab85720bd587a92dddcb9bc', '2026-11-11 01:38:55', 'PS. KASIUM'),
(32, 2, 'RINALDI DWI HUDAYA', 'BRIPKA', 'e5cef33179092bbfcd8a459c991e0ee3', '2026-11-11 01:39:15', 'OPERATOR ANGGARAN'),
(33, 2, 'JOKO PAMEKAS', 'AIPDA', '11d275a15df89b938bef5935056fae61', '2026-11-11 01:39:45', 'PS. KASIUM'),
(34, 2, 'GITA PRATIWI NINGRUM', 'BRIGADIR', '6a05018f4a08f7aa0bfc7e259a9f16e4', '2026-11-11 01:40:11', 'OPERATOR ANGGARAN'),
(35, 2, 'JOKO SUGIHARNO', 'PENATA', 'f3d35686e5d1efadef802f1d96fb1ffb', '2026-11-11 01:40:44', 'PS. KASIUM'),
(36, 2, 'RANGGA UMBARA', 'AIPDA', '21626cff9b332a0110b414d2070b5187', '2026-11-11 01:41:10', 'PS KASIUM'),
(37, 2, 'SRI WAHYUNI', 'PENDA TK I', 'b235f6020cf2208d343f3093f34fd500', '2026-11-11 01:41:37', 'PS KASIUM'),
(38, 2, 'DARUSSAMIN', 'AIPDA', '51f5880a4ed368a8017b6f9fa3fd5ec7', '2026-11-11 01:42:00', 'OPERATOR ANGGARAN'),
(39, 2, 'ERIC SATRIA', 'AIPDA', 'e018c45832363470c3ca1b16bf9e6ed0', '2026-11-11 03:21:03', 'PS KAURMINTU'),
(40, 2, 'SURYA ISRA ISLAMMARYANTI', 'BRIGPOL', '628c575ed7bcb27a0d470b440a13a43f', '2026-11-11 03:21:57', 'OPERATOR ANGGARAN');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','panitia') NOT NULL DEFAULT 'panitia',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$12$HKuxkke8gEyyqw3ADU591eSpqDRf8syM6IydEL.hf3odbRl72EB2a', 'admin', '2026-11-10 06:22:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peserta_id` (`peserta_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peserta`
--
ALTER TABLE `peserta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_token` (`qr_token`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peserta`
--
ALTER TABLE `peserta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`peserta_id`) REFERENCES `peserta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `peserta`
--
ALTER TABLE `peserta`
  ADD CONSTRAINT `peserta_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
