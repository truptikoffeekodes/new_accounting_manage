-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 11, 2023 at 11:26 AM
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
-- Table structure for table `purchase_challan`
--

CREATE TABLE `purchase_challan` (
  `id` int NOT NULL,
  `gl_group` int NOT NULL,
  `voucher_type` int NOT NULL,
  `challan_no` int NOT NULL,
  `custom_challan_no` varchar(255) NOT NULL,
  `challan_date` date DEFAULT NULL,
  `account` int NOT NULL,
  `acc_state` int NOT NULL,
  `tds_limit` varchar(80) NOT NULL,
  `gst_no` varchar(120) NOT NULL,
  `sup_chl_no` varchar(200) NOT NULL,
  `supply_date` date DEFAULT NULL,
  `broker` int DEFAULT NULL,
  `other` varchar(180) DEFAULT NULL,
  `lr_no` int NOT NULL,
  `lr_date` date NOT NULL,
  `city` int DEFAULT NULL,
  `transport` int NOT NULL,
  `transport_mode` varchar(160) NOT NULL,
  `vehicle` int NOT NULL,
  `supply_inv` varchar(255) NOT NULL,
  `tot_igst` int NOT NULL,
  `tot_cgst` int NOT NULL,
  `tot_sgst` int NOT NULL,
  `total_amount` int NOT NULL,
  `discount` int NOT NULL,
  `disc_type` varchar(160) NOT NULL,
  `taxes` varchar(180) NOT NULL,
  `inv_taxability` varchar(255) NOT NULL,
  `igst_acc` varchar(255) NOT NULL,
  `cgst_acc` varchar(255) NOT NULL,
  `sgst_acc` varchar(255) NOT NULL,
  `cess_type` varchar(80) NOT NULL,
  `cess` decimal(19,2) NOT NULL,
  `tds_amt` decimal(19,2) NOT NULL,
  `tds_per` int NOT NULL,
  `amty` decimal(19,2) NOT NULL,
  `amty_type` varchar(160) NOT NULL,
  `net_amount` decimal(19,2) NOT NULL,
  `round` int NOT NULL,
  `round_diff` decimal(19,2) NOT NULL,
  `taxable` decimal(19,2) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_by` int NOT NULL,
  `update_at` datetime NOT NULL,
  `is_delete` tinyint(1) NOT NULL,
  `is_cancle` tinyint(1) NOT NULL,
  `is_update_glgroup` int NOT NULL DEFAULT '0',
  `is_update_gst` int NOT NULL DEFAULT '0',
  `is_update_discount` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase_challan`
--

INSERT INTO `purchase_challan` (`id`, `gl_group`, `voucher_type`, `challan_no`, `custom_challan_no`, `challan_date`, `account`, `acc_state`, `tds_limit`, `gst_no`, `sup_chl_no`, `supply_date`, `broker`, `other`, `lr_no`, `lr_date`, `city`, `transport`, `transport_mode`, `vehicle`, `supply_inv`, `tot_igst`, `tot_cgst`, `tot_sgst`, `total_amount`, `discount`, `disc_type`, `taxes`, `inv_taxability`, `igst_acc`, `cgst_acc`, `sgst_acc`, `cess_type`, `cess`, `tds_amt`, `tds_per`, `amty`, `amty_type`, `net_amount`, `round`, `round_diff`, `taxable`, `created_by`, `created_at`, `update_by`, `update_at`, `is_delete`, `is_cancle`, `is_update_glgroup`, `is_update_gst`, `is_update_discount`) VALUES
(1, 13, 53, 1, '33', '2022-04-08', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-04-07', NULL, 0, 'ROAD', 0, '', 1800, 900, 900, 10000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '11800.00', 6, '0.00', '10000.00', 0, '2022-04-07 23:27:29', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(2, 13, 53, 2, '24/05/22', '2022-05-24', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-05-26', NULL, 0, 'ROAD', 0, '', 135000, 67500, 67500, 750000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '885000.00', 6, '0.00', '750000.00', 0, '2022-05-26 16:09:28', 0, '2022-05-26 16:09:52', 0, 0, 1, 1, 1),
(3, 13, 53, 3, 'CS-03/MAY/2022', '2022-05-31', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-30', NULL, 0, 'ROAD', 0, '', 13509, 6755, 6755, 75050, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '88559.00', 6, '0.00', '75050.00', 0, '2022-05-31 13:33:39', 0, '2022-09-30 12:52:33', 0, 0, 1, 1, 1),
(4, 13, 53, 4, 'CS-01/JUN/2022', '2022-06-27', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-30', NULL, 0, 'ROAD', 0, '', 35501, 17750, 17750, 197225, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '232726.00', 6, '0.00', '197225.00', 0, '2022-06-28 14:58:40', 0, '2022-09-30 12:56:56', 0, 0, 1, 1, 1),
(5, 13, 53, 5, '200428/22-23', '2022-07-06', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-07-07', NULL, 0, 'ROAD', 0, '', 4665, 2332, 2332, 25916, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '30581.00', 6, '0.00', '25916.00', 0, '2022-07-07 14:33:14', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(6, 13, 53, 6, 'RC-011/22-23', '2022-07-07', 482, 12, '', '24AAJCR3798Q1ZE', '', NULL, NULL, '', 0, '2022-07-13', NULL, 0, 'ROAD', 0, '', 143016, 71508, 71508, 794534, 0, '', '[\"igst\"]', 'Taxable', '606', '', '', '', '0.00', '0.00', 0, '0.00', '', '937550.00', 6, '0.35', '794533.60', 0, '2022-07-12 17:27:32', 0, '2022-07-13 15:11:06', 0, 0, 1, 1, 1),
(7, 13, 53, 7, 'RC-011/22-23', '2022-07-07', 482, 12, '', '24AAJCR3798Q1ZE', '', NULL, NULL, '', 0, '2022-07-23', NULL, 0, 'ROAD', 0, '', 0, 0, 0, 796696, 0, '', '[\"igst\"]', 'Taxable', '606', '', '', '', '0.00', '0.00', 0, '0.00', '', '796696.00', 6, '-0.28', '796696.00', 0, '2022-07-14 13:52:29', 0, '2022-07-23 12:30:45', 0, 0, 1, 1, 1),
(8, 13, 53, 8, 'Test1234', '2022-07-23', 149, 22, '', '27APBPK0641P1ZR', '', NULL, NULL, '', 0, '2022-07-23', NULL, 0, 'ROAD', 0, '', 1758, 879, 879, 9768, 0, '', '[\"cgst\",\"sgst\"]', 'N/A', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '11526.00', 6, '0.00', '9768.00', 0, '2022-07-23 11:00:25', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(9, 13, 53, 9, '200553/22-23', '2022-07-25', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-08-03', NULL, 0, 'ROAD', 0, '', 0, 0, 0, 738400, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '738400.00', 6, '0.00', '738400.00', 0, '2022-07-25 15:50:13', 0, '2022-08-03 15:49:26', 0, 0, 1, 1, 1),
(10, 13, 53, 10, 'CS-01/AUG/2022', '2022-08-01', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-08-03', NULL, 0, 'ROAD', 0, '', 42750, 21375, 21375, 237500, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '280250.00', 6, '0.00', '237500.00', 0, '2022-08-03 15:12:43', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(11, 13, 53, 11, '200552/22-23', '2022-07-25', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-08-10', NULL, 0, 'ROAD', 0, '', 143154, 71577, 71577, 795300, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '938454.00', 6, '0.00', '795300.00', 0, '2022-08-03 15:18:20', 0, '2022-08-10 12:17:23', 0, 0, 1, 1, 1),
(12, 13, 53, 12, 'CS-03/AUG/2022', '2022-08-08', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-08-13', NULL, 0, 'ROAD', 0, '', 35100, 17550, 17550, 195000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '230100.00', 6, '0.00', '195000.00', 0, '2022-08-13 12:36:08', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(13, 13, 53, 13, 'CS-02/AUG/2022', '2022-08-08', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-29', NULL, 0, 'ROAD', 0, '', 9464, 4732, 4732, 52578, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '62042.00', 6, '0.00', '52578.00', 0, '2022-08-13 12:37:03', 0, '2022-09-29 18:54:19', 0, 0, 1, 1, 1),
(14, 13, 53, 14, 'CS-04/AUG/2022', '2022-08-19', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-08-20', NULL, 0, 'ROAD', 0, '', 132750, 66375, 66375, 737500, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '870250.00', 6, '0.00', '737500.00', 0, '2022-08-20 18:17:33', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(15, 13, 53, 15, 'CS-01/SEP/2022', '2022-09-01', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-03', NULL, 0, 'ROAD', 0, '', 36000, 18000, 18000, 200000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '236000.00', 6, '0.00', '200000.00', 0, '2022-09-03 17:50:23', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(16, 13, 53, 16, 'CS-02/SEP/2022', '2022-09-17', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-26', NULL, 0, 'ROAD', 0, '', 121500, 60750, 60750, 675000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '796500.00', 6, '0.00', '675000.00', 0, '2022-09-26 17:04:53', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(17, 13, 53, 17, 'WO-02/Jun/2022', '2022-09-30', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-09-30', NULL, 0, 'ROAD', 0, '', 301217, 150609, 150609, 1673430, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '1974647.00', 6, '-0.40', '1673430.00', 0, '2022-09-30 12:34:57', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(18, 13, 53, 18, 'CS-001/JUL/2022', '2022-07-04', 559, 22, '', '27AACCJ6015B1Z2', '', NULL, NULL, '', 0, '2022-10-12', NULL, 0, 'ROAD', 0, '', 0, 0, 0, 100000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '100000.00', 6, '0.00', '100000.00', 0, '2022-09-30 13:21:43', 0, '2022-10-12 14:56:07', 0, 0, 1, 1, 1),
(19, 13, 53, 19, 'CS-03/OCT/2022', '2022-10-17', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-18', NULL, 0, 'ROAD', 0, '', 22320, 11160, 11160, 124000, 0, '', '[\"cgst\",\"sgst\"]', 'N/A', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '146320.00', 6, '0.00', '124000.00', 0, '2022-10-18 10:20:23', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(20, 13, 53, 20, 'CS-01/OCT/2022', '2022-10-11', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 51300, 25650, 25650, 285000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '336300.00', 6, '0.00', '285000.00', 0, '2022-10-20 11:16:40', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(21, 13, 53, 21, 'CS-02/OCT/2022', '2022-10-11', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 349425, 174713, 174713, 1941250, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '2290675.00', 6, '0.00', '1941250.00', 0, '2022-10-20 11:42:11', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(22, 13, 53, 22, 'CS-03/SEP/2022', '2022-09-26', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 43200, 21600, 21600, 240000, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '283200.00', 6, '0.00', '240000.00', 0, '2022-10-20 11:46:57', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(23, 13, 53, 23, 'CSEL-03/Sep/2022', '2022-09-27', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 36612, 18306, 18306, 203400, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '240012.00', 6, '0.00', '203400.00', 0, '2022-10-20 12:48:41', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(24, 13, 53, 24, 'CSLP-02/Sep/2022', '2022-09-27', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 486000, 243000, 243000, 2700000, 0, '', '[\"cgst\",\"sgst\"]', 'N/A', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '3186000.00', 6, '0.00', '2700000.00', 0, '2022-10-20 15:33:19', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(25, 13, 53, 25, 'CSLP-01/Sep/2022', '2022-09-27', 95, 22, '', '27AAACA4213E1ZB', '', NULL, NULL, '', 0, '2022-10-20', NULL, 0, 'ROAD', 0, '', 325944, 162972, 162972, 1810800, 0, '', '[\"cgst\",\"sgst\"]', 'Taxable', '', '608', '610', '', '0.00', '0.00', 0, '0.00', '', '2136744.00', 6, '0.00', '1810800.00', 0, '2022-10-20 15:34:03', 0, '0000-00-00 00:00:00', 0, 0, 1, 1, 1),
(26, 13, 53, 26, '', '2023-01-10', 684, 12, '', '73UIERD4587T8Z4', '', NULL, 685, '', 0, '0000-00-00', NULL, 0, '', 0, '', 1800, 900, 900, 11000, 0, 'Fixed', '[\"igst\"]', 'N/A', '605', '', '', 'Fixed', '0.00', '0.00', 0, '0.00', 'Fixed', '12800.00', 6, '0.00', '11000.00', 1, '2023-01-10 17:44:12', 0, '0000-00-00 00:00:00', 0, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `purchase_challan`
--
ALTER TABLE `purchase_challan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `purchase_challan`
--
ALTER TABLE `purchase_challan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
