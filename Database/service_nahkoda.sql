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
-- Database: `service_nahkoda`
--

-- --------------------------------------------------------

--
-- Table structure for table `nahkodas`
--

CREATE TABLE `nahkodas` (
  `id` int NOT NULL,
  `nama` varchar(200) NOT NULL,
  `nomor_hp` varchar(200) NOT NULL,
  `jenis_kelamin` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nahkodas`
--

INSERT INTO `nahkodas` (`id`, `nama`, `nomor_hp`, `jenis_kelamin`) VALUES
(1, 'Cecep4', '0812132', 'Pria'),
(2, 'Dedep New3', '08123456789', 'Laki-Laki2'),
(3, 'dodiKu', '09889', 'pria'),
(4, 'cery', '09232387', 'wanita'),
(5, 'xavier', '9023783278', 'Pria');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nahkodas`
--
ALTER TABLE `nahkodas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nahkodas`
--
ALTER TABLE `nahkodas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
