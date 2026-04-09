-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 23, 2026 at 01:23 PM
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
-- Database: `bais-system`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_personal_info`
--

CREATE TABLE `tbl_personal_info` (
  `PI_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `FNAME` varchar(50) DEFAULT NULL,
  `MNAME` varchar(50) DEFAULT NULL,
  `LNAME` varchar(50) DEFAULT NULL,
  `CITIZEN` varchar(10) DEFAULT NULL,
  `SEX` varchar(10) DEFAULT NULL,
  `CIVIL` varchar(20) DEFAULT NULL,
  `AGE` varchar(255) DEFAULT NULL,
  `CONTACT` varchar(11) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `STREET` varchar(255) DEFAULT NULL,
  `BRGY` varchar(50) DEFAULT NULL,
  `CITY` varchar(50) DEFAULT NULL,
  `TYPE` varchar(20) DEFAULT NULL,
  `PHOTO` longtext DEFAULT NULL,
  `SIGNATURE` longtext DEFAULT NULL,
  `DATE_ADDED` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `PI_STATUS` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_personal_info`
--

INSERT INTO `tbl_personal_info` (`PI_ID`, `USER_ID`, `FNAME`, `MNAME`, `LNAME`, `CITIZEN`, `SEX`, `CIVIL`, `AGE`, `CONTACT`, `EMAIL`, `STREET`, `BRGY`, `CITY`, `TYPE`, `PHOTO`, `SIGNATURE`, `DATE_ADDED`, `PI_STATUS`) VALUES
(34, 35, 'Gwapo ko', 'Manaban', 'Fundador', 'filipino', 'male', 'married', '79', '09319158016', 'fundadordiongie@gmail.com', 'juan luna', 'II', 'BAIS', '1', 'photo_1774246098_69c0d8d288f0b.jpg', 'signature_1774246098_69c0d8d2896ae.png', '2026-03-23 06:10:35', 'active'),
(36, 40, 'Crestine', NULL, 'Mae', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-23 09:35:24', 'yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  ADD PRIMARY KEY (`PI_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  MODIFY `PI_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
