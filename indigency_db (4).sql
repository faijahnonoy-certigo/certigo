-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 03:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indigency_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$Kmtkb3d24EgXfSrkgxrT6OYIOW3VmhRNLwvBTeuDnvVPS3SNEm7He', '2025-11-04 12:32:24');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `tracking_no` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `yearresidency` int(11) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `validid` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `holdingid` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `date_submitted` datetime DEFAULT current_timestamp(),
  `pickup_date` date DEFAULT NULL,
  `date_claimed` date DEFAULT NULL,
  `reject_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `tracking_no`, `firstname`, `lastname`, `address`, `yearresidency`, `contact`, `email`, `purpose`, `remarks`, `validid`, `cedula`, `holdingid`, `status`, `date_submitted`, `pickup_date`, `date_claimed`, `reject_reason`) VALUES
(1, NULL, 'Juan', 'Dela Cruz', 'Balangkas, Valenzuela', NULL, NULL, NULL, 'For employment', NULL, NULL, NULL, NULL, 'Completed', '2025-10-24 18:09:23', NULL, NULL, NULL),
(2, NULL, 'Maria', 'Santos', 'Balangkas, Valenzuela', NULL, NULL, NULL, 'For scholarship', NULL, NULL, NULL, NULL, 'Rejected', '2025-10-24 18:09:23', NULL, NULL, NULL),
(3, NULL, '1', '1', '1', 1, '1', '1@gmail.com', '1', '1', '1761305412_1165599.jpg', '1761305412_1165599.jpg', '1761305412_1165599.jpg', 'Rejected', '2025-10-24 19:30:12', NULL, NULL, NULL),
(4, NULL, 'Gail', 'Escabilla', '1234567890p', 20, '23456789', 'gail@gmail.com', 'qwerftgyhuji', 'defrghjk', '1761305836_1165599.jpg', '1761305836_1165599.jpg', '1761305836_1165599.jpg', 'Completed', '2025-10-24 19:37:16', NULL, NULL, NULL),
(5, NULL, 'Faijah', 'Nonoy', 'Akruhatan', 20, '0987654321', 'faijah@gmail.com', 'Scholarship', 'basta', '1761306306_1165599.jpg', '1761306306_1165599.jpg', '1761306306_1165599.jpg', 'Completed', '2025-10-24 19:45:06', NULL, NULL, NULL),
(6, NULL, 'q', 'q', 'q', 1, '1', '1@gmail.com', 'q', 'q', '1761321702_5.png', '1761321702_marquee-1.png', '1761321702_1.png', 'Rejected', '2025-10-25 00:01:42', NULL, NULL, NULL),
(7, NULL, 'mama', 'mo', '12345', 12, '12345', '12@gmail.com', 'qwdf', '123er', '1761321771_faijah-profile-9.jpg', '1761321771_faijah-profile-1.jpg', '1761321771_faijah-profile-2.jpg', 'Rejected', '2025-10-25 00:02:51', NULL, NULL, NULL),
(8, 'REQ-20251024-8', 'lian', 'lian', '123456', 12, '12345', '1@gmail.com', 'we', '', '1761322205_faijah-profile-1.jpg', '1761322205_faijah-profile-2.jpg', '1761322205_faijah-profile-5.jpg', 'Completed', '2025-10-25 00:10:05', NULL, NULL, NULL),
(9, 'REQ-20251024-9', 'barbie', 'doll', '123456789', 12, '123', 'aniwni@gmail.com', 'owmspoq', 'qdon', '1761322366_shs-electron-1.jpg', '1761322366_faijah-profile-3.jpg', '1761322366_faijah-profile-8.jpg', 'Completed', '2025-10-25 00:12:46', NULL, NULL, NULL),
(10, 'REQ-20251024-10', 'Faijah', 'Nonoy', 'Karuhatan Val', 20, '1234567890', 'faijahnonoy@gmail.com', 'Scholarship', 'Wala naman', '1761323169_faijah-profile-9.jpg', '1761323169_certificatee-2.jpg', '1761323169_faijah-profile-9.jpg', 'Completed', '2025-10-25 00:26:09', NULL, NULL, NULL),
(11, 'REQ-20251024-11', 'Sid', 'Lucero', 'qwsdefg', 1, '12345', 'sid@gmail.com', 'ww', '', '1761323894_efag.jpg', '1761323894_certificate-dict.png', '1761323894_efag.jpg', 'Completed', '2025-10-25 00:38:14', NULL, NULL, NULL),
(12, 'REQ-20251025-12', 'Rose Anne ', 'Cacayorin', '1337 Duhat St. Bagbaguin Valenzuela City', 18, '09171628441', 'caca@gmail.com', 'School', 'None', '1761347882_ab67616100005174ecddf4e9db3637257468860e.jpg', '1761347882_ab67616100005174ecddf4e9db3637257468860e.jpg', '1761347882_Untitled design (1).png', 'Completed', '2025-10-25 07:18:02', NULL, NULL, NULL),
(13, 'REQ-20251103-13', 'q', 'q', 'q', 1, '1', '1@gmail.com', '1', '1', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', 'Completed', '2025-11-03 11:37:52', NULL, NULL, NULL),
(14, 'REQ-20251103-14', 'a', 'a', 'a', 1, '1', '1@gmail.com', '2', '2', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', 'Completed', '2025-11-03 11:43:56', NULL, NULL, NULL),
(15, 'REQ-20251024-10', 'Faijah', 'Nonoy', 'Karuhatan Val', 20, '1234567890', 'faijahnonoy@gmail.com', 'Scholarship', 'Wala naman', '1761323169_faijah-profile-9.jpg', '1761323169_certificatee-2.jpg', '1761323169_faijah-profile-9.jpg', 'Completed', '2025-10-25 00:26:09', NULL, NULL, NULL),
(16, 'REQ-20251103-16', 'Faijah', 'Nonoy', 'a', 1, '1', '1@gmail.com', '1', '1', '1762141797_id b2b_page-0001.jpg', '1762141797_id b2b_page-0001.jpg', '1762141797_id b2b_page-0001.jpg', 'Completed', '2025-11-03 11:49:57', NULL, NULL, NULL),
(17, 'REQ-20251103-17', 'Faijah', 'q', 'q1', 1, '1', '1@gmail.com', '1', '1', '1762143903_id b2b_page-0001.jpg', '1762143903_id b2b_page-0001.jpg', '1762143903_id b2b_page-0001.jpg', 'Completed', '2025-11-03 12:25:03', '2025-11-24', '2025-11-03', NULL),
(18, 'REQ-20251103-18', 'q', 'q', 'q', 1, '1', '1@gmail.com', '1', '1', '1762144183_id b2b_page-0001.jpg', '1762144183_id b2b_page-0001.jpg', '1762144183_id b2b_page-0001.jpg', 'Rejected', '2025-11-03 12:29:43', NULL, NULL, 'incomplete proof of residency and other proof'),
(19, 'REQ-20251103-19', 'Faijah ', 'Nonoy', 'karuhatan', 20, '0987654321', 'faijah@gmail.com', 's', 'w', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', 'Completed', '2025-11-03 12:41:19', '2025-11-07', '2025-11-03', NULL),
(20, 'REQ-20251103-20', 'Faijah', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', 'This is for my Kuya Win Scholarship, named to me.', '1762160285_philid.jpg', '1762160285_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762160285_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', '2025-11-03 16:58:05', '2025-11-06', '2025-11-03', NULL),
(21, 'REQ-20251103-21', 'q', 'q', 'q', 1, '1', '1@gmail.com', '1', '1', '1762164583_philid.jpg', '1762164583_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762164583_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', '2025-11-03 18:09:43', '2025-11-06', '2025-11-03', NULL),
(22, 'REQ-20251103-22', 'Faijah', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 20, '098765432', 'faijahnonoy20@gmail.com', 'school', '52 Daliva Street Karuhatan Valenzuela City\r\n20\r\n09945421889\r\nfaijahnonoy20@gmail.com\r\nScholarship\r\nThis is for my Kuya Win Scholarship, named to me.', '1762166358_philid.jpg', '1762166358_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762166358_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', '2025-11-03 18:39:18', '2025-11-10', '2025-11-03', NULL),
(23, 'REQ-20251104-23', 'q', 'q', 'q', 1, '1', '1@gmail.com', 's', 's', '1762264036_philid.jpg', '1762264036_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', '1762264036_tumblr_nmswgm3wo71uqxegmo1_500.jpg', 'Approved', '2025-11-04 21:47:16', NULL, NULL, NULL),
(24, 'REQ-20251104-24', 'Faijah', 'Nonoy', 'qwertyuio', 20, '0987654321', '1@gmail.com', 's', 'm', '690a0ab25d4a8_1762265778.jpg', '690a0ab25d91b_1762265778.jpg', '690a0ab25de9b_1762265778.jpg', 'Approved', '2025-11-04 22:16:18', NULL, NULL, NULL),
(25, 'REQ-20251104-25', 'Faijah', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 20, '0987654321', 'faijahnonoy@gmail.com', 'Scholarship', '52 Daliva Street Karuhatan Valenzuela City\r\n20\r\n09945421889\r\nfaijahnonoy20@gmail.com\r\nScholarship\r\nThis is for my Kuya Win Scholarship, named to me.', '690a0c5d6f7d3_1762266205.jpg', '690a0c5d6fd27_1762266205.jpg', '690a0c5d715f0_1762266205.jpg', 'Pending', '2025-11-04 22:23:25', NULL, NULL, NULL),
(26, 'REQ-20251105-26', 'Faijah', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 20, '0987654321', 'faijahnonoy@gmail.com', 'Scholarship', 'n', '690b47b21f135_1762346930.jpg', '690b47b21f9fb_1762346930.jpg', '690b47b21ff8f_1762346930.jpg', 'Pending', '2025-11-05 20:48:50', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `request_history`
--

CREATE TABLE `request_history` (
  `id` int(11) NOT NULL,
  `tracking_no` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `remarks` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request_history`
--

INSERT INTO `request_history` (`id`, `tracking_no`, `status`, `remarks`, `timestamp`) VALUES
(1, 'REQ-20251103-13', 'Completed', NULL, '2025-11-03 11:42:53'),
(2, 'REQ-20251103-14', 'Approved', NULL, '2025-11-03 11:44:39'),
(3, 'REQ-20251103-14', 'Completed', NULL, '2025-11-03 11:44:59'),
(4, 'REQ-20251103-16', 'Approved', NULL, '2025-11-03 11:50:34'),
(5, 'REQ-20251103-16', 'Completed', NULL, '2025-11-03 11:51:57'),
(6, 'REQ-20251103-17', 'Approved', NULL, '2025-11-03 12:25:31'),
(7, 'REQ-20251103-17', 'For Pick-up', NULL, '2025-11-03 12:25:57'),
(8, 'REQ-20251103-17', 'Completed', NULL, '2025-11-03 12:26:39'),
(9, 'REQ-20251103-18', 'Rejected', NULL, '2025-11-03 12:36:34'),
(10, 'REQ-20251103-19', 'Approved', NULL, '2025-11-03 12:42:02'),
(11, 'REQ-20251103-19', 'For Pick-up', NULL, '2025-11-03 12:42:39'),
(12, 'REQ-20251103-19', 'Completed', NULL, '2025-11-03 12:43:19'),
(13, 'REQ-20251103-20', 'Approved', NULL, '2025-11-03 16:58:43'),
(14, 'REQ-20251103-20', 'For Pick-up', NULL, '2025-11-03 16:59:08'),
(15, 'REQ-20251103-20', 'Completed', NULL, '2025-11-03 16:59:30'),
(16, 'REQ-20251103-21', 'Approved', NULL, '2025-11-03 18:10:03'),
(17, 'REQ-20251103-21', 'For Pick-up', NULL, '2025-11-03 18:10:14'),
(18, 'REQ-20251103-21', 'Completed', NULL, '2025-11-03 18:10:34'),
(19, 'REQ-20251103-22', 'Approved', NULL, '2025-11-03 18:39:53'),
(20, 'REQ-20251103-22', 'For Pick-up', NULL, '2025-11-03 18:40:09'),
(21, 'REQ-20251103-22', 'Completed', NULL, '2025-11-03 18:40:45'),
(22, 'REQ-20251104-23', 'Approved', NULL, '2025-11-04 21:47:41'),
(23, 'REQ-20251104-24', 'Approved', NULL, '2025-11-04 22:16:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_history`
--
ALTER TABLE `request_history`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `request_history`
--
ALTER TABLE `request_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
