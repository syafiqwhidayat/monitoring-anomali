-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 02:34 PM
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
  `id_user` int(11) NOT NULL,
  `id_wilayah` varchar(16) NOT NULL,
  `id_rtart` varchar(24) NOT NULL,
  `konfirmasi` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anomali`
--

INSERT INTO `anomali` (`id`, `id_kategori_anomali`, `id_user`, `id_wilayah`, `id_rtart`, `konfirmasi`, `date_created`, `date_deleted`) VALUES
(1, 1, 1, '1311040008000401', '1311040008000401001011', '', '2025-11-13 20:22:45', NULL),
(2, 1, 1, '1311040008000401', '1311040008000401001021', 'Sesuai Lapangan', '2025-11-13 20:22:45', NULL),
(4, 13, 1, '1311021002000100', '131102100200010000101', 'Perlu Perbaikan: NIK salah format', '0000-00-00 00:00:00', NULL),
(5, 10, 1, '1311030005000901', '131103000500090100101', 'Data Ganda: Ditemukan entri serupa di Blok B', '0000-00-00 00:00:00', NULL),
(6, 9, 1, '1311040003000902', '131104000300090200101', 'Sesuai Lapangan: Tidak ada perubahan data', '0000-00-00 00:00:00', NULL),
(7, 14, 1, '1311011007000502', '131101100700050200101', 'Perlu perbaikan: Kode wilayah RT/RW keliru', '0000-00-00 00:00:00', NULL),
(8, 18, 1, '1311023003000300', '131102300300030000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(9, 1, 1, '1311040008000503', '131104000800050300101', 'Data Ganda: Responden ditemukan di Blok Sensus lain', '0000-00-00 00:00:00', NULL),
(10, 10, 1, '1311040005000501', '131104000500050100101', 'Perlu perbaikan: Nilai penghasilan tidak masuk akal', '0000-00-00 00:00:00', NULL),
(11, 11, 1, '1311020005002500', '131102000500250000101', 'Sesuai Lapangan: Data sudah diverifikasi ulang', '0000-00-00 00:00:00', NULL),
(12, 14, 1, '1311040006000802', '131104000600080200101', 'Data Ganda: Duplikasi data Kepala Keluarga', '0000-00-00 00:00:00', NULL),
(13, 5, 1, '1311040004000601', '131104000400060100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(14, 8, 1, '1311023004000401', '131102300400040100101', 'Perlu perbaikan: Kelengkapan data pendidikan formal', '0000-00-00 00:00:00', NULL),
(15, 8, 1, '1311040007000801', '131104000700080100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(16, 20, 1, '1311020001000802', '131102000100080200101', 'Perlu perbaikan: Status rumah tangga perlu diperjelas', '0000-00-00 00:00:00', NULL),
(17, 16, 1, '1311010007000300', '131101000700030000101', 'Data Ganda: Entry berulang oleh pengguna yang sama', '0000-00-00 00:00:00', NULL),
(18, 5, 1, '1311011001000301', '131101100100030100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(19, 17, 1, '1311031004000902', '131103100400090200101', 'Perlu perbaikan: Jenis kelamin KK terbalik', '0000-00-00 00:00:00', NULL),
(20, 16, 1, '1311012004000101', '131101200400010100101', 'Data Ganda: Hanya perlu menghapus satu entri', '0000-00-00 00:00:00', NULL),
(21, 15, 1, '1311040008000601', '131104000800060100101', 'Sesuai Lapangan: Data telah diperbaiki dan diverifikasi', '0000-00-00 00:00:00', NULL),
(22, 16, 1, '1311011003000402', '131101100300040200101', 'Perlu perbaikan: NIK kosong, wajib diisi', '0000-00-00 00:00:00', NULL),
(23, 10, 1, '1311010008000502', '131101000800050200101', 'Sesuai Lapangan: Ditemukan error pada sistem, bukan data', '0000-00-00 00:00:00', NULL),
(24, 10, 1, '1311021002000401', '131102100200040100101', 'Perlu perbaikan: Kolom pekerjaan terlewat', '0000-00-00 00:00:00', NULL),
(25, 4, 1, '1311022002000301', '131102200200030100101', 'Data Ganda: Kesalahan penginputan nama', '0000-00-00 00:00:00', NULL),
(26, 4, 1, '1311010006000900', '131101000600090000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(27, 6, 1, '1311022004000100', '131102200400010000101', 'Perlu perbaikan: Tanggal wawancara tidak sesuai', '0000-00-00 00:00:00', NULL),
(28, 10, 1, '1311040006000701', '131104000600070100101', 'Perlu perbaikan: Usia KK harus > 17 tahun', '0000-00-00 00:00:00', NULL),
(29, 10, 1, '1311022003000100', '131102200300010000101', 'Sesuai Lapangan: Terverifikasi, data input sudah benar', '0000-00-00 00:00:00', NULL),
(30, 19, 1, '1311011007000701', '131101100700070100101', 'Data Ganda: Responden ini sudah tercatat di KK sebelah', '0000-00-00 00:00:00', NULL),
(31, 5, 1, '1311021002000402', '131102100200040200101', 'Perlu perbaikan: Status pekerjaan tidak konsisten', '0000-00-00 00:00:00', NULL),
(32, 13, 1, '1311010006000402', '131101000600040200101', 'Sesuai Lapangan: Setelah diperiksa, data valid', '0000-00-00 00:00:00', NULL),
(33, 1, 1, '1311020003002102', '131102000300210200101', 'Perlu perbaikan: Alamat tidak terisi lengkap', '0000-00-00 00:00:00', NULL),
(34, 13, 1, '1311011005000202', '131101100500020200101', 'Data Ganda: Duplikasi ID responden', '0000-00-00 00:00:00', NULL),
(35, 11, 1, '1311030005000802', '131103000500080200101', 'Sesuai Lapangan: Tidak ada koreksi data', '0000-00-00 00:00:00', NULL),
(36, 8, 1, '1311021002000600', '131102100200060000101', 'Perlu perbaikan: Tanggal lahir bertentangan dengan usia', '0000-00-00 00:00:00', NULL),
(37, 6, 1, '1311010005000501', '131101000500050100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(38, 11, 1, '1311031004001002', '131103100400100200101', 'Perlu perbaikan: Hubungan dengan KK salah', '0000-00-00 00:00:00', NULL),
(39, 11, 1, '1311020003000900', '131102000300090000101', 'Sesuai Lapangan: Anomali disebabkan oleh entry ganda di server', '0000-00-00 00:00:00', NULL),
(40, 6, 1, '1311041001000600', '131104100100060000101', 'Data Ganda: Terdeteksi NIK yang sama', '0000-00-00 00:00:00', NULL),
(41, 8, 1, '1311020003002002', '131102000300200200101', 'Perlu perbaikan: Koordinat GPS perlu diperbarui', '0000-00-00 00:00:00', NULL),
(42, 6, 1, '1311040003000601', '131104000300060100101', 'Sesuai Lapangan: Anomali telah diabaikan (false positive)', '0000-00-00 00:00:00', NULL),
(43, 5, 1, '1311041004000101', '131104100400010100101', 'Perlu perbaikan: Jumlah anggota KK tidak sinkron', '0000-00-00 00:00:00', NULL),
(44, 19, 1, '1311041003000102', '131104100300010200101', 'Sesuai Lapangan: Data di lapangan sudah akurat', '0000-00-00 00:00:00', NULL),
(45, 1, 1, '1311020003000301', '131102000300030100101', 'Perlu perbaikan: Jenis lantai rumah dan aset tidak wajar', '0000-00-00 00:00:00', NULL),
(46, 5, 1, '1311040008000801', '131104000800080100101', 'Sesuai Lapangan: Dikonfirmasi oleh supervisor', '0000-00-00 00:00:00', NULL),
(47, 11, 1, '1311010006000100', '131101000600010000101', 'Data Ganda: Entri sudah dihapus', '0000-00-00 00:00:00', NULL),
(48, 6, 1, '1311012001000200', '131101200100020000101', 'Perlu perbaikan: Status perkawinan harus diubah', '0000-00-00 00:00:00', NULL),
(49, 16, 1, '1311021003000102', '131102100300010200101', 'Sesuai Lapangan: Data ini valid, bukan anomali', '0000-00-00 00:00:00', NULL),
(50, 9, 1, '1311020005000100', '131102000500010000101', 'Data Ganda: NIK duplikat telah diperbaiki secara otomatis', '0000-00-00 00:00:00', NULL),
(51, 3, 1, '1311020003001502', '131102000300150200101', 'Perlu perbaikan: Sumber air minum tidak tercatat', '0000-00-00 00:00:00', NULL),
(52, 2, 1, '1311040007000600', '131104000700060000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(53, 2, 1, '1311040003001101', '131104000300110100101', 'Sesuai Lapangan: Dikonfirmasi data usia valid', '0000-00-00 00:00:00', NULL),
(54, 4, 1, '1311030005001501', '131103000500150100101', 'Perlu perbaikan: Kode pos salah', '0000-00-00 00:00:00', NULL),
(55, 2, 1, '1311011002000201', '131101100200020100101', 'Data Ganda: Entri ini sudah ada, hapus', '0000-00-00 00:00:00', NULL),
(56, 2, 1, '1311012001000200', '131101200100020000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(57, 2, 1, '1311040003000201', '131104000300020100101', 'Perlu perbaikan: Jenis atap rumah tidak sesuai', '0000-00-00 00:00:00', NULL),
(58, 2, 1, '1311041003000101', '131104100300010100101', 'Sesuai Lapangan: Tidak ada koreksi', '0000-00-00 00:00:00', NULL),
(59, 1, 1, '1311020003000900', '131102000300090000101', 'Data Ganda: Duplikasi NIK dan Nama', '0000-00-00 00:00:00', NULL),
(60, 1, 1, '1311011004000100', '131101100400010000101', 'Perlu perbaikan: Jumlah anggota KK tidak sesuai', '0000-00-00 00:00:00', NULL),
(61, 3, 1, '1311022001000100', '131102200100010000101', 'Sesuai Lapangan: Validasi NIK berhasil', '0000-00-00 00:00:00', NULL),
(62, 4, 1, '1311030002001500', '131103000200150000101', 'Perlu perbaikan: Status disabilitas perlu diperjelas', '0000-00-00 00:00:00', NULL),
(63, 1, 1, '1311011002000201', '131101100200020100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(64, 2, 1, '1311030005001101', '131103000500110100101', 'Data Ganda: Entri telah digabungkan', '0000-00-00 00:00:00', NULL),
(65, 3, 1, '1311041004000400', '131104100400040000101', 'Perlu perbaikan: Kolom jenis kelamin kosong', '0000-00-00 00:00:00', NULL),
(66, 2, 1, '1311010008000700', '131101000800070000101', 'Sesuai Lapangan: Dikonfirmasi oleh petugas lapangan', '0000-00-00 00:00:00', NULL),
(67, 4, 1, '1311041001001200', '131104100100120000101', 'Perlu perbaikan: Kode wilayah desa/kelurahan salah', '0000-00-00 00:00:00', NULL),
(68, 4, 1, '1311030005000802', '131103000500080200101', 'Data Ganda: Entri duplikat dengan nama serupa', '0000-00-00 00:00:00', NULL),
(69, 1, 1, '1311030001001203', '131103000100120300101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(70, 1, 1, '1311020005002600', '131102000500260000101', 'Perlu perbaikan: Data kepemilikan kendaraan tidak wajar', '0000-00-00 00:00:00', NULL),
(71, 3, 1, '1311030002000900', '131103000200090000101', 'Sesuai Lapangan: Sudah diperbaiki pada database', '0000-00-00 00:00:00', NULL),
(72, 2, 1, '1311011004000302', '131101100400030200101', 'Perlu perbaikan: Tanggal lahir kosong', '0000-00-00 00:00:00', NULL),
(73, 3, 1, '1311012003000400', '131101200300040000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(74, 3, 1, '1311041004000101', '131104100400010100101', 'Data Ganda: Duplikasi alamat dan KK', '0000-00-00 00:00:00', NULL),
(75, 3, 1, '1311010005000301', '131101000500030100101', 'Perlu perbaikan: Kolom hubungan keluarga tidak diisi', '0000-00-00 00:00:00', NULL),
(76, 1, 1, '1311031001000500', '131103100100050000101', 'Sesuai Lapangan: Dikonfirmasi oleh pihak desa', '0000-00-00 00:00:00', NULL),
(77, 2, 1, '1311010005000602', '131101000500060200101', 'Perlu perbaikan: NIK kurang digit', '0000-00-00 00:00:00', NULL),
(78, 4, 1, '1311030001001201', '131103000100120100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(79, 4, 1, '1311020003002202', '131102000300220200101', 'Data Ganda: Entri telah di-merge', '0000-00-00 00:00:00', NULL),
(80, 2, 1, '1311011002000400', '131101100200040000101', 'Perlu perbaikan: Tingkat pendidikan tidak sinkron', '0000-00-00 00:00:00', NULL),
(81, 3, 1, '1311040003000801', '131104000300080100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(82, 3, 1, '1311020008000200', '131102000800020000101', 'Perlu perbaikan: Usia menikah pertama tidak valid', '0000-00-00 00:00:00', NULL),
(83, 3, 1, '1311011006000203', '131101100600020300101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(84, 1, 1, '1311030004000701', '131103000400070100101', 'Perlu perbaikan: Status KK tidak ada', '0000-00-00 00:00:00', NULL),
(85, 1, 1, '1311040008000202', '131104000800020200101', 'Data Ganda: Duplikasi NIK', '0000-00-00 00:00:00', NULL),
(86, 4, 1, '1311020008000102', '131102000800010200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(87, 4, 1, '1311023004000202', '131102300400020200101', 'Perlu perbaikan: Kode wilayah diubah', '0000-00-00 00:00:00', NULL),
(88, 2, 1, '1311020008000704', '131102000800070400101', 'Data Ganda: Entri duplikat', '0000-00-00 00:00:00', NULL),
(89, 4, 1, '1311010008000301', '131101000800030100101', 'Perlu perbaikan: Jenis lantai perlu diubah', '0000-00-00 00:00:00', NULL),
(90, 4, 1, '1311031001000302', '131103100100030200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(91, 2, 1, '1311020008000800', '131102000800080000101', 'Perlu perbaikan: Nama KK salah input', '0000-00-00 00:00:00', NULL),
(92, 2, 1, '1311040008000701', '131104000800070100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(93, 4, 1, '1311040008000802', '131104000800080200101', 'Perlu perbaikan: Status bekerja perlu diverifikasi', '0000-00-00 00:00:00', NULL),
(94, 1, 1, '1311030004000302', '131103000400030200101', 'Data Ganda: Entri duplikat telah dibatalkan', '0000-00-00 00:00:00', NULL),
(95, 4, 1, '1311030004000703', '131103000400070300101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(96, 1, 1, '1311030005000801', '131103000500080100101', 'Perlu perbaikan: Format tanggal lahir salah', '0000-00-00 00:00:00', NULL),
(97, 1, 1, '1311011003000402', '131101100300040200101', 'Sesuai Lapangan: Anomali disebabkan oleh system lag', '0000-00-00 00:00:00', NULL),
(98, 4, 1, '1311020003000501', '131102000300050100101', 'Perlu perbaikan: Tingkat pendidikan tidak wajar', '0000-00-00 00:00:00', NULL),
(99, 4, 1, '1311030004000602', '131103000400060200101', 'Data Ganda: Responden ditemukan di Blok sebelah', '0000-00-00 00:00:00', NULL),
(100, 1, 1, '1311023001000100', '131102300100010000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(101, 2, 1, '1311011007000602', '131101100700060200101', 'Perlu perbaikan: Lama tinggal diubah', '0000-00-00 00:00:00', NULL),
(102, 4, 1, '1311031004000801', '131103100400080100101', 'Sesuai Lapangan: Data valid', '0000-00-00 00:00:00', NULL),
(103, 1, 1, '1311021002000800', '131102100200080000101', 'Perlu perbaikan: Usia lansia tercatat bekerja keras', '0000-00-00 00:00:00', NULL),
(104, 1, 1, '1311030004000200', '131103000400020000101', 'Data Ganda: NIK duplikat telah dihapus', '0000-00-00 00:00:00', NULL),
(105, 1, 1, '1311040005000700', '131104000500070000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(106, 2, 1, '1311030001001202', '131103000100120200101', 'Perlu perbaikan: Kepemilikan KKS bertentangan', '0000-00-00 00:00:00', NULL),
(107, 3, 1, '1311011001000302', '131101100100030200101', 'Sesuai Lapangan: Anomali false positive', '0000-00-00 00:00:00', NULL),
(108, 4, 1, '1311011007000501', '131101100700050100101', 'Perlu perbaikan: Data migrasi tidak sinkron', '0000-00-00 00:00:00', NULL),
(109, 4, 1, '1311012004000401', '131101200400040100101', 'Data Ganda: Duplikasi ditemukan di kabupaten lain', '0000-00-00 00:00:00', NULL),
(110, 4, 1, '1311010008001500', '131101000800150000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(111, 3, 1, '1311031001000401', '131103100100040100101', 'Perlu perbaikan: Pengeluaran per bulan kosong', '0000-00-00 00:00:00', NULL),
(112, 4, 1, '1311020005001500', '131102000500150000101', 'Data Ganda: Entri sudah diperbaiki', '0000-00-00 00:00:00', NULL),
(113, 1, 1, '1311012001000200', '131101200100020000101', 'Sesuai Lapangan: Validasi data gizi balita', '0000-00-00 00:00:00', NULL),
(114, 3, 1, '1311030002000600', '131103000200060000101', 'Perlu perbaikan: Jenis bahan bakar memasak tidak sesuai', '0000-00-00 00:00:00', NULL),
(115, 4, 1, '1311021004000900', '131102100400090000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(116, 1, 1, '1311030005001101', '131103000500110100101', 'Perlu perbaikan: Data kepemilikan TV/Komputer', '0000-00-00 00:00:00', NULL),
(117, 3, 1, '1311012003000400', '131101200300040000101', 'Data Ganda: Duplikasi nama keluarga', '0000-00-00 00:00:00', NULL),
(118, 4, 1, '1311020005000600', '131102000500060000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(119, 1, 1, '1311040008000402', '131104000800040200101', 'Perlu perbaikan: Status pelajar > 30 tahun', '0000-00-00 00:00:00', NULL),
(120, 4, 1, '1311020003001700', '131102000300170000101', 'Sesuai Lapangan: Data sudah sesuai dengan KK', '0000-00-00 00:00:00', NULL),
(121, 4, 1, '1311010006000600', '131101000600060000101', 'Perlu perbaikan: Anggota KK lebih dari 10', '0000-00-00 00:00:00', NULL),
(122, 2, 1, '1311010005000601', '131101000500060100101', 'Data Ganda: Duplikasi ID responden di sistem', '0000-00-00 00:00:00', NULL),
(123, 4, 1, '1311011007000201', '131101100700020100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(124, 2, 1, '1311041003000101', '131104100300010100101', 'Perlu perbaikan: Data kepemilikan ternak tidak wajar', '0000-00-00 00:00:00', NULL),
(125, 3, 1, '1311022002000100', '131102200200010000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(126, 2, 1, '1311040006000402', '131104000600040200101', 'Perlu perbaikan: Penghasilan tercatat nol, harus diperiksa', '0000-00-00 00:00:00', NULL),
(127, 3, 1, '1311031004000102', '131103100400010200101', 'Data Ganda: Responden ini sudah meninggal', '0000-00-00 00:00:00', NULL),
(128, 1, 1, '1311030002001000', '131103000200100000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(129, 2, 1, '1311020001000200', '131102000100020000101', 'Perlu perbaikan: Status BPJS/Asuransi harus diisi', '0000-00-00 00:00:00', NULL),
(130, 4, 1, '1311021003000500', '131102100300050000101', 'Sesuai Lapangan: Data sudah divalidasi silang', '0000-00-00 00:00:00', NULL),
(131, 1, 1, '1311021001000900', '131102100100090000101', 'Perlu perbaikan: Kesalahan penulisan NIK', '0000-00-00 00:00:00', NULL),
(132, 2, 1, '1311030005000802', '131103000500080200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(133, 3, 1, '1311011003000103', '131101100300010300101', 'Perlu perbaikan: NIK belum terdaftar di Dukcapil', '0000-00-00 00:00:00', NULL),
(134, 3, 1, '1311020008001000', '131102000800100000101', 'Sesuai Lapangan: Validasi data kepemilikan aset aman', '0000-00-00 00:00:00', NULL),
(135, 4, 1, '1311010007000600', '131101000700060000101', 'Data Ganda: Duplikasi ditemukan di BS yang sama', '0000-00-00 00:00:00', NULL),
(136, 4, 1, '1311040003000506', '131104000300050600101', 'Perlu perbaikan: Nama responden tidak lengkap', '0000-00-00 00:00:00', NULL),
(137, 2, 1, '1311022003000502', '131102200300050200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(138, 1, 1, '1311031002000802', '131103100200080200101', 'Data Ganda: Duplikasi ID RTART', '0000-00-00 00:00:00', NULL),
(139, 2, 1, '1311031003000100', '131103100300010000101', 'Perlu perbaikan: Kolom pekerjaan masih nol', '0000-00-00 00:00:00', NULL),
(140, 3, 1, '1311030001001202', '131103000100120200101', 'Sesuai Lapangan: Anomali diabaikan', '0000-00-00 00:00:00', NULL),
(141, 4, 1, '1311030005000502', '131103000500050200101', 'Perlu perbaikan: Status KK harus \"Bapak\" atau \"Ibu\"', '0000-00-00 00:00:00', NULL),
(142, 2, 1, '1311030005000401', '131103000500040100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(143, 1, 1, '1311040003000402', '131104000300040200101', 'Data Ganda: Ditemukan entry ganda di laporan harian', '0000-00-00 00:00:00', NULL),
(144, 4, 1, '1311010006000600', '131101000600060000101', 'Perlu perbaikan: Jenis usaha perlu diverifikasi', '0000-00-00 00:00:00', NULL),
(145, 4, 1, '1311040003000402', '131104000300040200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(146, 1, 1, '1311040003000901', '131104000300090100101', 'Perlu perbaikan: Status rumah ganda', '0000-00-00 00:00:00', NULL),
(147, 3, 1, '1311011007000602', '131101100700060200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(148, 4, 1, '1311041004000102', '131104100400010200101', 'Perlu perbaikan: Data tanggal survei salah', '0000-00-00 00:00:00', NULL),
(149, 4, 1, '1311012004000200', '131101200400020000101', 'Data Ganda: Duplikasi nama dan alamat', '0000-00-00 00:00:00', NULL),
(150, 3, 1, '1311030004000100', '131103000400010000101', 'Sesuai Lapangan: Anomali telah diabaikan', '0000-00-00 00:00:00', NULL),
(151, 3, 1, '1311012002000402', '131101200200040200101', 'Perlu perbaikan: Status pernikahan tidak jelas', '0000-00-00 00:00:00', NULL),
(152, 2, 1, '1311030001001301', '131103000100130100101', 'Data Ganda: Duplikasi ditemukan pada NIK', '0000-00-00 00:00:00', NULL),
(153, 3, 1, '1311012003000100', '131101200300010000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(154, 1, 1, '1311012004000200', '131101200400020000101', 'Perlu perbaikan: Kolom status kematian terlewat', '0000-00-00 00:00:00', NULL),
(155, 1, 1, '1311022004000601', '131102200400060100101', 'Data Ganda: Duplikasi data RTART', '0000-00-00 00:00:00', NULL),
(156, 4, 1, '1311012002000300', '131101200200030000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(157, 3, 1, '1311030005001401', '131103000500140100101', 'Perlu perbaikan: Kode wilayah perlu dikoreksi', '0000-00-00 00:00:00', NULL),
(158, 3, 1, '1311011003000401', '131101100300040100101', 'Data Ganda: Entri telah dihapus secara massal', '0000-00-00 00:00:00', NULL),
(159, 2, 1, '1311040006000703', '131104000600070300101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(160, 1, 1, '1311030001000402', '131103000100040200101', 'Perlu perbaikan: Nomor KK salah format', '0000-00-00 00:00:00', NULL),
(161, 2, 1, '1311040003000901', '131104000300090100101', 'Sesuai Lapangan: Dikonfirmasi oleh petugas senior', '0000-00-00 00:00:00', NULL),
(162, 1, 1, '1311020005000600', '131102000500060000101', 'Perlu perbaikan: Data kepemilikan lahan tidak jelas', '0000-00-00 00:00:00', NULL),
(163, 1, 1, '1311020008000901', '131102000800090100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(164, 2, 1, '1311021001000700', '131102100100070000101', 'Data Ganda: Duplikasi ditemukan di Kecamatan berbeda', '0000-00-00 00:00:00', NULL),
(165, 4, 1, '1311020001000100', '131102000100010000101', 'Perlu perbaikan: Data penggunaan air tidak konsisten', '0000-00-00 00:00:00', NULL),
(166, 2, 1, '1311021002000401', '131102100200040100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(167, 3, 1, '1311020005002400', '131102000500240000101', 'Perlu perbaikan: Status pekerjaan pensiunan', '0000-00-00 00:00:00', NULL),
(168, 4, 1, '1311010008001300', '131101000800130000101', 'Data Ganda: Duplikasi data anggota keluarga', '0000-00-00 00:00:00', NULL),
(169, 1, 1, '1311040006001400', '131104000600140000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(170, 3, 1, '1311030001000201', '131103000100020100101', 'Perlu perbaikan: Jenis atap rumah perlu diverifikasi', '0000-00-00 00:00:00', NULL),
(171, 2, 1, '1311030002000300', '131103000200030000101', 'Sesuai Lapangan: Tidak ada perubahan', '0000-00-00 00:00:00', NULL),
(172, 1, 1, '1311020001000400', '131102000100040000101', 'Perlu perbaikan: Kolom gender tidak terisi', '0000-00-00 00:00:00', NULL),
(173, 4, 1, '1311040003000701', '131104000300070100101', 'Perlu perbaikan: Penghasilan tidak wajar', '0000-00-00 00:00:00', NULL),
(174, 1, 1, '1311010006000401', '131101000600040100101', 'Data Ganda: Responden ini sudah tercatat sebagai ART', '0000-00-00 00:00:00', NULL),
(175, 2, 1, '1311011003000402', '131101100300040200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(176, 4, 1, '1311020003001800', '131102000300180000101', 'Perlu perbaikan: Kolom tanggungan keluarga kosong', '0000-00-00 00:00:00', NULL),
(177, 3, 1, '1311031002000802', '131103100200080200101', 'Sesuai Lapangan: Koreksi minor pada kode wilayah', '0000-00-00 00:00:00', NULL),
(178, 3, 1, '1311023004000300', '131102300400030000101', 'Data Ganda: Duplikasi alamat rumah tangga', '0000-00-00 00:00:00', NULL),
(179, 3, 1, '1311010006000600', '131101000600060000101', 'Perlu perbaikan: Sumber penerangan tidak jelas', '0000-00-00 00:00:00', NULL),
(180, 3, 1, '1311031004000801', '131103100400080100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(181, 3, 1, '1311020003001600', '131102000300160000101', 'Perlu perbaikan: Pendidikan terakhir tidak diisi', '0000-00-00 00:00:00', NULL),
(182, 4, 1, '1311020008000400', '131102000800040000101', 'Data Ganda: Duplikasi entri dengan stempel waktu berbeda', '0000-00-00 00:00:00', NULL),
(183, 3, 1, '1311040003000802', '131104000300080200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(184, 2, 1, '1311023004000401', '131102300400040100101', 'Perlu perbaikan: Data kepemilikan aset perlu diperjelas', '0000-00-00 00:00:00', NULL),
(185, 1, 1, '1311020008000102', '131102000800010200101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(186, 1, 1, '1311020001000700', '131102000100070000101', 'Perlu perbaikan: Format nomor kontak tidak baku', '0000-00-00 00:00:00', NULL),
(187, 3, 1, '1311010008001500', '131101000800150000101', 'Sesuai Lapangan: Tidak ada tindak lanjut', '0000-00-00 00:00:00', NULL),
(188, 1, 1, '1311022003000100', '131102200300010000101', 'Data Ganda: Duplikasi total pengeluaran', '0000-00-00 00:00:00', NULL),
(189, 1, 1, '1311011006000501', '131101100600050100101', 'Perlu perbaikan: Data disabilitas dipertanyakan', '0000-00-00 00:00:00', NULL),
(190, 2, 1, '1311030001000900', '131103000100090000101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(191, 3, 1, '1311012004000300', '131101200400030000101', 'Perlu perbaikan: Jenis usaha tidak terisi', '0000-00-00 00:00:00', NULL),
(192, 4, 1, '1311020005000501', '131102000500050100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL),
(193, 2, 1, '1311010006001200', '131101000600120000101', 'Perlu perbaikan: Kategori anomali perlu diubah', '0000-00-00 00:00:00', NULL),
(194, 2, 1, '1311012004000102', '131101200400010200101', 'Data Ganda: Duplikasi dalam satu KK', '0000-00-00 00:00:00', NULL),
(195, 2, 1, '1311040008000901', '131104000800090100101', 'Sesuai Lapangan: Data final', '0000-00-00 00:00:00', NULL),
(196, 1, 1, '1311040007000402', '131104000700040200101', 'Perlu perbaikan: Lama tinggal tidak konsisten', '0000-00-00 00:00:00', NULL),
(197, 2, 1, '1311030004000802', '131103000400080200101', 'Sesuai Lapangan: Anomali teratasi', '0000-00-00 00:00:00', NULL),
(198, 4, 1, '1311011002000301', '131101100200030100101', 'Perlu perbaikan: Jenis rumah tidak valid', '0000-00-00 00:00:00', NULL),
(199, 3, 1, '1311040008000402', '131104000800040200101', 'Data Ganda: Duplikasi ID responden', '0000-00-00 00:00:00', NULL),
(200, 3, 1, '1311021004000100', '131102100400010000101', 'Perlu perbaikan: NIK dan tanggal lahir tidak cocok', '0000-00-00 00:00:00', NULL),
(201, 3, 1, '1311022004000401', '131102200400040100101', 'Sesuai Lapangan', '0000-00-00 00:00:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anomali`
--
ALTER TABLE `anomali`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anomali`
--
ALTER TABLE `anomali`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
