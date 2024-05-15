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
-- Database: `service_user`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `role` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(100) DEFAULT NULL,
  `nomor_telepon` varchar(100) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `jenis_kelamin`, `nomor_telepon`, `alamat`) VALUES
(1, 'admin', 'admin11111', 'admin@gmail.com', 'admin', NULL, NULL, NULL),
(2, 'Doni', 'doni11111', 'doni@gmail.com', 'ship_owner', NULL, NULL, NULL),
(3, 'Budi', 'budi11111', 'budi@gmail.com', 'passenger', NULL, NULL, NULL),
(4, 'nama_pengguna_test', 'kata_sandi_test', 'contoh@example.com_test', 'peran_pengguna_test', NULL, NULL, NULL),
(5, 'andi', 'andi11111', 'andi@gmail.com', 'passenger', NULL, NULL, NULL),
(6, 'xaxa', 'xaxa11111', 'xaxa@gmail.com', 'passenger', 'pria', '8282323', 'medan'),
(7, 'vava', 'vava11111', 'vava@gmail.com', 'passenger', 'pria', '293932', 'medan'),
(8, 'nana', 'nana11111', 'nana@gmail.com', 'passenger', 'pria', '232332', 'medan'),
(9, 'haha', 'haha11111', 'haha@gmail.com', 'passenger', 'pria', '232332', 'medan'),
(10, 'yaya', 'yaya11111', 'yaya@gmail.com', 'passenger', 'pria', '3433434', 'sduigsd'),
(11, 'kiki', 'kiki11111', 'kiki@gmail.com', 'passenger', 'pra', '373443', 'nedab'),
(12, 'gigi', 'gigi11111', 'gigi@gmail.com', 'passenger', 'pria', '232332', 'medan'),
(13, 'nini', 'nini11111', 'nini@gmail.com', 'passenger', 'pria', '373443', 'dsfsdfi'),
(14, 'pk', 'pk11111', 'pk@gmail.com', 'ship_owner', 'pria', '373443', 'dsfsdfi'),
(15, 'sambas', 'sambas11111', 'sambas@gmail.com', 'ship_owner', 'pria', '373443', 'dsfsdfi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
