-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2025 at 02:44 PM
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
  `id_rtart` varchar(21) NOT NULL,
  `nm_krt` varchar(255) NOT NULL,
  `nm_art` varchar(255) NOT NULL,
  `konfirmasi` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anomali`
--

INSERT INTO `anomali` (`id`, `id_kategori_anomali`, `id_user`, `id_wilayah`, `id_rtart`, `nm_krt`, `nm_art`, `konfirmasi`, `date_created`, `date_deleted`) VALUES
(1, 1, 1, '1311040008000400', '131104000800040000101', 'Syafiq', 'Wahyu', '', '2025-11-13 20:22:45', NULL),
(2, 1, 1, '1311040008000400', '131104000800040000102', 'Syafiq', 'Hidayat', 'Sesuai Lapangan', '2025-11-13 20:22:45', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
