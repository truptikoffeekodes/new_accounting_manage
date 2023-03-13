-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 11, 2023 at 11:27 AM
-- Server version: 8.0.31-0ubuntu0.20.04.1
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `TRU2022JV8E`
--

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` int NOT NULL,
  `gl_group` int NOT NULL,
  `voucher_type` int NOT NULL,
  `return_no` int NOT NULL,
  `return_date` date DEFAULT NULL,
  `tds_limit` varchar(180) DEFAULT NULL,
  `acc_state` int DEFAULT NULL,
  `other` varchar(180) DEFAULT NULL,
  `invoice` int DEFAULT NULL,
  `amty` varchar(30) NOT NULL,
  `amty_type` varchar(30) NOT NULL,
  `total_amount` varchar(30) NOT NULL,
  `net_amount` varchar(30) NOT NULL,
  `round` int NOT NULL,
  `round_diff` decimal(19,2) NOT NULL,
  `taxable` decimal(19,2) NOT NULL,
  `brokerage_type` varchar(60) NOT NULL,
  `discount` varchar(30) NOT NULL,
  `disc_type` varchar(30) NOT NULL,
  `account` varchar(120) DEFAULT NULL,
  `broker` varchar(120) DEFAULT NULL,
  `gst_no` varchar(40) NOT NULL,
  `lr_no` varchar(30) NOT NULL,
  `lr_date` date DEFAULT NULL,
  `weight` varchar(120) NOT NULL,
  `freight` varchar(120) NOT NULL,
  `city` int DEFAULT NULL,
  `due_days` varchar(120) NOT NULL,
  `vehicle` int DEFAULT NULL,
  `due_date` varchar(150) NOT NULL,
  `transport_mode` varchar(80) DEFAULT NULL,
  `transport` int DEFAULT NULL,
  `tot_igst` varchar(200) NOT NULL,
  `tot_cgst` varchar(200) NOT NULL,
  `tot_sgst` varchar(200) NOT NULL,
  `taxes` varchar(180) NOT NULL,
  `inv_taxability` varchar(255) NOT NULL,
  `igst_acc` varchar(255) NOT NULL,
  `cgst_acc` varchar(255) NOT NULL,
  `sgst_acc` varchar(255) NOT NULL,
  `cess_type` varchar(160) NOT NULL,
  `cess` int NOT NULL,
  `tds_amt` int NOT NULL,
  `tds_per` varchar(160) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_by` int NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_cancle` tinyint(1) NOT NULL,
  `is_update_glgroup` int NOT NULL DEFAULT '0',
  `is_update_gst` int NOT NULL DEFAULT '0',
  `is_update_discount` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_return`
--

INSERT INTO `purchase_return` (`id`, `gl_group`, `voucher_type`, `return_no`, `return_date`, `tds_limit`, `acc_state`, `other`, `invoice`, `amty`, `amty_type`, `total_amount`, `net_amount`, `round`, `round_diff`, `taxable`, `brokerage_type`, `discount`, `disc_type`, `account`, `broker`, `gst_no`, `lr_no`, `lr_date`, `weight`, `freight`, `city`, `due_days`, `vehicle`, `due_date`, `transport_mode`, `transport`, `tot_igst`, `tot_cgst`, `tot_sgst`, `taxes`, `inv_taxability`, `igst_acc`, `cgst_acc`, `sgst_acc`, `cess_type`, `cess`, `tds_amt`, `tds_per`, `created_by`, `created_at`, `update_by`, `update_at`, `is_delete`, `is_cancle`, `is_update_glgroup`, `is_update_gst`, `is_update_discount`) VALUES
(1, 13, 54, 1, '2022-09-15', '', 22, '600042/22-23', 60, '', 'Fixed', '625000', '737500', 6, '0.00', '625000.00', 'fix', '', 'Fixed', '95', NULL, '', '', '0000-00-00', '', '', NULL, '', NULL, '', '', NULL, '112500.00', '56250.00', '56250.00', '[\"sgst\",\"cgst\"]', 'N/A', '', '608', '610', 'Fixed', 0, 0, '', 3, '2022-10-07 10:16:49', 0, NULL, 0, 0, 1, 1, 1),
(2, 13, 54, 2, '2022-10-20', '', 12, '', 59, '', 'Fixed', '11479.76', '11537', 6, '-0.32', '11479.76', 'fix', '', 'Fixed', '510', NULL, '', '', '0000-00-00', '', '', NULL, '', NULL, '', '', NULL, '57.56', '28.78', '28.78', '[\"igst\"]', 'Exempt', '606', '', '', 'Fixed', 0, 0, '', 3, '2022-10-18 13:35:16', 3, '2022-10-26 12:38:05', 0, 0, 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchase_return`
--
ALTER TABLE `purchase_return`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
