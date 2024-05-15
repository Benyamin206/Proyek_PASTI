-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 15, 2024 at 05:01 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `service_jadwal`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` int NOT NULL,
  `kapal_id` int NOT NULL,
  `nahkoda_id` int NOT NULL,
  `rute_id` int NOT NULL,
  `waktu_berangkat` datetime NOT NULL,
  `stok` int DEFAULT NULL,
  `harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `kapal_id`, `nahkoda_id`, `rute_id`, `waktu_berangkat`, `stok`, `harga`) VALUES
(1, 1, 1, 1, '2024-05-30 11:00:00', 28, 20000),
(2, 2, 1, 1, '2025-05-30 11:00:00', 45, 20000),
(3, 1, 2, 3, '2024-05-16 12:00:00', 50, 20000),
(4, 2, 1, 2, '2025-05-30 11:00:00', 45, 20000),
(5, 2, 1, 2, '2025-05-30 11:00:00', 30, 20000),
(6, 2, 1, 2, '2025-05-30 11:00:00', 30, 25000),
(7, 2, 1, 2, '2025-09-30 12:00:00', 30, 25000),
(8, 1, 1, 1, '2024-10-03 12:00:00', 20, 15000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
