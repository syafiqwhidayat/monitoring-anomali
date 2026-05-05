-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 05, 2026 at 06:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring_anomali`
--

-- --------------------------------------------------------

--
-- Table structure for table `anomali`
--

CREATE TABLE `anomali` (
  `id` int(11) NOT NULL,
  `id_kategori_anomali` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_wilayah` varchar(16) NOT NULL,
  `id_assigment` varchar(24) NOT NULL,
  `konfirmasi` text NOT NULL DEFAULT '\'\'',
  `is_lap` tinyint(1) NOT NULL DEFAULT 0,
  `is_insert` tinyint(1) DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anomali`
--

INSERT INTO `anomali` (`id`, `id_kategori_anomali`, `id_user`, `id_wilayah`, `id_assigment`, `konfirmasi`, `is_lap`, `is_insert`, `date_created`, `date_updated`, `date_deleted`) VALUES
(4, 13, 1, '1311021002000100', '76', 'Perlu Perbaikan: NIK salah format', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(5, 10, 1, '1311030005000901', '122', 'Data Ganda: Ditemukan entri serupa di Blok B', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(6, 9, 1, '1311040003000902', '143', 'Sesuai Lapangan: Tidak ada perubahan data', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(7, 14, 1, '1311011007000502', '32', 'Perlu perbaikan: Kode wilayah RT/RW keliru', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(8, 18, 1, '1311023003000300', '95', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(9, 1, 1, '1311040008000503', '158', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(10, 10, 1, '1311040005000501', '146', 'Perlu perbaikan: Nilai penghasilan tidak masuk akal', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(11, 11, 1, '1311020005002500', '65', 'Sesuai Lapangan: Data sudah diverifikasi ulang', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(12, 14, 1, '1311040006000802', '151', 'Data Ganda: Duplikasi data Kepala Keluarga', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(13, 5, 1, '1311040004000601', '145', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(14, 8, 1, '1311023004000401', '98', 'Perlu perbaikan: Kelengkapan data pendidikan formal', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(15, 8, 1, '1311040007000801', '155', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(16, 20, 1, '1311020001000802', '49', 'Perlu perbaikan: Status rumah tangga perlu diperjelas', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(17, 16, 1, '1311010007000300', '10', 'Data Ganda: Entry berulang oleh pengguna yang sama', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(18, 5, 1, '1311011001000301', '17', 'ini adalah contoh jawaban. apakah berhasil disimpan?', 0, NULL, '0000-00-00 00:00:00', '2026-05-02 23:58:59', NULL),
(19, 17, 1, '1311031004000902', '133', 'Perlu perbaikan: Jenis kelamin KK terbalik', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(20, 16, 1, '1311012004000101', '40', 'Data Ganda: Hanya perlu menghapus satu entri', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(21, 15, 1, '1311040008000601', '159', 'Sesuai Lapangan: Data telah diperbaiki dan diverifikasi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(22, 16, 1, '1311011003000402', '24', 'Perlu perbaikan: NIK kosong, wajib diisi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(23, 10, 1, '1311010008000502', '13', 'Sesuai Lapangan: Ditemukan error pada sistem, bukan data', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(24, 10, 1, '1311021002000401', '77', 'Perlu perbaikan: Kolom pekerjaan terlewat', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(25, 4, 1, '1311022002000301', '88', 'Data Ganda: Kesalahan penginputan nama', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(26, 4, 1, '1311010006000900', '8', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(27, 6, 1, '1311022004000100', '91', 'Sesuai Kondisi Lapangan', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:41', NULL),
(28, 10, 1, '1311040006000701', '149', 'Perlu perbaikan: Usia KK harus > 17 tahun', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(29, 10, 1, '1311022003000100', '89', 'Sesuai Lapangan: Terverifikasi, data input sudah benar', 0, NULL, '0000-00-00 00:00:00', '2025-11-24 12:11:46', NULL),
(30, 19, 1, '1311011007000701', '34', 'Data Ganda: Responden ini sudah tercatat di KK sebelah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(31, 5, 1, '1311021002000402', '78', 'Perlu perbaikan: Status pekerjaan tidak konsisten', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(32, 13, 1, '1311010006000402', '6', 'Sesuai Lapangan: Setelah diperiksa, data valid', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(33, 1, 1, '1311020003002102', '58', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(34, 13, 1, '1311011005000202', '27', 'Data Ganda: Duplikasi ID responden', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(35, 11, 1, '1311030005000802', '121', 'Sesuai Lapangan: Tidak ada koreksi data', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(36, 8, 1, '1311021002000600', '79', 'Perlu perbaikan: Tanggal lahir bertentangan dengan usia', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(37, 6, 1, '1311010005000501', '1', 'Sesuai Kondisi Lapangan', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:41', NULL),
(38, 11, 1, '1311031004001002', '134', 'Perlu perbaikan: Hubungan dengan KK salah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(39, 11, 1, '1311020003000900', '52', 'Sesuai Lapangan: Anomali disebabkan oleh entry ganda di server', 0, NULL, '0000-00-00 00:00:00', '2025-11-24 12:07:33', NULL),
(40, 6, 1, '1311041001000600', '164', 'Sesuai Kondisi Lapangan', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:41', NULL),
(41, 8, 1, '1311020003002002', '57', 'Perlu perbaikan: Koordinat GPS perlu diperbarui', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(42, 6, 1, '1311040003000601', '138', 'Sesuai Kondisi Lapangan', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:41', NULL),
(43, 5, 1, '1311041004000101', '168', 'Perlu perbaikan: Jumlah anggota KK tidak sinkron', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(44, 19, 1, '1311041003000102', '167', 'Sesuai Lapangan: Data di lapangan sudah akurat', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(45, 1, 1, '1311020003000301', '50', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(46, 5, 1, '1311040008000801', '161', 'Sesuai Lapangan: Dikonfirmasi oleh supervisor', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(47, 11, 1, '1311010006000100', '4', 'Data Ganda: Entri sudah dihapus', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(48, 6, 1, '1311012001000200', '35', 'Sesuai Kondisi Lapangan', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:41', NULL),
(49, 16, 1, '1311021003000102', '81', 'Sesuai Lapangan: Data ini valid, bukan anomali', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(50, 9, 1, '1311020005000100', '60', 'Data Ganda: NIK duplikat telah diperbaiki secara otomatis', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(51, 3, 1, '1311020003001502', '53', 'Perlu perbaikan: Sumber air minum tidak tercatat', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(52, 2, 1, '1311040007000600', '154', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(53, 2, 1, '1311040003001101', '144', 'Sesuai Lapangan: Dikonfirmasi data usia valid', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(54, 4, 1, '1311030005001501', '125', 'Perlu perbaikan: Kode pos salah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(55, 2, 1, '1311011002000201', '19', 'Data Ganda: Entri ini sudah ada, hapus', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(56, 2, 1, '1311012001000200', '35', 'update sayfiq', 0, NULL, '0000-00-00 00:00:00', '2026-01-29 06:41:54', NULL),
(57, 2, 1, '1311040003000201', '135', 'Perlu perbaikan: Jenis atap rumah tidak sesuai', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(58, 2, 1, '1311041003000101', '166', 'Sesuai Lapangan: Tidak ada koreksi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(59, 1, 1, '1311020003000900', '52', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(60, 1, 1, '1311011004000100', '25', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(61, 3, 1, '1311022001000100', '86', 'Sesuai Lapangan: Validasi NIK berhasil', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(62, 4, 1, '1311030002001500', '110', 'Perlu perbaikan: Status disabilitas perlu diperjelas', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(63, 1, 1, '1311011002000201', '19', 'memang tidak sesuai dengan NIK', 0, NULL, '0000-00-00 00:00:00', '2026-05-03 02:00:20', NULL),
(64, 2, 1, '1311030005001101', '123', 'Data Ganda: Entri telah digabungkan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(65, 3, 1, '1311041004000400', '170', 'Perlu perbaikan: Kolom jenis kelamin kosong', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(66, 2, 1, '1311010008000700', '14', 'Sesuai Lapangan: Dikonfirmasi oleh petugas lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(67, 4, 1, '1311041001001200', '165', 'Perlu perbaikan: Kode wilayah desa/kelurahan salah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(68, 4, 1, '1311030005000802', '121', 'Data Ganda: Entri duplikat dengan nama serupa', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(69, 1, 1, '1311030001001203', '104', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(70, 1, 1, '1311020005002600', '66', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(71, 3, 1, '1311030002000900', '108', 'Sesuai Lapangan: Sudah diperbaiki pada database', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(72, 2, 1, '1311011004000302', '26', 'Perlu perbaikan: Tanggal lahir kosong', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(73, 3, 1, '1311012003000400', '39', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(74, 3, 1, '1311041004000101', '168', 'Data Ganda: Duplikasi alamat dan KK', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(76, 1, 1, '1311031001000500', '128', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(77, 2, 1, '1311010005000602', '3', 'Perlu perbaikan: NIK kurang digit', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(78, 4, 1, '1311030001001201', '102', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(79, 4, 1, '1311020003002202', '59', 'Data Ganda: Entri telah di-merge', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(80, 2, 1, '1311011002000400', '21', 'Perlu perbaikan: Tingkat pendidikan tidak sinkron', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(81, 3, 1, '1311040003000801', '140', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(82, 3, 1, '1311020008000200', '68', 'Perlu perbaikan: Usia menikah pertama tidak valid', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(83, 3, 1, '1311011006000203', '28', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(84, 1, 1, '1311030004000701', '115', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(85, 1, 1, '1311040008000202', '156', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(86, 4, 1, '1311020008000102', '67', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(87, 4, 1, '1311023004000202', '96', 'Perlu perbaikan: Kode wilayah diubah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(88, 2, 1, '1311020008000704', '70', 'Data Ganda: Entri duplikat', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(89, 4, 1, '1311010008000301', '12', 'Perlu perbaikan: Jenis lantai perlu diubah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(90, 4, 1, '1311031001000302', '126', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(91, 2, 1, '1311020008000800', '71', 'Perlu perbaikan: Nama KK salah input', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(92, 2, 1, '1311040008000701', '160', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(93, 4, 1, '1311040008000802', '162', 'Perlu perbaikan: Status bekerja perlu diverifikasi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(94, 1, 1, '1311030004000302', '113', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(95, 4, 1, '1311030004000703', '116', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(96, 1, 1, '1311030005000801', '120', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(97, 1, 1, '1311011003000402', '24', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(98, 4, 1, '1311020003000501', '51', 'Perlu perbaikan: Tingkat pendidikan tidak wajar', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(99, 4, 1, '1311030004000602', '114', 'Data Ganda: Responden ditemukan di Blok sebelah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(100, 1, 1, '1311023001000100', '94', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(101, 2, 1, '1311011007000602', '33', 'Perlu perbaikan: Lama tinggal diubah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(102, 4, 1, '1311031004000801', '132', 'Sesuai Lapangan: Data valid', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(103, 1, 1, '1311021002000800', '80', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(104, 1, 1, '1311030004000200', '112', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(105, 1, 1, '1311040005000700', '147', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(106, 2, 1, '1311030001001202', '103', 'Perlu perbaikan: Kepemilikan KKS bertentangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(107, 3, 1, '1311011001000302', '18', 'Sesuai Lapangan: Anomali false positive', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(108, 4, 1, '1311011007000501', '31', 'Perlu perbaikan: Data migrasi tidak sinkron', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(109, 4, 1, '1311012004000401', '44', 'Data Ganda: Duplikasi ditemukan di kabupaten lain', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(110, 4, 1, '1311010008001500', '16', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(111, 3, 1, '1311031001000401', '127', 'Perlu perbaikan: Pengeluaran per bulan kosong', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(112, 4, 1, '1311020005001500', '63', 'Data Ganda: Entri sudah diperbaiki', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(113, 1, 1, '1311012001000200', '35', 'update lagi', 0, NULL, '0000-00-00 00:00:00', '2026-01-29 06:41:58', NULL),
(114, 3, 1, '1311030002000600', '107', 'Perlu perbaikan: Jenis bahan bakar memasak tidak sesuai', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(115, 4, 1, '1311021004000900', '85', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(116, 1, 1, '1311030005001101', '123', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(117, 3, 1, '1311012003000400', '39', 'Data Ganda: Duplikasi nama keluarga', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(118, 4, 1, '1311020005000600', '62', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(119, 1, 1, '1311040008000402', '157', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(120, 4, 1, '1311020003001700', '55', 'Sesuai Lapangan: Data sudah sesuai dengan KK', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(121, 4, 1, '1311010006000600', '7', 'Perlu perbaikan: Anggota KK lebih dari 10', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(122, 2, 1, '1311010005000601', '2', 'Data Ganda: Duplikasi ID responden di sistem', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(123, 4, 1, '1311011007000201', '30', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(124, 2, 1, '1311041003000101', '166', 'Perlu perbaikan: Data kepemilikan ternak tidak wajar', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(125, 3, 1, '1311022002000100', '87', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(126, 2, 1, '1311040006000402', '148', 'Perlu perbaikan: Penghasilan tercatat nol, harus diperiksa', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(127, 3, 1, '1311031004000102', '131', 'Data Ganda: Responden ini sudah meninggal', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(128, 1, 1, '1311030002001000', '109', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(129, 2, 1, '1311020001000200', '46', 'Perlu perbaikan: Status BPJS/Asuransi harus diisi', 0, NULL, '0000-00-00 00:00:00', '2025-11-24 12:07:18', NULL),
(130, 4, 1, '1311021003000500', '83', 'Sesuai Lapangan: Data sudah divalidasi silang', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(131, 1, 1, '1311021001000900', '75', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(132, 2, 1, '1311030005000802', '121', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(133, 3, 1, '1311011003000103', '22', 'Perlu perbaikan: NIK belum terdaftar di Dukcapil', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(134, 3, 1, '1311020008001000', '73', 'Sesuai Lapangan: Validasi data kepemilikan aset aman', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(135, 4, 1, '1311010007000600', '11', 'Data Ganda: Duplikasi ditemukan di BS yang sama', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(136, 4, 1, '1311040003000506', '137', 'Perlu perbaikan: Nama responden tidak lengkap', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(137, 2, 1, '1311022003000502', '90', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(138, 1, 1, '1311031002000802', '129', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(139, 2, 1, '1311031003000100', '130', 'Perlu perbaikan: Kolom pekerjaan masih nol', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(140, 3, 1, '1311030001001202', '103', 'Sesuai Lapangan: Anomali diabaikan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(141, 4, 1, '1311030005000502', '119', 'Perlu perbaikan: Status KK harus \"Bapak\" atau \"Ibu\"', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(142, 2, 1, '1311030005000401', '118', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(143, 1, 1, '1311040003000402', '136', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(144, 4, 1, '1311010006000600', '7', 'Perlu perbaikan: Jenis usaha perlu diverifikasi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(145, 4, 1, '1311040003000402', '136', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(146, 1, 1, '1311040003000901', '142', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(147, 3, 1, '1311011007000602', '33', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(148, 4, 1, '1311041004000102', '169', 'Perlu perbaikan: Data tanggal survei salah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(149, 4, 1, '1311012004000200', '42', 'Data Ganda: Duplikasi nama dan alamat', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(150, 3, 1, '1311030004000100', '111', 'Sesuai Lapangan: Anomali telah diabaikan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(151, 3, 1, '1311012002000402', '37', 'Perlu perbaikan: Status pernikahan tidak jelas', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(152, 2, 1, '1311030001001301', '105', 'Data Ganda: Duplikasi ditemukan pada NIK', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(153, 3, 1, '1311012003000100', '38', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(154, 1, 1, '1311012004000200', '42', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(155, 1, 1, '1311022004000601', '93', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(156, 4, 1, '1311012002000300', '36', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(157, 3, 1, '1311030005001401', '124', 'Perlu perbaikan: Kode wilayah perlu dikoreksi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(158, 3, 1, '1311011003000401', '23', 'Data Ganda: Entri telah dihapus secara massal', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(159, 2, 1, '1311040006000703', '150', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(160, 1, 1, '1311030001000402', '100', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(161, 2, 1, '1311040003000901', '142', 'Sesuai Lapangan: Dikonfirmasi oleh petugas senior', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(162, 1, 1, '1311020005000600', '62', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(163, 1, 1, '1311020008000901', '72', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(164, 2, 1, '1311021001000700', '74', 'Data Ganda: Duplikasi ditemukan di Kecamatan berbeda', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(165, 4, 1, '1311020001000100', '45', 'Perlu perbaikan: Data penggunaan air tidak konsisten', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(166, 2, 1, '1311021002000401', '77', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(167, 3, 1, '1311020005002400', '64', 'Perlu perbaikan: Status pekerjaan pensiunan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(168, 4, 1, '1311010008001300', '15', 'Data Ganda: Duplikasi data anggota keluarga', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(169, 1, 1, '1311040006001400', '152', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(170, 3, 1, '1311030001000201', '99', 'Perlu perbaikan: Jenis atap rumah perlu diverifikasi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(171, 2, 1, '1311030002000300', '106', 'Sesuai Lapangan: Tidak ada perubahan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(172, 1, 1, '1311020001000400', '47', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(173, 4, 1, '1311040003000701', '139', 'Perlu perbaikan: Penghasilan tidak wajar', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(174, 1, 1, '1311010006000401', '5', 'ini contoh jawaban', 0, NULL, '0000-00-00 00:00:00', '2026-05-02 09:48:33', NULL),
(175, 2, 1, '1311011003000402', '24', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(176, 4, 1, '1311020003001800', '56', 'Perlu perbaikan: Kolom tanggungan keluarga kosong', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(177, 3, 1, '1311031002000802', '129', 'Sesuai Lapangan: Koreksi minor pada kode wilayah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(178, 3, 1, '1311023004000300', '97', 'Data Ganda: Duplikasi alamat rumah tangga', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(179, 3, 1, '1311010006000600', '7', 'Perlu perbaikan: Sumber penerangan tidak jelas', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(180, 3, 1, '1311031004000801', '132', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(181, 3, 1, '1311020003001600', '54', 'Perlu perbaikan: Pendidikan terakhir tidak diisi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(182, 4, 1, '1311020008000400', '69', 'Data Ganda: Duplikasi entri dengan stempel waktu berbeda', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(183, 3, 1, '1311040003000802', '141', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(184, 2, 1, '1311023004000401', '98', 'Perlu perbaikan: Data kepemilikan aset perlu diperjelas', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(185, 1, 1, '1311020008000102', '67', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(186, 1, 1, '1311020001000700', '48', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(187, 3, 1, '1311010008001500', '16', 'Sesuai Lapangan: Tidak ada tindak lanjut', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(188, 1, 1, '1311022003000100', '89', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(189, 1, 1, '1311011006000501', '29', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(190, 2, 1, '1311030001000900', '101', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(191, 3, 1, '1311012004000300', '43', 'Perlu perbaikan: Jenis usaha tidak terisi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(192, 4, 1, '1311020005000501', '61', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(193, 2, 1, '1311010006001200', '9', 'Perlu perbaikan: Kategori anomali perlu diubah', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(194, 2, 1, '1311012004000102', '41', 'Data Ganda: Duplikasi dalam satu KK', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(195, 2, 1, '1311040008000901', '163', 'Sesuai Lapangan: Data final', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(196, 1, 1, '1311040007000402', '153', '', 0, NULL, '0000-00-00 00:00:00', '2026-01-08 02:15:58', NULL),
(197, 2, 1, '1311030004000802', '117', 'Sesuai Lapangan: Anomali teratasi', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(198, 4, 1, '1311011002000301', '20', 'Perlu perbaikan: Jenis rumah tidak valid', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(199, 3, 1, '1311040008000402', '157', 'Data Ganda: Duplikasi ID responden', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(200, 3, 1, '1311021004000100', '84', 'Perlu perbaikan: NIK dan tanggal lahir tidak cocok', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(201, 3, 1, '1311022004000401', '92', 'Sesuai Lapangan', 0, NULL, '0000-00-00 00:00:00', NULL, NULL),
(258, 27, NULL, '1311', '174', '', 0, NULL, '2026-05-03 09:16:33', '2026-05-04 10:13:00', NULL),
(259, 28, NULL, '1311', '174', 'usahanya menggunakan telfon biasa dalam bertransaksi. dan juga dalam proses masih manual dengan sklala besar.', 0, NULL, '2026-05-03 09:17:05', '2026-05-04 09:53:49', NULL),
(260, 27, NULL, '1311', '175', '', 0, NULL, '2026-05-03 09:17:32', '2026-05-04 10:05:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `assigment`
--

CREATE TABLE `assigment` (
  `id` int(11) NOT NULL,
  `id_wilayah` varchar(16) DEFAULT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `kd_assigment` varchar(24) NOT NULL,
  `kd_krt` varchar(3) DEFAULT NULL,
  `kd_art` varchar(2) DEFAULT NULL,
  `kd_nrt` varchar(11) DEFAULT NULL,
  `nm_krt` varchar(255) DEFAULT NULL,
  `nm_art` varchar(255) DEFAULT NULL,
  `nm_nrt` varchar(255) DEFAULT NULL,
  `id_bs` varchar(14) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assigment`
--

INSERT INTO `assigment` (`id`, `id_wilayah`, `id_kegiatan`, `kd_assigment`, `kd_krt`, `kd_art`, `kd_nrt`, `nm_krt`, `nm_art`, `nm_nrt`, `id_bs`) VALUES
(1, '1311010005000501', 1, '131101000500050100101', '001', '01', NULL, 'Fajar', 'Fajar', NULL, ''),
(2, '1311010005000601', 1, '131101000500060100101', '001', '01', NULL, 'Fani', 'Fani', NULL, ''),
(3, '1311010005000602', 1, '131101000500060200101', '001', '01', NULL, 'Erna', 'Gagas', NULL, ''),
(4, '1311010006000100', 1, '131101000600010000101', '001', '01', NULL, 'Kirana', 'Kirana', NULL, ''),
(5, '1311010006000401', 1, '131101000600040100101', '001', '01', NULL, 'Kencana', 'Kencana', NULL, ''),
(6, '1311010006000402', 1, '131101000600040200101', '001', '01', NULL, 'Hafiz', 'Hafiz', NULL, ''),
(7, '1311010006000600', 1, '131101000600060000101', '001', '01', NULL, 'Juwita', 'Juwita', NULL, ''),
(8, '1311010006000900', 1, '131101000600090000101', '001', '01', NULL, 'Fajar', 'Fajar', NULL, ''),
(9, '1311010006001200', 1, '131101000600120000101', '001', '01', NULL, 'Kumala', 'Kumala', NULL, ''),
(10, '1311010007000300', 1, '131101000700030000101', '001', '01', NULL, 'Erna', 'Erna', NULL, ''),
(11, '1311010007000600', 1, '131101000700060000101', '001', '01', NULL, 'Winda', 'Winda', NULL, ''),
(12, '1311010008000301', 1, '131101000800030100101', '001', '01', NULL, 'Usada', 'Usada', NULL, ''),
(13, '1311010008000502', 1, '131101000800050200101', '001', '01', NULL, 'Yuda', 'Yuda', NULL, ''),
(14, '1311010008000700', 1, '131101000800070000101', '001', '01', NULL, 'Farida', 'Farida', NULL, ''),
(15, '1311010008001300', 1, '131101000800130000101', '001', '01', NULL, 'Anisa', 'Anisa', NULL, ''),
(16, '1311010008001500', 1, '131101000800150000101', '001', '01', NULL, 'Permata', 'Permata', NULL, ''),
(17, '1311011001000301', 1, '131101100100030100101', '001', '01', NULL, 'Wawan', 'Wawan', NULL, ''),
(18, '1311011001000302', 1, '131101100100030200101', '001', '01', NULL, 'Arya', 'Arya', NULL, ''),
(19, '1311011002000201', 1, '131101100200020100101', '001', '01', NULL, 'Usman', 'Usman', NULL, ''),
(20, '1311011002000301', 1, '131101100200030100101', '001', '01', NULL, 'Gani', 'Gani', NULL, ''),
(21, '1311011002000400', 1, '131101100200040000101', '001', '01', NULL, 'Hafiz', 'Hafiz', NULL, ''),
(22, '1311011003000103', 1, '131101100300010300101', '001', '01', NULL, 'Maimunah', 'Maimunah', NULL, ''),
(23, '1311011003000401', 1, '131101100300040100101', '001', '01', NULL, 'Melati', 'Melati', NULL, ''),
(24, '1311011003000402', 1, '131101100300040200101', '001', '01', NULL, 'Gagas', 'Gagas', NULL, ''),
(25, '1311011004000100', 1, '131101100400010000101', '001', '01', NULL, 'Mega', 'Mega', NULL, ''),
(26, '1311011004000302', 1, '131101100400030200101', '001', '01', NULL, 'Saiful', 'Saiful', NULL, ''),
(27, '1311011005000202', 1, '131101100500020200101', '001', '01', NULL, 'Wahyu', 'Wahyu', NULL, ''),
(28, '1311011006000203', 1, '131101100600020300101', '001', '01', NULL, 'Akbar', 'Akbar', NULL, ''),
(29, '1311011006000501', 1, '131101100600050100101', '001', '01', NULL, 'Kirana', 'Kirana', NULL, ''),
(30, '1311011007000201', 1, '131101100700020100101', '001', '01', NULL, 'Septi', 'Septi', NULL, ''),
(31, '1311011007000501', 1, '131101100700050100101', '001', '01', NULL, 'Riska', 'Riska', NULL, ''),
(32, '1311011007000502', 1, '131101100700050200101', '001', '01', NULL, 'Sinta', 'Sinta', NULL, ''),
(33, '1311011007000602', 1, '131101100700060200101', '001', '01', NULL, 'Sinta', 'Sinta', NULL, ''),
(34, '1311011007000701', 1, '131101100700070100101', '001', '01', NULL, 'Jamal', 'Jamal', NULL, ''),
(35, '1311012001000200', 1, '131101200100020000101', '001', '01', NULL, 'Lintang', 'Lintang', NULL, ''),
(36, '1311012002000300', 1, '131101200200030000101', '001', '01', NULL, 'Hilda', 'Hilda', NULL, ''),
(37, '1311012002000402', 1, '131101200200040200101', '001', '01', NULL, 'Winda', 'Winda', NULL, ''),
(38, '1311012003000100', 1, '131101200300010000101', '001', '01', NULL, 'Lutfi', 'Lutfi', NULL, ''),
(39, '1311012003000400', 1, '131101200300040000101', '001', '01', NULL, 'Yani', 'Yani', NULL, ''),
(40, '1311012004000101', 1, '131101200400010100101', '001', '01', NULL, 'Murni', 'Murni', NULL, ''),
(41, '1311012004000102', 1, '131101200400010200101', '001', '01', NULL, 'Rendi', 'Rendi', NULL, ''),
(42, '1311012004000200', 1, '131101200400020000101', '001', '01', NULL, 'Intan', 'Intan', NULL, ''),
(43, '1311012004000300', 1, '131101200400030000101', '001', '01', NULL, 'Pratiwi', 'Pratiwi', NULL, ''),
(44, '1311012004000401', 1, '131101200400040100101', '001', '01', NULL, 'Candra', 'Candra', NULL, ''),
(45, '1311020001000100', 1, '131102000100010000101', '001', '01', NULL, 'Permata', 'Permata', NULL, ''),
(46, '1311020001000200', 1, '131102000100020000101', '001', '01', NULL, 'Juni', 'Juni', NULL, ''),
(47, '1311020001000400', 1, '131102000100040000101', '001', '01', NULL, 'Restu', 'Restu', NULL, ''),
(48, '1311020001000700', 1, '131102000100070000101', '001', '01', NULL, 'Gede', 'Gede', NULL, ''),
(49, '1311020001000802', 1, '131102000100080200101', '001', '01', NULL, 'Erlin', 'Erlin', NULL, ''),
(50, '1311020003000301', 1, '131102000300030100101', '001', '01', NULL, 'Juwita', 'Juwita', NULL, ''),
(51, '1311020003000501', 1, '131102000300050100101', '001', '01', NULL, 'Eka', 'Eka', NULL, ''),
(52, '1311020003000900', 1, '131102000300090000101', '001', '01', NULL, 'Udin', 'Udin', NULL, ''),
(53, '1311020003001502', 1, '131102000300150200101', '001', '01', NULL, 'Alif', 'Alif', NULL, ''),
(54, '1311020003001600', 1, '131102000300160000101', '001', '01', NULL, 'Wawan', 'Wawan', NULL, ''),
(55, '1311020003001700', 1, '131102000300170000101', '001', '01', NULL, 'Taufik', 'Taufik', NULL, ''),
(56, '1311020003001800', 1, '131102000300180000101', '001', '01', NULL, 'Mukti', 'Mukti', NULL, ''),
(57, '1311020003002002', 1, '131102000300200200101', '001', '01', NULL, 'Sofyan', 'Sofyan', NULL, ''),
(58, '1311020003002102', 1, '131102000300210200101', '001', '01', NULL, 'Agung', 'Agung', NULL, ''),
(59, '1311020003002202', 1, '131102000300220200101', '001', '01', NULL, 'Kahfi', 'Kahfi', NULL, ''),
(60, '1311020005000100', 1, '131102000500010000101', '001', '01', NULL, 'Kania', 'Kania', NULL, ''),
(61, '1311020005000501', 1, '131102000500050100101', '001', '01', NULL, 'Cipta', 'Cipta', NULL, ''),
(62, '1311020005000600', 1, '131102000500060000101', '001', '01', NULL, 'Mia', 'Mia', NULL, ''),
(63, '1311020005001500', 1, '131102000500150000101', '001', '01', NULL, 'Kartika', 'Kartika', NULL, ''),
(64, '1311020005002400', 1, '131102000500240000101', '001', '01', NULL, 'Ningsih', 'Ningsih', NULL, ''),
(65, '1311020005002500', 1, '131102000500250000101', '001', '01', NULL, 'Galih', 'Galih', NULL, ''),
(66, '1311020005002600', 1, '131102000500260000101', '001', '01', NULL, 'Pratiwi', 'Pratiwi', NULL, ''),
(67, '1311020008000102', 1, '131102000800010200101', '001', '01', NULL, 'Taufik', 'Taufik', NULL, ''),
(68, '1311020008000200', 1, '131102000800020000101', '001', '01', NULL, 'Fahmi', 'Fahmi', NULL, ''),
(69, '1311020008000400', 1, '131102000800040000101', '001', '01', NULL, 'Lintang', 'Lintang', NULL, ''),
(70, '1311020008000704', 1, '131102000800070400101', '001', '01', NULL, 'Gani', 'Gani', NULL, ''),
(71, '1311020008000800', 1, '131102000800080000101', '001', '01', NULL, 'Yuli', 'Yuli', NULL, ''),
(72, '1311020008000901', 1, '131102000800090100101', '001', '01', NULL, 'Karim', 'Karim', NULL, ''),
(73, '1311020008001000', 1, '131102000800100000101', '001', '01', NULL, 'Najwa', 'Najwa', NULL, ''),
(74, '1311021001000700', 1, '131102100100070000101', '001', '01', NULL, 'Kencana', 'Kencana', NULL, ''),
(75, '1311021001000900', 1, '131102100100090000101', '001', '01', NULL, 'Ratih', 'Ratih', NULL, ''),
(76, '1311021002000100', 1, '131102100200010000101', '001', '01', NULL, 'Kusuma', 'Kusuma', NULL, ''),
(77, '1311021002000401', 1, '131102100200040100101', '001', '01', NULL, 'Lusia', 'Lusia', NULL, ''),
(78, '1311021002000402', 1, '131102100200040200101', '001', '01', NULL, 'Maya', 'Maya', NULL, ''),
(79, '1311021002000600', 1, '131102100200060000101', '001', '01', NULL, 'Mita', 'Mita', NULL, ''),
(80, '1311021002000800', 1, '131102100200080000101', '001', '01', NULL, 'Dinda', 'Dinda', NULL, ''),
(81, '1311021003000102', 1, '131102100300010200101', '001', '01', NULL, 'Panji', 'Panji', NULL, ''),
(82, '1311021003000402', 1, '131102100300040200101', '001', '01', NULL, 'Gede', 'Gede', NULL, ''),
(83, '1311021003000500', 1, '131102100300050000101', '001', '01', NULL, 'Juni', 'Juni', NULL, ''),
(84, '1311021004000100', 1, '131102100400010000101', '001', '01', NULL, 'Arya', 'Arya', NULL, ''),
(85, '1311021004000900', 1, '131102100400090000101', '001', '01', NULL, 'Wawan', 'Wawan', NULL, ''),
(86, '1311022001000100', 1, '131102200100010000101', '001', '01', NULL, 'Ika', 'Ika', NULL, ''),
(87, '1311022002000100', 1, '131102200200010000101', '001', '01', NULL, 'Ika', 'Ika', NULL, ''),
(88, '1311022002000301', 1, '131102200200030100101', '001', '01', NULL, 'Krisna', 'Krisna', NULL, ''),
(89, '1311022003000100', 1, '131102200300010000101', '001', '01', NULL, 'Nadia', 'Nadia', NULL, ''),
(90, '1311022003000502', 1, '131102200300050200101', '001', '01', NULL, 'Intan', 'Intan', NULL, ''),
(91, '1311022004000100', 1, '131102200400010000101', '001', '01', NULL, 'Eni', 'Eni', NULL, ''),
(92, '1311022004000401', 1, '131102200400040100101', '001', '01', NULL, 'Nadia', 'Nadia', NULL, ''),
(93, '1311022004000601', 1, '131102200400060100101', '001', '01', NULL, 'Widodo', 'Widodo', NULL, ''),
(94, '1311023001000100', 1, '131102300100010000101', '001', '01', NULL, 'Karim', 'Karim', NULL, ''),
(95, '1311023003000300', 1, '131102300300030000101', '001', '01', NULL, 'Laila', 'Laila', NULL, ''),
(96, '1311023004000202', 1, '131102300400020200101', '001', '01', NULL, 'Wati', 'Wati', NULL, ''),
(97, '1311023004000300', 1, '131102300400030000101', '001', '01', NULL, 'Panji', 'Panji', NULL, ''),
(98, '1311023004000401', 1, '131102300400040100101', '001', '01', NULL, 'Ratna', 'Ratna', NULL, ''),
(99, '1311030001000201', 1, '131103000100020100101', '001', '01', NULL, 'Zaki', 'Zaki', NULL, ''),
(100, '1311030001000402', 1, '131103000100040200101', '001', '01', NULL, 'Usman', 'Usman', NULL, ''),
(101, '1311030001000900', 1, '131103000100090000101', '001', '01', NULL, 'Rendi', 'Rendi', NULL, ''),
(102, '1311030001001201', 1, '131103000100120100101', '001', '01', NULL, 'Firman', 'Firman', NULL, ''),
(103, '1311030001001202', 1, '131103000100120200101', '001', '01', NULL, 'Sari', 'Sari', NULL, ''),
(104, '1311030001001203', 1, '131103000100120300101', '001', '01', NULL, 'Rio', 'Rio', NULL, ''),
(105, '1311030001001301', 1, '131103000100130100101', '001', '01', NULL, 'Mada', 'Mada', NULL, ''),
(106, '1311030002000300', 1, '131103000200030000101', '001', '01', NULL, 'Vina', 'Vina', NULL, ''),
(107, '1311030002000600', 1, '131103000200060000101', '001', '01', NULL, 'Eni', 'Eni', NULL, ''),
(108, '1311030002000900', 1, '131103000200090000101', '001', '01', NULL, 'Kholid', 'Kholid', NULL, ''),
(109, '1311030002001000', 1, '131103000200100000101', '001', '01', NULL, 'Wawan', 'Wawan', NULL, ''),
(110, '1311030002001500', 1, '131103000200150000101', '001', '01', NULL, 'Wira', 'Wira', NULL, ''),
(111, '1311030004000100', 1, '131103000400010000101', '001', '01', NULL, 'Nur', 'Nur', NULL, ''),
(112, '1311030004000200', 1, '131103000400020000101', '001', '01', NULL, 'Lutfi', 'Lutfi', NULL, ''),
(113, '1311030004000302', 1, '131103000400030200101', '001', '01', NULL, 'Langit', 'Langit', NULL, ''),
(114, '1311030004000602', 1, '131103000400060200101', '001', '01', NULL, 'Eni', 'Eni', NULL, ''),
(115, '1311030004000701', 1, '131103000400070100101', '001', '01', NULL, 'Sita', 'Sita', NULL, ''),
(116, '1311030004000703', 1, '131103000400070300101', '001', '01', NULL, 'Dinda', 'Dinda', NULL, ''),
(117, '1311030004000802', 1, '131103000400080200101', '001', '01', NULL, 'Galih', 'Galih', NULL, ''),
(118, '1311030005000401', 1, '131103000500040100101', '001', '01', NULL, 'Lestari', 'Lestari', NULL, ''),
(119, '1311030005000502', 1, '131103000500050200101', '001', '01', NULL, 'Budi', 'Budi', NULL, ''),
(120, '1311030005000801', 1, '131103000500080100101', '001', '01', NULL, 'Kasti', 'Kasti', NULL, ''),
(121, '1311030005000802', 1, '131103000500080200101', '001', '01', NULL, 'Lisa', 'Lisa', NULL, ''),
(122, '1311030005000901', 1, '131103000500090100101', '001', '01', NULL, 'Pramono', 'Pramono', NULL, ''),
(123, '1311030005001101', 1, '131103000500110100101', '001', '01', NULL, 'Murni', 'Murni', NULL, ''),
(124, '1311030005001401', 1, '131103000500140100101', '001', '01', NULL, 'Langit', 'Langit', NULL, ''),
(125, '1311030005001501', 1, '131103000500150100101', '001', '01', NULL, 'Murni', 'Murni', NULL, ''),
(126, '1311031001000302', 1, '131103100100030200101', '001', '01', NULL, 'Pramono', 'Pramono', NULL, ''),
(127, '1311031001000401', 1, '131103100100040100101', '001', '01', NULL, 'Siti', 'Siti', NULL, ''),
(128, '1311031001000500', 1, '131103100100050000101', '001', '01', NULL, 'Nisa', 'Nisa', NULL, ''),
(129, '1311031002000802', 1, '131103100200080200101', '001', '01', NULL, 'Panji', 'Panji', NULL, ''),
(130, '1311031003000100', 1, '131103100300010000101', '001', '01', NULL, 'Hafiz', 'Hafiz', NULL, ''),
(131, '1311031004000102', 1, '131103100400010200101', '001', '01', NULL, 'Gede', 'Gede', NULL, ''),
(132, '1311031004000801', 1, '131103100400080100101', '001', '01', NULL, 'Maharani', 'Maharani', NULL, ''),
(133, '1311031004000902', 1, '131103100400090200101', '001', '01', NULL, 'Panji', 'Panji', NULL, ''),
(134, '1311031004001002', 1, '131103100400100200101', '001', '01', NULL, 'Cahya', 'Cahya', NULL, ''),
(135, '1311040003000201', 1, '131104000300020100101', '001', '01', NULL, 'Akbar', 'Akbar', NULL, ''),
(136, '1311040003000402', 1, '131104000300040200101', '001', '01', NULL, 'Sinta', 'Sinta', NULL, ''),
(137, '1311040003000506', 1, '131104000300050600101', '001', '01', NULL, 'Pramono', 'Pramono', NULL, ''),
(138, '1311040003000601', 1, '131104000300060100101', '001', '01', NULL, 'Lusia', 'Lusia', NULL, ''),
(139, '1311040003000701', 1, '131104000300070100101', '001', '01', NULL, 'Pramono', 'Pramono', NULL, ''),
(140, '1311040003000801', 1, '131104000300080100101', '001', '01', NULL, 'Nabila', 'Nabila', NULL, ''),
(141, '1311040003000802', 1, '131104000300080200101', '001', '01', NULL, 'Putri', 'Putri', NULL, ''),
(142, '1311040003000901', 1, '131104000300090100101', '001', '01', NULL, 'Nabila', 'Nabila', NULL, ''),
(143, '1311040003000902', 1, '131104000300090200101', '001', '01', NULL, 'Jabar', 'Jabar', NULL, ''),
(144, '1311040003001101', 1, '131104000300110100101', '001', '01', NULL, 'Bachtiar', 'Bachtiar', NULL, ''),
(145, '1311040004000601', 1, '131104000400060100101', '001', '01', NULL, 'Zulfa', 'Zulfa', NULL, ''),
(146, '1311040005000501', 1, '131104000500050100101', '001', '01', NULL, 'Julian', 'Julian', NULL, ''),
(147, '1311040005000700', 1, '131104000500070000101', '001', '01', NULL, 'Melati', 'Melati', NULL, ''),
(148, '1311040006000402', 1, '131104000600040200101', '001', '01', NULL, 'Wati', 'Wati', NULL, ''),
(149, '1311040006000701', 1, '131104000600070100101', '001', '01', NULL, 'Made', 'Made', NULL, ''),
(150, '1311040006000703', 1, '131104000600070300101', '001', '01', NULL, 'Erlin', 'Erlin', NULL, ''),
(151, '1311040006000802', 1, '131104000600080200101', '001', '01', NULL, 'Hamid', 'Hamid', NULL, ''),
(152, '1311040006001400', 1, '131104000600140000101', '001', '01', NULL, 'Hamid', 'Hamid', NULL, ''),
(153, '1311040007000402', 1, '131104000700040200101', '001', '01', NULL, 'Citra', 'Citra', NULL, ''),
(154, '1311040007000600', 1, '131104000700060000101', '001', '01', NULL, 'Juni', 'Juni', NULL, ''),
(155, '1311040007000801', 1, '131104000700080100101', '001', '01', NULL, 'Krisna', 'Krisna', NULL, ''),
(156, '1311040008000202', 1, '131104000800020200101', '001', '01', NULL, 'Melati', 'Melati', NULL, ''),
(157, '1311040008000402', 1, '131104000800040200101', '001', '01', NULL, 'Aji', 'Aji', NULL, ''),
(158, '1311040008000503', 1, '131104000800050300101', '001', '01', NULL, 'Laras', 'Laras', NULL, ''),
(159, '1311040008000601', 1, '131104000800060100101', '001', '01', NULL, 'Nadia', 'Nadia', NULL, ''),
(160, '1311040008000701', 1, '131104000800070100101', '001', '01', NULL, 'Dewi', 'Dewi', NULL, ''),
(161, '1311040008000801', 1, '131104000800080100101', '001', '01', NULL, 'Cahya', 'Cahya', NULL, ''),
(162, '1311040008000802', 1, '131104000800080200101', '001', '01', NULL, 'Urip', 'Urip', NULL, ''),
(163, '1311040008000901', 1, '131104000800090100101', '001', '01', NULL, 'Cahya', 'Cahya', NULL, ''),
(164, '1311041001000600', 1, '131104100100060000101', '001', '01', NULL, 'Haris', 'Haris', NULL, ''),
(165, '1311041001001200', 1, '131104100100120000101', '001', '01', NULL, 'Halim', 'Halim', NULL, ''),
(166, '1311041003000101', 1, '131104100300010100101', '001', '01', NULL, 'Siti', 'Siti', NULL, ''),
(167, '1311041003000102', 1, '131104100300010200101', '001', '01', NULL, 'Risa', 'Risa', NULL, ''),
(168, '1311041004000101', 1, '131104100400010100101', '001', '01', NULL, 'Yuni', 'Yuni', NULL, ''),
(169, '1311041004000102', 1, '131104100400010200101', '001', '01', NULL, 'Raden', 'Raden', NULL, ''),
(170, '1311041004000400', 1, '131104100400040000101', '001', '01', NULL, 'Laras', 'Laras', NULL, ''),
(171, '1311010005000301', 1, '﻿131101000500030100101', '001', '01', NULL, 'Ningsih', 'Ningsih', NULL, ''),
(174, '1311040', 2, '131104099226655', NULL, NULL, '99226655', NULL, NULL, 'Usaha Keripik Pisang', NULL),
(175, '1311040', 2, '131104065468186', NULL, NULL, '65468186', NULL, NULL, 'Usaha Travel Citra', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auth_groups_users`
--

CREATE TABLE `auth_groups_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_groups_users`
--

INSERT INTO `auth_groups_users` (`id`, `user_id`, `group`, `created_at`) VALUES
(1, 1, 'superadmin', '2026-04-24 12:46:38'),
(2, 9, 'operator', '2026-04-25 10:03:34'),
(3, 10, 'operator', '2026-04-25 10:24:04'),
(4, 12, 'admin', '2026-04-26 08:12:06'),
(5, 13, 'admin', '2026-04-26 08:20:07'),
(6, 14, 'superadmin', '2026-04-26 08:24:25'),
(7, 15, 'superadmin', '2026-04-26 08:26:32'),
(9, 17, 'admin', '2026-04-26 08:31:36'),
(10, 18, 'admin', '2026-04-26 08:34:19'),
(11, 20, 'operator', '2026-04-26 08:38:17'),
(13, 22, 'mitra', '2026-04-28 06:07:22'),
(15, 25, 'mitra', '2026-04-28 06:09:10'),
(17, 27, 'mitra', '2026-04-28 06:09:10'),
(18, 28, 'mitra', '2026-04-28 06:09:10'),
(19, 29, 'mitra', '2026-04-28 06:09:10'),
(20, 30, 'mitra', '2026-04-28 06:09:10'),
(25, 31, 'operator', '2026-04-30 07:35:51'),
(26, 26, 'operator', '2026-04-30 07:36:20'),
(27, 21, 'operator', '2026-04-30 07:39:21'),
(28, 24, 'operator', '2026-04-30 07:40:10'),
(29, 16, 'admin', '2026-04-30 07:40:33');

-- --------------------------------------------------------

--
-- Table structure for table `auth_identities`
--

CREATE TABLE `auth_identities` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `secret` varchar(255) NOT NULL,
  `secret2` varchar(255) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `extra` text DEFAULT NULL,
  `force_reset` tinyint(1) NOT NULL DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_identities`
--

INSERT INTO `auth_identities` (`id`, `user_id`, `type`, `name`, `secret`, `secret2`, `expires`, `extra`, `force_reset`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'email_password', '', 'admin@bps.go.id', '$2y$12$ABTl6j3cU0nUnYQX8ZtduueuhxFnl90kTNFCvR7aKJW07rndP0gRi', NULL, NULL, 0, '2026-05-05 00:48:58', '2026-04-21 10:37:34', '2026-05-05 00:48:58'),
(2, 10, 'email_password', NULL, 'rahma@bps.go.id', '$2y$12$X14/qhoB8z19wIlIiwooG.reINbblN51AsVFPmEC5lunwqU0p0vOa', NULL, NULL, 0, NULL, '2026-04-25 10:24:04', '2026-04-25 10:24:04'),
(3, 12, 'email_password', NULL, 'syafiq@bps.go.id', '$2y$12$LWxwPGQxTgy7G1EkwfxrxOy1m1Wrcq/XwkURZLSaOB84AcGIqqjVW', NULL, NULL, 0, NULL, '2026-04-26 08:12:06', '2026-04-26 08:34:42'),
(4, 13, 'email_password', NULL, 'admin2@bps.go.id', '$2y$12$sbTWanVolOX7VXTwc/BLj.UQBnRbpCarSfL8YnksbbnRug5OXmcBK', NULL, NULL, 0, NULL, '2026-04-26 08:20:07', '2026-04-26 08:20:07'),
(5, 14, 'email_password', NULL, 'admin3@bps.go.id', '$2y$12$JGgPvSQgKSHTiuswMQMc3u4yPjTLJlXQvBZFeSVemFcck6Zcu0Uiy', NULL, NULL, 0, '2026-04-26 08:49:10', '2026-04-26 08:24:25', '2026-04-26 08:49:10'),
(6, 15, 'email_password', NULL, 'admin4@bps.go.id', '$2y$12$.oyNGcORunoQesRTTvwR1.P440PKM2kiiTRCHjr55fRYyBHEZBfWW', NULL, NULL, 0, NULL, '2026-04-26 08:26:32', '2026-04-26 08:26:32'),
(7, 16, 'email_password', NULL, 'admin5@bps.go.id', '$2y$12$0YDjiVN55p8w9KiZCNVEkOrn2nuVE3yvpaaxIe381IMHDXaR6Plyu', NULL, NULL, 0, NULL, '2026-04-26 08:29:22', '2026-04-26 08:29:22'),
(8, 17, 'email_password', NULL, 'admin6@bps.go.id', '$2y$12$B4H/huLDld40SyBe6D.CSu7gBKbMAzNCyopasgKFmLS7W1ILgdgE.', NULL, NULL, 0, NULL, '2026-04-26 08:31:36', '2026-04-26 08:31:36'),
(9, 18, 'email_password', NULL, 'admin7@bps.go.id', '$2y$12$KxAzBGuvKZGNqG2QrKkrKe4dtlMiymXJ98awAWkGHTCCAGewxC2CO', NULL, NULL, 0, NULL, '2026-04-26 08:34:18', '2026-04-26 08:34:19'),
(10, 19, 'email_password', NULL, 'operator@bps.go.id', '$2y$12$xwmVIJw0JwR34DleACsQzOlGz83uuI601KZ.hWsBsVm6Gb4HGFVZK', NULL, NULL, 0, NULL, '2026-04-26 08:37:15', '2026-04-26 08:37:16'),
(11, 20, 'email_password', NULL, 'operator2@bps.go.id', '$2y$12$CVswX9KSXYbOuRlEJNy6d.ZHj/nOy9MUmILK.HrITnjrt5lOvB6Qm', NULL, NULL, 0, NULL, '2026-04-26 08:38:17', '2026-04-26 08:38:17'),
(12, 21, 'email_password', NULL, 'operator3@bps.go.id', '$2y$12$MP9LnltA4GJs.FDNW1Mt8.TbhrXX8DVA11qzCFcJ41Sxi5T2Q1rEO', NULL, NULL, 0, NULL, '2026-04-26 08:38:45', '2026-04-26 08:38:46'),
(13, 22, 'email_password', NULL, 'diaramayana91@gmail.com', '$2y$12$STn2U0WskjHZ5OAxGD37buch9BXEVoFz3oGORXy/GAq/7j2bIuO66', NULL, NULL, 0, NULL, '2026-04-28 03:13:47', '2026-04-28 03:13:47'),
(14, 24, 'email_password', NULL, 'sriyanto@bps.go.id', '$2y$12$TSqNA4eD9WRzx.LWEwfmx.Vpk2gUcDIaDrrrMNkyqZpqEL1FiZx2O', NULL, NULL, 0, NULL, '2026-04-28 03:18:12', '2026-04-28 03:18:12'),
(15, 25, 'email_password', NULL, 'norasyarkawi@gmail.com', '$2y$12$CfYYym7tmX/wxTrfcw5roOx4u8hb7vVrCLYfI9VNDC6jQtNUnX6fG', NULL, NULL, 0, NULL, '2026-04-28 03:18:12', '2026-04-28 03:18:12'),
(16, 26, 'email_password', NULL, 'dwimaagus@bps.go.id', '$2y$12$n4eIF7cobSkPAvHbSaiuOevwa/BgiMQnN6ZDcqUO/hwfM5DY1yLJG', NULL, NULL, 0, NULL, '2026-04-28 03:18:13', '2026-04-28 03:18:13'),
(17, 27, 'email_password', NULL, 'viandiapdiyanto@gmail.com', '$2y$12$JY/dJRH/hP.Tg7f8T9JFIOQhRYEeotJ96gDPPajS.wjLsCzJ0VJ52', NULL, NULL, 0, NULL, '2026-04-28 03:18:13', '2026-04-28 03:18:13'),
(18, 28, 'email_password', NULL, 'Hendry.ops.ho@gmail.com', '$2y$12$8aJqmyNGz/s2Q7EU3ZJfpeq/Lh3f1NkodLNuQBUbfcOWTuK76xhie', NULL, NULL, 0, NULL, '2026-04-28 03:18:13', '2026-04-28 03:18:14'),
(19, 29, 'email_password', NULL, 'tutisunarni1976@gmail.com', '$2y$12$tghqOeFbG3GmU9oI7aBTFOEjIeR8NT88iugCShkuK0N7NqQoRLqNS', NULL, NULL, 0, NULL, '2026-04-28 03:22:09', '2026-04-28 03:22:09'),
(20, 30, 'email_password', NULL, 'shisiadeeva99@gmail.com', '$2y$12$fBTlQOYrD/xqzTeuivLJ1Ow1Jiodf.DH/C95YLDQ3pvw23s7IpaV.', NULL, NULL, 0, NULL, '2026-04-28 03:35:58', '2026-04-28 03:35:58'),
(21, 31, 'email_password', NULL, 'taufiq.agung@bps.go.id', '$2y$12$aJ1fDuN0XUdLwYKsLq5E2eUwEIECRNOogFzDPWlsySyxIWu76Xr5.', NULL, NULL, 0, NULL, '2026-04-28 03:35:59', '2026-04-28 03:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `auth_logins`
--

CREATE TABLE `auth_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auth_logins`
--

INSERT INTO `auth_logins` (`id`, `ip_address`, `user_agent`, `id_type`, `identifier`, `user_id`, `date`, `success`) VALUES
(1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-21 10:38:37', 1),
(2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-22 00:26:52', 1),
(3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-23 00:15:24', 0),
(4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-23 00:15:31', 1),
(5, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-23 07:29:52', 1),
(6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 02:37:27', 1),
(7, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 12:08:32', 1),
(8, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 22:10:32', 0),
(9, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 22:10:37', 1),
(10, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 23:41:52', 0),
(11, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 23:41:53', 0),
(12, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 23:41:59', 0),
(13, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 23:42:07', 0),
(14, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 23:42:20', 1),
(15, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-24 23:49:58', 0),
(16, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 23:50:03', 1),
(17, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-24 23:50:03', 1),
(18, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-25 01:42:34', 1),
(19, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-25 01:43:58', 1),
(20, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-25 07:34:13', 0),
(21, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-25 07:34:19', 1),
(22, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-25 13:37:11', 1),
(23, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-25 17:45:43', 0),
(24, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-25 17:45:53', 0),
(25, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-25 17:46:07', 1),
(26, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-26 02:06:02', 1),
(27, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-26 08:07:00', 1),
(28, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-26 08:47:34', 0),
(29, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-26 08:47:40', 0),
(30, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin3@bps.go.id', 14, '2026-04-26 08:47:50', 1),
(31, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-26 08:48:02', 1),
(32, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin3@bps.go.id', 14, '2026-04-26 08:49:10', 1),
(33, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-26 08:49:42', 1),
(34, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-04-26 13:14:16', 0),
(35, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-26 13:14:21', 1),
(36, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-27 01:43:11', 1),
(37, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-27 23:09:38', 1),
(38, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-28 23:31:06', 1),
(39, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-28 23:44:43', 0),
(40, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'rahma@bps.go.id', NULL, '2026-04-28 23:44:51', 0),
(41, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-28 23:45:04', 1),
(42, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-30 02:05:44', 1),
(43, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-30 07:13:23', 1),
(44, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-04-30 16:19:52', 1),
(45, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-05-01 02:26:54', 0),
(46, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-01 02:26:59', 1),
(47, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-05-01 11:24:18', 0),
(48, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-01 11:24:22', 1),
(49, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', NULL, '2026-05-01 23:47:40', 0),
(50, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-01 23:47:47', 1),
(51, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-02 01:57:09', 1),
(52, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-02 09:47:47', 1),
(53, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Mobile Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-02 23:05:14', 1),
(54, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-03 20:12:10', 1),
(55, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-03 23:33:04', 1),
(56, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'email_password', 'admin@bps.go.id', 1, '2026-05-05 00:48:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `auth_permissions_users`
--

CREATE TABLE `auth_permissions_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `permission` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_remember_tokens`
--

CREATE TABLE `auth_remember_tokens` (
  `id` int(11) UNSIGNED NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashedValidator` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_token_logins`
--

CREATE TABLE `auth_token_logins` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `id_type` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `date` datetime NOT NULL,
  `success` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `broadcasts`
--

CREATE TABLE `broadcasts` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `kategori` enum('sop','kondef','kbli') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `broadcasts`
--

INSERT INTO `broadcasts` (`id`, `id_user`, `id_kegiatan`, `kategori`, `judul`, `isi`, `created_at`, `deleted_at`) VALUES
(1, 1, 1, 'sop', 'Pengumuman Update Fasih Engine', 'Assalamualaikum bapak/ibu semua, utk pekerjaan manen sawit menggunakan alat manual, itu statusnya tetap pekerja ya bapak ibu. jika majikannya tetap tp lebih dr 1, maka pekerjaa utama adalah di 1 majikan yang paling banyak jam kerja biasanya dalam seminggu, dilanjutkan tambahan utama majikan terbanyak selanjutnya.', '2025-11-13 20:41:37', NULL),
(2, 1, 1, 'sop', 'Pengumuman Perbaikan Anomali', 'Assalamualaikum bapak/ibu semua, utk pekerjaan manen sawit menggunakan alat manual, itu statusnya tetap pekerja ya bapak ibu. jika majikannya tetap tp lebih dr 1, maka pekerjaa utama adalah di 1 majikan yang paling banyak jam kerja biasanya dalam seminggu, dilanjutkan tambahan utama majikan terbanyak selanjutnya.', '2025-11-13 20:41:37', NULL),
(9, 1, 1, 'sop', 'Jangan Lupa Memakai Atribut', 'Kami menemukan masih banyak petugas yg tidak menggunakan atribut ketika pendataan kelapangan. mohon untuk di gunakan atribut yang telah diberikan.', '2026-05-01 18:55:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comics`
--

CREATE TABLE `comics` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `sampul` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comics`
--

INSERT INTO `comics` (`id`, `judul`, `slug`, `penulis`, `penerbit`, `sampul`, `created_at`, `updated_at`) VALUES
(1, 'Naruto', 'naruto', 'Masashi Kishimoto', 'Shonen Jump', 'naruto.jpeg', NULL, '2025-10-29 11:49:58'),
(2, 'One Piece', 'one-piece', 'Eichiro Oda', 'Gramedia', 'onepiece.jpeg', NULL, '2025-10-29 11:49:58'),
(3, 'Eye Shield 21', 'eye-shield-21', 'Syafiq', 'Gramedia', '', '2025-10-29 13:04:39', '2025-11-11 14:37:09');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_anomali`
--

CREATE TABLE `kategori_anomali` (
  `id` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `level_anomali` varchar(4) NOT NULL DEFAULT '1300',
  `kode_anomali` varchar(20) NOT NULL,
  `flag` enum('1','2','3') DEFAULT '3',
  `definisi_anomali` text DEFAULT NULL,
  `detil_anomali` text DEFAULT NULL,
  `is_show` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_anomali`
--

INSERT INTO `kategori_anomali` (`id`, `id_kegiatan`, `level_anomali`, `kode_anomali`, `flag`, `definisi_anomali`, `detil_anomali`, `is_show`, `date_created`, `date_updated`, `date_deleted`) VALUES
(1, 1, '1300', 'AN01', '3', 'Cek konsistensi jenis kelamin Kepala Keluarga.', 'Jenis kelamin KK tidak sesuai dengan NIK', 1, '2025-11-05 13:04:39', '2026-04-28 04:23:22', NULL),
(2, 1, '1300', 'AN02', '3', 'Verifikasi usia responden yang berusia di bawah 5 tahun.', 'Usia di bawah 5 tahun Tapi sudah tamat SD', 1, '2025-11-05 13:04:45', '2026-04-28 04:23:47', NULL),
(3, 1, '1300', 'AN03', '2', 'Cek data pekerjaan untuk responden berusia di atas 70 tahun.', 'Responden lansia (>70) terdeteksi memiliki pekerjaan full-time apakah benar?', 1, '2025-11-05 13:05:10', '2026-04-28 04:24:14', NULL),
(4, 1, '1300', 'AN04', '3', 'Pemeriksaan status perkawinan responden di bawah 15 tahun.', 'Responden di bawah dibawah 15 tahun, tapi sudah pernah menikah. apakah benar?', 0, '2025-11-05 13:05:30', '2026-04-28 04:24:43', NULL),
(5, 1, '1300', 'AN05', '3', 'Cek kelengkapan data alamat dan kode pos.', 'Kolom kode pos kosong atau tidak valid.', 1, '2025-11-05 13:06:01', '2026-01-29 05:48:19', NULL),
(6, 1, '1300', 'AN06', '3', 'Konsistensi hubungan dengan Kepala Keluarga (Hubungan Ganda).', 'Ditemukan lebih dari satu responden berstatus \"Istri\" dalam satu KK.', 1, '2025-11-05 13:06:22', NULL, NULL),
(7, 1, '1300', 'AN07', '3', 'Verifikasi status disabilitas dan kemampuan bekerja.', 'Responden disabilitas berat terdeteksi bekerja di sektor formal.', 1, '2025-11-05 13:07:05', NULL, NULL),
(8, 1, '1300', 'AN08', '3', 'Cek kesesuaian tingkat pendidikan dengan jenis pekerjaan.', 'Pendidikan SD memiliki pekerjaan sebagai Manajer/Profesional.', 0, '2025-11-05 13:07:40', NULL, NULL),
(9, 1, '1300', 'AN09', '3', 'Pemeriksaan data migrasi (tempat lahir dan tempat tinggal saat ini).', 'Tempat lahir berada di luar negeri, tetapi tidak ada catatan migrasi.', 1, '2025-11-05 13:08:00', NULL, NULL),
(10, 1, '1300', 'AN10', '3', 'Cek keberadaan NIK yang duplikat dalam wilayah sensus.', 'Nomor Induk Kependudukan (NIK) terdeteksi ganda.', 1, '2025-11-05 13:08:35', NULL, NULL),
(11, 1, '1300', 'AN11', '3', 'Validasi tanggal lahir (usia lebih dari 100 tahun).', 'Responden berusia sangat lanjut (>100) memerlukan verifikasi data.', 1, '2025-11-05 13:09:12', NULL, NULL),
(12, 1, '1300', 'AN12', '3', 'Pemeriksaan data kepemilikan aset (aset dan pendapatan tidak konsisten).', 'Pendapatan sangat rendah, namun memiliki aset properti mewah.', 0, '2025-11-05 13:09:40', NULL, NULL),
(13, 1, '1300', 'AN13', '3', 'Cek konsistensi data kepemilikan lahan pertanian.', 'Lahan pertanian yang dicatat melebihi batas kepemilikan maksimum.', 1, '2025-11-05 13:10:05', NULL, NULL),
(14, 1, '1300', 'AN14', '3', 'Verifikasi nomor telepon (terdapat format yang tidak baku).', 'Format nomor kontak responden tidak mengikuti standar nasional.', 1, '2025-11-05 13:10:30', NULL, NULL),
(15, 1, '1300', 'AN15', '3', 'Cek kelengkapan kolom penghasilan per bulan (terdapat nilai 0).', 'Kolom penghasilan wajib terisi, namun bernilai nol (0).', 0, '2025-11-05 13:11:00', NULL, NULL),
(16, 1, '1300', 'AN16', '3', 'Konsistensi status kepemilikan rumah (kontrak/milik sendiri).', 'Ditemukan status kepemilikan ganda atau tidak jelas.', 1, '2025-11-05 13:11:34', '0000-00-00 00:00:00', NULL),
(17, 1, '1300', 'AN17', '3', 'Pemeriksaan kode wilayah (kecamatan dan desa tidak sesuai).', 'Kombinasi kode kecamatan dan desa tidak terdaftar.', 1, '2025-11-05 13:12:01', NULL, NULL),
(18, 1, '1300', 'AN18', '3', 'Validasi data penggunaan air minum dan sanitasi.', 'Air minum dari PDAM, namun tidak memiliki fasilitas sanitasi yang layak.', 1, '2025-11-05 13:12:30', NULL, NULL),
(19, 1, '1300', 'AN19', '3', 'Cek kelengkapan data vaksinasi pada anak-anak.', 'Anak usia sekolah tidak memiliki catatan vaksinasi dasar lengkap.', 0, '2025-11-05 13:13:00', NULL, NULL),
(20, 1, '1300', 'AN20', '3', 'Pemeriksaan status kepemilikan hewan ternak (jumlah melebihi batas wajar).', 'Ditemukan jumlah ternak yang tidak realistis untuk ukuran rumah tangga biasa.', 1, '2025-11-05 13:13:30', NULL, NULL),
(24, 1, '1300', 'A2', '3', 'anomali baru A2', 'anomali baru A2', 1, '2026-04-21 07:46:01', '2026-04-22 10:02:09', NULL),
(25, 1, '1300', 'A34', '3', 'Anomali Baru A34', 'Anomali Baru A34', 0, '2026-04-21 07:46:01', '2026-04-22 10:02:26', NULL),
(26, 1, '1300', 'A50', '3', 'Anomali Baru A50', 'Anomali Baru A50', 0, '2026-04-21 07:46:01', '2026-04-22 10:02:45', NULL),
(27, 2, '1300', 'P1', '3', 'Potensi Usaha Ekonomi Digital KBLI Perbank-an', 'Usaha ini terdefinisi sebagai usaha dengan KBLI Perbank-an. apakah benar tidak menggunakan digital?', 1, '2026-05-03 09:14:26', '2026-05-04 09:40:58', NULL),
(28, 2, '1300', 'P2', '3', 'Potensi Ekonomi Digital Jumlah Omset', 'Usaha ini memiliki omset yg besar, apakah benar tidak menggunakan digital dalam penjualannya?', 1, '2026-05-03 09:14:26', '2026-05-03 02:17:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `kode_kegiatan` varchar(255) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `detil_kegiatan` varchar(255) NOT NULL,
  `is_rt` tinyint(1) NOT NULL DEFAULT 1,
  `level_wilayah` int(2) NOT NULL DEFAULT 4,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_deleted` datetime DEFAULT NULL,
  `priode_awal` datetime NOT NULL,
  `priode_akhir` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `kode_kegiatan`, `nama_kegiatan`, `detil_kegiatan`, `is_rt`, `level_wilayah`, `date_created`, `date_deleted`, `priode_awal`, `priode_akhir`) VALUES
(1, 'SE2026', 'SE2026 Pendataan', 'Sensus Ekonomi Pendataan', 1, 7, '2025-11-13 20:41:37', NULL, '2025-11-01 00:00:30', '2025-11-30 23:59:30'),
(2, 'SE2026_Potensi', 'SE2026-Potensi Ekonomi Kreatif', 'Anomali potensi Ekonomi Kreatif', 0, 7, '2025-11-13 20:41:37', NULL, '2025-08-01 00:00:30', '2025-08-30 23:59:30');

-- --------------------------------------------------------

--
-- Table structure for table `log_upload`
--

CREATE TABLE `log_upload` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `jenis` enum('wilayah','anomali') NOT NULL DEFAULT 'anomali',
  `nama_file` varchar(255) NOT NULL,
  `status` enum('pending','proses','selesai','gagal') NOT NULL DEFAULT 'pending',
  `total_baris` int(11) NOT NULL DEFAULT 0,
  `berhasil` int(11) NOT NULL DEFAULT 0,
  `gagal` int(11) NOT NULL DEFAULT 0,
  `error_details` longtext DEFAULT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_upload`
--

INSERT INTO `log_upload` (`id`, `id_kegiatan`, `jenis`, `nama_file`, `status`, `total_baris`, `berhasil`, `gagal`, `error_details`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 1, 'wilayah', '1777344311_7b879b4cf127778258ba.xlsx', 'selesai', 16, 0, 16, '[{\"baris\":2,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":3,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":4,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":5,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":6,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":7,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":8,\"data\":\"tutisunarni1976@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":9,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":10,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":11,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":12,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":13,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":14,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":15,\"data\":\"rahmisuwinda@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":16,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":17,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}}]', 1, '2026-04-28 02:45:11', '2026-04-28 02:57:42'),
(2, 1, 'wilayah', '1777345142_5ce635dcd1a8aef12551.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Duplicate entry \'11\' for key \'username\'\"]}]', 1, '2026-04-28 02:59:02', '2026-04-28 03:13:48'),
(3, 1, 'wilayah', '1777345197_64de40738c1637e6c636.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Allowed fields must be specified for model: \\\"App\\\\Models\\\\WilayahTugasModel\\\"\"]}]', 1, '2026-04-28 02:59:57', '2026-04-28 03:18:14'),
(4, 1, 'wilayah', '1777345292_32888085a6b04d806aff.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Attempt to read property \\\"id\\\" on bool\"]}]', 1, '2026-04-28 03:01:32', '2026-04-28 03:22:09'),
(5, 1, 'wilayah', '1777345367_25d2d68c75af9b40c4fa.xlsx', 'selesai', 16, 0, 16, '[{\"baris\":2,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":3,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":4,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":5,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":6,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":7,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":8,\"data\":\"tutisunarni1976@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":9,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":10,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":11,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":12,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":13,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":14,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":15,\"data\":\"rahmisuwinda@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":16,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":17,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}}]', 1, '2026-04-28 03:02:47', '2026-04-28 03:02:47'),
(6, 1, 'wilayah', '1777347357_15efa201ffef957246f3.xlsx', 'selesai', 16, 6, 10, '[{\"baris\":3,\"data\":\"1311010007000500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":4,\"data\":\"1311011003000400\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":9,\"data\":\"1311023004000200\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":10,\"data\":\"1311030001000400\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":11,\"data\":\"1311030004000300\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":12,\"data\":\"1311030005001500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":13,\"data\":\"1311040003000900\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":14,\"data\":\"1311040004000500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":15,\"data\":\"1311040008000600\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":16,\"data\":\"taufiq.agung@bps.go.id\",\"pesan\":[]}]', 1, '2026-04-28 03:35:57', '2026-04-28 03:35:59'),
(7, 2, 'wilayah', '1777881388_b0fab7ea4f542a1b5621.xlsx', 'pending', 0, 0, 0, NULL, 1, '2026-05-04 07:56:28', '2026-05-04 07:56:28'),
(8, 2, 'anomali', '1777952871_55a41c8e8fcfbcba5d62.xlsx', 'proses', 0, 0, 0, NULL, 1, NULL, NULL),
(9, 2, 'anomali', '1777952967_850d3f0c88c9c7d688f7.xlsx', 'selesai', 0, 0, 0, '[{\"baris\":\"-\",\"id_assigment\":\"Sistem\",\"messages\":[\"Array to string conversion\"]}]', 1, NULL, NULL),
(10, 2, 'anomali', '1777953773_de5e6dc474d305bfc851.xlsx', 'pending', 0, 0, 0, NULL, 1, '2026-05-05 11:02:53', '2026-05-05 11:02:53'),
(11, 2, 'anomali', '1777953791_9123096a01f93f374fee.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"messages\":[\"CodeIgniter\\\\BaseModel::timeToString(): Argument #1 ($properties) must be of type array, string given, called in C:\\\\Users\\\\syafi\\\\Documents\\\\Project\\\\monitoring-anomali\\\\vendor\\\\codeigniter4\\\\framework\\\\system\\\\BaseModel.php on line 1865\"]}]', 1, '2026-05-05 11:03:11', '2026-05-05 04:20:53'),
(12, 2, 'anomali', '1777953832_f1d36e905be475608a3d.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"messages\":[\"CodeIgniter\\\\BaseModel::timeToString(): Argument #1 ($properties) must be of type array, string given, called in C:\\\\Users\\\\syafi\\\\Documents\\\\Project\\\\monitoring-anomali\\\\vendor\\\\codeigniter4\\\\framework\\\\system\\\\BaseModel.php on line 1865\"]}]', 1, '2026-05-05 11:03:52', '2026-05-05 11:03:52');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2020-12-28-223112', 'CodeIgniter\\Shield\\Database\\Migrations\\CreateAuthTables', 'default', 'CodeIgniter\\Shield', 1776766670, 1),
(2, '2021-07-04-041948', 'CodeIgniter\\Settings\\Database\\Migrations\\CreateSettingsTable', 'default', 'CodeIgniter\\Settings', 1776766670, 1),
(3, '2021-11-14-143905', 'CodeIgniter\\Settings\\Database\\Migrations\\AddContextColumn', 'default', 'CodeIgniter\\Settings', 1776766670, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(9) NOT NULL,
  `class` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(31) NOT NULL DEFAULT 'string',
  `context` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `se_monitoring`
--

CREATE TABLE `se_monitoring` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(4) NOT NULL,
  `id_log` int(11) UNSIGNED NOT NULL,
  `jml_open` int(11) DEFAULT 0,
  `jml_submit` int(11) DEFAULT 0,
  `tbh_open` int(11) DEFAULT 0,
  `tbh_submit` int(11) DEFAULT 0,
  `jml_ed` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `se_monitoring`
--

INSERT INTO `se_monitoring` (`id`, `kd_wilayah`, `id_log`, `jml_open`, `jml_submit`, `tbh_open`, `tbh_submit`, `jml_ed`, `created_at`) VALUES
(1, '1300', 0, 10, 5, 10, 5, 9, '2026-05-01 08:00:00'),
(2, '1301', 0, 8, 4, 8, 4, 8, '2026-05-01 08:00:00'),
(3, '1302', 0, 12, 6, 12, 6, 10, '2026-05-01 08:00:00'),
(4, '1303', 0, 15, 7, 15, 7, 10, '2026-05-01 08:00:00'),
(5, '1304', 0, 9, 3, 9, 3, 7, '2026-05-01 08:00:00'),
(6, '1305', 0, 11, 5, 11, 5, 8, '2026-05-01 08:00:00'),
(7, '1306', 0, 14, 8, 14, 8, 11, '2026-05-01 08:00:00'),
(8, '1307', 0, 7, 2, 7, 2, 6, '2026-05-01 08:00:00'),
(9, '1308', 0, 16, 9, 16, 9, 12, '2026-05-01 08:00:00'),
(10, '1309', 0, 10, 5, 10, 5, 9, '2026-05-01 08:00:00'),
(11, '1310', 0, 13, 6, 13, 6, 10, '2026-05-01 08:00:00'),
(12, '1311', 0, 18, 10, 18, 10, 14, '2026-05-01 08:00:00'),
(13, '1312', 0, 11, 4, 11, 4, 8, '2026-05-01 08:00:00'),
(14, '1371', 0, 25, 15, 25, 15, 20, '2026-05-01 08:00:00'),
(15, '1372', 0, 14, 8, 14, 8, 11, '2026-05-01 08:00:00'),
(16, '1373', 0, 12, 7, 12, 7, 10, '2026-05-01 08:00:00'),
(17, '1374', 0, 10, 5, 10, 5, 8, '2026-05-01 08:00:00'),
(18, '1375', 0, 15, 9, 15, 9, 12, '2026-05-01 08:00:00'),
(19, '1376', 0, 9, 4, 9, 4, 7, '2026-05-01 08:00:00'),
(20, '1377', 0, 8, 3, 8, 3, 6, '2026-05-01 08:00:00'),
(21, '1300', 0, 15, 8, 5, 3, 10, '2026-05-02 08:00:00'),
(22, '1301', 0, 13, 7, 5, 3, 9, '2026-05-02 08:00:00'),
(23, '1302', 0, 17, 9, 5, 3, 10, '2026-05-02 08:00:00'),
(24, '1303', 0, 20, 10, 5, 3, 11, '2026-05-02 08:00:00'),
(25, '1304', 0, 14, 6, 5, 3, 8, '2026-05-02 08:00:00'),
(26, '1305', 0, 16, 8, 5, 3, 9, '2026-05-02 08:00:00'),
(27, '1306', 0, 19, 11, 5, 3, 12, '2026-05-02 08:00:00'),
(28, '1307', 0, 12, 5, 5, 3, 7, '2026-05-02 08:00:00'),
(29, '1308', 0, 21, 12, 5, 3, 13, '2026-05-02 08:00:00'),
(30, '1309', 0, 15, 8, 5, 3, 10, '2026-05-02 08:00:00'),
(31, '1310', 0, 18, 9, 5, 3, 11, '2026-05-02 08:00:00'),
(32, '1311', 0, 23, 13, 5, 3, 15, '2026-05-02 08:00:00'),
(33, '1312', 0, 16, 7, 5, 3, 9, '2026-05-02 08:00:00'),
(34, '1371', 0, 30, 18, 5, 3, 20, '2026-05-02 08:00:00'),
(35, '1372', 0, 19, 11, 5, 3, 12, '2026-05-02 08:00:00'),
(36, '1373', 0, 17, 10, 5, 3, 10, '2026-05-02 08:00:00'),
(37, '1374', 0, 15, 8, 5, 3, 9, '2026-05-02 08:00:00'),
(38, '1375', 0, 20, 12, 5, 3, 13, '2026-05-02 08:00:00'),
(39, '1376', 0, 14, 7, 5, 3, 8, '2026-05-02 08:00:00'),
(40, '1377', 0, 13, 6, 5, 3, 7, '2026-05-02 08:00:00'),
(52, '1300', 0, 22, 14, 7, 6, 11, '2026-05-03 08:00:00'),
(53, '1301', 0, 20, 13, 7, 6, 10, '2026-05-03 08:00:00'),
(54, '1302', 0, 24, 15, 7, 6, 12, '2026-05-03 08:00:00'),
(55, '1303', 0, 27, 16, 7, 6, 13, '2026-05-03 08:00:00'),
(56, '1304', 0, 21, 12, 7, 6, 10, '2026-05-03 08:00:00'),
(57, '1305', 0, 23, 14, 7, 6, 10, '2026-05-03 08:00:00'),
(58, '1306', 0, 26, 17, 7, 6, 14, '2026-05-03 08:00:00'),
(59, '1307', 0, 19, 11, 7, 6, 8, '2026-05-03 08:00:00'),
(60, '1308', 0, 28, 18, 7, 6, 15, '2026-05-03 08:00:00'),
(61, '1309', 0, 22, 14, 7, 6, 11, '2026-05-03 08:00:00'),
(62, '1310', 0, 25, 15, 7, 6, 12, '2026-05-03 08:00:00'),
(63, '1311', 0, 30, 19, 7, 6, 16, '2026-05-03 08:00:00'),
(64, '1312', 0, 23, 13, 7, 6, 11, '2026-05-03 08:00:00'),
(65, '1371', 0, 37, 24, 7, 6, 22, '2026-05-03 08:00:00'),
(66, '1372', 0, 26, 17, 7, 6, 14, '2026-05-03 08:00:00'),
(67, '1373', 0, 24, 16, 7, 6, 12, '2026-05-03 08:00:00'),
(68, '1374', 0, 22, 14, 7, 6, 10, '2026-05-03 08:00:00'),
(69, '1375', 0, 27, 18, 7, 6, 15, '2026-05-03 08:00:00'),
(70, '1376', 0, 21, 13, 7, 6, 9, '2026-05-03 08:00:00'),
(71, '1377', 0, 20, 12, 7, 6, 9, '2026-05-03 08:00:00'),
(83, '1300', 0, 32, 22, 10, 8, 13, '2026-05-04 08:00:00'),
(84, '1301', 0, 30, 21, 10, 8, 12, '2026-05-04 08:00:00'),
(85, '1302', 0, 34, 23, 10, 8, 14, '2026-05-04 08:00:00'),
(86, '1303', 0, 37, 24, 10, 8, 15, '2026-05-04 08:00:00'),
(87, '1304', 0, 31, 20, 10, 8, 11, '2026-05-04 08:00:00'),
(88, '1305', 0, 33, 22, 10, 8, 12, '2026-05-04 08:00:00'),
(89, '1306', 0, 36, 25, 10, 8, 15, '2026-05-04 08:00:00'),
(90, '1307', 0, 29, 19, 10, 8, 10, '2026-05-04 08:00:00'),
(91, '1308', 0, 38, 26, 10, 8, 16, '2026-05-04 08:00:00'),
(92, '1309', 0, 32, 22, 10, 8, 13, '2026-05-04 08:00:00'),
(93, '1310', 0, 35, 23, 10, 8, 14, '2026-05-04 08:00:00'),
(94, '1311', 0, 40, 27, 10, 8, 18, '2026-05-04 08:00:00'),
(95, '1312', 0, 33, 21, 10, 8, 13, '2026-05-04 08:00:00'),
(96, '1371', 0, 47, 32, 10, 8, 24, '2026-05-04 08:00:00'),
(97, '1372', 0, 36, 25, 10, 8, 15, '2026-05-04 08:00:00'),
(98, '1373', 0, 34, 24, 10, 8, 14, '2026-05-04 08:00:00'),
(99, '1374', 0, 32, 22, 10, 8, 12, '2026-05-04 08:00:00'),
(100, '1375', 0, 37, 26, 10, 8, 16, '2026-05-04 08:00:00'),
(101, '1376', 0, 31, 21, 10, 8, 11, '2026-05-04 08:00:00'),
(102, '1377', 0, 30, 20, 10, 8, 10, '2026-05-04 08:00:00'),
(114, '1300', 0, 36, 26, 4, 4, 13, '2026-05-05 08:00:00'),
(115, '1301', 0, 34, 25, 4, 4, 12, '2026-05-05 08:00:00'),
(116, '1302', 0, 38, 27, 4, 4, 14, '2026-05-05 08:00:00'),
(117, '1303', 0, 41, 28, 4, 4, 15, '2026-05-05 08:00:00'),
(118, '1304', 0, 35, 24, 4, 4, 12, '2026-05-05 08:00:00'),
(119, '1305', 0, 37, 26, 4, 4, 13, '2026-05-05 08:00:00'),
(120, '1306', 0, 40, 29, 4, 4, 16, '2026-05-05 08:00:00'),
(121, '1307', 0, 33, 23, 4, 4, 10, '2026-05-05 08:00:00'),
(122, '1308', 0, 42, 30, 4, 4, 17, '2026-05-05 08:00:00'),
(123, '1309', 0, 36, 26, 4, 4, 13, '2026-05-05 08:00:00'),
(124, '1310', 0, 39, 27, 4, 4, 15, '2026-05-05 08:00:00'),
(125, '1311', 0, 44, 31, 4, 4, 19, '2026-05-05 08:00:00'),
(126, '1312', 0, 37, 25, 4, 4, 13, '2026-05-05 08:00:00'),
(127, '1371', 0, 51, 36, 4, 4, 24, '2026-05-05 08:00:00'),
(128, '1372', 0, 40, 29, 4, 4, 16, '2026-05-05 08:00:00'),
(129, '1373', 0, 38, 28, 4, 4, 14, '2026-05-05 08:00:00'),
(130, '1374', 0, 36, 26, 4, 4, 12, '2026-05-05 08:00:00'),
(131, '1375', 0, 41, 30, 4, 4, 17, '2026-05-05 08:00:00'),
(132, '1376', 0, 35, 25, 4, 4, 11, '2026-05-05 08:00:00'),
(133, '1377', 0, 34, 24, 4, 4, 11, '2026-05-05 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `se_upload_log`
--

CREATE TABLE `se_upload_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `status` enum('pending','proses','selesai','gagal') NOT NULL DEFAULT 'pending',
  `total_baris` int(11) NOT NULL DEFAULT 0,
  `berhasil` int(11) NOT NULL DEFAULT 0,
  `gagal` int(11) NOT NULL DEFAULT 0,
  `error_details` longtext DEFAULT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `se_upload_log`
--

INSERT INTO `se_upload_log` (`id`, `id_kegiatan`, `nama_file`, `status`, `total_baris`, `berhasil`, `gagal`, `error_details`, `id_user`, `created_at`, `updated_at`) VALUES
(2, 2, 'ini_nama_file', 'pending', 3, 3, 0, NULL, 1, '2026-05-04 13:04:01', NULL),
(10, 2, 'template_monitoring.xlsx', '', 20, 1, 0, '[]', 1, '2026-05-04 16:16:50', NULL),
(11, 2, 'template_monitoring.xlsx', '', 20, 1, 0, '[]', 1, '2026-05-04 16:17:14', NULL),
(12, 2, 'template_monitoring.xlsx', 'pending', 20, 0, 0, NULL, 1, '2026-05-04 16:19:20', NULL),
(13, 2, 'template_monitoring.xlsx', 'pending', 20, 0, 0, NULL, 1, '2026-05-04 16:20:59', NULL),
(14, 2, 'template_monitoring.xlsx', '', 20, 19, 0, '[]', 1, '2026-05-04 16:21:39', NULL),
(15, 2, 'template_monitoring.xlsx', '', 20, 19, 0, '[]', 1, '2026-05-04 16:22:07', NULL),
(16, 2, 'template_monitoring.xlsx', 'pending', 19, 0, 0, NULL, 1, '2026-05-04 16:25:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `status_message` varchar(255) DEFAULT NULL,
  `wilayah_kerja` varchar(4) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_active` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `status`, `status_message`, `wilayah_kerja`, `active`, `last_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'Syafiq Hidayat', NULL, NULL, '1311', 0, '2026-05-05 04:06:28', '2026-04-21 10:37:33', '2026-04-21 10:37:33', NULL),
(9, 'rahma4', '', NULL, NULL, '1311', 0, NULL, '2026-04-25 10:03:34', '2026-04-25 10:03:34', NULL),
(10, 'rahma', 'Rahma Hidayah', NULL, NULL, '1311', 0, NULL, '2026-04-25 10:24:04', '2026-04-26 05:19:43', NULL),
(12, 'syafiq', 'Syafiq Hidayat', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:12:06', '2026-04-30 04:53:25', '2026-04-30 04:53:25'),
(13, 'admin2', 'Admin Dua', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:20:07', '2026-04-30 07:38:06', '2026-04-30 07:38:06'),
(14, 'admin3', 'Admin 3', NULL, NULL, '1311', 0, '2026-04-26 08:49:30', '2026-04-26 08:24:25', '2026-04-30 07:38:02', '2026-04-30 07:38:02'),
(15, 'admin4', 'admin empat', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:26:32', '2026-04-30 07:37:56', '2026-04-30 07:37:56'),
(16, 'admin5', 'Admin', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:29:22', '2026-04-30 07:40:33', NULL),
(17, 'admin6', 'admin enam', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:31:36', '2026-04-30 07:38:11', '2026-04-30 07:38:11'),
(18, 'admin7', 'admin tujuh', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:34:18', '2026-04-30 07:38:17', '2026-04-30 07:38:17'),
(19, 'operator', 'Operator', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:37:15', '2026-04-26 08:37:15', NULL),
(20, 'operator2', 'Operator Dua', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:38:17', '2026-04-30 07:40:43', '2026-04-30 07:40:43'),
(21, 'operator3', 'Operator Tiga', NULL, NULL, '1311', 0, NULL, '2026-04-26 08:38:45', '2026-04-30 07:40:48', '2026-04-30 07:40:48'),
(22, 'diaramayana91', 'Diaramayana91@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:13:47', '2026-04-28 04:18:37', NULL),
(24, 'sriyanto', 'Sriyanto', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:18:12', '2026-04-30 07:40:10', NULL),
(25, 'norasyarkawi', 'norasyarkawi@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:18:12', '2026-04-28 03:18:12', NULL),
(26, 'dwimaagus', 'Dwima Agus', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:18:12', '2026-04-30 07:36:20', NULL),
(27, 'viandiapdiyanto', 'viandiapdiyanto@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:18:13', '2026-04-28 03:18:13', NULL),
(28, 'Hendry.ops.ho', 'Hendry.ops.ho@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:18:13', '2026-04-28 03:18:13', NULL),
(29, 'tutisunarni1976', 'tutisunarni1976@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:22:09', '2026-04-28 03:22:09', NULL),
(30, 'shisiadeeva99', 'shisiadeeva99@gmail.com', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:35:58', '2026-04-28 03:35:58', NULL),
(31, 'taufiq.agung', 'Taufiq Agung', NULL, NULL, '1311', 0, NULL, '2026-04-28 03:35:58', '2026-04-30 07:35:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users_backup`
--

CREATE TABLE `users_backup` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','operator','mitra') NOT NULL,
  `level_akun` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_backup`
--

INSERT INTO `users_backup` (`id`, `nama`, `email`, `password`, `role`, `level_akun`) VALUES
(1, 'syafiq hidayat', '', '', 'admin', 'provinsi');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah`
--

CREATE TABLE `wilayah` (
  `id` varchar(16) NOT NULL,
  `kd_prov` varchar(2) NOT NULL,
  `kd_kab` varchar(2) NOT NULL,
  `kd_kec` varchar(3) NOT NULL,
  `kd_des` varchar(3) NOT NULL,
  `kd_sls` varchar(4) NOT NULL,
  `kd_subsls` varchar(2) NOT NULL,
  `kd_bs` varchar(4) NOT NULL,
  `nm_prov` varchar(255) NOT NULL,
  `nm_kab` varchar(255) NOT NULL,
  `nm_kec` varchar(255) NOT NULL,
  `nm_des` varchar(255) NOT NULL,
  `nm_sls` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah`
--

INSERT INTO `wilayah` (`id`, `kd_prov`, `kd_kab`, `kd_kec`, `kd_des`, `kd_sls`, `kd_subsls`, `kd_bs`, `nm_prov`, `nm_kab`, `nm_kec`, `nm_des`, `nm_sls`) VALUES
('1311010005000101', '13', '11', '010', '005', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG BALAI TANGAH'),
('1311010005000102', '13', '11', '010', '005', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG BALAI TANGAH'),
('1311010005000103', '13', '11', '010', '005', '0001', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG BALAI TANGAH'),
('1311010005000201', '13', '11', '010', '005', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TANAH ABANG'),
('1311010005000202', '13', '11', '010', '005', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TANAH ABANG'),
('1311010005000301', '13', '11', '010', '005', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SUNGAI KEMUNING'),
('1311010005000302', '13', '11', '010', '005', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SUNGAI KEMUNING'),
('1311010005000401', '13', '11', '010', '005', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SUNGAI BAYE'),
('1311010005000402', '13', '11', '010', '005', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SUNGAI BAYE'),
('1311010005000501', '13', '11', '010', '005', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SAKATO'),
('1311010005000502', '13', '11', '010', '005', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG SAKATO'),
('1311010005000601', '13', '11', '010', '005', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TARANDAM'),
('1311010005000602', '13', '11', '010', '005', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TARANDAM'),
('1311010005000701', '13', '11', '010', '005', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TALAGO PERMAI'),
('1311010005000702', '13', '11', '010', '005', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG TALAGO PERMAI'),
('1311010005000801', '13', '11', '010', '005', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG PASAR BARU'),
('1311010005000802', '13', '11', '010', '005', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG PASAR BARU'),
('1311010005000803', '13', '11', '010', '005', '0008', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG PASAR BARU'),
('1311010005000804', '13', '11', '010', '005', '0008', '04', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI', 'JORONG PASAR BARU'),
('1311010006000100', '13', '11', '010', '006', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG RAHMAT'),
('1311010006000200', '13', '11', '010', '006', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG TAUFIK'),
('1311010006000300', '13', '11', '010', '006', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG HIDAYAH'),
('1311010006000401', '13', '11', '010', '006', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG KOTO INDAH'),
('1311010006000402', '13', '11', '010', '006', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG KOTO INDAH'),
('1311010006000500', '13', '11', '010', '006', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG IRADAT'),
('1311010006000600', '13', '11', '010', '006', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG INAYAH'),
('1311010006000700', '13', '11', '010', '006', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG TAWAKAL'),
('1311010006000800', '13', '11', '010', '006', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG KHASANAH'),
('1311010006000900', '13', '11', '010', '006', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG RANAH PANJANG'),
('1311010006001000', '13', '11', '010', '006', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG IKHLAS'),
('1311010006001100', '13', '11', '010', '006', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG KOTO TANGAH'),
('1311010006001200', '13', '11', '010', '006', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG TAWAKAL BARU'),
('1311010006001300', '13', '11', '010', '006', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA KOTO SALAK', 'JORONG AMANAH'),
('1311010007000101', '13', '11', '010', '007', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG KAMPUNG BARU'),
('1311010007000102', '13', '11', '010', '007', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG KAMPUNG BARU'),
('1311010007000201', '13', '11', '010', '007', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG BUKIT BERBUNGA'),
('1311010007000202', '13', '11', '010', '007', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG BUKIT BERBUNGA'),
('1311010007000300', '13', '11', '010', '007', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG PASA PAGI'),
('1311010007000401', '13', '11', '010', '007', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG BALAI TIMUR'),
('1311010007000402', '13', '11', '010', '007', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG BALAI TIMUR'),
('1311010007000501', '13', '11', '010', '007', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG KAMBANG BARU'),
('1311010007000502', '13', '11', '010', '007', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG KAMBANG BARU'),
('1311010007000600', '13', '11', '010', '007', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'SUNGAI RUMBAI TIMUR', 'JORONG UJUNG KOTO'),
('1311010008000101', '13', '11', '010', '008', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO MULIA'),
('1311010008000102', '13', '11', '010', '008', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO MULIA'),
('1311010008000201', '13', '11', '010', '008', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG MULIA BAKTI'),
('1311010008000202', '13', '11', '010', '008', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG MULIA BAKTI'),
('1311010008000301', '13', '11', '010', '008', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO BAKTI'),
('1311010008000302', '13', '11', '010', '008', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO BAKTI'),
('1311010008000400', '13', '11', '010', '008', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG BAKTI MAKARYO'),
('1311010008000501', '13', '11', '010', '008', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO RANAH'),
('1311010008000502', '13', '11', '010', '008', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO RANAH'),
('1311010008000601', '13', '11', '010', '008', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO RANAH BARU'),
('1311010008000602', '13', '11', '010', '008', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO RANAH BARU'),
('1311010008000700', '13', '11', '010', '008', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO MUDIK'),
('1311010008000801', '13', '11', '010', '008', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO MUDIK BARU'),
('1311010008000802', '13', '11', '010', '008', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG KOTO MUDIK BARU'),
('1311010008000901', '13', '11', '010', '008', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG CAHAYA KOTO'),
('1311010008000902', '13', '11', '010', '008', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG CAHAYA KOTO'),
('1311010008001000', '13', '11', '010', '008', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG CAHAYA MURNI'),
('1311010008001100', '13', '11', '010', '008', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG RANAH MINANG'),
('1311010008001200', '13', '11', '010', '008', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG RANAH TALAGO'),
('1311010008001300', '13', '11', '010', '008', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG LARAS MINANG'),
('1311010008001400', '13', '11', '010', '008', '0014', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG LARAS MUDA'),
('1311010008001500', '13', '11', '010', '008', '0015', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG BATAS MINANG'),
('1311010008001600', '13', '11', '010', '008', '0016', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SUNGAI RUMBAI', 'KURNIA SELATAN', 'JORONG BATAS BARU'),
('1311011001000101', '13', '11', '011', '001', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH MULIA'),
('1311011001000102', '13', '11', '011', '001', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH MULIA'),
('1311011001000103', '13', '11', '011', '001', '0001', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH MULIA'),
('1311011001000201', '13', '11', '011', '001', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH JAYA'),
('1311011001000202', '13', '11', '011', '001', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH JAYA'),
('1311011001000301', '13', '11', '011', '001', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH MAKMUR'),
('1311011001000302', '13', '11', '011', '001', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH MAKMUR'),
('1311011001000401', '13', '11', '011', '001', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG MAYANG TAURAI'),
('1311011001000402', '13', '11', '011', '001', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG MAYANG TAURAI'),
('1311011001000501', '13', '11', '011', '001', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH BAKTI'),
('1311011001000502', '13', '11', '011', '001', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH BAKTI'),
('1311011001000503', '13', '11', '011', '001', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO GADANG', 'JORONG RANAH BAKTI'),
('1311011002000101', '13', '11', '011', '002', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG GUGUAK TINGGI'),
('1311011002000102', '13', '11', '011', '002', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG GUGUAK TINGGI'),
('1311011002000201', '13', '11', '011', '002', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG KOTO AGUNG'),
('1311011002000202', '13', '11', '011', '002', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG KOTO AGUNG'),
('1311011002000301', '13', '11', '011', '002', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG TANJUANG BATUANG'),
('1311011002000302', '13', '11', '011', '002', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG TANJUANG BATUANG'),
('1311011002000303', '13', '11', '011', '002', '0003', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG TANJUANG BATUANG'),
('1311011002000400', '13', '11', '011', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO TINGGI', 'JORONG SUKA MAJU'),
('1311011003000101', '13', '11', '011', '003', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG SUNGAI LIKIAN'),
('1311011003000102', '13', '11', '011', '003', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG SUNGAI LIKIAN'),
('1311011003000103', '13', '11', '011', '003', '0001', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG SUNGAI LIKIAN'),
('1311011003000201', '13', '11', '011', '003', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG BARU'),
('1311011003000202', '13', '11', '011', '003', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG BARU'),
('1311011003000300', '13', '11', '011', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG PASIR MAYANG'),
('1311011003000401', '13', '11', '011', '003', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG TUO'),
('1311011003000402', '13', '11', '011', '003', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'BONJOL', 'JORONG TUO'),
('1311011004000100', '13', '11', '011', '004', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG RUMAH NAN AMPEK'),
('1311011004000200', '13', '11', '011', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG RANAH BARU'),
('1311011004000301', '13', '11', '011', '004', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG ULAK BANJIR'),
('1311011004000302', '13', '11', '011', '004', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG ULAK BANJIR'),
('1311011004000400', '13', '11', '011', '004', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG RANAH PASAR'),
('1311011004000500', '13', '11', '011', '004', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG ABAI SIAT'),
('1311011004000600', '13', '11', '011', '004', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG BERINGIN'),
('1311011004000700', '13', '11', '011', '004', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG PADANG BUNGUR TIMUR'),
('1311011004000800', '13', '11', '011', '004', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG SAIYO'),
('1311011004000900', '13', '11', '011', '004', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG PADANG BUNGUR BARAT'),
('1311011004001000', '13', '11', '011', '004', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG PAYO MALINTANG'),
('1311011004001100', '13', '11', '011', '004', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG SAKATO'),
('1311011004001201', '13', '11', '011', '004', '0012', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG BUKIT AMAN'),
('1311011004001202', '13', '11', '011', '004', '0012', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG BUKIT AMAN'),
('1311011004001300', '13', '11', '011', '004', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'ABAI SIAT', 'JORONG SUNGAI TONTANG'),
('1311011005000100', '13', '11', '011', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO BESAR', 'JORONG KOTO BESAR'),
('1311011005000201', '13', '11', '011', '005', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO BESAR', 'JORONG KOTO DIATEH'),
('1311011005000202', '13', '11', '011', '005', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO BESAR', 'JORONG KOTO DIATEH'),
('1311011005000300', '13', '11', '011', '005', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO BESAR', 'JORONG KOTO DIBAWUAH'),
('1311011005000400', '13', '11', '011', '005', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO BESAR', 'JORONG PAKAN JUMAT'),
('1311011006000101', '13', '11', '011', '006', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG BUKIT GADING'),
('1311011006000102', '13', '11', '011', '006', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG BUKIT GADING'),
('1311011006000201', '13', '11', '011', '006', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG KOTO TANGAH'),
('1311011006000202', '13', '11', '011', '006', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG KOTO TANGAH'),
('1311011006000203', '13', '11', '011', '006', '0002', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG KOTO TANGAH'),
('1311011006000301', '13', '11', '011', '006', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG BUKIT MAKMUR'),
('1311011006000302', '13', '11', '011', '006', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG BUKIT MAKMUR'),
('1311011006000400', '13', '11', '011', '006', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG DURIAN GADANG'),
('1311011006000501', '13', '11', '011', '006', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG KOTO PANJANG'),
('1311011006000502', '13', '11', '011', '006', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO LAWEH', 'JORONG KOTO PANJANG'),
('1311011007000101', '13', '11', '011', '007', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG SUNGAI NABUHAN'),
('1311011007000102', '13', '11', '011', '007', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG SUNGAI NABUHAN'),
('1311011007000201', '13', '11', '011', '007', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG KOTO TUO'),
('1311011007000202', '13', '11', '011', '007', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG KOTO TUO'),
('1311011007000300', '13', '11', '011', '007', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG SEI JERINJING'),
('1311011007000400', '13', '11', '011', '007', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TELAGA BIRU'),
('1311011007000501', '13', '11', '011', '007', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TANJUNG PAKU ALAM'),
('1311011007000502', '13', '11', '011', '007', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TANJUNG PAKU ALAM'),
('1311011007000601', '13', '11', '011', '007', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG CENDANA'),
('1311011007000602', '13', '11', '011', '007', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG CENDANA'),
('1311011007000701', '13', '11', '011', '007', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TARATAK BARU'),
('1311011007000702', '13', '11', '011', '007', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TARATAK BARU'),
('1311011007000800', '13', '11', '011', '007', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BESAR', 'KOTO RANAH', 'JORONG TARATAK TINGGI'),
('1311012001000100', '13', '11', '012', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'LUBUK BESAR', 'JORONG LUBUK BESAR'),
('1311012001000200', '13', '11', '012', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'LUBUK BESAR', 'JORONG MANGUN JAYA'),
('1311012001000300', '13', '11', '012', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'LUBUK BESAR', 'JORONG KOTO UBI'),
('1311012001000401', '13', '11', '012', '001', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'LUBUK BESAR', 'JORONG SUNGAI BETUNG'),
('1311012001000402', '13', '11', '012', '001', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'LUBUK BESAR', 'JORONG SUNGAI BETUNG'),
('1311012002000101', '13', '11', '012', '002', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG BUKIT SEMBILAN'),
('1311012002000102', '13', '11', '012', '002', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG BUKIT SEMBILAN'),
('1311012002000200', '13', '11', '012', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG BATU KANGKUNG'),
('1311012002000300', '13', '11', '012', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG LUBUK BERINGIN'),
('1311012002000401', '13', '11', '012', '002', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG LUBUK BARU'),
('1311012002000402', '13', '11', '012', '002', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG LUBUK BARU'),
('1311012002000500', '13', '11', '012', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'ALAHAN NAN TIGO', 'JORONG SUNGAI PAPO'),
('1311012003000100', '13', '11', '012', '003', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SUNGAI LIMAU', 'JORONG KAYU ARO'),
('1311012003000200', '13', '11', '012', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SUNGAI LIMAU', 'JORONG TIMBULUN'),
('1311012003000300', '13', '11', '012', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SUNGAI LIMAU', 'JORONG SUNGAI LIMAU'),
('1311012003000400', '13', '11', '012', '003', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SUNGAI LIMAU', 'JORONG PINCURAN TUJUH'),
('1311012003000500', '13', '11', '012', '003', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SUNGAI LIMAU', 'JORONG KOTO TUO'),
('1311012004000101', '13', '11', '012', '004', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SINAMAR'),
('1311012004000102', '13', '11', '012', '004', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SINAMAR'),
('1311012004000200', '13', '11', '012', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SINAMAR TIMUR'),
('1311012004000300', '13', '11', '012', '004', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SINAMAR BARAT'),
('1311012004000401', '13', '11', '012', '004', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SUNGAI BERAWAN'),
('1311012004000402', '13', '11', '012', '004', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'SINAMAR', 'JORONG SUNGAI BERAWAN'),
('1311012005000100', '13', '11', '012', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'TANJUNG ALAM', 'JORONG MUARO SEMATAP'),
('1311012005000200', '13', '11', '012', '005', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'TANJUNG ALAM', 'JORONG TANJUNG ALAM'),
('1311012005000300', '13', '11', '012', '005', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'TANJUNG ALAM', 'JORONG AUR KUNING'),
('1311012005000400', '13', '11', '012', '005', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'ASAM JUJUHAN', 'TANJUNG ALAM', 'JORONG RANAH LAMO'),
('1311020001000100', '13', '11', '020', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG SUNGAI SAUNG'),
('1311020001000200', '13', '11', '020', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG LUBUK AGAM'),
('1311020001000301', '13', '11', '020', '001', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG PADANG KAWANG'),
('1311020001000302', '13', '11', '020', '001', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG PADANG KAWANG'),
('1311020001000400', '13', '11', '020', '001', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG KOTO GADANG'),
('1311020001000500', '13', '11', '020', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG PADANG BERIANG'),
('1311020001000600', '13', '11', '020', '001', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG CANGKIU BATU'),
('1311020001000700', '13', '11', '020', '001', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG KOTO DI ATEH'),
('1311020001000801', '13', '11', '020', '001', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG TELUK ALAI'),
('1311020001000802', '13', '11', '020', '001', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG TELUK ALAI'),
('1311020001000901', '13', '11', '020', '001', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG PASA BANDA'),
('1311020001000902', '13', '11', '020', '001', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG PASA BANDA'),
('1311020001001000', '13', '11', '020', '001', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'AMPANG KURANJI', 'JORONG SUNGAI PAYANG'),
('1311020003000100', '13', '11', '020', '003', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG KOTO KOTO BARU'),
('1311020003000201', '13', '11', '020', '003', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PASAR KOTO BARU'),
('1311020003000202', '13', '11', '020', '003', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PASAR KOTO BARU'),
('1311020003000203', '13', '11', '020', '003', '0002', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PASAR KOTO BARU'),
('1311020003000301', '13', '11', '020', '003', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SEBERANG PIRUKO BARAT'),
('1311020003000302', '13', '11', '020', '003', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SEBERANG PIRUKO BARAT'),
('1311020003000401', '13', '11', '020', '003', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SEBERANG PIRUKO TIMUR'),
('1311020003000402', '13', '11', '020', '003', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SEBERANG PIRUKO TIMUR'),
('1311020003000501', '13', '11', '020', '003', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SUNGAI BETUNG'),
('1311020003000502', '13', '11', '020', '003', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SUNGAI BETUNG'),
('1311020003000600', '13', '11', '020', '003', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG KOTO'),
('1311020003000701', '13', '11', '020', '003', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PALO KOTO'),
('1311020003000702', '13', '11', '020', '003', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PALO KOTO'),
('1311020003000801', '13', '11', '020', '003', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARIK BARU'),
('1311020003000802', '13', '11', '020', '003', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARIK BARU'),
('1311020003000900', '13', '11', '020', '003', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PASAR USANG'),
('1311020003001001', '13', '11', '020', '003', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SUNGAI LUKUIK'),
('1311020003001002', '13', '11', '020', '003', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SUNGAI LUKUIK'),
('1311020003001101', '13', '11', '020', '003', '0011', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LAKUAK LAWEH'),
('1311020003001102', '13', '11', '020', '003', '0011', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LAKUAK LAWEH'),
('1311020003001103', '13', '11', '020', '003', '0011', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LAKUAK LAWEH'),
('1311020003001201', '13', '11', '020', '003', '0012', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARIK SONTUL'),
('1311020003001202', '13', '11', '020', '003', '0012', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARIK SONTUL'),
('1311020003001301', '13', '11', '020', '003', '0013', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PALO PADANG'),
('1311020003001302', '13', '11', '020', '003', '0013', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PALO PADANG'),
('1311020003001400', '13', '11', '020', '003', '0014', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SEBERANG PIRUKO'),
('1311020003001501', '13', '11', '020', '003', '0015', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARAK LAWEH'),
('1311020003001502', '13', '11', '020', '003', '0015', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG PARAK LAWEH'),
('1311020003001600', '13', '11', '020', '003', '0016', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG TAPIAN NAPAL'),
('1311020003001700', '13', '11', '020', '003', '0017', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LUBUK PATIN'),
('1311020003001800', '13', '11', '020', '003', '0018', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG TARANDAM'),
('1311020003001900', '13', '11', '020', '003', '0019', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG BUKIT BAJANG'),
('1311020003002001', '13', '11', '020', '003', '0020', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SIMPANG TIGA'),
('1311020003002002', '13', '11', '020', '003', '0020', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG SIMPANG TIGA'),
('1311020003002101', '13', '11', '020', '003', '0021', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LUBUK PERING'),
('1311020003002102', '13', '11', '020', '003', '0021', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG LUBUK PERING'),
('1311020003002201', '13', '11', '020', '003', '0022', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG BUKIT BERANGIN'),
('1311020003002202', '13', '11', '020', '003', '0022', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO BARU', 'JORONG BUKIT BERANGIN'),
('1311020005000100', '13', '11', '020', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG SIALANG GAUNG'),
('1311020005000200', '13', '11', '020', '005', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG RANAH'),
('1311020005000301', '13', '11', '020', '005', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG SIMPANG EMPAT BELAS'),
('1311020005000302', '13', '11', '020', '005', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG SIMPANG EMPAT BELAS'),
('1311020005000400', '13', '11', '020', '005', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG LIMBAYUNG'),
('1311020005000501', '13', '11', '020', '005', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PASAR'),
('1311020005000502', '13', '11', '020', '005', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PASAR'),
('1311020005000600', '13', '11', '020', '005', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG GEMURUH'),
('1311020005000701', '13', '11', '020', '005', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG TARANTANG'),
('1311020005000702', '13', '11', '020', '005', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG TARANTANG'),
('1311020005000801', '13', '11', '020', '005', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG TANJUNG AMAN'),
('1311020005000802', '13', '11', '020', '005', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG TANJUNG AMAN'),
('1311020005000900', '13', '11', '020', '005', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PANDALEH'),
('1311020005001000', '13', '11', '020', '005', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG TABEK GUCI'),
('1311020005001100', '13', '11', '020', '005', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN SATU'),
('1311020005001200', '13', '11', '020', '005', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN SATU UTARA'),
('1311020005001300', '13', '11', '020', '005', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN SATU SELATAN'),
('1311020005001400', '13', '11', '020', '005', '0014', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN DUA'),
('1311020005001500', '13', '11', '020', '005', '0015', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN DUA SELATAN'),
('1311020005001600', '13', '11', '020', '005', '0016', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN DUA UTARA'),
('1311020005001700', '13', '11', '020', '005', '0017', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN TIGA'),
('1311020005001800', '13', '11', '020', '005', '0018', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN TIGA SELATAN'),
('1311020005001900', '13', '11', '020', '005', '0019', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN TIGA UTARA'),
('1311020005002000', '13', '11', '020', '005', '0020', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN EMPAT'),
('1311020005002100', '13', '11', '020', '005', '0021', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN EMPAT TIMUR'),
('1311020005002200', '13', '11', '020', '005', '0022', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN EMPAT BARAT'),
('1311020005002300', '13', '11', '020', '005', '0023', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN LIMA'),
('1311020005002400', '13', '11', '020', '005', '0024', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN LIMA BARAT'),
('1311020005002500', '13', '11', '020', '005', '0025', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN LIMA TIMUR'),
('1311020005002600', '13', '11', '020', '005', '0026', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN ENAM'),
('1311020005002700', '13', '11', '020', '005', '0027', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN ENAM BARAT'),
('1311020005002800', '13', '11', '020', '005', '0028', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'SIALANG GAUNG', 'JORONG PADANG BINTUNGAN ENAM TIMUR'),
('1311020008000101', '13', '11', '020', '008', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG TARATAK PARIK'),
('1311020008000102', '13', '11', '020', '008', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG TARATAK PARIK'),
('1311020008000200', '13', '11', '020', '008', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG KOTO PADANG'),
('1311020008000301', '13', '11', '020', '008', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG KOTO LINTAS'),
('1311020008000302', '13', '11', '020', '008', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG KOTO LINTAS'),
('1311020008000400', '13', '11', '020', '008', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG RAWANG SUNGAI'),
('1311020008000501', '13', '11', '020', '008', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG PINANG GADANG'),
('1311020008000502', '13', '11', '020', '008', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG PINANG GADANG'),
('1311020008000601', '13', '11', '020', '008', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG KAMPUNG BARU'),
('1311020008000602', '13', '11', '020', '008', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG KAMPUNG BARU'),
('1311020008000701', '13', '11', '020', '008', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000702', '13', '11', '020', '008', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000703', '13', '11', '020', '008', '0007', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000704', '13', '11', '020', '008', '0007', '04', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000705', '13', '11', '020', '008', '0007', '05', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000706', '13', '11', '020', '008', '0007', '06', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000707', '13', '11', '020', '008', '0007', '07', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000708', '13', '11', '020', '008', '0007', '08', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG SUNGAI LOMAK'),
('1311020008000800', '13', '11', '020', '008', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA SATU'),
('1311020008000901', '13', '11', '020', '008', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA DUA'),
('1311020008000902', '13', '11', '020', '008', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA DUA'),
('1311020008001000', '13', '11', '020', '008', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA TIGA'),
('1311020008001100', '13', '11', '020', '008', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA EMPAT'),
('1311020008001200', '13', '11', '020', '008', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA LIMA'),
('1311020008001300', '13', '11', '020', '008', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO BARU', 'KOTO PADANG', 'JORONG AUR JAYA ENAM'),
('1311021001000100', '13', '11', '021', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADUKUAN'),
('1311021001000201', '13', '11', '021', '001', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADANG RAMPAK'),
('1311021001000202', '13', '11', '021', '001', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADANG RAMPAK'),
('1311021001000300', '13', '11', '021', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADANG TENGAH SATU'),
('1311021001000400', '13', '11', '021', '001', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADANG TENGAH DUA'),
('1311021001000500', '13', '11', '021', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG PADANG TENGAH TIGA'),
('1311021001000601', '13', '11', '021', '001', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG SUNGAI RUMBAI'),
('1311021001000602', '13', '11', '021', '001', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG SUNGAI RUMBAI'),
('1311021001000700', '13', '11', '021', '001', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG SUNGAI BUNGUR'),
('1311021001000800', '13', '11', '021', '001', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG SUNGAI BUNGIN'),
('1311021001000900', '13', '11', '021', '001', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PADUKUAN', 'JORONG SUNGAI KASOK'),
('1311021002000100', '13', '11', '021', '002', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG LIMAU SUNDAI'),
('1311021002000200', '13', '11', '021', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG PULAU MAINAN'),
('1311021002000300', '13', '11', '021', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG JATI SALAM'),
('1311021002000401', '13', '11', '021', '002', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG LUBUK LESUNG'),
('1311021002000402', '13', '11', '021', '002', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG LUBUK LESUNG'),
('1311021002000500', '13', '11', '021', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG LUBUK LESUNG BARAT'),
('1311021002000600', '13', '11', '021', '002', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI KULUKUP'),
('1311021002000700', '13', '11', '021', '002', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI KULUKUP UTARA'),
('1311021002000800', '13', '11', '021', '002', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI LEMBUR'),
('1311021002000900', '13', '11', '021', '002', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI LEMBUR SELATAN'),
('1311021002001000', '13', '11', '021', '002', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI MAKMUR TIMUR'),
('1311021002001100', '13', '11', '021', '002', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'PULAU MAINAN', 'JORONG SUNGAI MAKMUR BARAT'),
('1311021003000101', '13', '11', '021', '003', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG SIMALIDU'),
('1311021003000102', '13', '11', '021', '003', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG SIMALIDU'),
('1311021003000200', '13', '11', '021', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG KAMPUNG BARU SIMALIDU'),
('1311021003000300', '13', '11', '021', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG KAMPUNG TENGAH'),
('1311021003000401', '13', '11', '021', '003', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG SETIA BUDI'),
('1311021003000402', '13', '11', '021', '003', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG SETIA BUDI'),
('1311021003000500', '13', '11', '021', '003', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG KAMPUNG HARAPAN'),
('1311021003000600', '13', '11', '021', '003', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG TANJUNG HARAPAN'),
('1311021003000700', '13', '11', '021', '003', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'SIMALIDU', 'JORONG SALAM BARU'),
('1311021004000100', '13', '11', '021', '004', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG KOTO.KOTO SALAK'),
('1311021004000200', '13', '11', '021', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG KAMPUNG BARU'),
('1311021004000300', '13', '11', '021', '004', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG RUMAH PADANG'),
('1311021004000400', '13', '11', '021', '004', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG LUBUK HARTO'),
('1311021004000500', '13', '11', '021', '004', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG PADANG TAROK'),
('1311021004000600', '13', '11', '021', '004', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG SUNGAI LANCAR'),
('1311021004000700', '13', '11', '021', '004', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG PEKAN SENAYAN'),
('1311021004000800', '13', '11', '021', '004', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG PADANG TAROK DUA'),
('1311021004000900', '13', '11', '021', '004', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG KOTO SALAK'),
('1311021004001000', '13', '11', '021', '004', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'KOTO SALAK', 'JORONG TANJUNG ALAM'),
('1311021005000100', '13', '11', '021', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG KOTO AMPALU'),
('1311021005000200', '13', '11', '021', '005', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG PASAR LAMA'),
('1311021005000301', '13', '11', '021', '005', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG PASAR BARU'),
('1311021005000302', '13', '11', '021', '005', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG PASAR BARU'),
('1311021005000400', '13', '11', '021', '005', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG MEKAR SARI'),
('1311021005000500', '13', '11', '021', '005', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG PINTU AGUNG'),
('1311021005000600', '13', '11', '021', '005', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG DUSUN BARU'),
('1311021005000700', '13', '11', '021', '005', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'KOTO SALAK', 'AMPALU', 'JORONG MANGKALANG'),
('1311022001000100', '13', '11', '022', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG KOTO BERINGIN SATU'),
('1311022001000200', '13', '11', '022', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG KOTO BERINGIN DUA'),
('1311022001000300', '13', '11', '022', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG SUNGAI MACANG'),
('1311022001000400', '13', '11', '022', '001', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG HARAPAN MULYA SATU'),
('1311022001000500', '13', '11', '022', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG HARAPAN MULYA II'),
('1311022001000600', '13', '11', '022', '001', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG MEKAR MULYA'),
('1311022001000700', '13', '11', '022', '001', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'KOTO BERINGIN', 'JORONG MULYA ABADI'),
('1311022002000100', '13', '11', '022', '002', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG SIPANGKUR SATU'),
('1311022002000200', '13', '11', '022', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG SIPANGKUR DUA');
INSERT INTO `wilayah` (`id`, `kd_prov`, `kd_kab`, `kd_kec`, `kd_des`, `kd_sls`, `kd_subsls`, `kd_bs`, `nm_prov`, `nm_kab`, `nm_kec`, `nm_des`, `nm_sls`) VALUES
('1311022002000301', '13', '11', '022', '002', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG SUMBER MULYA'),
('1311022002000302', '13', '11', '022', '002', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG SUMBER MULYA'),
('1311022002000400', '13', '11', '022', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG LAGAN JAYA SATU'),
('1311022002000500', '13', '11', '022', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG MEKAR MULYA'),
('1311022002000601', '13', '11', '022', '002', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG JAYA MULYA'),
('1311022002000602', '13', '11', '022', '002', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG JAYA MULYA'),
('1311022002000700', '13', '11', '022', '002', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SIPANGKUR', 'JORONG LAGAN JAYA DUA'),
('1311022003000100', '13', '11', '022', '003', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG SUNGAI LANGKOK'),
('1311022003000200', '13', '11', '022', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG KARYA HARAPAN'),
('1311022003000300', '13', '11', '022', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG KOTO HILALANG SATU'),
('1311022003000400', '13', '11', '022', '003', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG KOTO HILALANG DUA'),
('1311022003000501', '13', '11', '022', '003', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG BANJAR MAKMUR'),
('1311022003000502', '13', '11', '022', '003', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG BANJAR MAKMUR'),
('1311022003000600', '13', '11', '022', '003', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG KOTO HARJO'),
('1311022003000700', '13', '11', '022', '003', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG CANDI BIMA'),
('1311022003000800', '13', '11', '022', '003', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'SUNGAI LANGKOK', 'JORONG SEKAR MAKMUR'),
('1311022004000100', '13', '11', '022', '004', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG TIUMANG'),
('1311022004000200', '13', '11', '022', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG TIUMANG BARU'),
('1311022004000301', '13', '11', '022', '004', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUNGAI KALANG SATU'),
('1311022004000302', '13', '11', '022', '004', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUNGAI KALANG SATU'),
('1311022004000401', '13', '11', '022', '004', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG REJO SARI'),
('1311022004000402', '13', '11', '022', '004', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG REJO SARI'),
('1311022004000501', '13', '11', '022', '004', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUNGAI KALANG DUA'),
('1311022004000502', '13', '11', '022', '004', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUNGAI KALANG DUA'),
('1311022004000503', '13', '11', '022', '004', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUNGAI KALANG DUA'),
('1311022004000601', '13', '11', '022', '004', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUKO HARJO'),
('1311022004000602', '13', '11', '022', '004', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG SUKO HARJO'),
('1311022004000701', '13', '11', '022', '004', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG BUKIT HARAPAN'),
('1311022004000702', '13', '11', '022', '004', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG BUKIT HARAPAN'),
('1311022004000800', '13', '11', '022', '004', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIUMANG', 'TIUMANG', 'JORONG BUKIT HARAPAN JAYA'),
('1311023001000100', '13', '11', '023', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'BATU RIJAL', 'JORONG MORO BANGUN'),
('1311023001000200', '13', '11', '023', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'BATU RIJAL', 'JORONG BATU RIJAL'),
('1311023001000300', '13', '11', '023', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'BATU RIJAL', 'JORONG AUR KUNING'),
('1311023001000400', '13', '11', '023', '001', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'BATU RIJAL', 'JORONG SUNGAI ATANG'),
('1311023001000500', '13', '11', '023', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'BATU RIJAL', 'JORONG MOYO LUHUR'),
('1311023002000101', '13', '11', '023', '002', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'MUARO SOPAN', 'JORONG MUARO SOPAN'),
('1311023002000102', '13', '11', '023', '002', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'MUARO SOPAN', 'JORONG MUARO SOPAN'),
('1311023002000200', '13', '11', '023', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'MUARO SOPAN', 'JORONG BATU TAKAU'),
('1311023002000300', '13', '11', '023', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'MUARO SOPAN', 'JORONG SUNGAI SAKAI'),
('1311023002000400', '13', '11', '023', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'MUARO SOPAN', 'JORONG RIMBO AIA DINGIN'),
('1311023003000100', '13', '11', '023', '003', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'PADANG LAWEH', 'JORONG PADANG LAWEH'),
('1311023003000200', '13', '11', '023', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'PADANG LAWEH', 'JORONG KOTO LAMO'),
('1311023003000300', '13', '11', '023', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'PADANG LAWEH', 'JORONG BATANG TABEK'),
('1311023003000400', '13', '11', '023', '003', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'PADANG LAWEH', 'JORONG TITIAN AKAU'),
('1311023004000101', '13', '11', '023', '004', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG JATI MAKMUR'),
('1311023004000102', '13', '11', '023', '004', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG JATI MAKMUR'),
('1311023004000201', '13', '11', '023', '004', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG SOPAN JAYA'),
('1311023004000202', '13', '11', '023', '004', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG SOPAN JAYA'),
('1311023004000300', '13', '11', '023', '004', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG KAYU ARO'),
('1311023004000401', '13', '11', '023', '004', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG BUMI RAYA'),
('1311023004000402', '13', '11', '023', '004', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PADANG LAWEH', 'SOPAN JAYA', 'JORONG BUMI RAYA'),
('1311030001000101', '13', '11', '030', '001', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SITIUNG'),
('1311030001000102', '13', '11', '030', '001', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SITIUNG'),
('1311030001000201', '13', '11', '030', '001', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG KOTO SITIUNG'),
('1311030001000202', '13', '11', '030', '001', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG KOTO SITIUNG'),
('1311030001000300', '13', '11', '030', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SITIUNG TANGAH'),
('1311030001000401', '13', '11', '030', '001', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SITIUNG AGUNG'),
('1311030001000402', '13', '11', '030', '001', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SITIUNG AGUNG'),
('1311030001000500', '13', '11', '030', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG SUNGAI BAI'),
('1311030001000600', '13', '11', '030', '001', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PULAI'),
('1311030001000701', '13', '11', '030', '001', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO SELATAN'),
('1311030001000702', '13', '11', '030', '001', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO SELATAN'),
('1311030001000801', '13', '11', '030', '001', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO TENGAH'),
('1311030001000802', '13', '11', '030', '001', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO TENGAH'),
('1311030001000900', '13', '11', '030', '001', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO TIMUR'),
('1311030001001001', '13', '11', '030', '001', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO UTARA'),
('1311030001001002', '13', '11', '030', '001', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PIRUKO UTARA'),
('1311030001001101', '13', '11', '030', '001', '0011', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG LAWAI'),
('1311030001001102', '13', '11', '030', '001', '0011', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG LAWAI'),
('1311030001001201', '13', '11', '030', '001', '0012', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PISANG REBUS'),
('1311030001001202', '13', '11', '030', '001', '0012', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PISANG REBUS'),
('1311030001001203', '13', '11', '030', '001', '0012', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PISANG REBUS'),
('1311030001001301', '13', '11', '030', '001', '0013', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PADANG SIDONDANG'),
('1311030001001302', '13', '11', '030', '001', '0013', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SITIUNG', 'JORONG PADANG SIDONDANG'),
('1311030002000100', '13', '11', '030', '002', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SIGUNTUR SATU'),
('1311030002000200', '13', '11', '030', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SIGUNTUR ATEH'),
('1311030002000300', '13', '11', '030', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SIGUNTUR DUA'),
('1311030002000400', '13', '11', '030', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SIGUNTUR RANAH'),
('1311030002000500', '13', '11', '030', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG PALAYANGAN'),
('1311030002000600', '13', '11', '030', '002', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG TARATAK'),
('1311030002000700', '13', '11', '030', '002', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG TARATAK TANGAH'),
('1311030002000800', '13', '11', '030', '002', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG TARATAK RAWANG PARUPUAK'),
('1311030002000900', '13', '11', '030', '002', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG TARATAK BALAI SALASA'),
('1311030002001000', '13', '11', '030', '002', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG KOTO TUO'),
('1311030002001100', '13', '11', '030', '002', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG BALAI JANGGO'),
('1311030002001200', '13', '11', '030', '002', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG KOTO TUO BARAT'),
('1311030002001300', '13', '11', '030', '002', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SILULUK'),
('1311030002001400', '13', '11', '030', '002', '0014', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG PADANG TITIAN UREK'),
('1311030002001500', '13', '11', '030', '002', '0015', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG SUNGAI LANGSEK'),
('1311030002001600', '13', '11', '030', '002', '0016', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SIGUNTUR', 'JORONG PADANG ROCO'),
('1311030004000100', '13', '11', '030', '004', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG LUBUK AUR'),
('1311030004000200', '13', '11', '030', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KARYATAMA'),
('1311030004000301', '13', '11', '030', '004', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG BUNGO TANJUNG'),
('1311030004000302', '13', '11', '030', '004', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG BUNGO TANJUNG'),
('1311030004000401', '13', '11', '030', '004', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KAMPUNG DONDAN'),
('1311030004000402', '13', '11', '030', '004', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KAMPUNG DONDAN'),
('1311030004000501', '13', '11', '030', '004', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KOTO'),
('1311030004000502', '13', '11', '030', '004', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KOTO'),
('1311030004000503', '13', '11', '030', '004', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG KOTO'),
('1311030004000601', '13', '11', '030', '004', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG SEBERANG MIMPI'),
('1311030004000602', '13', '11', '030', '004', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG SEBERANG MIMPI'),
('1311030004000603', '13', '11', '030', '004', '0006', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG SEBERANG MIMPI'),
('1311030004000701', '13', '11', '030', '004', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG PALO TABEK'),
('1311030004000702', '13', '11', '030', '004', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG PALO TABEK'),
('1311030004000703', '13', '11', '030', '004', '0007', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG PALO TABEK'),
('1311030004000801', '13', '11', '030', '004', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG GANTIANG'),
('1311030004000802', '13', '11', '030', '004', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG GANTIANG'),
('1311030004000803', '13', '11', '030', '004', '0008', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'GUNUNG MEDAN', 'JORONG GANTIANG'),
('1311030005000100', '13', '11', '030', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG LUBUK PANJANG'),
('1311030005000200', '13', '11', '030', '005', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KOTO DAULAT'),
('1311030005000300', '13', '11', '030', '005', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI DUO'),
('1311030005000401', '13', '11', '030', '005', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI NAPAU'),
('1311030005000402', '13', '11', '030', '005', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI NAPAU'),
('1311030005000501', '13', '11', '030', '005', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI SALAK'),
('1311030005000502', '13', '11', '030', '005', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI SALAK'),
('1311030005000503', '13', '11', '030', '005', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG SUNGAI SALAK'),
('1311030005000601', '13', '11', '030', '005', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KARTIKA INDAH'),
('1311030005000602', '13', '11', '030', '005', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KARTIKA INDAH'),
('1311030005000701', '13', '11', '030', '005', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KOTO AGUNG KIRI'),
('1311030005000702', '13', '11', '030', '005', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KOTO AGUNG KIRI'),
('1311030005000703', '13', '11', '030', '005', '0007', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KOTO AGUNG KIRI'),
('1311030005000801', '13', '11', '030', '005', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG MARGO MULYO'),
('1311030005000802', '13', '11', '030', '005', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG MARGO MULYO'),
('1311030005000901', '13', '11', '030', '005', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG HARAPAN MAJU'),
('1311030005000902', '13', '11', '030', '005', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG HARAPAN MAJU'),
('1311030005001000', '13', '11', '030', '005', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KOTO AGUNG KANAN'),
('1311030005001101', '13', '11', '030', '005', '0011', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG CANDRA KIRANA'),
('1311030005001102', '13', '11', '030', '005', '0011', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG CANDRA KIRANA'),
('1311030005001200', '13', '11', '030', '005', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KARYA BUDAYA BARAT'),
('1311030005001301', '13', '11', '030', '005', '0013', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KARYA BUDAYA TIMUR'),
('1311030005001302', '13', '11', '030', '005', '0013', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG KARYA BUDAYA TIMUR'),
('1311030005001401', '13', '11', '030', '005', '0014', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG TELUK SIKAI'),
('1311030005001402', '13', '11', '030', '005', '0014', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG TELUK SIKAI'),
('1311030005001501', '13', '11', '030', '005', '0015', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG TAMAN SARI'),
('1311030005001502', '13', '11', '030', '005', '0015', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'SITIUNG', 'SUNGAI DUO', 'JORONG TAMAN SARI'),
('1311031001000101', '13', '11', '031', '001', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA SARI'),
('1311031001000102', '13', '11', '031', '001', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA SARI'),
('1311031001000200', '13', '11', '031', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA SATU'),
('1311031001000301', '13', '11', '031', '001', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA DUA'),
('1311031001000302', '13', '11', '031', '001', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA DUA'),
('1311031001000401', '13', '11', '031', '001', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG MULYA JAYA'),
('1311031001000402', '13', '11', '031', '001', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG MULYA JAYA'),
('1311031001000500', '13', '11', '031', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG TRIMULYA TIGA'),
('1311031001000600', '13', '11', '031', '001', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG MULYA BARU'),
('1311031001000700', '13', '11', '031', '001', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG SUKAJADI'),
('1311031001000801', '13', '11', '031', '001', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG PANYUBRANGAN'),
('1311031001000802', '13', '11', '031', '001', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'PANYUBARANGAN', 'JORONG PANYUBRANGAN'),
('1311031002000100', '13', '11', '031', '002', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG TABEK MAJU'),
('1311031002000200', '13', '11', '031', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG TABEK JAYA'),
('1311031002000300', '13', '11', '031', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG LUBUK PENDO'),
('1311031002000400', '13', '11', '031', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG SUKA MULIA TINGGI'),
('1311031002000500', '13', '11', '031', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG MULIA JAYA'),
('1311031002000601', '13', '11', '031', '002', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG PINANG JAYA'),
('1311031002000602', '13', '11', '031', '002', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG PINANG JAYA'),
('1311031002000701', '13', '11', '031', '002', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG PINANG MAKMUR'),
('1311031002000702', '13', '11', '031', '002', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG PINANG MAKMUR'),
('1311031002000801', '13', '11', '031', '002', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG SUMBER MAKMUR'),
('1311031002000802', '13', '11', '031', '002', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG SUMBER MAKMUR'),
('1311031002000803', '13', '11', '031', '002', '0008', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TABEK', 'JORONG SUMBER MAKMUR'),
('1311031003000100', '13', '11', '031', '003', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TIMPEH', 'JORONG SUNGAI BULIAN'),
('1311031003000200', '13', '11', '031', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TIMPEH', 'JORONG KOTO TENGAH'),
('1311031003000300', '13', '11', '031', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TIMPEH', 'JORONG KOTO HILIR'),
('1311031003000400', '13', '11', '031', '003', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TIMPEH', 'JORONG SUNGAI PINANG'),
('1311031004000101', '13', '11', '031', '004', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR'),
('1311031004000102', '13', '11', '031', '004', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR'),
('1311031004000201', '13', '11', '031', '004', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR I'),
('1311031004000202', '13', '11', '031', '004', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR I'),
('1311031004000300', '13', '11', '031', '004', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR II'),
('1311031004000400', '13', '11', '031', '004', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA MAKMUR III'),
('1311031004000500', '13', '11', '031', '004', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG MARGA JAYA'),
('1311031004000600', '13', '11', '031', '004', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG GUNUNG TALANG'),
('1311031004000701', '13', '11', '031', '004', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO'),
('1311031004000702', '13', '11', '031', '004', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO'),
('1311031004000801', '13', '11', '031', '004', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO I'),
('1311031004000802', '13', '11', '031', '004', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO I'),
('1311031004000901', '13', '11', '031', '004', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO II'),
('1311031004000902', '13', '11', '031', '004', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAIYO II'),
('1311031004001001', '13', '11', '031', '004', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAKATO'),
('1311031004001002', '13', '11', '031', '004', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAKATO'),
('1311031004001100', '13', '11', '031', '004', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAKATO I'),
('1311031004001201', '13', '11', '031', '004', '0012', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAKATO II'),
('1311031004001202', '13', '11', '031', '004', '0012', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'TARATAK TINGGI', 'JORONG SAKATO II'),
('1311031005000100', '13', '11', '031', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG RANAH MAKMUR'),
('1311031005000200', '13', '11', '031', '005', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG BUKIT SUBUR'),
('1311031005000300', '13', '11', '031', '005', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG SUNGAI PALABI'),
('1311031005000400', '13', '11', '031', '005', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG BANGUN ARGO'),
('1311031005000500', '13', '11', '031', '005', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG SUNGAI MANGGIS'),
('1311031005000600', '13', '11', '031', '005', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG BUKIT JAYA'),
('1311031005000700', '13', '11', '031', '005', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG SUNGAI TENANG'),
('1311031005000800', '13', '11', '031', '005', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'TIMPEH', 'RANAH PALABI', 'JORONG BUKIT TUJUH'),
('1311040003000101', '13', '11', '040', '003', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PULAU PUNJUNG'),
('1311040003000102', '13', '11', '040', '003', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PULAU PUNJUNG'),
('1311040003000201', '13', '11', '040', '003', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TANJUNG LIMAU'),
('1311040003000202', '13', '11', '040', '003', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TANJUNG LIMAU'),
('1311040003000301', '13', '11', '040', '003', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PADANG DURI'),
('1311040003000302', '13', '11', '040', '003', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PADANG DURI'),
('1311040003000401', '13', '11', '040', '003', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG RANAH PULAU PUNJUNG'),
('1311040003000402', '13', '11', '040', '003', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG RANAH PULAU PUNJUNG'),
('1311040003000403', '13', '11', '040', '003', '0004', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG RANAH PULAU PUNJUNG'),
('1311040003000501', '13', '11', '040', '003', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000502', '13', '11', '040', '003', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000503', '13', '11', '040', '003', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000504', '13', '11', '040', '003', '0005', '04', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000505', '13', '11', '040', '003', '0005', '05', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000506', '13', '11', '040', '003', '0005', '06', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG PANJANG'),
('1311040003000601', '13', '11', '040', '003', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PIKULAN'),
('1311040003000602', '13', '11', '040', '003', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PIKULAN'),
('1311040003000701', '13', '11', '040', '003', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG GAJAH'),
('1311040003000702', '13', '11', '040', '003', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG KUBANG GAJAH'),
('1311040003000801', '13', '11', '040', '003', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TARATAK PULAU PUNJUNG'),
('1311040003000802', '13', '11', '040', '003', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TARATAK PULAU PUNJUNG'),
('1311040003000803', '13', '11', '040', '003', '0008', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TARATAK PULAU PUNJUNG'),
('1311040003000901', '13', '11', '040', '003', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG SIMPANG POGANG'),
('1311040003000902', '13', '11', '040', '003', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG SIMPANG POGANG'),
('1311040003001001', '13', '11', '040', '003', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG BUKIK SABOLAH'),
('1311040003001002', '13', '11', '040', '003', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG BUKIK SABOLAH'),
('1311040003001101', '13', '11', '040', '003', '0011', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PASAR PULAU PUNJUNG'),
('1311040003001102', '13', '11', '040', '003', '0011', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PASAR PULAU PUNJUNG'),
('1311040003001103', '13', '11', '040', '003', '0011', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG PASAR PULAU PUNJUNG'),
('1311040003001201', '13', '11', '040', '003', '0012', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TABEK'),
('1311040003001202', '13', '11', '040', '003', '0012', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'IV KOTO PULAU PUNJUNG', 'JORONG TABEK'),
('1311040004000101', '13', '11', '040', '004', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG AMPANG KAMANG'),
('1311040004000102', '13', '11', '040', '004', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG AMPANG KAMANG'),
('1311040004000103', '13', '11', '040', '004', '0001', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG AMPANG KAMANG'),
('1311040004000200', '13', '11', '040', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG SUNGAI KILANGAN'),
('1311040004000301', '13', '11', '040', '004', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG SUNGAI SANGKIR'),
('1311040004000302', '13', '11', '040', '004', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG SUNGAI SANGKIR'),
('1311040004000401', '13', '11', '040', '004', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RANAH MACANG'),
('1311040004000402', '13', '11', '040', '004', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RANAH MACANG'),
('1311040004000501', '13', '11', '040', '004', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RANAH'),
('1311040004000502', '13', '11', '040', '004', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RANAH'),
('1311040004000601', '13', '11', '040', '004', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG KOTO TANGAH'),
('1311040004000602', '13', '11', '040', '004', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG KOTO TANGAH'),
('1311040004000701', '13', '11', '040', '004', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG KOTO GADANG'),
('1311040004000702', '13', '11', '040', '004', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG KOTO GADANG'),
('1311040004000801', '13', '11', '040', '004', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RAWANG SAKO'),
('1311040004000802', '13', '11', '040', '004', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG RAWANG SAKO'),
('1311040004000901', '13', '11', '040', '004', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG PADANG CANDI'),
('1311040004000902', '13', '11', '040', '004', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG PADANG CANDI'),
('1311040004000903', '13', '11', '040', '004', '0009', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG PADANG CANDI'),
('1311040004001000', '13', '11', '040', '004', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI DAREH', 'JORONG BUKIK KOMPE'),
('1311040005000100', '13', '11', '040', '005', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG KOTO TEBING TINGGI'),
('1311040005000201', '13', '11', '040', '005', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG RANAH LINTAS'),
('1311040005000202', '13', '11', '040', '005', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG RANAH LINTAS'),
('1311040005000301', '13', '11', '040', '005', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG BATANG TABEK'),
('1311040005000302', '13', '11', '040', '005', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG BATANG TABEK'),
('1311040005000401', '13', '11', '040', '005', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG SIDO MULYO'),
('1311040005000402', '13', '11', '040', '005', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG SIDO MULYO'),
('1311040005000501', '13', '11', '040', '005', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG PADANG SARI'),
('1311040005000502', '13', '11', '040', '005', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG PADANG SARI'),
('1311040005000600', '13', '11', '040', '005', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG GANTING SIMAUNG'),
('1311040005000700', '13', '11', '040', '005', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'TEBING TINGGI', 'JORONG RANAH COLAU'),
('1311040006000101', '13', '11', '040', '006', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KOTO LAMO'),
('1311040006000102', '13', '11', '040', '006', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KOTO LAMO'),
('1311040006000201', '13', '11', '040', '006', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KOTO LAMO ATAS'),
('1311040006000202', '13', '11', '040', '006', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KOTO LAMO ATAS'),
('1311040006000301', '13', '11', '040', '006', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG LABUH LURUS'),
('1311040006000302', '13', '11', '040', '006', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG LABUH LURUS'),
('1311040006000303', '13', '11', '040', '006', '0003', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG LABUH LURUS'),
('1311040006000401', '13', '11', '040', '006', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG LAMBAU'),
('1311040006000402', '13', '11', '040', '006', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG LAMBAU'),
('1311040006000501', '13', '11', '040', '006', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG JAMBU LIPO'),
('1311040006000502', '13', '11', '040', '006', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG JAMBU LIPO'),
('1311040006000503', '13', '11', '040', '006', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG JAMBU LIPO'),
('1311040006000601', '13', '11', '040', '006', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KUMANI'),
('1311040006000602', '13', '11', '040', '006', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KUMANI'),
('1311040006000701', '13', '11', '040', '006', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PASIR PUTIAH'),
('1311040006000702', '13', '11', '040', '006', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PASIR PUTIAH'),
('1311040006000703', '13', '11', '040', '006', '0007', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PASIR PUTIAH'),
('1311040006000704', '13', '11', '040', '006', '0007', '04', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PASIR PUTIAH'),
('1311040006000705', '13', '11', '040', '006', '0007', '05', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PASIR PUTIAH'),
('1311040006000801', '13', '11', '040', '006', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG SUNGAI NILI'),
('1311040006000802', '13', '11', '040', '006', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG SUNGAI NILI'),
('1311040006000901', '13', '11', '040', '006', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG MUARO MAU'),
('1311040006000902', '13', '11', '040', '006', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG MUARO MAU'),
('1311040006001001', '13', '11', '040', '006', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PULAU SANGIK'),
('1311040006001002', '13', '11', '040', '006', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG PULAU SANGIK'),
('1311040006001100', '13', '11', '040', '006', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG MUARO MOMONG'),
('1311040006001200', '13', '11', '040', '006', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG CIBARAU PANJANG'),
('1311040006001300', '13', '11', '040', '006', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG KULIM BASISIAK'),
('1311040006001400', '13', '11', '040', '006', '0014', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SUNGAI KAMBUT', 'JORONG SUNGAI KAMBUT DUA'),
('1311040007000100', '13', '11', '040', '007', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG LUBUK BULANG'),
('1311040007000201', '13', '11', '040', '007', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG KAMPUNG SURAU'),
('1311040007000202', '13', '11', '040', '007', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG KAMPUNG SURAU'),
('1311040007000300', '13', '11', '040', '007', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SUNGAI BELIT'),
('1311040007000401', '13', '11', '040', '007', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SIALANG'),
('1311040007000402', '13', '11', '040', '007', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SIALANG'),
('1311040007000500', '13', '11', '040', '007', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SIMPANG TIGO'),
('1311040007000600', '13', '11', '040', '007', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SUNGAI LOMAK'),
('1311040007000700', '13', '11', '040', '007', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG BATU AGUNG'),
('1311040007000801', '13', '11', '040', '007', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SIBUBUIK'),
('1311040007000802', '13', '11', '040', '007', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'GUNUNG SELASIH', 'JORONG SIBUBUIK'),
('1311040008000101', '13', '11', '040', '008', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG PARIK TARAJAK'),
('1311040008000102', '13', '11', '040', '008', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG PARIK TARAJAK'),
('1311040008000201', '13', '11', '040', '008', '0002', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KAPALO KOTO'),
('1311040008000202', '13', '11', '040', '008', '0002', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KAPALO KOTO'),
('1311040008000301', '13', '11', '040', '008', '0003', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG TABEK PAMATANG'),
('1311040008000302', '13', '11', '040', '008', '0003', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG TABEK PAMATANG'),
('1311040008000401', '13', '11', '040', '008', '0004', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KOTO SIKABAU'),
('1311040008000402', '13', '11', '040', '008', '0004', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KOTO SIKABAU'),
('1311040008000501', '13', '11', '040', '008', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT BARANGAN'),
('1311040008000502', '13', '11', '040', '008', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT BARANGAN'),
('1311040008000503', '13', '11', '040', '008', '0005', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT BARANGAN'),
('1311040008000504', '13', '11', '040', '008', '0005', '04', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT BARANGAN'),
('1311040008000505', '13', '11', '040', '008', '0005', '05', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT BARANGAN'),
('1311040008000601', '13', '11', '040', '008', '0006', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG TANJUNG SALILOK'),
('1311040008000602', '13', '11', '040', '008', '0006', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG TANJUNG SALILOK'),
('1311040008000603', '13', '11', '040', '008', '0006', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG TANJUNG SALILOK'),
('1311040008000701', '13', '11', '040', '008', '0007', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KOTO PANJANG'),
('1311040008000702', '13', '11', '040', '008', '0007', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KOTO PANJANG'),
('1311040008000801', '13', '11', '040', '008', '0008', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KAMPUNG BARU'),
('1311040008000802', '13', '11', '040', '008', '0008', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KAMPUNG BARU'),
('1311040008000803', '13', '11', '040', '008', '0008', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG KAMPUNG BARU'),
('1311040008000901', '13', '11', '040', '008', '0009', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG SUNGAI SONSANG'),
('1311040008000902', '13', '11', '040', '008', '0009', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG SUNGAI SONSANG'),
('1311040008000903', '13', '11', '040', '008', '0009', '03', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG SUNGAI SONSANG'),
('1311040008001001', '13', '11', '040', '008', '0010', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT MINDAWA'),
('1311040008001002', '13', '11', '040', '008', '0010', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG BUKIT MINDAWA'),
('1311040008001100', '13', '11', '040', '008', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'JORONG CAMPUR JAYA'),
('1311040008100101', '13', '11', '040', '008', '1001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'PT AWB'),
('1311040008100102', '13', '11', '040', '008', '1001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'PULAU PUNJUNG', 'SIKABAU', 'PT AWB'),
('1311041001000100', '13', '11', '041', '001', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG BANAI'),
('1311041001000200', '13', '11', '041', '001', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG MONGGE SIUNG'),
('1311041001000300', '13', '11', '041', '001', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG MUDIK BANAI'),
('1311041001000400', '13', '11', '041', '001', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG MUDIK LAGO'),
('1311041001000500', '13', '11', '041', '001', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG MUDIK TALAOK'),
('1311041001000600', '13', '11', '041', '001', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG BUKIT KEMUNING'),
('1311041001000700', '13', '11', '041', '001', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG BUGAH'),
('1311041001000800', '13', '11', '041', '001', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG BUKIT NANEH'),
('1311041001000900', '13', '11', '041', '001', '0009', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG PADANG HILALANG'),
('1311041001001000', '13', '11', '041', '001', '0010', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG SUNGAI SIMPOLA'),
('1311041001001100', '13', '11', '041', '001', '0011', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG LUBUK PAUH'),
('1311041001001200', '13', '11', '041', '001', '0012', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG SUNGAI LIMAU'),
('1311041001001300', '13', '11', '041', '001', '0013', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'BANAI', 'JORONG LUBUK LABU'),
('1311041002000100', '13', '11', '041', '002', '0001', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG LUBUK KARAK');
INSERT INTO `wilayah` (`id`, `kd_prov`, `kd_kab`, `kd_kec`, `kd_des`, `kd_sls`, `kd_subsls`, `kd_bs`, `nm_prov`, `nm_kab`, `nm_kec`, `nm_des`, `nm_sls`) VALUES
('1311041002000200', '13', '11', '041', '002', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG SUMANIK'),
('1311041002000300', '13', '11', '041', '002', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG KOTO LAMO'),
('1311041002000400', '13', '11', '041', '002', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG SIRAHO'),
('1311041002000500', '13', '11', '041', '002', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG SUNGAI KAPUR'),
('1311041002000600', '13', '11', '041', '002', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'LUBUK KARAK', 'JORONG SINGOLAN'),
('1311041003000101', '13', '11', '041', '003', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG SILAGO'),
('1311041003000102', '13', '11', '041', '003', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG SILAGO'),
('1311041003000200', '13', '11', '041', '003', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG RANAH KAYU KALEK'),
('1311041003000300', '13', '11', '041', '003', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG LUBUK BENUANG'),
('1311041003000400', '13', '11', '041', '003', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG BUKIT DURIAN KUBANGAN'),
('1311041003000500', '13', '11', '041', '003', '0005', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG AMPANG KURANJI'),
('1311041003000600', '13', '11', '041', '003', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG AGUNG BATU'),
('1311041003000700', '13', '11', '041', '003', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG PAKANI'),
('1311041003000800', '13', '11', '041', '003', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'SILAGO', 'JORONG BATANG SINGOLAN SATU'),
('1311041004000101', '13', '11', '041', '004', '0001', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG PULAU ANJOLAI'),
('1311041004000102', '13', '11', '041', '004', '0001', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG PULAU ANJOLAI'),
('1311041004000200', '13', '11', '041', '004', '0002', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG KOTO BARU'),
('1311041004000300', '13', '11', '041', '004', '0003', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG SILOMBIK'),
('1311041004000400', '13', '11', '041', '004', '0004', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG LAGAN'),
('1311041004000501', '13', '11', '041', '004', '0005', '01', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG LUBUK MANSAGU'),
('1311041004000502', '13', '11', '041', '004', '0005', '02', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG LUBUK MANSAGU'),
('1311041004000600', '13', '11', '041', '004', '0006', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG DURIAN SIMPAI'),
('1311041004000700', '13', '11', '041', '004', '0007', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG KOTO'),
('1311041004000800', '13', '11', '041', '004', '0008', '00', '', 'SUMATERA BARAT', 'DHARMASRAYA', 'IX KOTO', 'KOTO NAN IV DIBAWUAH', 'JORONG KUAT SAKATO');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_tugas`
--

CREATE TABLE `wilayah_tugas` (
  `id` int(11) NOT NULL,
  `id_wilayah` varchar(16) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `id_ppl` int(11) UNSIGNED NOT NULL,
  `id_pml` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah_tugas`
--

INSERT INTO `wilayah_tugas` (`id`, `id_wilayah`, `id_kegiatan`, `id_ppl`, `id_pml`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1311010006000700', 1, 22, 24, '2026-04-28 10:22:09', '2026-04-28 10:22:09', '2026-04-28 10:22:09'),
(2, '1311011007000300', 1, 25, 26, '2026-04-28 10:22:09', '2026-04-28 10:22:09', '2026-04-28 10:22:09'),
(3, '1311020005000400', 1, 27, 28, '2026-04-28 10:22:09', '2026-04-28 10:22:09', '2026-04-28 10:22:09'),
(4, '1311020005002800', 1, 27, 28, '2026-04-28 10:22:09', '2026-04-28 10:22:09', '2026-04-28 10:22:09'),
(5, '1311010006000700', 1, 22, 24, '2026-04-28 10:35:58', '2026-04-28 10:35:58', '2026-04-28 10:35:58'),
(6, '1311011007000300', 1, 25, 26, '2026-04-28 10:35:58', '2026-04-28 10:35:58', '2026-04-28 10:35:58'),
(7, '1311020005000400', 1, 27, 28, '2026-04-28 10:35:58', '2026-04-28 10:35:58', '2026-04-28 10:35:58'),
(8, '1311020005002800', 1, 27, 28, '2026-04-28 10:35:58', '2026-04-28 10:35:58', '2026-04-28 10:35:58'),
(9, '1311021003000300', 1, 29, 24, '2026-04-28 10:35:58', '2026-04-28 10:35:58', '2026-04-28 10:35:58'),
(10, '1311041003000500', 1, 30, 31, '2026-04-28 10:35:59', '2026-04-28 10:35:59', '2026-04-28 10:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `wilayah_upload_log`
--

CREATE TABLE `wilayah_upload_log` (
  `id` int(11) UNSIGNED NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `status` enum('pending','proses','selesai','gagal') NOT NULL DEFAULT 'pending',
  `total_baris` int(11) NOT NULL DEFAULT 0,
  `berhasil` int(11) NOT NULL DEFAULT 0,
  `gagal` int(11) NOT NULL DEFAULT 0,
  `error_details` longtext DEFAULT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayah_upload_log`
--

INSERT INTO `wilayah_upload_log` (`id`, `id_kegiatan`, `nama_file`, `status`, `total_baris`, `berhasil`, `gagal`, `error_details`, `id_user`, `created_at`, `updated_at`) VALUES
(1, 1, '1777344311_7b879b4cf127778258ba.xlsx', 'selesai', 16, 0, 16, '[{\"baris\":2,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":3,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":4,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":5,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":6,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":7,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":8,\"data\":\"tutisunarni1976@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":9,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":10,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":11,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":12,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":13,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":14,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":15,\"data\":\"rahmisuwinda@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":16,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":17,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}}]', 1, '2026-04-28 02:45:11', '2026-04-28 02:57:42'),
(2, 1, '1777345142_5ce635dcd1a8aef12551.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Duplicate entry \'11\' for key \'username\'\"]}]', 1, '2026-04-28 02:59:02', '2026-04-28 03:13:48'),
(3, 1, '1777345197_64de40738c1637e6c636.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Allowed fields must be specified for model: \\\"App\\\\Models\\\\WilayahTugasModel\\\"\"]}]', 1, '2026-04-28 02:59:57', '2026-04-28 03:18:14'),
(4, 1, '1777345292_32888085a6b04d806aff.xlsx', 'gagal', 0, 0, 0, '[{\"baris\":\"-\",\"data\":\"Sistem\",\"pesan\":[\"Attempt to read property \\\"id\\\" on bool\"]}]', 1, '2026-04-28 03:01:32', '2026-04-28 03:22:09'),
(5, 1, '1777345367_25d2d68c75af9b40c4fa.xlsx', 'selesai', 16, 0, 16, '[{\"baris\":2,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":3,\"data\":\"diaramayana91@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":4,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":5,\"data\":\"norasyarkawi@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":6,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":7,\"data\":\"viandiapdiyanto@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":8,\"data\":\"tutisunarni1976@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":9,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":10,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":11,\"data\":\"dwinasuryanti5@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":12,\"data\":\"anifebria94@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":13,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":14,\"data\":\"hutrieni83@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":15,\"data\":\"rahmisuwinda@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":16,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}},{\"baris\":17,\"data\":\"shisiadeeva99@gmail.com\",\"pesan\":{\"idWilayah\":\"The idWilayah field is required.\"}}]', 1, '2026-04-28 03:02:47', '2026-04-28 03:02:47'),
(6, 1, '1777347357_15efa201ffef957246f3.xlsx', 'selesai', 16, 6, 10, '[{\"baris\":3,\"data\":\"1311010007000500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":4,\"data\":\"1311011003000400\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":9,\"data\":\"1311023004000200\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":10,\"data\":\"1311030001000400\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":11,\"data\":\"1311030004000300\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":12,\"data\":\"1311030005001500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":13,\"data\":\"1311040003000900\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":14,\"data\":\"1311040004000500\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":15,\"data\":\"1311040008000600\",\"pesan\":{\"idWilayah\":\"The idWilayah field must contain a previously existing value in the database.\"}},{\"baris\":16,\"data\":\"taufiq.agung@bps.go.id\",\"pesan\":[]}]', 1, '2026-04-28 03:35:57', '2026-04-28 03:35:59'),
(7, 2, '1777881388_b0fab7ea4f542a1b5621.xlsx', 'pending', 0, 0, 0, NULL, 1, '2026-05-04 07:56:28', '2026-05-04 07:56:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anomali`
--
ALTER TABLE `anomali`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kategori_anomali` (`id_kategori_anomali`),
  ADD KEY `fk_assigment` (`id_assigment`);

--
-- Indexes for table `assigment`
--
ALTER TABLE `assigment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wilayah` (`id_wilayah`);

--
-- Indexes for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_groups_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_secret` (`type`,`secret`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_logins`
--
ALTER TABLE `auth_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_permissions_users_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `auth_remember_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_type_identifier` (`id_type`,`identifier`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `broadcasts`
--
ALTER TABLE `broadcasts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comics`
--
ALTER TABLE `comics`
  ADD PRIMARY KEY (`id`,`updated_at`);

--
-- Indexes for table `kategori_anomali`
--
ALTER TABLE `kategori_anomali`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_upload`
--
ALTER TABLE `log_upload`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `se_monitoring`
--
ALTER TABLE `se_monitoring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_wil_time` (`kd_wilayah`,`created_at`);

--
-- Indexes for table `se_upload_log`
--
ALTER TABLE `se_upload_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_backup`
--
ALTER TABLE `users_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wilayah_tugas`
--
ALTER TABLE `wilayah_tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tugas_wilayah` (`id_wilayah`),
  ADD KEY `tugas_kegiatan` (`id_kegiatan`),
  ADD KEY `tugas_ppl` (`id_ppl`),
  ADD KEY `tugas_pml` (`id_pml`);

--
-- Indexes for table `wilayah_upload_log`
--
ALTER TABLE `wilayah_upload_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anomali`
--
ALTER TABLE `anomali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `assigment`
--
ALTER TABLE `assigment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

--
-- AUTO_INCREMENT for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `auth_identities`
--
ALTER TABLE `auth_identities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `auth_logins`
--
ALTER TABLE `auth_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auth_token_logins`
--
ALTER TABLE `auth_token_logins`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `broadcasts`
--
ALTER TABLE `broadcasts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comics`
--
ALTER TABLE `comics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `kategori_anomali`
--
ALTER TABLE `kategori_anomali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_upload`
--
ALTER TABLE `log_upload`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `se_monitoring`
--
ALTER TABLE `se_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=225;

--
-- AUTO_INCREMENT for table `se_upload_log`
--
ALTER TABLE `se_upload_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users_backup`
--
ALTER TABLE `users_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wilayah_tugas`
--
ALTER TABLE `wilayah_tugas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wilayah_upload_log`
--
ALTER TABLE `wilayah_upload_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anomali`
--
ALTER TABLE `anomali`
  ADD CONSTRAINT `fk_kategori_anomali` FOREIGN KEY (`id_kategori_anomali`) REFERENCES `kategori_anomali` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_groups_users`
--
ALTER TABLE `auth_groups_users`
  ADD CONSTRAINT `auth_groups_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_identities`
--
ALTER TABLE `auth_identities`
  ADD CONSTRAINT `auth_identities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_permissions_users`
--
ALTER TABLE `auth_permissions_users`
  ADD CONSTRAINT `auth_permissions_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auth_remember_tokens`
--
ALTER TABLE `auth_remember_tokens`
  ADD CONSTRAINT `auth_remember_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wilayah_tugas`
--
ALTER TABLE `wilayah_tugas`
  ADD CONSTRAINT `tugas_kegiatan` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id`),
  ADD CONSTRAINT `tugas_pml` FOREIGN KEY (`id_pml`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tugas_ppl` FOREIGN KEY (`id_ppl`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tugas_wilayah` FOREIGN KEY (`id_wilayah`) REFERENCES `wilayah` (`id`);

--
-- Constraints for table `wilayah_upload_log`
--
ALTER TABLE `wilayah_upload_log`
  ADD CONSTRAINT `log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
