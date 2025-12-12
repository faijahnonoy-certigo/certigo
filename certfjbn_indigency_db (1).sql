-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 12, 2025 at 12:25 PM
-- Server version: 11.4.8-MariaDB-cll-lve-log
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `certfjbn_indigency_db`
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
(2, 'certigo-balangkas-admin', '$2y$10$YJ3mIGbmkZlgRXJZbilV7eevh65OCJZM7nrJZyWN7Z/tApzXvKDwK', '2025-11-13 15:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(11) NOT NULL,
  `tracking_no` varchar(50) DEFAULT NULL,
  `firstname` varchar(100) NOT NULL,
  `middleinitial` varchar(10) DEFAULT NULL,
  `lastname` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `yearresidency` int(11) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `validid` varchar(255) DEFAULT NULL,
  `cedula` varchar(255) DEFAULT NULL,
  `holdingid` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `student_patient_name` varchar(255) DEFAULT NULL,
  `student_patient_address` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `date_submitted` datetime DEFAULT current_timestamp(),
  `pickup_date` date DEFAULT NULL,
  `date_claimed` date DEFAULT NULL,
  `reject_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `tracking_no`, `firstname`, `middleinitial`, `lastname`, `address`, `dateofbirth`, `age`, `gender`, `yearresidency`, `contact`, `email`, `purpose`, `remarks`, `validid`, `cedula`, `holdingid`, `status`, `student_patient_name`, `student_patient_address`, `relationship`, `date_submitted`, `pickup_date`, `date_claimed`, `reject_reason`) VALUES
(1, 'REQ-20251111-1', 'Faijah', NULL, 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', NULL, NULL, NULL, 20, '09216825112', '1@gmail.com', 'Scholarship', '', '6913656c7efdc_1762878828.jpg', '6913656c7f512_1762878828.jpg', '6913656c7faca_1762878828.jpg', 'Rejected', NULL, NULL, NULL, '2025-11-12 00:33:48', NULL, NULL, 'Not eligible for this document'),
(2, 'REQ-20251111-2', 'Uriel ', 'Z', 'Mercadejas', '52', NULL, NULL, NULL, 20, '09876543212', '1@gmail.com', 'Scholarship', '', '6913666b3db9b_1762879083.jpg', '6913666b3e28d_1762879083.jpg', '6913666b3ea21_1762879083.jpg', 'Rejected', NULL, NULL, NULL, '2025-11-12 00:38:03', NULL, NULL, 'Duplicate request submitted'),
(3, 'REQ-20251111-3', 'Anton', 'Z', 'Mercadejas', '0', NULL, NULL, NULL, 54, '12345678987', '1@gmail.com', 'Scholarship', '', '69136761b460e_1762879329.jpg', '69136761b4b59_1762879329.jpg', '69136761b5074_1762879329.jpg', 'Completed', NULL, NULL, NULL, '2025-11-12 00:42:09', '2025-11-19', '2025-11-12', NULL),
(4, NULL, 'Gail', NULL, 'Escabilla', '1234567890p', NULL, NULL, NULL, 20, '23456789', 'gail@gmail.com', 'qwerftgyhuji', 'defrghjk', '1761305836_1165599.jpg', '1761305836_1165599.jpg', '1761305836_1165599.jpg', 'Completed', NULL, NULL, NULL, '2025-10-24 19:37:16', NULL, NULL, NULL),
(5, NULL, 'Faijah', NULL, 'Nonoy', 'Akruhatan', NULL, NULL, NULL, 20, '0987654321', 'faijah@gmail.com', 'Scholarship', 'basta', '1761306306_1165599.jpg', '1761306306_1165599.jpg', '1761306306_1165599.jpg', 'Completed', NULL, NULL, NULL, '2025-10-24 19:45:06', NULL, NULL, NULL),
(6, NULL, 'q', NULL, 'q', 'q', NULL, NULL, NULL, 1, '1', '1@gmail.com', 'q', 'q', '1761321702_5.png', '1761321702_marquee-1.png', '1761321702_1.png', 'Rejected', NULL, NULL, NULL, '2025-10-25 00:01:42', NULL, NULL, NULL),
(7, NULL, 'mama', NULL, 'mo', '12345', NULL, NULL, NULL, 12, '12345', '12@gmail.com', 'qwdf', '123er', '1761321771_faijah-profile-9.jpg', '1761321771_faijah-profile-1.jpg', '1761321771_faijah-profile-2.jpg', 'Rejected', NULL, NULL, NULL, '2025-10-25 00:02:51', NULL, NULL, NULL),
(8, 'REQ-20251024-8', 'lian', NULL, 'lian', '123456', NULL, NULL, NULL, 12, '12345', '1@gmail.com', 'we', '', '1761322205_faijah-profile-1.jpg', '1761322205_faijah-profile-2.jpg', '1761322205_faijah-profile-5.jpg', 'Completed', NULL, NULL, NULL, '2025-10-25 00:10:05', NULL, NULL, NULL),
(9, 'REQ-20251024-9', 'barbie', NULL, 'doll', '123456789', NULL, NULL, NULL, 12, '123', 'aniwni@gmail.com', 'owmspoq', 'qdon', '1761322366_shs-electron-1.jpg', '1761322366_faijah-profile-3.jpg', '1761322366_faijah-profile-8.jpg', 'Completed', NULL, NULL, NULL, '2025-10-25 00:12:46', NULL, NULL, NULL),
(10, 'REQ-20251024-10', 'Faijah', NULL, 'Nonoy', 'Karuhatan Val', NULL, NULL, NULL, 20, '1234567890', 'faijahnonoy@gmail.com', 'Scholarship', 'Wala naman', '1761323169_faijah-profile-9.jpg', '1761323169_certificatee-2.jpg', '1761323169_faijah-profile-9.jpg', 'Completed', NULL, NULL, NULL, '2025-10-25 00:26:09', NULL, NULL, NULL),
(11, 'REQ-20251024-11', 'Sid', NULL, 'Lucero', 'qwsdefg', NULL, NULL, NULL, 1, '12345', 'sid@gmail.com', 'ww', '', '1761323894_efag.jpg', '1761323894_certificate-dict.png', '1761323894_efag.jpg', 'Completed', NULL, NULL, NULL, '2025-10-25 00:38:14', NULL, NULL, NULL),
(12, 'REQ-20251025-12', 'Rose Anne ', NULL, 'Cacayorin', '1337 Duhat St. Bagbaguin Valenzuela City', NULL, NULL, NULL, 18, '09171628441', 'caca@gmail.com', 'School', 'None', '1761347882_ab67616100005174ecddf4e9db3637257468860e.jpg', '1761347882_ab67616100005174ecddf4e9db3637257468860e.jpg', '1761347882_Untitled design (1).png', 'Completed', NULL, NULL, NULL, '2025-10-25 07:18:02', NULL, NULL, NULL),
(13, 'REQ-20251103-13', 'q', NULL, 'q', 'q', NULL, NULL, NULL, 1, '1', '1@gmail.com', '1', '1', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', '1762141072_IMG_3547_20250425_142026_keyed_3600.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 11:37:52', NULL, NULL, NULL),
(14, 'REQ-20251103-14', 'a', NULL, 'a', 'a', NULL, NULL, NULL, 1, '1', '1@gmail.com', '2', '2', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', '1762141436_IMG_3544_20250425_141918_keyed_3600.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 11:43:56', NULL, NULL, NULL),
(15, 'REQ-20251024-10', 'Faijah', NULL, 'Nonoy', 'Karuhatan Val', NULL, NULL, NULL, 20, '1234567890', 'faijahnonoy@gmail.com', 'Scholarship', 'Wala naman', '1761323169_faijah-profile-9.jpg', '1761323169_certificatee-2.jpg', '1761323169_faijah-profile-9.jpg', 'Completed', NULL, NULL, NULL, '2025-10-25 00:26:09', NULL, NULL, NULL),
(16, 'REQ-20251103-16', 'Faijah', NULL, 'Nonoy', 'a', NULL, NULL, NULL, 1, '1', '1@gmail.com', '1', '1', '1762141797_id b2b_page-0001.jpg', '1762141797_id b2b_page-0001.jpg', '1762141797_id b2b_page-0001.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 11:49:57', NULL, NULL, NULL),
(17, 'REQ-20251103-17', 'Faijah', NULL, 'q', 'q1', NULL, NULL, NULL, 1, '1', '1@gmail.com', '1', '1', '1762143903_id b2b_page-0001.jpg', '1762143903_id b2b_page-0001.jpg', '1762143903_id b2b_page-0001.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 12:25:03', '2025-11-24', '2025-11-03', NULL),
(18, 'REQ-20251103-18', 'q', NULL, 'q', 'q', NULL, NULL, NULL, 1, '1', '1@gmail.com', '1', '1', '1762144183_id b2b_page-0001.jpg', '1762144183_id b2b_page-0001.jpg', '1762144183_id b2b_page-0001.jpg', 'Rejected', NULL, NULL, NULL, '2025-11-03 12:29:43', NULL, NULL, 'incomplete proof of residency and other proof'),
(19, 'REQ-20251103-19', 'Faijah ', NULL, 'Nonoy', 'karuhatan', NULL, NULL, NULL, 20, '0987654321', 'faijah@gmail.com', 's', 'w', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', '1762144879_IMG_3547_20250425_142026_keyed_3600.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 12:41:19', '2025-11-07', '2025-11-03', NULL),
(20, 'REQ-20251103-20', 'Faijah', NULL, 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', NULL, NULL, NULL, 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', 'This is for my Kuya Win Scholarship, named to me.', '1762160285_philid.jpg', '1762160285_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762160285_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 16:58:05', '2025-11-06', '2025-11-03', NULL),
(21, 'REQ-20251103-21', 'q', NULL, 'q', 'q', NULL, NULL, NULL, 1, '1', '1@gmail.com', '1', '1', '1762164583_philid.jpg', '1762164583_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762164583_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 18:09:43', '2025-11-06', '2025-11-03', NULL),
(22, 'REQ-20251103-22', 'Faijah', NULL, 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', NULL, NULL, NULL, 20, '098765432', 'faijahnonoy20@gmail.com', 'school', '52 Daliva Street Karuhatan Valenzuela City\r\n20\r\n09945421889\r\nfaijahnonoy20@gmail.com\r\nScholarship\r\nThis is for my Kuya Win Scholarship, named to me.', '1762166358_philid.jpg', '1762166358_tumblr_nmswgm3wo71uqxegmo1_500.jpg', '1762166358_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', 'Completed', NULL, NULL, NULL, '2025-11-03 18:39:18', '2025-11-10', '2025-11-03', NULL),
(23, 'REQ-20251104-23', 'q', NULL, 'q', 'q', NULL, NULL, NULL, 1, '1', '1@gmail.com', 's', 's', '1762264036_philid.jpg', '1762264036_f4afba31-36c1-4555-aa16-b453afca8f6e.jpg', '1762264036_tumblr_nmswgm3wo71uqxegmo1_500.jpg', 'Approved', NULL, NULL, NULL, '2025-11-04 21:47:16', NULL, NULL, NULL),
(24, 'REQ-20251104-24', 'Faijah', NULL, 'Nonoy', 'qwertyuio', NULL, NULL, NULL, 20, '0987654321', '1@gmail.com', 's', 'm', '690a0ab25d4a8_1762265778.jpg', '690a0ab25d91b_1762265778.jpg', '690a0ab25de9b_1762265778.jpg', 'For Pick-up', NULL, NULL, NULL, '2025-11-04 22:16:18', '2025-11-17', NULL, NULL),
(25, 'REQ-20251104-25', 'Faijah', NULL, 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', NULL, NULL, NULL, 20, '0987654321', 'faijahnonoy@gmail.com', 'Scholarship', '52 Daliva Street Karuhatan Valenzuela City\r\n20\r\n09945421889\r\nfaijahnonoy20@gmail.com\r\nScholarship\r\nThis is for my Kuya Win Scholarship, named to me.', '690a0c5d6f7d3_1762266205.jpg', '690a0c5d6fd27_1762266205.jpg', '690a0c5d715f0_1762266205.jpg', 'Approved', NULL, NULL, NULL, '2025-11-04 22:23:25', NULL, NULL, NULL),
(26, 'REQ-20251105-26', 'Faijah', NULL, 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', NULL, NULL, NULL, 20, '0987654321', 'faijahnonoy@gmail.com', 'Scholarship', 'n', '690b47b21f135_1762346930.jpg', '690b47b21f9fb_1762346930.jpg', '690b47b21ff8f_1762346930.jpg', 'For Pick-up', NULL, NULL, NULL, '2025-11-05 20:48:50', '2025-11-15', NULL, NULL),
(27, 'REQ-20251113-27', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Educational Assistance', '', '6915aacf426ee_1763027663.png', '6915aacf44011_1763027663.png', '6915aacf445dc_1763027663.png', 'For Pick-up', '', '', 'Self', '2025-11-13 17:54:23', '2025-11-10', NULL, NULL),
(28, 'REQ-20251113-28', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Educational Assistance', '', '6915b00f64256_1763029007.png', '6915b00f646d1_1763029007.png', '6915b00f64c7f_1763029007.png', 'For Pick-up', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Applicant', '2025-11-13 18:16:47', '2025-11-20', NULL, NULL),
(29, 'REQ-20251113-29', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', '', '6915ce8ad687a_1763036810.png', '6915ce8ad6a81_1763036810.png', '6915ce8ad6b04_1763036810.png', 'Approve', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Applicant', '2025-11-13 07:26:50', NULL, NULL, NULL),
(30, 'REQ-20251113-30', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2005-04-25', 20, 'Male', 20, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', '', '6915ec111471c_1763044369.png', '6915ec1114944_1763044369.png', '6915ec1114a12_1763044369.png', 'Rejected', 'Benedict Bustamante', 'ilang ilang', 'Applicant', '2025-11-13 09:32:49', NULL, NULL, 'Invalid or unclear ID/image provided'),
(31, 'REQ-20251113-31', 'sean', 'patricio', 'vasquez', 'balangkas valenzuela city', '2005-09-29', 20, 'Male', 20, '09123456789', 'testing@gmail.com', 'Scholarship', '', '6915f738a2875_1763047224.jpg', '6915f738a29bb_1763047224.jpg', '6915f738a2aa9_1763047224.jpg', 'Approved', 'mark vasquez', 'balangkas val city', 'Applicant', '2025-11-13 10:20:24', NULL, NULL, NULL),
(32, 'REQ-20251113-32', 'Petter Carey', 'Talisay', 'Abulencia', '#2 Bignay St. Balangkas, Valenzuela City', '2005-06-26', 20, 'Male', 20, '09991191911', 'abulenciapeter0977@gmail.com', 'Educational Assistance', '', '6915f866cb696_1763047526.png', '6915f866cbecd_1763047526.png', '6915f866cc796_1763047526.png', 'Pending', 'Petter Carey Abulencia', '#2 Bignay St. Balangkas, Valenzuela City', 'Applicant', '2025-11-13 10:25:26', NULL, NULL, NULL),
(33, 'REQ-20251113-33', 'Faijah', 'F', 'Nonoy', 'Lorem Ipsum Dolor', '2005-11-13', 55, 'Female', 20, '09123456789', 'test@gmail.com', 'Financial Assistance', '', '6915f8ab1f1a5_1763047595.jpg', '6915f8ab1f2b4_1763047595.jpg', '6915f8ab1f347_1763047595.jpg', 'Pending', 'dfjsisisisiisks', 'ff', 'Mother', '2025-11-13 10:26:35', NULL, NULL, NULL),
(34, 'REQ-20251113-34', 'Francis Kian', 'Quezada', 'Garcia', '67 Peter Bignay St. Balangkas, Valenzuela City', '2005-12-16', 19, 'Male', 19, '09227660761', 'franciskiangarcia@gmail.com', 'Scholarship', 'I really need this ASAP since the due date for scholarship is almost up.', '6915fa6e84edd_1763048046.png', '6915fa6e85047_1763048046.png', '6915fa6e85192_1763048046.png', 'Approve', 'Peter Bignay', '67 Peter Bignay St. Balangkas, Valenzuela City', 'Child', '2025-11-13 10:34:06', NULL, NULL, NULL),
(35, 'REQ-20251113-35', 'Gail Ann', 'Baldio', 'Escabilla', 'Gansjskskxks', '2005-07-15', 20, 'Female', 5, '09123456789', 'gansjs@gmail.com', 'Scholarship', '', '6915faa3cbe35_1763048099.jpg', '6915faa3cbf5b_1763048099.jpg', '6915faa3cc075_1763048099.jpg', 'Approved', 'Hajsjs', 'Ejjdjdkdkd', 'Applicant', '2025-11-13 10:34:59', NULL, NULL, NULL),
(36, 'REQ-20251114-36', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-02', 20, 'Female', 20, '09945421889', 'faijahnonoy@gmail.com', 'Educational Assistance', '', '69172e61785b4_1763126881.png', '69172e61787a0_1763126881.png', '69172e6178918_1763126881.png', 'For Pick-up', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Applicant', '2025-11-14 08:28:01', '2025-11-15', NULL, NULL),
(37, 'REQ-20251114-37', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2025-11-18', 45, 'Male', 20, '09945421889', 'faijahnonoy@gmail.com', 'Scholarship', '', '691735b322313_1763128755.jpg', '691735b3224a9_1763128755.jpg', '691735b3225dd_1763128755.jpg', 'Approve', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Applicant', '2025-11-14 08:59:15', NULL, NULL, NULL),
(38, 'REQ-20251114-38', 'berto', 'al', 'bayawak', 'balangkas', '2013-07-24', 18, 'Female', 12, '09991191911', 'faijahnonoy@gmail.com', 'Burial Assistance', '', '691736f57627b_1763129077.png', '691736f577070_1763129077.png', '691736f577ac6_1763129077.png', 'For Pick-up', 'BERTO', '#2 Bignay St. Balangkas, Valenzuela City', 'Applicant', '2025-11-14 09:04:37', '2025-11-15', NULL, NULL),
(39, 'REQ-20251114-39', 'sdfghsrt', 'dfber', 'sdfgn', 'sdfghnm', '2005-02-14', 20, 'Male', 5, '09123456789', 'faijahnonoy@gmail.com', 'Scholarship', '', '691737af76d5f_1763129263.png', '691737af76e57_1763129263.png', '691737af76ee0_1763129263.png', 'For Pick-up', 'sdfghjk', 'asdfghjk', 'Applicant', '2025-11-14 09:07:43', '2025-11-27', NULL, NULL),
(40, 'REQ-20251114-40', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2025-11-05', 20, 'Male', 20, '09945421889', 'faijahnonoy@gmail.com', 'Educational Assistance', '', '69174663b009f_1763133027.png', '69174663b02a1_1763133027.png', '69174663b0362_1763133027.png', 'Completed', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Father', '2025-11-14 10:10:27', '2025-11-16', '2025-11-14', NULL),
(41, 'REQ-20251114-41', 'fghjk', 'sjfghjk', 'dfghiop', 'sdtyuiop', '2025-11-14', 60, 'Male', 5, '09123456789', 'faijahnonoy@gmail.com', 'Scholarship', '', '69174795905e0_1763133333.png', '6917479590942_1763133333.png', '6917479590bf2_1763133333.png', 'Pending', 'dfghj', 'asdfg', 'Applicant', '2025-11-14 10:15:33', NULL, NULL, NULL),
(42, 'REQ-20251114-42', 'Baron', 'kiko', 'Geisler', 'macapuno st.', '2005-11-01', 20, 'Female', 20, '09991119999', 'faijahnonoy@gmail.com', 'Financial Assistance', 'no', '691747dd60724_1763133405.jpg', '691747dd61ca7_1763133405.jpg', '691747dd621b8_1763133405.jpg', 'Pending', 'peter ombudsman', 'macapuno st.', 'Legal Guardian', '2025-11-14 10:16:45', NULL, NULL, NULL),
(43, 'REQ-20251114-43', 'spongebob', 'sandy', 'squarepants', 'bikini bottom', '1972-11-08', 53, 'Male', 41, '09991119999', 'faijahnonoy@gmail.com', 'Medical Assistance', 'sample', '691748cf11d7d_1763133647.png', '691748cf11ec0_1763133647.webp', '691748cf11f4d_1763133647.jpg', 'Pending', 'peter ombudsman', 'macapuno st.', 'Mother', '2025-11-14 10:20:47', NULL, NULL, NULL),
(44, 'REQ-20251114-44', 'xcghjkl;', 'xdfghjkl', 'xcvbnm', 'wertyuiop', '2025-11-14', 18, 'Male', 45, '09123456789', 'faijahnonoy@gmail.com', 'Scholarship', '', '69174f7c924cd_1763135356.png', '69174f7c9283a_1763135356.png', '69174f7c92aba_1763135356.png', 'Approved', 'dfghjkl', 'n/a', 'Father', '2025-11-14 10:49:16', NULL, NULL, NULL),
(45, 'REQ-20251115-45', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', '', '6917d86c21c08_1763170412.jpg', '6917d86c21dee_1763170412.jpg', '6917d86c21f27_1763170412.jpg', 'Approved', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'Applicant', '2025-11-14 20:33:32', NULL, NULL, NULL),
(46, 'REQ-20251115-46', 'Paulo', 'Formento', 'Galarosa', 'Unit 5 Bldg 13 Disiplina Village Ugong Valenzuela City', '1994-11-16', 18, 'Male', 15, '09189573890', 'paulogalarosa@plv.edu.ph', 'Employment', '', '6917df7606c1b_1763172214.jpg', '6917df7606dc2_1763172214.txt', '6917df760748b_1763172214.jpg', 'Completed', 'N/AAA', 'N/AAA', 'Applicant', '2025-11-14 21:03:34', '2025-11-16', '2025-12-11', NULL),
(47, 'REQ-20251121-47', 'Sean', 'req', 'Test ', 'Valenzuela City', '2000-01-12', 25, 'Male', 25, '09123456789', 'testing@gmail.com', 'Scholarship', 'hhehe', '69208da3b185d_1763741091.jpg', '69208da3b1c27_1763741091.jpg', '69208da3b1d2e_1763741091.jpg', 'Approved', 'sean req test', 'Valenzuela City', 'Applicant', '2025-11-21 11:04:51', NULL, NULL, NULL),
(48, 'REQ-20251127-48', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2005-04-25', 20, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'asdadsads', '6927f6f16de7c_1764226801.png', '6927f6f16e042_1764226801.png', '6927f6f16e0eb_1764226801.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Applicant', '2025-11-27 02:00:01', NULL, NULL, NULL),
(49, 'REQ-20251127-49', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-10-09', 21, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Financial Assistance', 'adasda', '6928173fbd9c0_1764235071.png', '6928173fbdc05_1764235071.png', '6928173fbddba_1764235071.png', 'Pending', 'Pamantasan ng Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', 'Applicant', '2025-11-27 04:17:51', NULL, NULL, NULL),
(50, 'REQ-20251127-50', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-10-09', 21, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Financial Assistance', 'adasda', '6928175e0d518_1764235102.png', '6928175e0d74b_1764235102.png', '6928175e0d8f9_1764235102.png', 'Pending', 'Pamantasan ng Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', 'Applicant', '2025-11-27 04:18:22', NULL, NULL, NULL),
(51, 'REQ-20251127-51', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-10-09', 21, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Financial Assistance', 'adasda', '692817828b956_1764235138.png', '692817828bba1_1764235138.png', '692817828bd55_1764235138.png', 'Pending', 'Pamantasan ng Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', 'Applicant', '2025-11-27 04:18:58', NULL, NULL, NULL),
(52, 'REQ-20251127-52', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-11-01', 31, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Burial Assistance', '23213', '692817b9dc8b7_1764235193.png', '692817b9dcaca_1764235193.png', '692817b9dcbb7_1764235193.png', 'Rejected', 'Benedict Bustamante', 'ilang ilang', 'Father', '2025-11-27 04:19:53', NULL, NULL, 'Incorrect personal information'),
(53, 'REQ-20251127-53', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-11-20', 21, 'Female', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Educational Assistance', '2312312', '692818c0e1085_1764235456.png', '692818c0e1402_1764235456.png', '692818c0e16d1_1764235456.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Mother', '2025-11-27 04:24:16', NULL, NULL, NULL),
(54, 'REQ-20251127-54', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-10-31', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'wewqwqeqe', '6928194a1dba4_1764235594.png', '6928194a1dde7_1764235594.png', '6928194a1dfa2_1764235594.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Mother', '2025-11-27 04:26:34', NULL, NULL, NULL),
(55, 'REQ-20251127-55', 'Fatima', 'L', 'Ria', '5152 Ilang-ilang Street GSIS', '2025-11-29', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Educational Assistance', 'edqwewqq', '692819b7add81_1764235703.png', '692819b7adff1_1764235703.png', '692819b7ae204_1764235703.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Mother', '2025-11-27 04:28:23', NULL, NULL, NULL),
(56, 'REQ-20251127-56', 'Benedict', 'N', 'Bustamante', 'ilang ilang', '2025-10-29', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Employment', 'wewqeqe', '69281a86441b7_1764235910.png', '69281a86443e4_1764235910.png', '69281a86444ca_1764235910.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Applicant', '2025-11-27 04:31:50', NULL, NULL, NULL),
(57, 'REQ-20251127-57', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-11-01', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Financial Assistance', 'asdadas', '69281b7c54285_1764236156.png', '69281b7c54548_1764236156.odt', '69281b7c54624_1764236156.png', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Father', '2025-11-27 04:35:56', NULL, NULL, NULL),
(58, 'REQ-20251127-58', 'Fatima', 'L', 'Ria', '5152 Ilang-ilang Street GSIS', '2025-11-14', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Burial Assistance', '23131', '69281cb448034_1764236468.png', '69281cb44836b_1764236468.png', '69281cb44861f_1764236468.png', 'Rejected', 'Fatima Llavor Ria', '5152 Ilang-ilang Street GSIS', 'Applicant', '2025-11-27 04:41:08', NULL, NULL, 'Incomplete requirements'),
(59, 'REQ-20251127-59', 'Benedict', 'N', 'Bustamante', 'ilang ilang', '2025-12-06', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Burial Assistance', '213213', '69283078c26fa_1764241528.png', '69283078c2b13_1764241528.png', '69283078c2d55_1764241528.png', 'Approved', 'Benedict Bustamante', 'ilang ilang', 'Applicant', '2025-11-27 06:05:28', NULL, NULL, NULL),
(60, 'REQ-20251127-60', 'Benedict', 'N', 'Bustamante', 'ilang ilang', '2025-11-30', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'adsadsadas', '69283182269a1_1764241794.png', '6928318226c2a_1764241794.png', '6928318226e7b_1764241794.png', 'Approved', 'Fatima Llavor Ria', '5152 Ilang-ilang Street GSIS', 'Father', '2025-11-27 06:09:54', NULL, NULL, NULL),
(61, 'REQ-20251127-61', 'Benedict', 'L', 'Bustamante', 'ilang ilang', '2025-12-04', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'sdasdsa', '692831c8a4b6c_1764241864.png', '692831c8a4d76_1764241864.png', '692831c8a4ec6_1764241864.png', 'Approved', 'Fatima Llavor Ria', '5152 Ilang-ilang Street GSIS', 'Father', '2025-11-27 06:11:04', NULL, NULL, NULL),
(62, 'REQ-20251127-62', 'Benedict', 'sd', 'Bustamante', 'ilang ilang', '2025-10-30', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Burial Assistance', 'sadas', '6928320833b2e_1764241928.png', '6928320833d86_1764241928.png', '6928320833f62_1764241928.png', 'Approved', 'adasd', 'sdad', 'Sibling', '2025-11-27 06:12:08', NULL, NULL, NULL),
(63, 'REQ-20251127-63', 'Pamantasan', 'N', 'Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', '2025-10-30', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Burial Assistance', 'sadsa', '69283319cbbfe_1764242201.png', '69283319cbe39_1764242201.png', '69283319cc014_1764242201.png', 'Approved', 'Pamantasan ng Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', 'Mother', '2025-11-27 06:16:41', NULL, NULL, NULL),
(64, 'REQ-20251127-64', 'Pamantasan', 'N', 'Lungsod ng Valenzuela (Valenzuela City, Metro Manila)', 'Ilang-ilang Street GSIS HILLS Ugong', '2025-11-15', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'sdsa', '692833bfb4c99_1764242367.png', '692833bfb4ecb_1764242367.png', '692833bfb5073_1764242367.png', 'Approved', 'Benedict Bustamante', 'ilang ilang', 'Father', '2025-11-27 06:19:27', NULL, NULL, NULL),
(65, 'REQ-20251127-65', 'damn', 'd', 'yeah', 'asdada', '2025-11-24', 21, 'Female', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Employment', 'dasadsa', '692888c181088_1764264129.exe', '692888c182257_1764264129.exe', '692888c1832a0_1764264129.exe', 'Pending', 'Benedict Bustamante', 'ilang ilang', 'Applicant', '2025-11-27 12:22:09', NULL, NULL, NULL),
(66, 'REQ-20251127-66', 'ben', 'busta', 'mante', 'asda', '0000-00-00', 32, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'asd\r\n', '69288e0668459_1764265478.exe', '69288e066967a_1764265478.exe', '69288e066a751_1764265478.png', 'Pending', 'As', 'dasd', 'Applicant', '2025-11-27 12:44:38', NULL, NULL, NULL),
(67, 'REQ-20251127-67', 'Ako', 'Si', 'Budoy', 'Dyan Dyan Lang Sa Gilid Gilid', '2025-10-27', 21, 'Male', 21, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', 'adsadsa asd saas dsa', '69288fe12a79f_1764265953.png', '69288fe12bab1_1764265953.png', '69288fe12bbae_1764265953.png', 'Pending', 'Dasd', 'Dssadsa', 'Father', '2025-11-27 12:52:33', NULL, NULL, NULL),
(68, 'REQ-20251127-68', 'Benedict', '', 'Bustamante', 'Ilang Ilang', '2025-10-30', 21, 'Male', 12, '09671829634', 'benedictriabustamante@gmail.com', 'Educational Assistance', 'Asdsa', '692895ef47f07_1764267503.png', '692895ef480c8_1764267503.png', '692895ef4821e_1764267503.png', 'Pending', '', '', 'N/A', '2025-11-27 13:18:23', NULL, NULL, NULL),
(69, 'REQ-20251211-69', 'Dfd', '', 'Dfgfds', 'Dfgbfgf', '2000-06-25', 25, 'Female', 8, '09123456789', 'sdfdfd@gmail.com', 'Scholarship', '', '693b27f60af17_1765484534.png', '693b27f60b161_1765484534.png', '693b27f60b231_1765484534.png', 'Pending', '', '', 'N/A', '2025-12-11 15:22:14', NULL, NULL, NULL),
(70, 'REQ-20251211-70', 'Jhgfd', '', 'Dfgfds', 'Dfgbfgf', '2000-06-25', 25, 'Female', 8, '09123456789', 'sdfdfd@gmail.com', 'Scholarship', '', '693b3132a9681_1765486898.png', '693b3132a9784_1765486898.png', '693b3132a9804_1765486898.png', 'Pending', '', '', '', '2025-12-11 16:01:38', NULL, NULL, NULL),
(71, 'REQ-20251211-71', 'Benedict', 'R', 'Bustamante', 'Ilang Ilang', '2005-04-25', 20, 'Male', 7, '09671829634', 'benedictriabustamante@gmail.com', 'Employment', 'Asd', '693b36a075751_1765488288.png', '693b36a0758cb_1765488288.png', '693b36a075aad_1765488288.png', 'undefined', '', '', '', '2025-12-11 16:24:48', NULL, NULL, NULL),
(72, 'REQ-20251211-72', 'Ann', '', 'Escabilla', 'Gsjdjdns', '2001-12-12', 24, 'Male', 23, '09764589637', 'hsbdhhd@gmail.com', 'Scholarship', '', '693b3bc3b7283_1765489603.png', '693b3bc3b7386_1765489603.png', '693b3bc3b742e_1765489603.png', 'undefined', '', '', 'N/A', '2025-12-11 16:46:43', NULL, NULL, NULL),
(73, 'REQ-20251211-73', 'Gail Ann', '', 'Escabilla', 'Gansjskskxks', '2003-12-12', 22, 'Female', 6, '09123456789', 'ydfucvivivv@gmail.com', 'Financial Assistance', '', '693b4bbdc757f_1765493693.jpeg', '693b4bbdc77e9_1765493693.png', '693b4bbdc788d_1765493693.jpg', 'Pending', '', '', 'N/A', '2025-12-11 17:54:53', NULL, NULL, NULL),
(74, 'REQ-20251211-74', 'Sjnsjx', 'Snnsj', 'Snnsjx', 'Hsnxjkskss', '1999-12-12', 26, 'Male', 5, '09123456789', 'ydfucvivivv@gmail.com', 'Employment', '', '693b4c6355b35_1765493859.png', '693b4c6355e45_1765493859.png', '693b4c6355ed7_1765493859.png', 'Pending', '', '', 'N/A', '2025-12-11 17:57:39', '2025-12-10', NULL, NULL),
(75, 'REQ-20251211-75', 'Winnie', 'Jsjdjx', 'Wkdjenxks', 'Bsndnxx', '2005-12-12', 20, 'Female', 5, '09123456789', 'ydfucvivivv@gmail.com', 'Employment', '', '693b4f30dd2ae_1765494576.png', '693b4f30dd457_1765494576.png', '693b4f30dd4f8_1765494576.png', 'undefined', '', '', '', '2025-12-11 18:09:36', '2025-12-12', NULL, NULL),
(76, 'REQ-20251211-76', 'Benedict', '', 'Bustamante', 'Ilang Ilang', '2005-04-25', 20, 'Male', 2, '09671829634', 'benedictriabustamante@gmail.com', 'Scholarship', '', '693b52144fd0c_1765495316.png', '693b52144fed4_1765495316.png', '693b521450169_1765495316.png', 'Pending', '', '', '', '2025-12-11 18:21:56', '2026-01-31', NULL, NULL),
(77, 'REQ-20251212-77', 'Faijah', 'F', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', '', '693ba5760a786_1765516662.png', '693ba5760ad85_1765516662.png', '693ba5760b110_1765516662.png', 'Rejected', '', '', '', '2025-12-12 00:17:42', '2025-12-13', NULL, 'Incomplete requirements'),
(78, 'REQ-20251212-78', 'Sean', 'Patricio', 'Vasquez', 'Bignay St. Balangkas', '2005-09-29', 20, 'Male', 20, '09123456789', 'testing@gmail.com', 'Scholarship', '', '693bbb6862c82_1765522280.jpg', '693bbb686305e_1765522280.jpg', '693bbb6863380_1765522280.jpg', 'undefined', '', '', '', '2025-12-12 01:51:20', '2025-12-15', NULL, NULL),
(79, 'REQ-20251212-79', 'Sean', '', 'Testt', 'Aa', '2002-02-26', 23, 'Female', 22, '09123456789', 'testing@gmail.com', 'Medical Assistance', '', '693bbef990a6a_1765523193.pdf', '693bbef990cbb_1765523193.pdf', '693bbef990e94_1765523193.pdf', 'Pending', '', '', '', '2025-12-12 02:06:33', '2025-12-23', NULL, NULL),
(80, 'REQ-20251212-80', 'Berto', 'Al', 'Bayawak', 'Balangkas', '2007-02-06', 18, 'Male', 18, '09991191911', 'abulenciapeter0977@gmail.com', 'Scholarship', 'None', '693bcc80a1250_1765526656.jpg', '693bcc80a1331_1765526656.jpg', '693bcc80a13b9_1765526656.jpg', 'Pending', '', '', '', '2025-12-12 03:04:16', '2025-12-14', NULL, NULL),
(81, 'REQ-20251212-81', 'Kurt', '', 'Talagtag', '125 B.garcia St. Bisig, Valenzuela City', '2004-08-12', 21, 'Female', 4, '09677647273', 'kurtchristiantalagtag@plv.edu.ph', 'Scholarship', '', '693bf1973d4ba_1765536151.jpg', '693bf1973d7f3_1765536151.jpg', '693bf1973d91c_1765536151.jpg', 'Pending', '', '', 'Mother', '2025-12-12 05:42:31', '2025-12-31', NULL, NULL),
(82, 'REQ-20251212-82', 'Benidict', '', 'Misal', 'Hsjxjjss', '2005-06-22', 20, 'Male', 5, '09123456789', 'ydfucvivivv@gmail.com', 'Employment', '', '693bf8f3ce98b_1765538035.png', '693bf8f3ceb3d_1765538035.png', '693bf8f3cebdb_1765538035.png', 'Pending', '', '', 'N/A', '2025-12-12 06:13:55', '2025-12-13', NULL, NULL),
(83, 'REQ-20251212-83', 'Faijah', 'S', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2005-02-20', 20, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', 'N.a', '693bf977a4db2_1765538167.png', '693bf977a5171_1765538167.jpg', '693bf977a5208_1765538167.png', 'Pending', '', '', '', '2025-12-12 06:16:07', '2025-12-31', NULL, NULL),
(84, 'REQ-20251212-84', 'Faijah', '', 'Nonoy', '52 Daliva Street Karuhatan Valenzuela City', '2000-01-20', 25, 'Female', 20, '09945421889', 'faijahnonoy20@gmail.com', 'Scholarship', 'Sss', '693bfc503c1f6_1765538896.png', '693bfc503c6c1_1765538896.png', '693bfc503cb25_1765538896.png', 'Pending', 'Faijah F Nonoy', '52 Daliva Street Karuhatan Valenzuela City', 'N/A', '2025-12-12 06:28:16', '2025-12-20', NULL, NULL),
(85, 'REQ-20251212-85', 'Winnie', '', 'Wkdjenxks', 'Gsjdjdns', '1986-12-13', 39, 'Female', 15, '09123456789', 'ydfucvivivv@gmail.com', 'Burial Assistance', '', '693c3f5b89ae5_1765556059.png', '693c3f5b89c3a_1765556059.png', '693c3f5b89d2f_1765556059.png', 'Completed', '', '', '', '2025-12-12 11:14:19', '2025-12-15', '2025-12-12', NULL),
(86, 'REQ-20251212-86', 'Test', '', 'Submit', 'Balangkas St', '1981-02-13', 44, 'Male', 35, '09123456789', 'testing@gmail.com', 'Scholarship', 'Hahah', '693c401ee97e2_1765556254.png', '693c401ee9a5c_1765556254.png', '693c401ee9da4_1765556254.png', 'Pending', '', '', '', '2025-12-12 11:17:34', NULL, NULL, NULL);

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
(23, 'REQ-20251104-24', 'Approved', NULL, '2025-11-04 22:16:53'),
(24, 'REQ-20251105-26', 'Approved', NULL, '2025-11-06 09:12:27'),
(25, 'REQ-20251105-26', 'For Pick-up', NULL, '2025-11-06 09:12:48'),
(26, 'REQ-20251105-26', 'Completed', NULL, '2025-11-06 09:13:08'),
(27, 'REQ-20251104-25', 'Rejected', NULL, '2025-11-06 09:14:00'),
(28, 'REQ-20251106-27', 'Approved', NULL, '2025-11-06 09:16:48'),
(29, 'REQ-20251106-27', 'For Pick-up', NULL, '2025-11-06 09:17:39'),
(30, 'REQ-20251106-27', 'Completed', NULL, '2025-11-06 09:18:10'),
(31, 'REQ-20251107-36', 'Rejected', NULL, '2025-11-07 19:06:51'),
(32, 'REQ-20251107-35', 'Approved', NULL, '2025-11-07 19:13:03'),
(33, 'REQ-20251107-35', 'For Pick-up', NULL, '2025-11-07 19:13:10'),
(34, 'REQ-20251107-35', 'Completed', NULL, '2025-11-07 19:13:22'),
(35, 'REQ-20251107-34', 'Approved', NULL, '2025-11-08 09:33:51'),
(36, 'REQ-20251111-3', 'Approved', NULL, '2025-11-12 00:46:16'),
(37, 'REQ-20251111-1', 'Rejected', NULL, '2025-11-12 00:49:59'),
(38, 'REQ-20251111-3', 'For Pick-up', NULL, '2025-11-12 02:20:40'),
(39, 'REQ-20251111-3', 'Completed', NULL, '2025-11-12 02:20:52'),
(40, 'REQ-20251111-2', 'Rejected', NULL, '2025-11-12 02:21:54'),
(41, 'REQ-20251113-27', 'Approved', NULL, '2025-11-13 18:04:32'),
(42, 'REQ-20251113-27', 'For Pick-up', NULL, '2025-11-13 19:02:56'),
(43, 'REQ-20251113-28', 'Approved', NULL, '2025-11-14 08:24:00'),
(44, 'REQ-20251113-28', 'For Pick-up', NULL, '2025-11-14 08:24:09'),
(45, 'REQ-20251114-36', 'Approved', NULL, '2025-11-14 08:29:02'),
(46, 'REQ-20251114-36', 'For Pick-up', NULL, '2025-11-14 08:29:12'),
(47, 'REQ-20251113-29', 'Approve', NULL, '2025-11-14 08:56:35'),
(48, 'REQ-20251104-24', 'For Pick-up', NULL, '2025-11-14 08:56:43'),
(49, 'REQ-20251114-37', 'Approve', NULL, '2025-11-14 08:59:37'),
(50, 'REQ-20251113-35', 'Approved', NULL, '2025-11-14 09:00:08'),
(51, 'REQ-20251105-26', 'Approved', NULL, '2025-11-14 09:04:24'),
(52, 'REQ-20251105-26', 'For Pick-up', NULL, '2025-11-14 09:04:39'),
(53, 'REQ-20251114-38', 'Approved', NULL, '2025-11-14 09:06:29'),
(54, 'REQ-20251113-34', 'Approve', NULL, '2025-11-14 09:06:44'),
(55, 'REQ-20251113-30', 'Rejected', NULL, '2025-11-14 09:10:00'),
(56, 'REQ-20251114-38', 'For Pick-up', NULL, '2025-11-14 09:15:14'),
(57, 'REQ-20251114-39', 'Approved', NULL, '2025-11-14 09:40:32'),
(58, 'REQ-20251114-39', 'For Pick-up', NULL, '2025-11-14 09:40:45'),
(59, 'REQ-20251104-25', 'Approved', NULL, '2025-11-14 10:13:22'),
(60, 'REQ-20251113-31', 'Approved', NULL, '2025-11-14 10:40:21'),
(61, 'REQ-20251114-40', 'Approved', NULL, '2025-11-14 20:20:42'),
(62, 'REQ-20251114-40', 'For Pick-up', NULL, '2025-11-14 20:21:00'),
(63, 'REQ-20251114-40', 'Completed', NULL, '2025-11-14 20:21:19'),
(64, 'REQ-20251115-45', 'Approved', NULL, '2025-11-14 20:42:28'),
(65, 'REQ-20251115-46', 'Approved', NULL, '2025-11-14 21:04:23'),
(66, 'REQ-20251115-46', 'For Pick-up', NULL, '2025-11-14 21:04:38'),
(67, 'REQ-20251114-44', 'Approved', NULL, '2025-11-21 05:59:57'),
(68, 'REQ-20251121-47', 'Approved', NULL, '2025-11-21 11:16:20'),
(69, 'REQ-20251127-64', 'Approved', NULL, '2025-11-27 10:22:23'),
(70, 'REQ-20251127-63', 'Approved', NULL, '2025-11-27 10:50:56'),
(71, 'REQ-20251127-62', 'Approved', NULL, '2025-11-27 10:55:24'),
(72, 'REQ-20251127-61', 'Approved', NULL, '2025-11-27 11:07:02'),
(73, 'REQ-20251127-60', 'Approved', NULL, '2025-11-27 11:07:13'),
(74, 'REQ-20251127-59', 'Approved', NULL, '2025-11-27 11:07:29'),
(75, 'REQ-20251127-58', 'Rejected', NULL, '2025-11-27 12:10:33'),
(76, 'REQ-20251211-72', 'undefined', NULL, '2025-12-11 16:55:39'),
(77, 'REQ-20251115-46', 'Completed', NULL, '2025-12-11 16:56:00'),
(78, 'REQ-20251211-71', 'undefined', NULL, '2025-12-11 16:56:49'),
(79, 'REQ-20251127-52', 'Rejected', NULL, '2025-12-11 17:01:10'),
(80, 'REQ-20251212-78', 'undefined', NULL, '2025-12-12 01:57:39'),
(81, 'REQ-20251211-75', 'undefined', NULL, '2025-12-12 04:18:37'),
(82, 'REQ-20251212-77', 'Rejected - Incomplete requirements (by certigo-bal', NULL, '2025-12-12 05:17:26'),
(83, 'REQ-20251212-80', 'Pending (by certigo-balangkas-admin)', NULL, '2025-12-12 05:17:32'),
(84, 'REQ-20251212-85', 'Approved (by certigo-balangkas-admin)', NULL, '2025-12-12 11:17:04'),
(85, 'REQ-20251212-85', 'For Pick-up (by certigo-balangkas-admin)', NULL, '2025-12-12 11:17:30'),
(86, 'REQ-20251212-85', 'Completed (by certigo-balangkas-admin)', NULL, '2025-12-12 11:18:19');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `request_history`
--
ALTER TABLE `request_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
