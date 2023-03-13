-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2020 at 09:59 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `texttile`
--

-- --------------------------------------------------------

--
-- Table structure for table `item_group`
--

CREATE TABLE `item_group` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `parent` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` int(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `update_by` int(255) NOT NULL,
  `update_at` datetime NOT NULL,
  `is_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_group`
--

INSERT INTO `item_group` (`id`, `code`, `name`, `parent`, `status`, `created_by`, `created_at`, `update_by`, `update_at`, `is_delete`) VALUES
(1, '611318', 'Jenith', 'parentgrp2', 1, 2, '2020-09-21 01:17:10', 0, '0000-00-00 00:00:00', 0),
(2, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:18:22', 0, '0000-00-00 00:00:00', 0),
(3, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:20:19', 0, '0000-00-00 00:00:00', 0),
(4, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:33:00', 0, '0000-00-00 00:00:00', 0),
(5, '401350', 'Jenith', 'parentgrp1', 0, 2, '2020-09-21 01:36:33', 0, '0000-00-00 00:00:00', 0),
(6, '401350', 'Jenith', 'parentgrp1', 0, 2, '2020-09-21 01:40:21', 0, '0000-00-00 00:00:00', 0),
(7, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:43:25', 0, '0000-00-00 00:00:00', 0),
(8, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:48:12', 0, '0000-00-00 00:00:00', 0),
(9, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:48:58', 0, '0000-00-00 00:00:00', 0),
(10, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 01:51:31', 0, '0000-00-00 00:00:00', 0),
(11, 'scsd', 'sdcds', 'parentgrp1', 1, 2, '2020-09-21 02:00:42', 0, '0000-00-00 00:00:00', 0),
(12, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 02:02:39', 0, '0000-00-00 00:00:00', 0),
(13, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 02:03:33', 0, '0000-00-00 00:00:00', 0),
(14, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 02:05:54', 0, '0000-00-00 00:00:00', 0),
(15, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 02:07:57', 0, '0000-00-00 00:00:00', 0),
(16, '401350', 'Jenith', 'parentgrp1', 1, 2, '2020-09-21 02:08:16', 0, '0000-00-00 00:00:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_group`
--
ALTER TABLE `item_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_group`
--
ALTER TABLE `item_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
