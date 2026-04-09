-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 07, 2026 at 07:21 AM
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
-- Table structure for table `tbl_brgy`
--

CREATE TABLE `tbl_brgy` (
  `BRGY_ID` int(11) NOT NULL,
  `BARANGAY` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brgy`
--

INSERT INTO `tbl_brgy` (`BRGY_ID`, `BARANGAY`) VALUES
(1, 'Barangay I (Pob.)'),
(2, 'Barangay II (Pob.)'),
(3, 'Basak'),
(4, 'Biñohon'),
(5, 'Cabanlutan'),
(6, 'Calasga-an'),
(7, 'Cambagahan'),
(8, 'Cambaguio'),
(9, 'Cambanjao'),
(10, 'Cambuilao'),
(11, 'Canlargo'),
(12, 'Capiñahan'),
(13, 'Consolacion'),
(14, 'Dansulan'),
(15, 'Hangyad'),
(16, 'Katacgahan (Tacgahan)'),
(17, 'La Paz'),
(18, 'Lo-oc'),
(19, 'Lonoy'),
(20, 'Mabunao'),
(21, 'Manlipac'),
(22, 'Mansangaban'),
(23, 'Okiot'),
(24, 'Olympia'),
(25, 'Panala-an'),
(26, 'Panam-angan'),
(27, 'Rosario'),
(28, 'Sab-ahan'),
(29, 'San Isidro'),
(30, 'Tagpo'),
(31, 'Talungon'),
(32, 'Tamisu'),
(33, 'Tamogong'),
(34, 'Tangculogan'),
(35, 'Valencia');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_certificates`
--

CREATE TABLE `tbl_certificates` (
  `CERT_ID` int(11) NOT NULL,
  `CERT_NAME` varchar(50) NOT NULL,
  `CONTENTS` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_certificates`
--

INSERT INTO `tbl_certificates` (`CERT_ID`, `CERT_NAME`, `CONTENTS`) VALUES
(1, 'Financial Assistance/Indigency', ''),
(2, 'Embalming', ''),
(3, 'Barangay Clearance', ''),
(4, 'Loan', ''),
(5, 'Burial Assistance', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gender`
--

CREATE TABLE `tbl_gender` (
  `GENDER_ID` int(11) NOT NULL,
  `GENDER_NAME` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_gender`
--

INSERT INTO `tbl_gender` (`GENDER_ID`, `GENDER_NAME`) VALUES
(1, 'Male'),
(2, 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_officials`
--

CREATE TABLE `tbl_officials` (
  `OFFICIAL_ID` int(11) NOT NULL,
  `PHOTO` text DEFAULT NULL,
  `EMP_ID` varchar(50) NOT NULL,
  `F_NAME` varchar(25) NOT NULL,
  `L_NAME` varchar(25) NOT NULL,
  `M_NAME` varchar(25) NOT NULL,
  `DOB` date NOT NULL,
  `POB` varchar(255) NOT NULL,
  `CIVIL_STATUS` int(11) NOT NULL,
  `EMAIL` varchar(50) NOT NULL,
  `CONTACT` varchar(15) NOT NULL,
  `POSITION` int(11) NOT NULL,
  `BRGY_ID` int(255) NOT NULL,
  `TITLE` varchar(20) NOT NULL,
  `OFF_SIGNATURE` longtext DEFAULT NULL,
  `DATE_STARTED` date NOT NULL,
  `DATE_ENDED` date DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_officials`
--

INSERT INTO `tbl_officials` (`OFFICIAL_ID`, `PHOTO`, `EMP_ID`, `F_NAME`, `L_NAME`, `M_NAME`, `DOB`, `POB`, `CIVIL_STATUS`, `EMAIL`, `CONTACT`, `POSITION`, `BRGY_ID`, `TITLE`, `OFF_SIGNATURE`, `DATE_STARTED`, `DATE_ENDED`, `STATUS`) VALUES
(15, '../profiles/avatar_15_1775446683.png', '09537217937', 'Junz', 'Fundador', 'Fundz', '2025-11-05', 'Manjuyod', 2, 'junzfundador142@gmail.com', '12345678912', 1, 9, 'Honorable', '../uploads/official_15_1775446878.png', '2026-04-02', NULL, 'active'),
(16, '69d1defb9960b.jpeg', '342434223', 'Junz', 'Eran', 'Eran', '2026-02-19', 'Cdaya', 2, 'fundadordiongie@gmail.com', '234234234234', 2, 9, 'junzcf.devs', NULL, '2026-04-05', '2026-04-05', 'ended');

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
  `DOB` date NOT NULL,
  `POB` varchar(255) NOT NULL,
  `CONTACT` varchar(11) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `STREET` varchar(255) DEFAULT NULL,
  `BRGY_ID` int(50) NOT NULL,
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

INSERT INTO `tbl_personal_info` (`PI_ID`, `USER_ID`, `FNAME`, `MNAME`, `LNAME`, `CITIZEN`, `SEX`, `CIVIL`, `AGE`, `DOB`, `POB`, `CONTACT`, `EMAIL`, `STREET`, `BRGY_ID`, `CITY`, `TYPE`, `PHOTO`, `SIGNATURE`, `DATE_ADDED`, `PI_STATUS`) VALUES
(44, 72, 'Dione', 'Manaban', 'Fundador', 'filipino', 'Female', 'Married', '79', '2019-02-12', 'Bais city', '09319158016', 'crestinemaemendezromano0217@gmail.com', 'Cannibol street', 9, 'Bais City', '1', 'photo_1775131472_69ce5b50b47af.jpg', '../uploads/official_72_1775476914.png', '2026-04-06 13:36:29', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_position`
--

CREATE TABLE `tbl_position` (
  `POSITION_ID` int(11) NOT NULL,
  `POSITION_NAME` varchar(255) NOT NULL,
  `DATE_ADDED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_position`
--

INSERT INTO `tbl_position` (`POSITION_ID`, `POSITION_NAME`, `DATE_ADDED`) VALUES
(1, 'captain', '2026-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `ID` int(11) NOT NULL,
  `BRGY_ID` int(11) DEFAULT NULL,
  `TITLE` longtext NOT NULL,
  `DESCRIPTION` longtext NOT NULL,
  `FILES` longtext NOT NULL,
  `TYPE` varchar(255) DEFAULT NULL,
  `DATE_CREATED` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `STATUS` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`ID`, `BRGY_ID`, `TITLE`, `DESCRIPTION`, `FILES`, `TYPE`, `DATE_CREATED`, `STATUS`) VALUES
(8, 9, 'Dimension Chorus 2nd', 'I love youuuu', '[\"1775208480_INFINITY___Premium_Dashboard.pdf\",\"1775208480_REVISED-Online_Reservation_System_for_Cugon_Bamboo_Resort__Chapter_1-4__FINALLE.pdf\",\"1775208480_02-28-2026.pdf\",\"1775208480_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\",\"1775208480_signature_1773053444659.png\",\"1775208480_0d8f0c0e-3319-48c5-b9a0-3f27bf88d862.jpeg\",\"1775208480_2ts3nfs9-qr.png\",\"1775208480_4zkxxeuy-qr.png\",\"1775208480_8e1f8aa3-b073-4e73-877f-70a6db58812b.jpeg\"]', '', '2026-04-05 11:53:51', 3),
(9, 9, 'Dimension Chorus 2nd', 'New uploads please be advise', '[\"1775219840_Accounting-Memorandum-No.-2-s.-2026.pdf\",\"1775219840_4b982cb7-770f-4df0-8547-47dab28cd5cd.jpeg\",\"1775219840_99b86fb1-5abf-4ea4-bbc3-09d6c82eeebc.jpeg\",\"1775219840_2025-2026_Endorsement_Letter.docx\",\"1775219840_2025-2026-Endorsement-Letter.pdf\",\"1775219840_396377b6-378e-455b-b736-520448ebc2ef.jpeg\",\"1775219840_Annex-B-1-RR-11-2018.docx\"]', '', '2026-04-05 11:53:52', 3),
(10, 9, 'Dimension Chorus 2nd', 'dsfasdfasdfsadfsafdsadf', '[\"1775220079_ANNEX-H-3-CS-Form-No.-212-Revised-2025-Attachment-Guide-to-Filling-Up-the-Personal-Data-Sheet-new.pdf\",\"1775220079_Black_White_Modern_Festive_New_Year_Party_Photo_Collage_Facebook_Post.pdf\",\"1775220079_Black_White_Modern_Festive_New_Year_Party_Photo_Collage_Facebook_Post.png\",\"1775220079_cid-2026-0010_deployment_of_54_student_teachers_for_teaching_internship_from_norsu_bais_city_campus.pdf\",\"1775220079_CLEARANCE-PTI-20252026_UPDATED-EXTERNAL-CAMPUS-BAIS.docx\",\"1775220079_danilo_cover_page___1_.pdf\",\"1775220079_DLP_IN_MATH-5_literal_wownga_final.docx\",\"1775220079_FINAL-Llera.Rhoann.Detailed.Lesson.Plan.In.Grade.5.docx\",\"1775220079_GE_6_ARTS_APPRECIATION_GARIANDO.pdf\"]', '', '2026-04-05 11:53:44', 3),
(11, 9, 'sdfsadfsadf', 'sfasfsad', '[\"1775220156_576921887_689445843814502_2162802240117555914_n.jpg\",\"1775220156_590235744_1415104546912614_4354408474593096173_n.png\",\"1775220156_ACCOMPLISHMENT-REPORT-ON-AWA-AND-TASKS__1_.docx\",\"1775220156_ARTICLE_2_3.pdf\",\"1775220156_ARTICLE_2_3.docx\",\"1775220156_b9d3b75d-c75b-42e1-a683-858a30eb048f.jpeg\"]', '', '2026-04-05 11:53:49', 3),
(12, 1, 'Dimension Chorus 2nd', 'Nive\'ssdlf;lsdf;lsmdf', '[\"1775286900_INFINITY___Premium_Dashboard.pdf\",\"1775286900_REVISED-Online_Reservation_System_for_Cugon_Bamboo_Resort__Chapter_1-4__FINALLE.pdf\",\"1775286900_02-28-2026.pdf\",\"1775286900_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\",\"1775286900_4zkxxeuy-qr.png\",\"1775286900_8e1f8aa3-b073-4e73-877f-70a6db58812b.jpeg\",\"1775286900_9d73273b-c49e-43d2-8377-42767990b2e6.jpeg\",\"1775286900_52d1dde0-3fae-45b6-8c42-7112c3fa8012.jpeg\",\"1775286900_75b73661-24e8-4f18-8d6e-6886f2f80b13.jpeg\"]', '', '2026-04-06 02:24:56', 1),
(13, 1, 'dfsadfsadf', 'asdasdas', '[\"1775305289_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\"]', '', '2026-04-04 12:58:51', 3),
(14, 1, 'asdasdas', 'asdasdasdasd', '[\"1775306032_02-28-2026.pdf\"]', '', '2026-04-04 13:00:03', 3),
(15, 1, 'asdasd', 'asdasdasd', '[\"1775306206_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\"]', '', '2026-04-04 13:04:30', 3),
(16, 1, 'Dimension Chorus 2nd', 'sdfsfsdf', '[\"1775306361_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\"]', '', '2026-04-05 11:43:37', 1),
(17, 1, 'sdfsdfsd', 'cvdfvdfvgdfsg', '[\"1775306427_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\"]', '', '2026-04-05 11:43:34', 1),
(18, 1, 'gwapo', 'sdfsdf', '[\"1775306537_02e60fb4-d321-4c7b-97fb-69ff6ae2f5cf.jpeg\"]', '', '2026-04-05 11:43:31', 1),
(19, 1, 'gwapo', 'asdasdasd', '[\"1775306621_DTR_Generator.pdf\"]', '', '2026-04-06 02:24:45', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_requests`
--

CREATE TABLE `tbl_requests` (
  `REQ_ID` int(11) NOT NULL,
  `USER_ID` int(255) NOT NULL,
  `CERT_ID` int(255) NOT NULL,
  `PURPOSE` varchar(255) NOT NULL,
  `LETTER` text DEFAULT NULL,
  `CTRL_NUM` varchar(20) NOT NULL,
  `REQ_DATE` datetime NOT NULL,
  `REQ_STATUS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_requests`
--

INSERT INTO `tbl_requests` (`REQ_ID`, `USER_ID`, `CERT_ID`, `PURPOSE`, `LETTER`, `CTRL_NUM`, `REQ_DATE`, `REQ_STATUS`) VALUES
(77, 72, 4, 'Motorcycle Loan', '', '7780-1525-4591', '2026-04-02 20:04:32', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_staff_login`
--

CREATE TABLE `tbl_staff_login` (
  `OFFICIALS_LOG_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `OFFICIALS_ID` int(11) NOT NULL,
  `EMPLOYEE_ID` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `TOKEN` varchar(255) NOT NULL,
  `LOG_STATUS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_staff_login`
--

INSERT INTO `tbl_staff_login` (`OFFICIALS_LOG_ID`, `USER_ID`, `OFFICIALS_ID`, `EMPLOYEE_ID`, `PASSWORD`, `TOKEN`, `LOG_STATUS`) VALUES
(2, 71, 15, '12345', '$2y$10$AL/YuyGMJaUDt2UcAqStI.8lyZMzxEXLRHf.JXQXIpjEzEDxYeFW.', 'cf8844d7a098a27f2b483c2b446c5014aed24d66b022d368842b918a0e8144c5', 'active'),
(4, 73, 16, '342434223', '$2y$10$fHcdIHQUZ3fesLIF619lhOqMrAaM15x.ZyvBNkzXJgBp355oyzYgy', '48f785d126eadc6a7e59b47c07cc6b618e206f677d5575d0c6207603f4ea4a6b', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status`
--

CREATE TABLE `tbl_status` (
  `STATUS_ID` int(11) NOT NULL,
  `STATUS_NAME` varchar(255) NOT NULL,
  `DATE_ADDED` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_status`
--

INSERT INTO `tbl_status` (`STATUS_ID`, `STATUS_NAME`, `DATE_ADDED`) VALUES
(1, 'Single', '2026-02-28'),
(2, 'Married', '2026-03-28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `TR_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `MESSAGE` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `u_id` int(11) NOT NULL,
  `google_uid` longtext DEFAULT NULL,
  `PP` varchar(255) DEFAULT NULL,
  `u_email` varchar(255) DEFAULT NULL,
  `u_username` varchar(20) DEFAULT NULL,
  `u_password` varchar(255) DEFAULT NULL,
  `user_role` int(1) NOT NULL DEFAULT 3,
  `u_otp` varchar(10) DEFAULT NULL,
  `OTP_DATE` datetime DEFAULT NULL,
  `DATE_CREATED` datetime NOT NULL,
  `u_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`u_id`, `google_uid`, `PP`, `u_email`, `u_username`, `u_password`, `user_role`, `u_otp`, `OTP_DATE`, `DATE_CREATED`, `u_status`) VALUES
(71, NULL, NULL, 'junzfundador142@gmail.com', NULL, '$2y$10$AL/YuyGMJaUDt2UcAqStI.8lyZMzxEXLRHf.JXQXIpjEzEDxYeFW.', 1, NULL, '2026-04-02 11:36:37', '2026-04-02 11:36:37', 'yes'),
(72, 'Ydl0XiOVPkaprY0PsEq4hpO3DeF2', 'https://lh3.googleusercontent.com/a/ACg8ocLokfiqsCDBf2BUsUY7gHggo26MtbHmaKEDRoKslmFaSHlphp0=s96-c', 'crestinemaemendezromano0217@gmail.com', NULL, '$2y$10$BJTG5P4S3VEVpK8bdPTwDeo3vLXcDcThzn5IYcFm0hZxS1V/eCMrW', 3, NULL, NULL, '2026-04-05 07:26:54', 'yes'),
(73, NULL, NULL, 'fundadordiongie@gmail.com', NULL, '$2y$10$fHcdIHQUZ3fesLIF619lhOqMrAaM15x.ZyvBNkzXJgBp355oyzYgy', 2, NULL, '2026-04-05 12:03:07', '2026-04-05 12:03:07', 'yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_brgy`
--
ALTER TABLE `tbl_brgy`
  ADD PRIMARY KEY (`BRGY_ID`);

--
-- Indexes for table `tbl_certificates`
--
ALTER TABLE `tbl_certificates`
  ADD PRIMARY KEY (`CERT_ID`);

--
-- Indexes for table `tbl_gender`
--
ALTER TABLE `tbl_gender`
  ADD PRIMARY KEY (`GENDER_ID`);

--
-- Indexes for table `tbl_officials`
--
ALTER TABLE `tbl_officials`
  ADD PRIMARY KEY (`OFFICIAL_ID`);

--
-- Indexes for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  ADD PRIMARY KEY (`PI_ID`);

--
-- Indexes for table `tbl_position`
--
ALTER TABLE `tbl_position`
  ADD PRIMARY KEY (`POSITION_ID`);

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  ADD PRIMARY KEY (`REQ_ID`);

--
-- Indexes for table `tbl_staff_login`
--
ALTER TABLE `tbl_staff_login`
  ADD PRIMARY KEY (`OFFICIALS_LOG_ID`);

--
-- Indexes for table `tbl_status`
--
ALTER TABLE `tbl_status`
  ADD PRIMARY KEY (`STATUS_ID`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`TR_ID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_brgy`
--
ALTER TABLE `tbl_brgy`
  MODIFY `BRGY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_certificates`
--
ALTER TABLE `tbl_certificates`
  MODIFY `CERT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_gender`
--
ALTER TABLE `tbl_gender`
  MODIFY `GENDER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_officials`
--
ALTER TABLE `tbl_officials`
  MODIFY `OFFICIAL_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_personal_info`
--
ALTER TABLE `tbl_personal_info`
  MODIFY `PI_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `tbl_position`
--
ALTER TABLE `tbl_position`
  MODIFY `POSITION_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_requests`
--
ALTER TABLE `tbl_requests`
  MODIFY `REQ_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `tbl_staff_login`
--
ALTER TABLE `tbl_staff_login`
  MODIFY `OFFICIALS_LOG_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_status`
--
ALTER TABLE `tbl_status`
  MODIFY `STATUS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `TR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
