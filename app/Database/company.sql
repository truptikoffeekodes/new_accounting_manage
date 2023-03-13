-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2020 at 01:36 PM
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
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `company_group` varchar(50) NOT NULL,
  `name` varchar(80) NOT NULL,
  `financial_form` date NOT NULL,
  `financial_to` date NOT NULL,
  `localtax_no` varchar(120) NOT NULL,
  `localtax_date` date NOT NULL,
  `centraltax_no` varchar(120) NOT NULL,
  `centraltax_date` date NOT NULL,
  `servicetax_no` varchar(120) NOT NULL,
  `servicetax_date` date NOT NULL,
  `service_classifi` varchar(120) NOT NULL,
  `cin` varchar(120) NOT NULL,
  `vat_reg_no` varchar(120) NOT NULL,
  `vat_reg_date` date NOT NULL,
  `cst_no` varchar(120) NOT NULL,
  `cst_date` date NOT NULL,
  `incomtax_pan` varchar(120) NOT NULL,
  `ward_no` varchar(120) NOT NULL,
  `sign_capture` varchar(120) NOT NULL,
  `account_code` varchar(120) NOT NULL,
  `form_company` varchar(100) NOT NULL,
  `business_type` varchar(80) NOT NULL,
  `contact_person` varchar(70) NOT NULL,
  `alternate_contact` varchar(80) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `pin` varchar(50) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `alternate_phone` varchar(100) NOT NULL,
  `fax` varchar(80) NOT NULL,
  `mobile` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `bank_ac_name` varchar(100) NOT NULL,
  `bank_ac_no` varchar(80) NOT NULL,
  `bank_name` varchar(80) NOT NULL,
  `ifsc` varchar(80) NOT NULL,
  `branch_address` varchar(255) NOT NULL,
  `ac_type` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
