-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2023 at 05:39 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventori`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangbadstock`
--

CREATE TABLE `barangbadstock` (
  `idBarangBadstock` int(11) NOT NULL,
  `kodeTransaksi` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangbadstock`
--

INSERT INTO `barangbadstock` (`idBarangBadstock`, `kodeTransaksi`, `jumlah`, `tanggal`, `catatan`, `user_id`, `gudang_id`, `supplier_id`) VALUES
(1, 'BDS-000001', 1, '2023-11-02', 'rusak', 1, 1, 1),
(2, 'BDS-000002', 1, '2023-11-05', 'tidak bisa hidup', 1, 1, 1);

--
-- Triggers `barangbadstock`
--
DELIMITER $$
CREATE TRIGGER `stokBadstock` AFTER INSERT ON `barangbadstock` FOR EACH ROW BEGIN
	UPDATE gudang SET stok = stok - NEW.jumlah
    WHERE idBarang = NEW.gudang_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barangkeluar`
--

CREATE TABLE `barangkeluar` (
  `idBarangKeluar` int(11) NOT NULL,
  `kodeTransaksi` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangkeluar`
--

INSERT INTO `barangkeluar` (`idBarangKeluar`, `kodeTransaksi`, `jumlah`, `tanggal`, `user_id`, `gudang_id`) VALUES
(1, 'KLR-000001', 2, '2023-11-02', 1, 1),
(2, 'KLR-000002', 1, '2023-11-05', 1, 1),
(3, 'KLR-000003', 6, '2023-11-05', 1, 1),
(4, 'KLR-000004', 5, '2023-11-05', 1, 1),
(5, 'KLR-000005', 6, '2023-11-05', 1, 1);

--
-- Triggers `barangkeluar`
--
DELIMITER $$
CREATE TRIGGER `stokKeluar` AFTER INSERT ON `barangkeluar` FOR EACH ROW BEGIN
	UPDATE gudang SET stok = stok - NEW.jumlah
    WHERE idBarang = NEW.gudang_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barangmasuk`
--

CREATE TABLE `barangmasuk` (
  `idBarangMasuk` int(11) NOT NULL,
  `kodeTransaksi` varchar(10) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `gudang_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangmasuk`
--

INSERT INTO `barangmasuk` (`idBarangMasuk`, `kodeTransaksi`, `jumlah`, `tanggal`, `user_id`, `gudang_id`, `supplier_id`) VALUES
(1, 'MSK-000001', 5, '2023-11-02', 1, 1, 1),
(2, 'MSK-000002', 3, '2023-11-05', 1, 1, 1),
(5, 'MSK-000005', 1, '2023-11-05', 1, 1, 1),
(7, 'MSK-000004', 5, '2023-11-05', 1, 1, 1),
(9, 'MSK-000003', 2, '2023-11-05', 1, 1, 1),
(10, 'MSK-000006', 5, '2023-11-05', 1, 1, 1),
(11, 'MSK-000007', 7, '2023-11-05', 1, 1, 1);

--
-- Triggers `barangmasuk`
--
DELIMITER $$
CREATE TRIGGER `stokMasuk` AFTER INSERT ON `barangmasuk` FOR EACH ROW BEGIN
	UPDATE gudang SET stok = stok + NEW.jumlah
    WHERE idBarang = NEW.gudang_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `idBarang` int(11) NOT NULL,
  `kodeBarang` varchar(10) DEFAULT NULL,
  `namaBarang` varchar(50) DEFAULT NULL,
  `jenisBarang` varchar(50) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`idBarang`, `kodeBarang`, `namaBarang`, `jenisBarang`, `stok`, `user_id`) VALUES
(1, 'GDG-000001', 'Monitor', 'Elektronik', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `idSupplier` int(11) NOT NULL,
  `kodeSupplier` varchar(10) DEFAULT NULL,
  `namaSupplier` varchar(50) DEFAULT NULL,
  `nohp` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`idSupplier`, `kodeSupplier`, `namaSupplier`, `nohp`) VALUES
(1, 'SPL-000001', 'Agus Bachtiar', '082312875489');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `nip` int(15) DEFAULT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `jabatan` enum('pegawai','admin','pimpinan') DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idUser`, `nip`, `nama`, `jabatan`, `password`) VALUES
(1, 2023110003, 'Agung Riski Ariza', 'admin', '$2y$10$vc.ExcekJfu0bjMQspT9yuv45k62xEMuHZg3N18S28IWCmgrZujre'),
(2, 2023110004, 'Agung', 'pimpinan', '$2y$10$Mhv6kbN4p7TF83EGA4Kr0uLqSrotzdiyX4OZKJ0K1MiGG2Kd3CDiS'),
(3, 2023110005, 'Riski', 'pegawai', '$2y$10$XWwMpVCUyCz/UWHfpAa5kejjfxPlTFZuKa85dZDPGzO98nsegNBi6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangbadstock`
--
ALTER TABLE `barangbadstock`
  ADD PRIMARY KEY (`idBarangBadstock`),
  ADD UNIQUE KEY `kodeTransaksi` (`kodeTransaksi`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gudang_id` (`gudang_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD PRIMARY KEY (`idBarangKeluar`),
  ADD UNIQUE KEY `kodeTransaksi` (`kodeTransaksi`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gudang_id` (`gudang_id`);

--
-- Indexes for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD PRIMARY KEY (`idBarangMasuk`),
  ADD UNIQUE KEY `kodeTransaksi` (`kodeTransaksi`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gudang_id` (`gudang_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`idBarang`),
  ADD UNIQUE KEY `kodeBarang` (`kodeBarang`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`idSupplier`),
  ADD UNIQUE KEY `kodeSupplier` (`kodeSupplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangbadstock`
--
ALTER TABLE `barangbadstock`
  MODIFY `idBarangBadstock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  MODIFY `idBarangKeluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  MODIFY `idBarangMasuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `idBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `idSupplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangbadstock`
--
ALTER TABLE `barangbadstock`
  ADD CONSTRAINT `barangbadstock_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barangbadstock_ibfk_2` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`idBarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barangbadstock_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`idSupplier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barangkeluar`
--
ALTER TABLE `barangkeluar`
  ADD CONSTRAINT `barangkeluar_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barangkeluar_ibfk_2` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`idBarang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barangmasuk`
--
ALTER TABLE `barangmasuk`
  ADD CONSTRAINT `barangmasuk_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barangmasuk_ibfk_2` FOREIGN KEY (`gudang_id`) REFERENCES `gudang` (`idBarang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `barangmasuk_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`idSupplier`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gudang`
--
ALTER TABLE `gudang`
  ADD CONSTRAINT `gudang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
