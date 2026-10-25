-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2026 at 07:15 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prof_matin_eye_hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `operation_category`
--

CREATE TABLE `operation_category` (
  `id` int NOT NULL,
  `name` varchar(250) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `operation_category`
--

INSERT INTO `operation_category` (`id`, `name`, `created_at`) VALUES
(2, 'Eye', '2019-09-04 15:11:44'),
(3, 'Eye - SEH', '2024-03-16 03:55:27'),
(4, 'Mazed Molla Eye Hospital', '2024-03-23 14:31:38'),
(5, 'IOL', '2024-06-29 07:07:29');

-- --------------------------------------------------------

--
-- Table structure for table `operation_sub`
--

CREATE TABLE `operation_sub` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(250) COLLATE utf8mb3_unicode_ci NOT NULL,
  `single_rate` decimal(10,2) NOT NULL,
  `both_rate` decimal(10,2) NOT NULL,
  `commission_type` varchar(250) COLLATE utf8mb3_unicode_ci NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `commission_per` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `operation_sub`
--

INSERT INTO `operation_sub` (`id`, `category_id`, `name`, `single_rate`, `both_rate`, `commission_type`, `commission_amount`, `commission_per`, `created_at`) VALUES
(2, 2, 'SICS with PCIOL-Galaxy lens (Made in America)', 10000.00, 0.00, 'IOL', 400.00, 0.00, '2023-09-16 03:49:12'),
(4, 2, 'SICS with PCIOL-DGR lens (Made in America)', 12000.00, 0.00, 'IOL', 400.00, 0.00, '2023-09-16 03:49:32'),
(8, 2, 'Phaco with PCIOL-Alcon Lens (Made in America)', 36000.00, 0.00, 'IOL', 400.00, 0.00, '2023-09-16 03:50:25'),
(9, 2, 'Phaco with PCIOL-HOYA_150 Lens (Made in Japan)', 50000.00, 0.00, 'IOL', 0.00, 10.00, '2025-08-12 03:25:50'),
(10, 2, 'CHALAZION', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2023-09-16 03:51:00'),
(11, 2, 'EXICISION OF NESAL PTERYGIUM WITH FREE CONJ. AUTO GRAFTING', 10000.00, 10000.00, 'Minor', 0.00, 30.00, '2023-12-30 07:14:55'),
(12, 2, 'DCR', 12000.00, 0.00, 'Minor', 0.00, 30.00, '2026-04-07 08:48:03'),
(13, 2, 'DCT', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2024-04-22 01:52:20'),
(14, 2, 'DAILATION', 1000.00, 2000.00, 'Minor', 0.00, 30.00, '2023-09-16 03:52:15'),
(16, 2, 'Excision', 6000.00, 0.00, 'Minor', 0.00, 30.00, '2023-10-09 03:33:10'),
(18, 2, 'Phaco with PCIOL- ALCON_ACRYSOFT IQ LENS(MADE IN USA)', 45000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:18:48'),
(19, 2, 'SICS with PCIOL- CIMA LENS(MADE IN USA)', 16000.00, 0.00, 'IOL', 400.00, 0.00, '2023-10-12 03:19:43'),
(20, 2, 'Phaco with SICS with PCIOL- ALCON LENS (MADE IN USA)', 30000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:21:03'),
(23, 2, 'Phaco with PCIOL- ALCON_ACRYSOFT LENS(MADE IN USA)', 38000.00, 0.00, 'IOL', 400.00, 0.00, '2023-09-16 03:54:24'),
(24, 2, 'SICS with PCIOL- GLOWFOLD LENS (MADE IN USA)', 10000.00, 0.00, 'IOL', 400.00, 0.00, '2023-09-16 03:54:37'),
(29, 2, 'Phaco with PCIOL-HOYA (151 Aspheric) Lens (Made in Japan)', 35000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:23:06'),
(30, 2, 'Phaco with PCIOL_ ALCON_ACRYSOFT IQ LENS(MADE IN USA)', 50000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:23:30'),
(31, 2, 'EXICISION OF NESAL PTERYGIUM WITH FREE CONJ. NON_ GRAFTING', 6000.00, 12000.00, 'Minor', 0.00, 30.00, '2023-09-16 03:56:04'),
(32, 2, 'Phaco with PCIOL-MBI Lens (Made in America)', 42000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:24:24'),
(37, 2, 'Phaco with PCIOL-CURAMED Lens (Made in Netherland)', 32000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:27:30'),
(40, 2, 'Aurolab (India)', 8000.00, 0.00, 'IOL', 400.00, 0.00, '2024-07-03 04:37:02'),
(41, 2, 'Excellence (Indo America)', 11000.00, 0.00, 'IOL', 400.00, 0.00, '2026-04-07 08:44:43'),
(42, 2, 'US IOL (USA)', 19000.00, 0.00, 'IOL', 400.00, 0.00, '2024-07-03 04:36:04'),
(43, 2, 'CIMA (USA)', 19000.00, 0.00, 'IOL', 400.00, 0.00, '2024-07-03 04:35:52'),
(44, 2, 'CURAMED (Netherland)', 21000.00, 0.00, 'IOL', 400.00, 0.00, '2026-04-07 04:23:35'),
(45, 2, 'Dermoid Cyst', 10000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:22:54'),
(46, 2, 'Drainnage', 3500.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:23:55'),
(47, 2, 'Enucleation', 8000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:22:16'),
(49, 2, 'Foregin Body (Under Micros)', 1000.00, 0.00, 'IOL', 0.00, 30.00, '2023-10-12 03:38:33'),
(51, 2, 'Probing', 6000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:24:24'),
(52, 2, 'Pterygium', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2024-07-14 07:40:35'),
(53, 2, 'Pterygium with Auto Graft', 10000.00, 16000.00, 'Minor', 0.00, 30.00, '2024-08-08 05:26:07'),
(54, 2, 'Removal Of Lense', 6000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:26:04'),
(55, 2, 'Repair', 12000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:25:36'),
(56, 2, 'Stich Removal ( Under Micros)', 2500.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:25:26'),
(57, 2, 'Trab', 10000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:25:08'),
(59, 2, 'Phaco with Auro Preloaded, Hydro (India)', 18000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:42:24'),
(60, 2, 'Phaco With auro Flex Foldable Preloaded, Hydro (India)', 22000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:44:17'),
(61, 2, 'Phaco with Rayner Spheric preloaded (Ray One) UK', 30000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:45:30'),
(62, 2, 'Phaco With Rayner Aspheric Preloaded (UK)', 35000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:46:27'),
(63, 2, 'Pheco with Rayner Hydro. Aspheric preloaded (UK)', 40000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:48:48'),
(64, 2, 'Pheco with Alcon IQ Foladable (USA)', 50000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:50:15'),
(65, 2, 'Pheco with Alcon Acrysoft Single piece (USA)', 35000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 03:51:02'),
(69, 2, 'Pheco with MBI Yellow Foldable (USA)', 45000.00, 0.00, 'IOL', 0.00, 10.00, '2023-10-12 04:08:26'),
(70, 2, 'Pterygium (Camp)', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2026-08-04 16:37:54'),
(71, 2, 'Pterygium with Auto Graft (Camp)', 8500.00, 17000.00, 'Minor', 0.00, 30.00, '2026-08-04 16:39:50'),
(72, 2, 'DCT (Camp)', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2024-07-01 04:54:39'),
(73, 2, 'DCR (camp)', 8000.00, 0.00, 'Minor', 0.00, 30.00, '2025-04-22 05:09:04'),
(74, 2, 'SICS With PCIOL- Exelens (Indoneshia)', 7000.00, 14000.00, 'IOL', 400.00, 0.00, '2024-02-12 17:09:10'),
(76, 2, 'Excision (Camp)', 5000.00, 10000.00, 'Minor', 0.00, 30.00, '2026-08-04 16:36:10'),
(77, 2, 'Ptosis Correclim', 10000.00, 20000.00, 'Minor', 0.00, 30.00, '2024-02-12 17:24:45'),
(78, 2, 'Optima (India)', 5500.00, 0.00, 'IOL', 400.00, 0.00, '2024-02-12 17:09:27'),
(79, 2, 'Excellence (Camp)', 5000.00, 0.00, 'IOL', 400.00, 0.00, '2024-02-12 17:09:39'),
(80, 2, 'Remove of Lens', 5000.00, 0.00, 'Minor', 0.00, 30.00, '2024-02-12 17:24:32'),
(81, 2, 'OSI Lens', 19000.00, 0.00, 'IOL', 400.00, 0.00, '2024-07-03 04:36:30'),
(82, 2, 'IOL', 400.00, 0.00, 'IOL', 0.00, 0.00, '2024-02-12 17:09:56'),
(83, 2, 'C.F.B', 1000.00, 2000.00, 'Minor', 0.00, 0.00, '2024-03-16 04:05:20'),
(84, 3, 'PCIOL- CURAMED Lens (Made in Netherland)', 22000.00, 0.00, 'IOL', 400.00, 0.00, '2024-03-16 04:01:56'),
(85, 3, 'SICS with PCIOL -  CIMA LENS(MADE IN USA)', 20000.00, 0.00, 'IOL', 400.00, 0.00, '2024-03-16 03:59:39'),
(86, 2, 'Aurolab (India) - Camp', 6000.00, 0.00, 'IOL', 400.00, 0.00, '2024-03-31 05:10:42'),
(87, 3, 'Phaco With Alcon AcrySof IQ UltraSert Pre-loaded', 55000.00, 0.00, 'IOL', 0.00, 10.00, '2024-04-27 07:07:20'),
(88, 3, 'Membranous Conjunctivities', 1500.00, 3000.00, 'Minor', 0.00, 0.00, '2024-07-10 05:37:35'),
(89, 2, 'Repair of Conjunctival', 2000.00, 4000.00, 'Minor', 0.00, 0.00, '2024-07-10 05:38:09'),
(90, 3, 'CIMA LENS(MADE IN USA)', 20000.00, 40000.00, 'IOL', 400.00, 0.00, '2024-07-10 05:37:25'),
(91, 3, 'SICS with PCIOL - OSI LENS(MADE IN USA)', 17000.00, 34000.00, 'IOL', 400.00, 0.00, '2024-07-10 05:38:28'),
(92, 3, 'SICS with PCIOL - OSI LENS(MADE IN USA) - CAMP', 15000.00, 30000.00, 'iol', 400.00, 0.00, '2024-07-13 04:11:15'),
(93, 2, 'Retina Injection', 6000.00, 12000.00, '', 0.00, 0.00, '2024-08-18 03:40:31'),
(94, 2, 'SICS with PCIOL- US LENS(MADE IN USA)', 16000.00, 32000.00, 'iol', 400.00, 0.00, '2024-08-24 07:01:55'),
(95, 3, 'Removal of Extra C.F.B Fungal', 4000.00, 0.00, 'minor', 0.00, 30.00, '2024-08-25 05:34:47'),
(96, 2, 'Tarsorraphy', 8000.00, 8000.00, 'Minor', 0.00, 30.00, '2024-11-11 06:31:12'),
(97, 2, 'Retina Laser', 4000.00, 8000.00, '', 0.00, 0.00, '2024-11-30 16:02:39'),
(98, 3, 'Repair of Lid', 3000.00, 6000.00, 'minor', 0.00, 0.00, '2025-02-17 06:28:13'),
(99, 2, 'Entropion Correction', 5000.00, 10000.00, 'minor', 0.00, 30.00, '2025-05-05 02:19:09'),
(100, 5, 'MDR LENS (MADE IN USA)', 18000.00, 24000.00, 'IOL', 0.00, 0.00, '2026-04-07 08:47:44'),
(101, 3, 'Dacryocystorhinostomy (DCR) with Tube', 13000.00, 26000.00, 'minor', 0.00, 0.00, '2025-06-18 05:11:06'),
(102, 3, 'Corneal Scraping', 2500.00, 5000.00, 'minor', 0.00, 0.00, '2025-07-07 04:41:21'),
(103, 2, 'Epilation', 3000.00, 6000.00, 'Minor', 0.00, 40.00, '2025-07-23 07:03:45'),
(104, 2, 'Cimflex Flod USA', 30000.00, 0.00, 'iol', 0.00, 0.00, '2025-08-12 03:24:07'),
(105, 2, 'Eyelid (USA)', 22000.00, 44000.00, 'IOL', 400.00, 0.00, '2026-08-09 10:02:14'),
(106, 2, 'Eyekon (USA)', 16000.00, 32000.00, 'iol', 400.00, 0.00, '2025-09-30 09:46:30'),
(107, 5, 'MDR Lens', 12000.00, 24000.00, 'IOL', 400.00, 0.00, '2026-04-28 08:59:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `operation_category`
--
ALTER TABLE `operation_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_sub`
--
ALTER TABLE `operation_sub`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `operation_category`
--
ALTER TABLE `operation_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `operation_sub`
--
ALTER TABLE `operation_sub`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
