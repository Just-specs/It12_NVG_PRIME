-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 06:04 PM
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
-- Database: `dispatch`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `email`, `mobile`, `company`, `created_at`, `updated_at`) VALUES
(1, '819 VENTURE', '819venture@example.com', '09148229020', '819 VENTURE', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(2, 'AGRI EXIM', 'agriexim@example.com', '09409226088', 'AGRI EXIM', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(4, 'F2 LIZHEN', 'f2lizhen@example.com', '09869302124', 'F2 LIZHEN', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(6, 'FRUIT WHARF', 'fruitwharf@example.com', '09747236888', 'FRUIT WHARF', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(7, 'GENTLE SUPREME', 'gentlesupreme@example.com', '09632872774', 'GENTLE SUPREME', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(8, 'GLOBAL FINELINE', 'globalfineline@example.com', '09974777440', 'GLOBAL FINELINE', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(9, 'GOOD FARMER CAGANGOHAN', 'goodfarmercagangohan@example.com', '09643425077', 'GOOD FARMER CAGANGOHAN', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(11, 'GSL', 'gsl@example.com', '09497248893', 'GSL', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(12, 'GSPI', 'gspi@example.com', '09316983202', 'GSPI', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(13, 'HUSTLING', 'hustling@example.com', '09674436852', 'HUSTLING', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(14, 'LONGHAUL', 'longhaul@example.com', '09666377995', 'LONGHAUL', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(15, 'PRIMEXYNERGIES', 'primexynergies@example.com', '09357643038', 'PRIMEXYNERGIES', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(16, 'RIGHTBOX LASANG', 'rightboxlasang@example.com', '09532490012', 'RIGHTBOX LASANG', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(17, 'SHARBATLY', 'sharbatly@example.com', '09796275082', 'SHARBATLY', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(18, 'TOTAL', 'total@example.com', '09174125110', 'TOTAL', '2025-12-13 06:10:15', '2025-12-13 06:10:15'),
(19, 'TRI-STAR', 'tristar@example.com', '09623544781', 'TRI-STAR', '2025-12-13 06:10:15', '2025-12-13 06:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `client_notifications`
--

CREATE TABLE `client_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trip_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `notification_type` enum('assignment','in-transit','delay','completed') NOT NULL,
  `message` text NOT NULL,
  `method` enum('sms','email','call') NOT NULL,
  `sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_notifications`
--

INSERT INTO `client_notifications` (`id`, `trip_id`, `client_id`, `notification_type`, `message`, `method`, `sent`, `created_at`, `updated_at`) VALUES
(1, 50, 6, 'in-transit', 'Your delivery is now in transit.', 'sms', 0, '2025-12-13 06:22:42', '2025-12-13 06:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_requests`
--

CREATE TABLE `delivery_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `contact_method` enum('mobile','email','group_chat') NOT NULL,
  `atw_reference` varchar(255) NOT NULL,
  `eir_number` varchar(50) DEFAULT NULL,
  `booking_number` varchar(50) DEFAULT NULL,
  `container_number` varchar(50) DEFAULT NULL,
  `seal_number` varchar(50) DEFAULT NULL,
  `pickup_location` varchar(255) NOT NULL,
  `delivery_location` varchar(255) NOT NULL,
  `container_size` varchar(255) NOT NULL,
  `container_type` varchar(255) NOT NULL,
  `preferred_schedule` datetime NOT NULL,
  `status` enum('pending','verified','assigned','in-transit','completed','cancelled','archived') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `atw_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_requests`
--

INSERT INTO `delivery_requests` (`id`, `client_id`, `contact_method`, `atw_reference`, `eir_number`, `booking_number`, `container_number`, `seal_number`, `pickup_location`, `delivery_location`, `container_size`, `container_type`, `preferred_schedule`, `status`, `notes`, `atw_verified`, `created_at`, `updated_at`, `archived_at`) VALUES
(1, 19, 'mobile', 'ATW-54242', NULL, NULL, 'TEMU 831432-6', NULL, 'DARONG', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-10-08 14:18:00', 'archived', 'EMPTY/CMA - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(2, 2, 'mobile', 'ATW-72892', NULL, NULL, 'CMAU 885124-6', NULL, 'TAGUM', 'CARMEN', '40ft', 'standard', '2025-12-11 06:07:00', 'completed', 'EMPTY RETURN/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(3, 8, 'mobile', 'ATW-47237', NULL, NULL, 'MSCU 820163-5', NULL, 'BAGUIO DISTRICT', 'DARONG', '40ft', 'standard', '2025-10-24 06:22:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(4, 6, 'mobile', 'ATW-42727', NULL, NULL, 'CMAU 851848-5', NULL, 'TUBOD', 'BAGUIO DISTRICT', '40ft', 'refrigerated', '2025-10-26 07:42:00', 'completed', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(5, 1, 'mobile', 'ATW-48185', NULL, NULL, 'TEMU 877404-3', NULL, 'TUBOD', 'CARMEN', '40ft', 'refrigerated', '2025-09-08 06:47:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(6, 2, 'mobile', 'ATW-39269', NULL, NULL, 'MSCU 824127-2', NULL, 'DARONG', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-12-25 16:42:00', 'completed', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(7, 15, 'mobile', 'ATW-69112', NULL, NULL, 'WHSU 876715-4', NULL, 'CARMEN', 'DARONG', '40ft', 'standard', '2025-10-25 10:00:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(8, 2, 'mobile', 'ATW-55209', NULL, NULL, 'TEMU 829078-5', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-09-11 17:00:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(9, 13, 'mobile', 'ATW-32146', NULL, NULL, 'WHSU 807292-8', NULL, 'BAGUIO DISTRICT', 'DICT', '40ft', 'standard', '2025-10-08 14:40:00', 'completed', 'LADEN/EVERGREEN - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(10, 2, 'mobile', 'ATW-41543', NULL, NULL, 'CMAU 852402-7', NULL, 'BAGUIO DISTRICT', 'DICT', '40ft', 'standard', '2025-10-15 17:39:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(11, 4, 'mobile', 'ATW-21572', NULL, NULL, 'WHSU 804761-3', NULL, 'TAGUM', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-11-23 15:51:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(12, 15, 'mobile', 'ATW-60514', NULL, NULL, 'MSCU 854526-5', NULL, 'BAGUIO DISTRICT', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-09-01 17:28:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(13, 4, 'mobile', 'ATW-59240', NULL, NULL, 'CMAU 833592-8', NULL, 'DICT', 'DARONG', '40ft', 'refrigerated', '2025-10-28 16:50:00', 'archived', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(14, 7, 'mobile', 'ATW-58095', NULL, NULL, 'TEMU 873974-1', NULL, 'DICT', 'KAUSWAGAN', '20ft', 'standard', '2025-11-07 15:24:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(15, 2, 'mobile', 'ATW-68301', NULL, NULL, 'CMAU 846916-5', NULL, 'KAUSWAGAN', 'TUBOD', '40ft', 'standard', '2025-09-16 15:04:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(16, 18, 'mobile', 'ATW-32765', NULL, NULL, 'MSCU 895623-2', NULL, 'PANABO', 'TAGUM', '40ft', 'standard', '2025-10-16 14:36:00', 'archived', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(17, 4, 'mobile', 'ATW-27607', NULL, NULL, 'CMAU 814860-2', NULL, 'BAGUIO DISTRICT', 'TAGUM', '40ft', 'standard', '2025-11-14 14:10:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(18, 4, 'mobile', 'ATW-30092', NULL, NULL, 'WHSU 845396-1', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-12-10 15:39:00', 'completed', 'EMPTY/CMA - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(19, 13, 'mobile', 'ATW-68571', NULL, NULL, 'MSCU 859956-5', NULL, 'DARONG', 'TAGUM', '40ft', 'refrigerated', '2025-10-28 10:50:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(20, 19, 'mobile', 'ATW-22886', NULL, NULL, 'CMAU 850353-4', NULL, 'TUBOD', 'DARONG', '20ft', 'refrigerated', '2025-11-27 13:40:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(21, 16, 'mobile', 'ATW-45793', NULL, NULL, 'WHSU 840671-8', NULL, 'BAGUIO DISTRICT', 'CARMEN', '40ft', 'refrigerated', '2025-09-19 11:13:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(22, 2, 'mobile', 'ATW-37051', NULL, NULL, 'CMAU 806219-1', NULL, 'TAGUM', 'DICT', '20ft', 'standard', '2025-11-28 08:07:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(23, 12, 'mobile', 'ATW-36078', NULL, NULL, 'TEMU 821170-3', NULL, 'TUBOD', 'DICT', '20ft', 'refrigerated', '2025-10-03 13:52:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(24, 6, 'mobile', 'ATW-65061', NULL, NULL, 'WHSU 817820-5', NULL, 'DARONG', 'DICT', '40ft', 'refrigerated', '2025-09-28 16:01:00', 'archived', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(25, 2, 'mobile', 'ATW-20102', NULL, NULL, 'CMAU 842264-2', NULL, 'TUBOD', 'PANABO', '20ft', 'standard', '2025-12-13 11:36:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(26, 4, 'mobile', 'ATW-69693', NULL, NULL, 'WHSU 845855-2', NULL, 'DARONG', 'PANABO', '20ft', 'refrigerated', '2025-11-14 09:03:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(27, 11, 'mobile', 'ATW-11612', NULL, NULL, 'CMAU 852561-4', NULL, 'KAUSWAGAN', 'DARONG', '20ft', 'standard', '2025-11-29 11:41:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(28, 2, 'mobile', 'ATW-36244', NULL, NULL, 'TEMU 810986-2', NULL, 'TUBOD', 'TAGUM', '20ft', 'refrigerated', '2025-09-26 17:00:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(29, 16, 'mobile', 'ATW-18316', NULL, NULL, 'MSCU 829906-3', NULL, 'KAUSWAGAN', 'DARONG', '40ft', 'standard', '2025-11-11 15:08:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(30, 9, 'mobile', 'ATW-74205', NULL, NULL, 'MSCU 875799-2', NULL, 'TAGUM', 'DARONG', '20ft', 'standard', '2025-09-30 17:13:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(31, 11, 'mobile', 'ATW-78581', NULL, NULL, 'WHSU 855204-6', NULL, 'TAGUM', 'PANABO', '20ft', 'standard', '2025-10-09 09:07:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(32, 19, 'mobile', 'ATW-54426', NULL, NULL, 'WHSU 891203-4', NULL, 'CARMEN', 'PANABO', '20ft', 'standard', '2025-12-28 15:49:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(33, 6, 'mobile', 'ATW-88158', NULL, NULL, 'TEMU 895959-8', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'standard', '2025-09-29 14:04:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(34, 6, 'mobile', 'ATW-38563', NULL, NULL, 'CMAU 880054-1', NULL, 'TUBOD', 'KAUSWAGAN', '20ft', 'standard', '2025-09-27 10:49:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(35, 12, 'mobile', 'ATW-30159', NULL, NULL, 'CMAU 817548-2', NULL, 'DICT', 'PANABO', '20ft', 'standard', '2025-10-20 12:14:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(36, 14, 'mobile', 'ATW-40623', NULL, NULL, 'MSCU 827490-1', NULL, 'DARONG', 'DICT', '40ft', 'refrigerated', '2025-10-30 15:00:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(37, 11, 'mobile', 'ATW-33219', NULL, NULL, 'MSCU 844734-4', NULL, 'CARMEN', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-11-05 15:25:00', 'archived', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(38, 12, 'mobile', 'ATW-26783', NULL, NULL, 'CMAU 830802-3', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'refrigerated', '2025-12-01 09:56:00', 'completed', 'EMPTY/CMA - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(39, 16, 'mobile', 'ATW-86992', NULL, NULL, 'TEMU 805306-6', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'standard', '2025-11-14 09:25:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(40, 9, 'mobile', 'ATW-95166', NULL, NULL, 'CMAU 826349-5', NULL, 'PANABO', 'TAGUM', '20ft', 'standard', '2025-12-07 06:47:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(41, 18, 'mobile', 'ATW-44023', NULL, NULL, 'WHSU 833321-7', NULL, 'CARMEN', 'PANABO', '20ft', 'standard', '2025-09-20 12:49:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 03:34:24', NULL),
(42, 1, 'mobile', 'ATW-50855', NULL, NULL, 'CMAU 894500-5', NULL, 'TAGUM', 'DARONG', '20ft', 'refrigerated', '2025-10-07 17:52:00', 'archived', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(43, 7, 'mobile', 'ATW-87275', NULL, NULL, 'MSCU 806725-1', NULL, 'TUBOD', 'TAGUM', '40ft', 'standard', '2025-10-18 08:37:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(44, 2, 'mobile', 'ATW-17576', NULL, NULL, 'MSCU 876264-2', NULL, 'BAGUIO DISTRICT', 'DARONG', '40ft', 'standard', '2025-10-02 07:37:00', 'archived', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(45, 16, 'mobile', 'ATW-78042', NULL, NULL, 'TEMU 812112-7', NULL, 'KAUSWAGAN', 'TAGUM', '40ft', 'refrigerated', '2025-11-15 12:01:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(46, 2, 'mobile', 'ATW-68034', NULL, NULL, 'MSCU 866512-6', NULL, 'KAUSWAGAN', 'CARMEN', '20ft', 'refrigerated', '2025-10-10 15:50:00', 'completed', 'EMPTY/CMA - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(47, 13, 'mobile', 'ATW-88843', NULL, NULL, 'WHSU 822350-4', NULL, 'TAGUM', 'KAUSWAGAN', '40ft', 'standard', '2025-10-17 16:02:00', 'archived', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(48, 9, 'mobile', 'ATW-42435', NULL, NULL, 'MSCU 867863-6', NULL, 'TUBOD', 'DICT', '20ft', 'standard', '2025-12-26 08:50:00', 'pending', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(49, 9, 'mobile', 'ATW-63231', NULL, NULL, 'TEMU 889805-4', NULL, 'TUBOD', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-10-15 13:37:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(50, 6, 'mobile', 'ATW-18286', NULL, NULL, 'CMAU 831673-2', NULL, 'PANABO', 'TAGUM', '20ft', 'standard', '2025-10-11 08:12:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(51, 4, 'mobile', 'ATW-26098', NULL, NULL, 'TEMU 818546-7', NULL, 'TUBOD', 'CARMEN', '40ft', 'refrigerated', '2025-10-18 07:30:00', 'completed', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(52, 19, 'mobile', 'ATW-98403', NULL, NULL, 'MSCU 838104-5', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'standard', '2025-12-21 06:46:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(53, 8, 'mobile', 'ATW-42151', NULL, NULL, 'TEMU 828120-1', NULL, 'KAUSWAGAN', 'TAGUM', '40ft', 'refrigerated', '2025-11-01 12:19:00', 'completed', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(54, 7, 'mobile', 'ATW-81658', NULL, NULL, 'CMAU 866306-5', NULL, 'DICT', 'CARMEN', '40ft', 'standard', '2025-12-13 16:03:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(55, 2, 'mobile', 'ATW-73282', NULL, NULL, 'CMAU 863685-6', NULL, 'TAGUM', 'DARONG', '40ft', 'refrigerated', '2025-10-29 10:56:00', 'archived', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(56, 9, 'mobile', 'ATW-79929', NULL, NULL, 'TEMU 827385-1', NULL, 'CARMEN', 'TUBOD', '20ft', 'standard', '2025-12-01 07:05:00', 'completed', 'LADEN/EVERGREEN - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(57, 13, 'mobile', 'ATW-11854', NULL, NULL, 'WHSU 891175-2', NULL, 'BAGUIO DISTRICT', 'CARMEN', '40ft', 'refrigerated', '2025-10-22 14:04:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(58, 4, 'mobile', 'ATW-46916', NULL, NULL, 'TEMU 897724-1', NULL, 'KAUSWAGAN', 'TAGUM', '40ft', 'standard', '2025-11-14 11:45:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(59, 6, 'mobile', 'ATW-41958', NULL, NULL, 'WHSU 884548-3', NULL, 'CARMEN', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-10-30 12:13:00', 'completed', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(60, 17, 'mobile', 'ATW-39918', NULL, NULL, 'TEMU 811979-6', NULL, 'CARMEN', 'TAGUM', '20ft', 'standard', '2025-09-25 17:17:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(61, 17, 'mobile', 'ATW-48750', NULL, NULL, 'TEMU 893778-1', NULL, 'DICT', 'CARMEN', '40ft', 'refrigerated', '2025-09-05 07:33:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(62, 2, 'mobile', 'ATW-95677', NULL, NULL, 'WHSU 836517-5', NULL, 'DARONG', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-09-08 11:45:00', 'completed', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(63, 2, 'mobile', 'ATW-83152', NULL, NULL, 'WHSU 829096-5', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'standard', '2025-10-19 12:33:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(64, 6, 'mobile', 'ATW-24504', NULL, NULL, 'CMAU 846525-7', NULL, 'PANABO', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-11-18 11:57:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(65, 9, 'mobile', 'ATW-53196', NULL, NULL, 'MSCU 849623-4', NULL, 'TAGUM', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-10-20 17:08:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(66, 18, 'mobile', 'ATW-97901', NULL, NULL, 'MSCU 810056-3', NULL, 'PANABO', 'TAGUM', '40ft', 'standard', '2025-09-29 11:52:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(67, 4, 'mobile', 'ATW-86147', NULL, NULL, 'WHSU 869064-8', NULL, 'CARMEN', 'DARONG', '20ft', 'refrigerated', '2025-12-23 14:54:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(68, 11, 'mobile', 'ATW-59831', NULL, NULL, 'TEMU 858454-2', NULL, 'PANABO', 'DICT', '40ft', 'refrigerated', '2025-10-22 13:41:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(69, 8, 'mobile', 'ATW-12338', NULL, NULL, 'WHSU 895781-1', NULL, 'TAGUM', 'PANABO', '20ft', 'standard', '2025-09-09 15:27:00', 'archived', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(70, 9, 'mobile', 'ATW-22040', NULL, NULL, 'WHSU 824005-3', NULL, 'PANABO', 'TUBOD', '20ft', 'standard', '2025-10-26 10:13:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(71, 12, 'mobile', 'ATW-82846', NULL, NULL, 'MSCU 804039-4', NULL, 'TUBOD', 'PANABO', '40ft', 'standard', '2025-10-17 17:28:00', 'completed', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(72, 13, 'mobile', 'ATW-98709', NULL, NULL, 'TEMU 891501-4', NULL, 'TUBOD', 'PANABO', '40ft', 'standard', '2025-11-04 09:25:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(73, 12, 'mobile', 'ATW-21642', NULL, NULL, 'WHSU 849332-8', NULL, 'TAGUM', 'DARONG', '40ft', 'standard', '2025-10-10 10:18:00', 'archived', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(74, 12, 'mobile', 'ATW-41306', NULL, NULL, 'MSCU 813651-5', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'refrigerated', '2025-11-09 14:08:00', 'completed', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(75, 4, 'mobile', 'ATW-67906', NULL, NULL, 'WHSU 866394-1', NULL, 'DICT', 'DARONG', '40ft', 'standard', '2025-09-22 17:34:00', 'archived', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(76, 8, 'mobile', 'ATW-74000', NULL, NULL, 'CMAU 899178-8', NULL, 'BAGUIO DISTRICT', 'KAUSWAGAN', '40ft', 'standard', '2025-10-24 15:42:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(77, 4, 'mobile', 'ATW-13028', NULL, NULL, 'MSCU 866123-8', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'refrigerated', '2025-12-03 13:28:00', 'completed', 'LADEN/EVERGREEN - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(78, 13, 'mobile', 'ATW-11844', NULL, NULL, 'WHSU 881331-7', NULL, 'BAGUIO DISTRICT', 'TUBOD', '40ft', 'refrigerated', '2025-10-06 17:25:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(79, 4, 'mobile', 'ATW-57193', NULL, NULL, 'WHSU 857613-6', NULL, 'PANABO', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-12-05 09:33:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(80, 17, 'mobile', 'ATW-80506', NULL, NULL, 'MSCU 811212-4', NULL, 'KAUSWAGAN', 'PANABO', '40ft', 'standard', '2025-11-10 11:51:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(81, 11, 'mobile', 'ATW-40491', NULL, NULL, 'TEMU 812365-1', NULL, 'PANABO', 'KAUSWAGAN', '20ft', 'standard', '2025-12-30 09:53:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(82, 9, 'mobile', 'ATW-86176', NULL, NULL, 'CMAU 859428-8', NULL, 'DARONG', 'TUBOD', '20ft', 'refrigerated', '2025-10-14 08:19:00', 'completed', 'EMPTY/CMA - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(83, 4, 'mobile', 'ATW-32674', NULL, NULL, 'TEMU 840402-3', NULL, 'KAUSWAGAN', 'DICT', '20ft', 'refrigerated', '2025-12-27 15:28:00', 'pending', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(84, 14, 'mobile', 'ATW-13421', NULL, NULL, 'MSCU 840729-3', NULL, 'DICT', 'TAGUM', '40ft', 'standard', '2025-12-04 06:55:00', 'completed', 'EMPTY/CMA - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(85, 14, 'mobile', 'ATW-67100', NULL, NULL, 'TEMU 803011-4', NULL, 'DARONG', 'TAGUM', '40ft', 'standard', '2025-10-08 11:26:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(86, 18, 'mobile', 'ATW-88544', NULL, NULL, 'CMAU 829570-6', NULL, 'DICT', 'CARMEN', '20ft', 'standard', '2025-11-01 10:42:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(87, 12, 'mobile', 'ATW-70577', NULL, NULL, 'WHSU 808831-4', NULL, 'TAGUM', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-10-01 13:33:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(88, 13, 'mobile', 'ATW-85008', NULL, NULL, 'WHSU 874439-2', NULL, 'CARMEN', 'DARONG', '20ft', 'refrigerated', '2025-11-07 13:13:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(89, 8, 'mobile', 'ATW-80818', NULL, NULL, 'MSCU 845603-3', NULL, 'PANABO', 'TUBOD', '20ft', 'standard', '2025-09-22 06:44:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(90, 19, 'mobile', 'ATW-73617', NULL, NULL, 'WHSU 831335-4', NULL, 'TAGUM', 'CARMEN', '20ft', 'standard', '2025-10-10 15:40:00', 'archived', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(91, 4, 'mobile', 'ATW-12266', NULL, NULL, 'MSCU 858662-1', NULL, 'DICT', 'TAGUM', '20ft', 'refrigerated', '2025-10-20 17:00:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(92, 1, 'mobile', 'ATW-19950', NULL, NULL, 'MSCU 809928-2', NULL, 'CARMEN', 'BAGUIO DISTRICT', '40ft', 'refrigerated', '2025-11-14 15:54:00', 'completed', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(93, 13, 'mobile', 'ATW-63946', NULL, NULL, 'CMAU 868099-2', NULL, 'DARONG', 'TAGUM', '40ft', 'standard', '2025-09-16 07:24:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(94, 4, 'mobile', 'ATW-83700', NULL, NULL, 'TEMU 877479-5', NULL, 'BAGUIO DISTRICT', 'TAGUM', '40ft', 'refrigerated', '2025-09-30 12:38:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(95, 17, 'mobile', 'ATW-29343', NULL, NULL, 'WHSU 892257-3', NULL, 'TAGUM', 'DICT', '20ft', 'refrigerated', '2025-10-20 16:58:00', 'archived', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(96, 12, 'mobile', 'ATW-54270', NULL, NULL, 'TEMU 863374-8', NULL, 'CARMEN', 'TUBOD', '40ft', 'refrigerated', '2025-11-18 06:28:00', 'archived', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(97, 16, 'mobile', 'ATW-41238', NULL, NULL, 'CMAU 839647-2', NULL, 'PANABO', 'CARMEN', '40ft', 'standard', '2025-12-07 07:07:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(98, 14, 'mobile', 'ATW-94315', NULL, NULL, 'MSCU 840277-3', NULL, 'TAGUM', 'TUBOD', '40ft', 'refrigerated', '2025-10-26 11:18:00', 'archived', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(99, 12, 'mobile', 'ATW-49699', NULL, NULL, 'MSCU 842973-7', NULL, 'DICT', 'TUBOD', '40ft', 'standard', '2025-12-25 09:13:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(100, 1, 'mobile', 'ATW-11198', NULL, NULL, 'WHSU 885053-2', NULL, 'BAGUIO DISTRICT', 'TUBOD', '20ft', 'refrigerated', '2025-11-26 17:06:00', 'completed', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(101, 17, 'mobile', 'ATW-35704', NULL, NULL, 'MSCU 809868-8', NULL, 'BAGUIO DISTRICT', 'PANABO', '20ft', 'standard', '2025-11-10 17:04:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(102, 14, 'mobile', 'ATW-44968', NULL, NULL, 'CMAU 847407-1', NULL, 'PANABO', 'CARMEN', '40ft', 'standard', '2025-12-29 15:52:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(103, 13, 'mobile', 'ATW-49391', NULL, NULL, 'WHSU 856865-7', NULL, 'BAGUIO DISTRICT', 'TUBOD', '20ft', 'refrigerated', '2025-12-09 07:39:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(104, 16, 'mobile', 'ATW-52762', NULL, NULL, 'MSCU 834229-5', NULL, 'TAGUM', 'DARONG', '40ft', 'refrigerated', '2025-09-25 17:25:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(105, 7, 'mobile', 'ATW-43320', NULL, NULL, 'CMAU 870770-2', NULL, 'TUBOD', 'PANABO', '40ft', 'standard', '2025-11-22 16:18:00', 'completed', 'EMPTY/CMA - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(106, 16, 'mobile', 'ATW-12730', NULL, NULL, 'CMAU 828626-4', NULL, 'TAGUM', 'CARMEN', '40ft', 'refrigerated', '2025-09-19 16:27:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(107, 13, 'mobile', 'ATW-54402', NULL, NULL, 'MSCU 815867-1', NULL, 'TAGUM', 'DICT', '20ft', 'refrigerated', '2025-12-02 17:23:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(108, 4, 'mobile', 'ATW-13148', NULL, NULL, 'MSCU 889319-8', NULL, 'PANABO', 'CARMEN', '40ft', 'standard', '2025-11-16 09:49:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(109, 12, 'mobile', 'ATW-64563', NULL, NULL, 'TEMU 803600-8', NULL, 'PANABO', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-11-03 06:13:00', 'archived', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(110, 4, 'mobile', 'ATW-67007', NULL, NULL, 'MSCU 894218-5', NULL, 'DICT', 'DARONG', '20ft', 'standard', '2025-09-07 13:47:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(111, 4, 'mobile', 'ATW-40467', NULL, NULL, 'WHSU 870794-4', NULL, 'PANABO', 'TUBOD', '20ft', 'refrigerated', '2025-10-18 12:46:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(112, 2, 'mobile', 'ATW-10218', NULL, NULL, 'MSCU 806949-5', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'refrigerated', '2025-09-07 10:15:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(113, 18, 'mobile', 'ATW-20079', NULL, NULL, 'MSCU 860763-4', NULL, 'DICT', 'DARONG', '40ft', 'standard', '2025-10-11 17:27:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(114, 1, 'mobile', 'ATW-85896', NULL, NULL, 'CMAU 886494-4', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-11-11 13:35:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(115, 1, 'mobile', 'ATW-16382', NULL, NULL, 'TEMU 851335-1', NULL, 'DARONG', 'CARMEN', '20ft', 'refrigerated', '2025-11-07 11:55:00', 'completed', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(116, 12, 'mobile', 'ATW-84547', NULL, NULL, 'WHSU 864510-8', NULL, 'PANABO', 'KAUSWAGAN', '40ft', 'standard', '2025-11-10 06:32:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(117, 12, 'mobile', 'ATW-53257', NULL, NULL, 'CMAU 812609-2', NULL, 'DICT', 'TAGUM', '40ft', 'refrigerated', '2025-10-28 08:05:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(118, 19, 'mobile', 'ATW-68132', NULL, NULL, 'CMAU 854400-5', NULL, 'DICT', 'TAGUM', '40ft', 'refrigerated', '2025-09-01 12:47:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(119, 12, 'mobile', 'ATW-67740', NULL, NULL, 'CMAU 834418-6', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'refrigerated', '2025-10-03 12:57:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(120, 6, 'mobile', 'ATW-51263', NULL, NULL, 'WHSU 870094-3', NULL, 'BAGUIO DISTRICT', 'PANABO', '20ft', 'refrigerated', '2025-09-19 09:19:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(121, 1, 'mobile', 'ATW-21276', NULL, NULL, 'WHSU 818478-7', NULL, 'PANABO', 'KAUSWAGAN', '20ft', 'standard', '2025-10-15 10:56:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(122, 2, 'mobile', 'ATW-10805', NULL, NULL, 'TEMU 821428-3', NULL, 'TAGUM', 'DARONG', '20ft', 'standard', '2025-11-07 06:17:00', 'completed', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(123, 15, 'mobile', 'ATW-54889', NULL, NULL, 'TEMU 800416-7', NULL, 'DARONG', 'TAGUM', '40ft', 'standard', '2025-11-28 15:16:00', 'completed', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(124, 6, 'mobile', 'ATW-80209', NULL, NULL, 'CMAU 809601-3', NULL, 'DICT', 'CARMEN', '40ft', 'refrigerated', '2025-09-24 11:23:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(125, 4, 'mobile', 'ATW-89702', NULL, NULL, 'TEMU 867565-5', NULL, 'PANABO', 'DICT', '40ft', 'standard', '2025-09-17 17:49:00', 'archived', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(126, 4, 'mobile', 'ATW-38789', NULL, NULL, 'MSCU 845004-7', NULL, 'PANABO', 'TAGUM', '20ft', 'refrigerated', '2025-10-22 11:15:00', 'archived', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(127, 9, 'mobile', 'ATW-80847', NULL, NULL, 'TEMU 856946-8', NULL, 'DICT', 'CARMEN', '20ft', 'standard', '2025-11-24 15:37:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(128, 12, 'mobile', 'ATW-86709', NULL, NULL, 'MSCU 808008-5', NULL, 'DICT', 'TAGUM', '20ft', 'refrigerated', '2025-12-19 08:22:00', 'completed', 'EMPTY/CMA - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(129, 9, 'mobile', 'ATW-52484', NULL, NULL, 'MSCU 812224-4', NULL, 'DARONG', 'CARMEN', '40ft', 'standard', '2025-10-21 10:39:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(130, 2, 'mobile', 'ATW-59518', NULL, NULL, 'TEMU 891790-6', NULL, 'DARONG', 'TUBOD', '40ft', 'refrigerated', '2025-09-13 11:50:00', 'archived', 'EMPTY/CMA - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(131, 7, 'mobile', 'ATW-52115', NULL, NULL, 'WHSU 842809-7', NULL, 'TAGUM', 'KAUSWAGAN', '20ft', 'standard', '2025-10-24 10:08:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(132, 9, 'mobile', 'ATW-42412', NULL, NULL, 'CMAU 851989-5', NULL, 'TUBOD', 'CARMEN', '20ft', 'standard', '2025-11-20 17:12:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(133, 14, 'mobile', 'ATW-32854', NULL, NULL, 'CMAU 814725-2', NULL, 'PANABO', 'DICT', '40ft', 'standard', '2025-10-10 11:43:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(134, 14, 'mobile', 'ATW-83244', NULL, NULL, 'CMAU 882219-3', NULL, 'DARONG', 'TUBOD', '20ft', 'standard', '2025-09-06 17:24:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(135, 19, 'mobile', 'ATW-93587', NULL, NULL, 'MSCU 899109-8', NULL, 'DICT', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-10-03 17:50:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(136, 19, 'mobile', 'ATW-16640', NULL, NULL, 'TEMU 880148-1', NULL, 'BAGUIO DISTRICT', 'TUBOD', '20ft', 'standard', '2025-09-22 16:32:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(137, 17, 'mobile', 'ATW-85563', NULL, NULL, 'CMAU 820870-3', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-09-05 06:42:00', 'archived', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(138, 19, 'mobile', 'ATW-54459', NULL, NULL, 'WHSU 850960-7', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-11-28 09:50:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(139, 13, 'mobile', 'ATW-39304', NULL, NULL, 'CMAU 852827-7', NULL, 'BAGUIO DISTRICT', 'TUBOD', '40ft', 'standard', '2025-12-05 11:40:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(140, 9, 'mobile', 'ATW-30223', NULL, NULL, 'TEMU 800394-5', NULL, 'CARMEN', 'TAGUM', '20ft', 'refrigerated', '2025-10-25 17:37:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(141, 2, 'mobile', 'ATW-87793', NULL, NULL, 'TEMU 809421-4', NULL, 'DICT', 'KAUSWAGAN', '40ft', 'standard', '2025-10-11 14:19:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(142, 2, 'mobile', 'ATW-19932', NULL, NULL, 'WHSU 865030-4', NULL, 'TUBOD', 'DARONG', '40ft', 'refrigerated', '2025-12-30 08:44:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(143, 18, 'mobile', 'ATW-45933', NULL, NULL, 'CMAU 872367-3', NULL, 'DICT', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-10-05 15:18:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(144, 18, 'mobile', 'ATW-46667', NULL, NULL, 'MSCU 860465-8', NULL, 'TUBOD', 'DICT', '20ft', 'refrigerated', '2025-09-27 10:41:00', 'completed', 'LADEN/EVERGREEN - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(145, 8, 'mobile', 'ATW-87591', NULL, NULL, 'TEMU 887924-7', NULL, 'CARMEN', 'DARONG', '40ft', 'standard', '2025-09-23 16:20:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(146, 18, 'mobile', 'ATW-48633', NULL, NULL, 'MSCU 811178-6', NULL, 'PANABO', 'TUBOD', '20ft', 'standard', '2025-09-22 15:14:00', 'completed', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(147, 14, 'mobile', 'ATW-99455', NULL, NULL, 'TEMU 814114-3', NULL, 'DARONG', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-10-05 14:22:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(148, 9, 'mobile', 'ATW-48472', NULL, NULL, 'TEMU 883773-1', NULL, 'TAGUM', 'PANABO', '20ft', 'standard', '2025-09-19 14:33:00', 'completed', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(149, 12, 'mobile', 'ATW-79728', NULL, NULL, 'TEMU 889972-8', NULL, 'DARONG', 'TUBOD', '20ft', 'refrigerated', '2025-11-22 14:13:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(150, 9, 'mobile', 'ATW-76342', NULL, NULL, 'MSCU 830749-6', NULL, 'DICT', 'BAGUIO DISTRICT', '40ft', 'refrigerated', '2025-11-08 12:51:00', 'archived', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(151, 13, 'mobile', 'ATW-38648', NULL, NULL, 'WHSU 879384-5', NULL, 'TAGUM', 'BAGUIO DISTRICT', '40ft', 'refrigerated', '2025-12-10 07:07:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(152, 13, 'mobile', 'ATW-73981', NULL, NULL, 'CMAU 808322-7', NULL, 'BAGUIO DISTRICT', 'DARONG', '20ft', 'standard', '2025-09-12 16:37:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(153, 7, 'mobile', 'ATW-57494', NULL, NULL, 'CMAU 885693-1', NULL, 'BAGUIO DISTRICT', 'CARMEN', '40ft', 'refrigerated', '2025-09-05 15:48:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(154, 9, 'mobile', 'ATW-87129', NULL, NULL, 'WHSU 897032-8', NULL, 'KAUSWAGAN', 'CARMEN', '20ft', 'refrigerated', '2025-11-05 10:46:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(155, 9, 'mobile', 'ATW-85788', NULL, NULL, 'MSCU 814476-1', NULL, 'TUBOD', 'PANABO', '40ft', 'refrigerated', '2025-09-22 10:06:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(156, 15, 'mobile', 'ATW-50645', NULL, NULL, 'CMAU 848309-7', NULL, 'DICT', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-12-14 11:52:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(157, 4, 'mobile', 'ATW-57277', NULL, NULL, 'CMAU 892084-7', NULL, 'TAGUM', 'PANABO', '40ft', 'standard', '2025-12-29 15:40:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(158, 19, 'mobile', 'ATW-12380', NULL, NULL, 'MSCU 843718-8', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'refrigerated', '2025-12-17 10:50:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(159, 12, 'mobile', 'ATW-22904', NULL, NULL, 'MSCU 885199-8', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-09-28 14:55:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(160, 7, 'mobile', 'ATW-50866', NULL, NULL, 'MSCU 853813-4', NULL, 'CARMEN', 'KAUSWAGAN', '20ft', 'standard', '2025-09-14 16:40:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(161, 19, 'mobile', 'ATW-45554', NULL, NULL, 'WHSU 821547-7', NULL, 'DICT', 'CARMEN', '40ft', 'standard', '2025-11-03 07:26:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(162, 16, 'mobile', 'ATW-65761', NULL, NULL, 'WHSU 877312-4', NULL, 'KAUSWAGAN', 'CARMEN', '20ft', 'standard', '2025-11-10 15:44:00', 'completed', 'LADEN/EVERGREEN - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(163, 13, 'mobile', 'ATW-49975', NULL, NULL, 'TEMU 883405-2', NULL, 'KAUSWAGAN', 'TUBOD', '40ft', 'refrigerated', '2025-11-22 06:38:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(164, 14, 'mobile', 'ATW-74356', NULL, NULL, 'WHSU 821104-7', NULL, 'TUBOD', 'PANABO', '40ft', 'standard', '2025-10-01 09:40:00', 'completed', 'LADEN/EVERGREEN - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(165, 9, 'mobile', 'ATW-46015', NULL, NULL, 'CMAU 816483-3', NULL, 'DARONG', 'TAGUM', '40ft', 'standard', '2025-12-09 11:21:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(166, 2, 'mobile', 'ATW-67600', NULL, NULL, 'WHSU 821929-6', NULL, 'TUBOD', 'BAGUIO DISTRICT', '40ft', 'refrigerated', '2025-09-13 12:54:00', 'completed', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(167, 13, 'mobile', 'ATW-21614', NULL, NULL, 'WHSU 853574-5', NULL, 'DICT', 'CARMEN', '40ft', 'refrigerated', '2025-10-22 14:16:00', 'archived', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(168, 4, 'mobile', 'ATW-75733', NULL, NULL, 'TEMU 876323-6', NULL, 'BAGUIO DISTRICT', 'DICT', '20ft', 'standard', '2025-11-24 06:23:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(169, 9, 'mobile', 'ATW-66440', NULL, NULL, 'CMAU 874759-2', NULL, 'BAGUIO DISTRICT', 'DARONG', '20ft', 'refrigerated', '2025-11-19 08:02:00', 'archived', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(170, 9, 'mobile', 'ATW-51334', NULL, NULL, 'TEMU 829617-4', NULL, 'TUBOD', 'DICT', '40ft', 'refrigerated', '2025-11-25 13:37:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(171, 12, 'mobile', 'ATW-51408', NULL, NULL, 'WHSU 887585-7', NULL, 'TUBOD', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-10-14 13:36:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(172, 18, 'mobile', 'ATW-89610', NULL, NULL, 'CMAU 846951-1', NULL, 'KAUSWAGAN', 'DICT', '40ft', 'standard', '2025-10-14 12:04:00', 'archived', 'LADEN/EVERGREEN - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(173, 12, 'mobile', 'ATW-50466', NULL, NULL, 'CMAU 886165-1', NULL, 'TUBOD', 'TAGUM', '20ft', 'refrigerated', '2025-11-24 13:36:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(174, 4, 'mobile', 'ATW-33905', NULL, NULL, 'MSCU 890797-2', NULL, 'PANABO', 'TUBOD', '40ft', 'refrigerated', '2025-11-05 17:49:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(175, 13, 'mobile', 'ATW-63559', NULL, NULL, 'MSCU 855659-1', NULL, 'DICT', 'TUBOD', '20ft', 'standard', '2025-09-15 11:21:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(176, 2, 'mobile', 'ATW-32536', NULL, NULL, 'CMAU 844190-7', NULL, 'KAUSWAGAN', 'CARMEN', '20ft', 'standard', '2025-10-02 11:19:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(177, 12, 'mobile', 'ATW-57593', NULL, NULL, 'WHSU 821148-1', NULL, 'DARONG', 'TAGUM', '20ft', 'refrigerated', '2025-11-20 16:17:00', 'archived', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(178, 9, 'mobile', 'ATW-90686', NULL, NULL, 'WHSU 829618-2', NULL, 'PANABO', 'TAGUM', '20ft', 'standard', '2025-09-07 08:40:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(179, 9, 'mobile', 'ATW-99646', NULL, NULL, 'WHSU 840173-6', NULL, 'KAUSWAGAN', 'DICT', '40ft', 'standard', '2025-12-11 11:23:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(180, 13, 'mobile', 'ATW-55824', NULL, NULL, 'WHSU 835713-8', NULL, 'DARONG', 'DICT', '40ft', 'standard', '2025-12-23 13:34:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(181, 11, 'mobile', 'ATW-61419', NULL, NULL, 'TEMU 831224-1', NULL, 'DICT', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-12-11 09:05:00', 'pending', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(182, 9, 'mobile', 'ATW-34710', NULL, NULL, 'WHSU 866496-5', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'refrigerated', '2025-12-25 15:14:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(183, 6, 'mobile', 'ATW-26566', NULL, NULL, 'MSCU 852656-3', NULL, 'BAGUIO DISTRICT', 'DARONG', '20ft', 'refrigerated', '2025-10-24 12:07:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(184, 18, 'mobile', 'ATW-55580', NULL, NULL, 'WHSU 821172-8', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-10-09 14:43:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(185, 9, 'mobile', 'ATW-81076', NULL, NULL, 'WHSU 863928-2', NULL, 'DARONG', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-11-15 08:45:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(186, 9, 'mobile', 'ATW-50884', NULL, NULL, 'WHSU 838285-4', NULL, 'CARMEN', 'DICT', '40ft', 'standard', '2025-10-10 11:32:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(187, 15, 'mobile', 'ATW-51355', NULL, NULL, 'CMAU 849914-4', NULL, 'TAGUM', 'BAGUIO DISTRICT', '40ft', 'standard', '2025-11-02 17:43:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(188, 4, 'mobile', 'ATW-72970', NULL, NULL, 'WHSU 883677-5', NULL, 'KAUSWAGAN', 'TAGUM', '20ft', 'standard', '2025-11-27 07:52:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(189, 7, 'mobile', 'ATW-28121', NULL, NULL, 'CMAU 802487-5', NULL, 'PANABO', 'TAGUM', '20ft', 'standard', '2025-09-01 10:40:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(190, 6, 'mobile', 'ATW-25735', NULL, NULL, 'TEMU 817829-1', NULL, 'TAGUM', 'DARONG', '20ft', 'standard', '2025-12-14 10:53:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(191, 17, 'mobile', 'ATW-63168', NULL, NULL, 'WHSU 871610-5', NULL, 'DARONG', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-10-12 07:28:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(192, 13, 'mobile', 'ATW-49339', NULL, NULL, 'TEMU 819692-1', NULL, 'DARONG', 'CARMEN', '40ft', 'standard', '2025-11-19 09:31:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(193, 2, 'mobile', 'ATW-27382', NULL, NULL, 'WHSU 822076-1', NULL, 'BAGUIO DISTRICT', 'TAGUM', '40ft', 'standard', '2025-10-29 14:12:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(194, 17, 'mobile', 'ATW-77865', NULL, NULL, 'WHSU 863020-1', NULL, 'CARMEN', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-12-13 06:45:00', 'completed', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(195, 12, 'mobile', 'ATW-23437', NULL, NULL, 'CMAU 861689-3', NULL, 'CARMEN', 'DARONG', '20ft', 'standard', '2025-11-14 17:22:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(196, 8, 'mobile', 'ATW-62769', NULL, NULL, 'WHSU 897827-5', NULL, 'BAGUIO DISTRICT', 'TAGUM', '40ft', 'standard', '2025-11-27 16:09:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(197, 8, 'mobile', 'ATW-36963', NULL, NULL, 'TEMU 882032-3', NULL, 'DICT', 'PANABO', '40ft', 'standard', '2025-12-25 14:13:00', 'pending', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(198, 2, 'mobile', 'ATW-72109', NULL, NULL, 'MSCU 898608-6', NULL, 'PANABO', 'CARMEN', '40ft', 'standard', '2025-10-29 09:57:00', 'completed', 'EMPTY/CMA - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(199, 12, 'mobile', 'ATW-70659', NULL, NULL, 'CMAU 879308-1', NULL, 'DICT', 'TUBOD', '20ft', 'refrigerated', '2025-12-07 11:48:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(200, 8, 'mobile', 'ATW-64318', NULL, NULL, 'WHSU 852189-2', NULL, 'CARMEN', 'DARONG', '20ft', 'refrigerated', '2025-12-30 06:22:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(201, 9, 'mobile', 'ATW-98987', NULL, NULL, 'WHSU 852470-4', NULL, 'CARMEN', 'PANABO', '40ft', 'standard', '2025-11-16 16:15:00', 'completed', 'EMPTY RETURN/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(202, 4, 'mobile', 'ATW-69391', NULL, NULL, 'TEMU 846243-1', NULL, 'PANABO', 'DARONG', '40ft', 'refrigerated', '2025-10-16 09:55:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(203, 12, 'mobile', 'ATW-75574', NULL, NULL, 'CMAU 851039-5', NULL, 'TAGUM', 'DICT', '20ft', 'standard', '2025-10-20 09:18:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(204, 15, 'mobile', 'ATW-73591', NULL, NULL, 'MSCU 814162-5', NULL, 'KAUSWAGAN', 'PANABO', '40ft', 'refrigerated', '2025-11-15 13:51:00', 'completed', 'LADEN/MAERSK - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(205, 15, 'mobile', 'ATW-34112', NULL, NULL, 'MSCU 862738-4', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-10-01 10:11:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(206, 6, 'mobile', 'ATW-61770', NULL, NULL, 'TEMU 896974-6', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-12-28 08:45:00', 'completed', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(207, 16, 'mobile', 'ATW-38810', NULL, NULL, 'CMAU 854541-7', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-09-29 13:23:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(208, 9, 'mobile', 'ATW-40475', NULL, NULL, 'CMAU 860815-6', NULL, 'PANABO', 'TUBOD', '40ft', 'refrigerated', '2025-12-27 16:35:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(209, 9, 'mobile', 'ATW-39107', NULL, NULL, 'TEMU 895364-6', NULL, 'KAUSWAGAN', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-09-25 16:37:00', 'completed', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(210, 2, 'mobile', 'ATW-64797', NULL, NULL, 'WHSU 889521-5', NULL, 'CARMEN', 'BAGUIO DISTRICT', '20ft', 'refrigerated', '2025-12-19 15:54:00', 'verified', 'LADEN/MAERSK - DOLE ASIA', 1, '2025-12-13 06:24:18', '2025-12-13 06:17:54', NULL),
(211, 11, 'mobile', 'ATW-90821', NULL, NULL, 'TEMU 807571-6', NULL, 'TAGUM', 'DICT', '20ft', 'refrigerated', '2025-11-26 12:17:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(212, 11, 'mobile', 'ATW-37206', NULL, NULL, 'MSCU 807630-1', NULL, 'KAUSWAGAN', 'CARMEN', '20ft', 'standard', '2025-10-20 16:25:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL);
INSERT INTO `delivery_requests` (`id`, `client_id`, `contact_method`, `atw_reference`, `eir_number`, `booking_number`, `container_number`, `seal_number`, `pickup_location`, `delivery_location`, `container_size`, `container_type`, `preferred_schedule`, `status`, `notes`, `atw_verified`, `created_at`, `updated_at`, `archived_at`) VALUES
(213, 6, 'mobile', 'ATW-43651', NULL, NULL, 'TEMU 865599-1', NULL, 'TAGUM', 'PANABO', '40ft', 'refrigerated', '2025-12-08 13:48:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(214, 9, 'mobile', 'ATW-78186', NULL, NULL, 'WHSU 822095-7', NULL, 'CARMEN', 'DICT', '20ft', 'standard', '2025-09-30 17:30:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(215, 19, 'mobile', 'ATW-76138', NULL, NULL, 'WHSU 822418-1', NULL, 'KAUSWAGAN', 'PANABO', '20ft', 'standard', '2025-11-17 13:35:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(216, 11, 'mobile', 'ATW-78417', NULL, NULL, 'WHSU 837448-2', NULL, 'PANABO', 'TAGUM', '40ft', 'standard', '2025-09-10 11:40:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(217, 2, 'mobile', 'ATW-82770', NULL, NULL, 'WHSU 836964-3', NULL, 'DARONG', 'TUBOD', '40ft', 'refrigerated', '2025-12-29 11:53:00', 'pending', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(218, 9, 'mobile', 'ATW-86290', NULL, NULL, 'TEMU 878331-7', NULL, 'DICT', 'PANABO', '40ft', 'refrigerated', '2025-10-03 11:13:00', 'archived', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(219, 18, 'mobile', 'ATW-92889', NULL, NULL, 'MSCU 834582-6', NULL, 'KAUSWAGAN', 'PANABO', '40ft', 'standard', '2025-12-30 10:14:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(220, 6, 'mobile', 'ATW-25239', NULL, NULL, 'TEMU 832586-8', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-09-24 11:33:00', 'completed', 'LADEN/EVERGREEN - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(221, 13, 'mobile', 'ATW-55984', NULL, NULL, 'CMAU 846215-6', NULL, 'CARMEN', 'TUBOD', '40ft', 'refrigerated', '2025-09-11 15:02:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(222, 6, 'mobile', 'ATW-47684', NULL, NULL, 'CMAU 852612-5', NULL, 'CARMEN', 'TUBOD', '20ft', 'standard', '2025-09-07 17:36:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(223, 8, 'mobile', 'ATW-99551', NULL, NULL, 'WHSU 808348-4', NULL, 'CARMEN', 'TUBOD', '20ft', 'standard', '2025-09-16 11:13:00', 'archived', 'EMPTY/CMA - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', '2025-12-17 07:52:57'),
(224, 9, 'mobile', 'ATW-70647', NULL, NULL, 'WHSU 893794-6', NULL, 'PANABO', 'KAUSWAGAN', '40ft', 'standard', '2025-12-14 07:16:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(225, 11, 'mobile', 'ATW-72513', NULL, NULL, 'TEMU 894976-5', NULL, 'DICT', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-09-11 06:06:00', 'completed', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(226, 2, 'mobile', 'ATW-10356', NULL, NULL, 'MSCU 846579-2', NULL, 'DICT', 'KAUSWAGAN', '20ft', 'standard', '2025-11-21 09:25:00', 'completed', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(227, 15, 'mobile', 'ATW-84311', NULL, NULL, 'TEMU 885862-6', NULL, 'DARONG', 'TAGUM', '20ft', 'refrigerated', '2025-11-02 13:31:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(228, 8, 'mobile', 'ATW-69331', NULL, NULL, 'TEMU 805462-4', NULL, 'CARMEN', 'DICT', '40ft', 'refrigerated', '2025-12-19 07:35:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(229, 4, 'mobile', 'ATW-94906', NULL, NULL, 'TEMU 854599-8', NULL, 'BAGUIO DISTRICT', 'DARONG', '40ft', 'standard', '2025-10-05 07:38:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(230, 1, 'mobile', 'ATW-43818', NULL, NULL, 'WHSU 816286-6', NULL, 'TUBOD', 'DARONG', '20ft', 'refrigerated', '2025-09-19 08:32:00', 'completed', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(231, 19, 'mobile', 'ATW-88500', NULL, NULL, 'TEMU 847811-8', NULL, 'KAUSWAGAN', 'CARMEN', '40ft', 'standard', '2025-11-23 07:41:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(232, 16, 'mobile', 'ATW-94099', NULL, NULL, 'MSCU 864299-6', NULL, 'CARMEN', 'TAGUM', '40ft', 'refrigerated', '2025-10-31 11:03:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(233, 18, 'mobile', 'ATW-13074', NULL, NULL, 'MSCU 815445-3', NULL, 'TUBOD', 'TAGUM', '20ft', 'refrigerated', '2025-12-09 16:12:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(234, 2, 'mobile', 'ATW-40265', NULL, NULL, 'MSCU 886123-5', NULL, 'CARMEN', 'TAGUM', '40ft', 'refrigerated', '2025-11-13 09:07:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(235, 11, 'mobile', 'ATW-12687', NULL, NULL, 'TEMU 822537-2', NULL, 'PANABO', 'TUBOD', '40ft', 'refrigerated', '2025-11-03 16:49:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(236, 4, 'mobile', 'ATW-91718', NULL, NULL, 'CMAU 897554-6', NULL, 'TAGUM', 'DICT', '40ft', 'refrigerated', '2025-10-21 13:04:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(237, 8, 'mobile', 'ATW-32917', NULL, NULL, 'CMAU 856741-6', NULL, 'TUBOD', 'TAGUM', '20ft', 'refrigerated', '2025-10-08 06:26:00', 'completed', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:18', '2025-12-13 06:24:18', NULL),
(238, 4, 'mobile', 'ATW-95269', NULL, NULL, 'MSCU 889619-3', NULL, 'KAUSWAGAN', 'TAGUM', '40ft', 'standard', '2025-11-27 15:24:00', 'completed', 'EMPTY/CMA - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(239, 16, 'mobile', 'ATW-36296', NULL, NULL, 'MSCU 874833-6', NULL, 'DICT', 'KAUSWAGAN', '40ft', 'standard', '2025-10-31 07:13:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(240, 19, 'mobile', 'ATW-39279', NULL, NULL, 'WHSU 820752-6', NULL, 'TAGUM', 'TUBOD', '40ft', 'refrigerated', '2025-10-12 13:38:00', 'completed', 'LADEN/EVERGREEN - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(241, 4, 'mobile', 'ATW-17942', NULL, NULL, 'CMAU 815892-3', NULL, 'BAGUIO DISTRICT', 'CARMEN', '40ft', 'standard', '2025-11-19 06:36:00', 'archived', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(242, 2, 'mobile', 'ATW-80911', NULL, NULL, 'TEMU 857978-7', NULL, 'DICT', 'KAUSWAGAN', '40ft', 'standard', '2025-11-03 16:58:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(243, 13, 'mobile', 'ATW-36626', NULL, NULL, 'CMAU 828401-4', NULL, 'TAGUM', 'PANABO', '40ft', 'standard', '2025-11-20 07:11:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(244, 9, 'mobile', 'ATW-84399', NULL, NULL, 'CMAU 847589-6', NULL, 'BAGUIO DISTRICT', 'TUBOD', '40ft', 'refrigerated', '2025-12-15 14:44:00', 'completed', 'EMPTY/CMA - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(245, 18, 'mobile', 'ATW-35395', NULL, NULL, 'TEMU 875494-1', NULL, 'BAGUIO DISTRICT', 'CARMEN', '20ft', 'refrigerated', '2025-11-15 14:06:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(246, 8, 'mobile', 'ATW-74753', NULL, NULL, 'TEMU 851179-5', NULL, 'DICT', 'TUBOD', '20ft', 'refrigerated', '2025-09-30 11:47:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(247, 15, 'mobile', 'ATW-44317', NULL, NULL, 'MSCU 865588-5', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-12-19 11:12:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(248, 6, 'mobile', 'ATW-32185', NULL, NULL, 'TEMU 833086-8', NULL, 'TAGUM', 'TUBOD', '40ft', 'standard', '2025-12-26 15:54:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(249, 9, 'mobile', 'ATW-96443', NULL, NULL, 'TEMU 837808-3', NULL, 'CARMEN', 'DICT', '40ft', 'standard', '2025-09-06 14:38:00', 'archived', 'EMPTY/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(250, 7, 'mobile', 'ATW-28862', NULL, NULL, 'MSCU 897744-5', NULL, 'DARONG', 'PANABO', '40ft', 'refrigerated', '2025-11-02 06:15:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(251, 12, 'mobile', 'ATW-92874', NULL, NULL, 'CMAU 860347-6', NULL, 'KAUSWAGAN', 'TAGUM', '20ft', 'refrigerated', '2025-09-03 12:30:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(252, 7, 'mobile', 'ATW-10246', NULL, NULL, 'MSCU 838479-3', NULL, 'DICT', 'CARMEN', '40ft', 'refrigerated', '2025-11-27 06:35:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(253, 11, 'mobile', 'ATW-32296', NULL, NULL, 'MSCU 874846-7', NULL, 'TAGUM', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-09-19 06:57:00', 'completed', 'EMPTY RETURN/WANHAI - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(254, 9, 'mobile', 'ATW-47793', NULL, NULL, 'TEMU 826041-3', NULL, 'DARONG', 'DICT', '20ft', 'standard', '2025-12-01 12:48:00', 'completed', 'EMPTY RETURN/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(255, 9, 'mobile', 'ATW-64967', NULL, NULL, 'TEMU 883023-2', NULL, 'TAGUM', 'CARMEN', '20ft', 'refrigerated', '2025-11-15 16:38:00', 'completed', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(256, 2, 'mobile', 'ATW-16919', NULL, NULL, 'TEMU 847417-8', NULL, 'CARMEN', 'TAGUM', '40ft', 'standard', '2025-12-06 10:41:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(257, 9, 'mobile', 'ATW-33244', NULL, NULL, 'MSCU 824143-1', NULL, 'DARONG', 'TAGUM', '40ft', 'refrigerated', '2025-12-30 14:40:00', 'completed', 'EMPTY RETURN/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(258, 15, 'mobile', 'ATW-24519', NULL, NULL, 'CMAU 822213-8', NULL, 'PANABO', 'TUBOD', '40ft', 'refrigerated', '2025-12-14 15:39:00', 'pending', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(259, 17, 'mobile', 'ATW-64826', NULL, NULL, 'TEMU 879420-4', NULL, 'CARMEN', 'TUBOD', '40ft', 'standard', '2025-12-29 10:26:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(260, 11, 'mobile', 'ATW-60161', NULL, NULL, 'WHSU 883434-1', NULL, 'DARONG', 'KAUSWAGAN', '40ft', 'standard', '2025-10-16 12:24:00', 'archived', 'LADEN/MAERSK - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(261, 4, 'mobile', 'ATW-51655', NULL, NULL, 'WHSU 862917-8', NULL, 'CARMEN', 'KAUSWAGAN', '40ft', 'standard', '2025-10-28 10:54:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(262, 11, 'mobile', 'ATW-86960', NULL, NULL, 'WHSU 828552-4', NULL, 'PANABO', 'DICT', '20ft', 'refrigerated', '2025-12-23 10:48:00', 'completed', 'EMPTY/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(263, 18, 'mobile', 'ATW-73830', NULL, NULL, 'CMAU 845968-4', NULL, 'PANABO', 'KAUSWAGAN', '20ft', 'refrigerated', '2025-12-20 08:38:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(264, 1, 'mobile', 'ATW-43464', NULL, NULL, 'TEMU 885978-5', NULL, 'TAGUM', 'DICT', '20ft', 'refrigerated', '2025-12-20 16:28:00', 'completed', 'LADEN/MAERSK - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(265, 2, 'mobile', 'ATW-12376', NULL, NULL, 'WHSU 850273-8', NULL, 'PANABO', 'DARONG', '20ft', 'standard', '2025-11-12 13:02:00', 'completed', 'EMPTY/COSCO - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(266, 2, 'mobile', 'ATW-92883', NULL, NULL, 'TEMU 806044-3', NULL, 'TUBOD', 'DICT', '20ft', 'standard', '2025-11-07 17:47:00', 'completed', 'LADEN/MAERSK - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(267, 4, 'mobile', 'ATW-58116', NULL, NULL, 'TEMU 834411-8', NULL, 'CARMEN', 'PANABO', '20ft', 'refrigerated', '2025-11-24 17:09:00', 'completed', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(268, 11, 'mobile', 'ATW-26469', NULL, NULL, 'TEMU 857775-2', NULL, 'BAGUIO DISTRICT', 'KAUSWAGAN', '20ft', 'standard', '2025-09-07 14:07:00', 'completed', 'LADEN/EVERGREEN - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(269, 9, 'mobile', 'ATW-41845', NULL, NULL, 'MSCU 806975-5', NULL, 'DARONG', 'DICT', '20ft', 'standard', '2025-09-26 14:28:00', 'completed', 'EMPTY/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(270, 9, 'mobile', 'ATW-21936', NULL, NULL, 'MSCU 807402-8', NULL, 'KAUSWAGAN', 'DARONG', '40ft', 'standard', '2025-09-14 08:37:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(271, 19, 'mobile', 'ATW-83371', NULL, NULL, 'WHSU 867060-2', NULL, 'PANABO', 'TUBOD', '20ft', 'refrigerated', '2025-11-03 09:02:00', 'completed', 'EMPTY/COSCO - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(272, 2, 'mobile', 'ATW-71086', NULL, NULL, 'TEMU 891641-7', NULL, 'CARMEN', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-12-27 15:25:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(273, 13, 'mobile', 'ATW-59830', NULL, NULL, 'WHSU 833638-2', NULL, 'BAGUIO DISTRICT', 'DICT', '40ft', 'standard', '2025-09-15 08:16:00', 'completed', 'EMPTY/COSCO - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(274, 18, 'mobile', 'ATW-57872', NULL, NULL, 'MSCU 807507-8', NULL, 'PANABO', 'TUBOD', '40ft', 'standard', '2025-11-10 13:49:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(275, 9, 'mobile', 'ATW-25736', NULL, NULL, 'WHSU 868214-4', NULL, 'CARMEN', 'DICT', '40ft', 'refrigerated', '2025-11-08 16:37:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(276, 1, 'mobile', 'ATW-13596', NULL, NULL, 'TEMU 882030-7', NULL, 'BAGUIO DISTRICT', 'TAGUM', '20ft', 'standard', '2025-09-23 11:28:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(277, 2, 'mobile', 'ATW-17365', NULL, NULL, 'CMAU 857481-2', NULL, 'DICT', 'DARONG', '40ft', 'standard', '2025-10-20 06:02:00', 'completed', 'EMPTY/COSCO - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(278, 13, 'mobile', 'ATW-85434', NULL, NULL, 'TEMU 856106-2', NULL, 'KAUSWAGAN', 'DARONG', '20ft', 'refrigerated', '2025-09-05 15:07:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(279, 14, 'mobile', 'ATW-53793', NULL, NULL, 'TEMU 892337-6', NULL, 'PANABO', 'DICT', '40ft', 'standard', '2025-09-16 17:35:00', 'archived', 'LADEN/EVERGREEN - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(280, 16, 'mobile', 'ATW-96135', NULL, NULL, 'WHSU 836814-7', NULL, 'CARMEN', 'DICT', '20ft', 'refrigerated', '2025-10-16 15:13:00', 'completed', 'LADEN/EVERGREEN - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(281, 16, 'mobile', 'ATW-69834', NULL, NULL, 'MSCU 817874-1', NULL, 'BAGUIO DISTRICT', 'CARMEN', '40ft', 'refrigerated', '2025-10-10 12:39:00', 'archived', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(282, 11, 'mobile', 'ATW-19863', NULL, NULL, 'WHSU 881039-2', NULL, 'BAGUIO DISTRICT', 'KAUSWAGAN', '40ft', 'standard', '2025-11-06 17:11:00', 'completed', 'EMPTY/CMA - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(283, 19, 'mobile', 'ATW-62236', NULL, NULL, 'MSCU 848975-2', NULL, 'TUBOD', 'DICT', '40ft', 'refrigerated', '2025-11-10 10:55:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(284, 6, 'mobile', 'ATW-77872', NULL, NULL, 'WHSU 810111-2', NULL, 'BAGUIO DISTRICT', 'TUBOD', '40ft', 'standard', '2025-12-22 11:03:00', 'completed', 'EMPTY/WANHAI - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(285, 11, 'mobile', 'ATW-99852', NULL, NULL, 'MSCU 803576-3', NULL, 'CARMEN', 'TAGUM', '40ft', 'standard', '2025-09-13 13:58:00', 'completed', 'EMPTY RETURN/WANHAI - LI-ZHEN CORP.', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(286, 2, 'mobile', 'ATW-10488', NULL, NULL, 'WHSU 825582-3', NULL, 'DARONG', 'DICT', '20ft', 'standard', '2025-10-26 06:02:00', 'completed', 'EMPTY/COSCO - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(287, 4, 'mobile', 'ATW-37025', NULL, NULL, 'TEMU 838827-6', NULL, 'DICT', 'TAGUM', '40ft', 'standard', '2025-12-26 08:52:00', 'pending', 'EMPTY RETURN/WANHAI - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(288, 14, 'mobile', 'ATW-86773', NULL, NULL, 'MSCU 822655-6', NULL, 'DARONG', 'TAGUM', '40ft', 'standard', '2025-10-30 13:34:00', 'completed', 'LADEN/MAERSK - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(289, 12, 'mobile', 'ATW-79278', NULL, NULL, 'MSCU 804971-3', NULL, 'DICT', 'KAUSWAGAN', '40ft', 'standard', '2025-11-29 09:27:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(290, 8, 'mobile', 'ATW-44006', NULL, NULL, 'WHSU 811989-8', NULL, 'CARMEN', 'TUBOD', '40ft', 'standard', '2025-11-09 06:24:00', 'completed', 'EMPTY/CMA - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(291, 18, 'mobile', 'ATW-19341', NULL, NULL, 'MSCU 816143-4', NULL, 'CARMEN', 'DICT', '20ft', 'standard', '2025-11-12 17:36:00', 'completed', 'EMPTY/CMA - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(292, 6, 'mobile', 'ATW-67298', NULL, NULL, 'CMAU 821894-2', NULL, 'PANABO', 'CARMEN', '20ft', 'standard', '2025-12-03 12:18:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(293, 18, 'mobile', 'ATW-53913', NULL, NULL, 'MSCU 805938-7', NULL, 'CARMEN', 'DICT', '20ft', 'standard', '2025-12-25 15:05:00', 'pending', 'LADEN/EVERGREEN - STANFILCO', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(294, 4, 'mobile', 'ATW-54993', NULL, NULL, 'WHSU 835473-2', NULL, 'PANABO', 'KAUSWAGAN', '40ft', 'standard', '2025-11-29 09:31:00', 'completed', 'LADEN/MAERSK - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(295, 19, 'mobile', 'ATW-97386', NULL, NULL, 'WHSU 860786-4', NULL, 'PANABO', 'TUBOD', '20ft', 'refrigerated', '2025-11-13 08:43:00', 'archived', 'EMPTY RETURN/WANHAI - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(296, 1, 'mobile', 'ATW-80339', NULL, NULL, 'MSCU 823431-1', NULL, 'BAGUIO DISTRICT', 'PANABO', '40ft', 'refrigerated', '2025-12-06 16:00:00', 'completed', 'EMPTY RETURN/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(297, 2, 'mobile', 'ATW-24688', NULL, NULL, 'WHSU 823256-4', NULL, 'TAGUM', 'KAUSWAGAN', '40ft', 'refrigerated', '2025-12-09 12:53:00', 'completed', 'LADEN/EVERGREEN - SUMIFRU', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(298, 15, 'mobile', 'ATW-46455', NULL, NULL, 'CMAU 822584-7', NULL, 'BAGUIO DISTRICT', 'DARONG', '40ft', 'standard', '2025-11-09 08:35:00', 'completed', 'EMPTY/WANHAI - SARAP FRUITS', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(299, 12, 'mobile', 'ATW-99780', NULL, NULL, 'MSCU 869773-7', NULL, 'TUBOD', 'BAGUIO DISTRICT', '20ft', 'standard', '2025-10-29 06:12:00', 'completed', 'EMPTY/WANHAI - AGRI EXIM GLOBAL', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(300, 8, 'mobile', 'ATW-60268', NULL, NULL, 'CMAU 892077-3', NULL, 'TUBOD', 'DICT', '20ft', 'refrigerated', '2025-09-11 11:42:00', 'completed', 'EMPTY/COSCO - DOLE ASIA', 0, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `license_number` varchar(255) NOT NULL,
  `status` enum('available','on-trip','off-duty') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `name`, `mobile`, `license_number`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ALANG', '09397308697', 'N36-37654322', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(2, 'CANETE', '09474568292', 'N73-54229406', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(3, 'CAÑETE', '09382606957', 'N57-71668418', 'available', '2025-12-13 09:43:38', '2025-12-13 03:34:24'),
(4, 'INTRUZO', '09841185723', 'N75-20684553', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(5, 'JACK', '09256711352', 'N74-93648570', 'available', '2025-12-13 09:43:38', '2025-12-13 03:16:49'),
(6, 'JACK-UP TOCMO-RETURN SERENO', '09788499091', 'N68-50146556', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(7, 'LAURENTE', '09226383198', 'N14-63333790', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(8, 'RIVERA', '09248814427', 'N18-70692717', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(9, 'SERENO', '09865429900', 'N72-79619925', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(10, 'TOCMO', '09793498914', 'N46-42371618', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(11, 'SANTOS', '09249745972', 'N78-72905013', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(12, 'DELA CRUZ', '09736880685', 'N62-95416622', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(13, 'REYES', '09943978070', 'N20-32577161', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(14, 'GONZALES', '09145366950', 'N19-76371844', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38'),
(15, 'MENDOZA', '09578580982', 'N81-26944818', 'available', '2025-12-13 09:43:38', '2025-12-13 09:43:38');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_11_033436_create_dispatch_table', 1),
(5, '2025_11_25_183035_add_role_to_users_table', 2),
(8, '2025_11_26_105207_update_users_role_column_values', 3),
(9, '2025_11_26_142430_add_role_to_users_table', 3),
(10, '2025_12_03_110322_add_google_fields_to_users_table', 4),
(11, '2025_12_13_052439_add_container_details_to_delivery_requests', 5),
(12, '2025_12_05_055258_create_password_reset_tokens_table', 6),
(13, '2025_12_14_015312_add_archived_status_to_delivery_requests_and_trips', 7),
(14, '2025_12_14_093734_merge_duplicate_clients', 7),
(15, '2025_12_17_234206_create_sessions_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('nosyasxd@gmail.com', '$2y$12$utBhVAau7DBYFKDiDHNqn.MDwVo4uSxHh3eQ9Q5tlSObXVqtICd2.', '2025-12-10 03:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7LshaNJWoWsaTmCXeQfxega90L1bjK8LaAETGYH3', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRkE4OEFqNjlIaHBQVXNHT2RWNDFrV0ZqYnkybGFTekNVN0JnZDRUUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMxOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1766047834),
('cKd4EnuZmpfTkp5KAuHyzm6kzSdRQsWDIAlrkGIC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMERpblA4VGJxUnhJWHhWT1k0VTRmWVlOWVVDNGg4Wmw4MTBIMXl3QiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1765636581),
('hNd015syUR7g8T2SIbhGMw4wWcipRHyLwunmt8Kj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV0pGbm1JS0FMRE9qR2xRM0x2OFhwTFduVXh3SXFKMDBVY25YdnhMdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL2dvb2dsZS9sb2dpbiI7czo1OiJyb3V0ZSI7czoxMjoiZ29vZ2xlLmxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxNzoiZ29vZ2xlX29hdXRoX2Zsb3ciO3M6NToibG9naW4iO3M6NToic3RhdGUiO3M6NDA6ImVLWUNzcU9VV0lkZ3l4V2c3OElISXA3VUJlaFdmeW5sTlNVUUs4OTYiO30=', 1765984267);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_request_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_time` datetime NOT NULL,
  `actual_start_time` datetime DEFAULT NULL,
  `actual_end_time` datetime DEFAULT NULL,
  `status` enum('scheduled','in-transit','completed','cancelled','archived') DEFAULT 'scheduled',
  `route_instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `delivery_request_id`, `driver_id`, `vehicle_id`, `scheduled_time`, `actual_start_time`, `actual_end_time`, `status`, `route_instructions`, `created_at`, `updated_at`, `archived_at`) VALUES
(1, 1, 2, 14, '2025-10-25 12:11:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(2, 2, 3, 4, '2025-10-15 11:43:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(3, 3, 3, 9, '2025-11-27 12:56:00', '2025-11-27 12:56:00', '2025-11-27 15:26:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(4, 4, 5, 11, '2025-11-05 09:06:00', '2025-11-05 09:06:00', '2025-11-05 11:36:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(5, 5, 2, 1, '2025-11-24 08:25:00', '2025-11-24 08:25:00', '2025-11-24 10:55:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(6, 6, 3, 6, '2025-09-05 15:03:00', '2025-09-05 15:03:00', '2025-09-05 17:33:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(7, 7, 4, 14, '2025-11-17 13:29:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(8, 8, 5, 15, '2025-09-13 07:39:00', '2025-09-13 07:39:00', '2025-09-13 10:09:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(9, 9, 3, 7, '2025-10-27 14:12:00', '2025-10-27 14:12:00', '2025-10-27 16:42:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(10, 10, 4, 1, '2025-12-23 13:39:00', '2025-12-23 13:39:00', '2025-12-23 16:09:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(11, 11, 1, 3, '2025-11-17 11:44:00', '2025-11-17 11:44:00', '2025-11-17 14:14:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(12, 12, 2, 11, '2025-10-19 13:32:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(13, 13, 4, 1, '2025-12-21 11:46:00', '2025-12-21 11:46:00', '2025-12-21 14:16:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(14, 14, 5, 5, '2025-10-20 07:34:00', '2025-10-20 07:34:00', '2025-10-20 10:04:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(15, 15, 2, 4, '2025-09-15 12:41:00', '2025-09-15 12:41:00', '2025-09-15 15:11:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(16, 16, 1, 12, '2025-10-07 13:06:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(17, 17, 4, 13, '2025-12-03 16:12:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(18, 18, 4, 12, '2025-09-23 17:10:00', '2025-09-23 17:10:00', '2025-09-23 19:40:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(19, 19, 1, 2, '2025-10-19 11:53:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(20, 20, 1, 13, '2025-11-21 06:27:00', '2025-11-21 06:27:00', '2025-11-21 08:57:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(21, 21, 2, 1, '2025-09-01 08:22:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(22, 22, 1, 9, '2025-12-18 15:02:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(23, 23, 1, 15, '2025-09-30 07:01:00', '2025-09-30 07:01:00', '2025-09-30 09:31:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(24, 24, 5, 14, '2025-12-13 14:15:00', '2025-12-13 14:15:00', '2025-12-13 16:45:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(25, 25, 5, 4, '2025-09-12 16:37:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(26, 26, 5, 9, '2025-10-14 16:56:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(27, 27, 2, 2, '2025-09-28 08:24:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(28, 28, 2, 6, '2025-12-02 14:36:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(29, 29, 5, 2, '2025-09-06 17:46:00', '2025-09-06 17:46:00', '2025-09-06 20:16:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(30, 30, 5, 11, '2025-12-04 17:19:00', '2025-12-04 17:19:00', '2025-12-04 19:49:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(31, 31, 3, 11, '2025-12-04 16:13:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(32, 32, 5, 5, '2025-10-23 08:51:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(33, 33, 2, 10, '2025-11-18 12:55:00', '2025-11-18 12:55:00', '2025-11-18 15:25:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(34, 34, 1, 1, '2025-12-01 09:46:00', '2025-12-13 14:16:08', NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:16:08', NULL),
(35, 35, 2, 4, '2025-11-22 07:13:00', '2025-11-22 07:13:00', '2025-11-22 09:43:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(36, 36, 1, 8, '2025-09-03 08:00:00', '2025-09-03 08:00:00', '2025-09-03 10:30:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(37, 37, 4, 9, '2025-10-06 15:40:00', '2025-10-06 15:40:00', '2025-10-06 18:10:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(38, 38, 4, 12, '2025-10-10 13:29:00', '2025-10-10 13:29:00', '2025-10-10 15:59:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(39, 39, 5, 16, '2025-11-28 07:05:00', '2025-11-28 07:05:00', '2025-11-28 09:35:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(40, 40, 5, 13, '2025-12-06 07:53:00', '2025-12-06 07:53:00', '2025-12-06 10:23:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(41, 41, 3, 15, '2025-12-28 14:33:00', NULL, '2025-12-13 11:34:24', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 03:34:24', NULL),
(42, 42, 2, 11, '2025-10-11 15:55:00', '2025-10-11 15:55:00', '2025-10-11 18:25:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(43, 43, 5, 7, '2025-09-26 15:30:00', '2025-09-26 15:30:00', '2025-09-26 18:00:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(44, 44, 1, 8, '2025-10-23 15:41:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(45, 45, 2, 2, '2025-12-17 12:28:00', NULL, NULL, 'scheduled', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(46, 46, 1, 3, '2025-11-12 06:09:00', '2025-11-12 06:09:00', '2025-11-12 08:39:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(47, 47, 2, 9, '2025-10-23 08:43:00', '2025-10-23 08:43:00', '2025-10-23 11:13:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(48, 48, 1, 6, '2025-09-18 07:29:00', '2025-09-18 07:29:00', '2025-09-18 09:59:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(49, 49, 2, 14, '2025-10-09 12:05:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(50, 50, 1, 15, '2025-11-03 07:23:00', '2025-12-13 14:22:42', NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:22:42', NULL),
(51, 51, 2, 3, '2025-10-28 16:28:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(52, 52, 2, 9, '2025-10-23 13:12:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(53, 53, 2, 15, '2025-12-19 13:31:00', NULL, NULL, 'scheduled', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(54, 54, 5, 6, '2025-12-17 07:11:00', '2025-12-17 07:11:00', '2025-12-17 09:41:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(55, 55, 5, 2, '2025-12-13 08:02:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(56, 56, 4, 16, '2025-12-27 08:58:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(57, 57, 1, 7, '2025-12-26 06:46:00', '2025-12-26 06:46:00', '2025-12-26 09:16:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(58, 58, 1, 10, '2025-12-25 11:53:00', '2025-12-25 11:53:00', '2025-12-25 14:23:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(59, 59, 5, 4, '2025-12-02 09:33:00', '2025-12-13 14:18:05', NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:18:05', NULL),
(60, 60, 2, 6, '2025-10-31 07:37:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(61, 61, 4, 9, '2025-12-21 06:38:00', '2025-12-21 06:38:00', '2025-12-21 09:08:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(62, 62, 1, 1, '2025-12-24 14:29:00', '2025-12-24 14:29:00', '2025-12-24 16:59:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(63, 63, 3, 2, '2025-12-14 07:07:00', '2025-12-14 07:07:00', '2025-12-14 09:37:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(64, 64, 4, 16, '2025-12-21 07:22:00', NULL, NULL, 'scheduled', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(65, 65, 3, 1, '2025-10-15 17:07:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(66, 66, 1, 14, '2025-09-04 16:29:00', '2025-09-04 16:29:00', '2025-09-04 18:59:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(67, 67, 4, 2, '2025-12-21 12:41:00', NULL, NULL, 'scheduled', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(68, 68, 1, 4, '2025-12-21 17:57:00', '2025-12-21 17:57:00', '2025-12-21 20:27:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(69, 69, 1, 11, '2025-10-19 06:33:00', '2025-10-19 06:33:00', '2025-10-19 09:03:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(70, 70, 4, 8, '2025-12-21 07:11:00', '2025-12-21 07:11:00', '2025-12-21 09:41:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(71, 71, 3, 12, '2025-11-24 14:12:00', '2025-11-24 14:12:00', '2025-11-24 16:42:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(72, 72, 4, 16, '2025-12-01 06:52:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(73, 73, 2, 4, '2025-09-11 14:15:00', '2025-09-11 14:15:00', '2025-09-11 16:45:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(74, 74, 5, 2, '2025-11-16 09:37:00', '2025-11-16 09:37:00', '2025-11-16 12:07:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(75, 75, 4, 10, '2025-10-05 16:48:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(76, 76, 5, 7, '2025-11-01 11:48:00', '2025-11-01 11:48:00', '2025-11-01 14:18:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(77, 77, 1, 2, '2025-10-28 14:25:00', '2025-10-28 14:25:00', '2025-10-28 16:55:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(78, 78, 2, 12, '2025-10-20 11:03:00', '2025-10-20 11:03:00', '2025-10-20 13:33:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(79, 79, 3, 16, '2025-11-16 07:44:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(80, 80, 3, 12, '2025-10-20 13:52:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(81, 81, 3, 14, '2025-11-28 16:01:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(82, 82, 5, 3, '2025-10-08 10:00:00', '2025-10-08 10:00:00', '2025-10-08 12:30:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(83, 83, 4, 3, '2025-09-09 08:41:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(84, 84, 3, 3, '2025-09-24 06:31:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(85, 85, 5, 12, '2025-10-22 09:32:00', '2025-10-22 09:32:00', '2025-10-22 12:02:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(86, 86, 1, 12, '2025-09-27 13:55:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(87, 87, 4, 6, '2025-12-24 14:33:00', '2025-12-24 14:33:00', '2025-12-24 17:03:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(88, 88, 5, 9, '2025-09-28 10:42:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(89, 89, 2, 2, '2025-12-03 16:29:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(90, 90, 5, 7, '2025-09-20 07:15:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(91, 91, 5, 15, '2025-11-07 12:28:00', '2025-11-07 12:28:00', '2025-11-07 14:58:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(92, 92, 5, 3, '2025-11-29 06:26:00', '2025-11-29 06:26:00', '2025-11-29 08:56:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(93, 93, 1, 13, '2025-09-26 07:17:00', '2025-09-26 07:17:00', '2025-09-26 09:47:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(94, 94, 5, 3, '2025-10-03 08:39:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(95, 95, 3, 15, '2025-12-18 12:11:00', '2025-12-18 12:11:00', '2025-12-18 14:41:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(96, 96, 3, 9, '2025-10-29 07:50:00', '2025-10-29 07:50:00', '2025-10-29 10:20:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(97, 97, 3, 16, '2025-11-12 08:45:00', '2025-11-12 08:45:00', '2025-11-12 11:15:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(98, 98, 2, 8, '2025-10-05 06:21:00', '2025-10-05 06:21:00', '2025-10-05 08:51:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(99, 99, 4, 8, '2025-11-25 15:34:00', '2025-11-25 15:34:00', '2025-11-25 18:04:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(100, 100, 2, 1, '2025-11-24 06:47:00', '2025-11-24 06:47:00', '2025-11-24 09:17:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(101, 101, 2, 13, '2025-10-27 12:17:00', '2025-10-27 12:17:00', '2025-10-27 14:47:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(102, 102, 3, 15, '2025-11-07 17:15:00', '2025-11-07 17:15:00', '2025-11-07 19:45:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(103, 103, 4, 15, '2025-11-12 13:02:00', '2025-11-12 13:02:00', '2025-11-12 15:32:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(104, 104, 1, 2, '2025-09-09 09:46:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(105, 105, 2, 16, '2025-09-11 11:41:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(106, 106, 4, 12, '2025-11-08 06:17:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(107, 107, 5, 1, '2025-11-09 11:37:00', '2025-12-13 14:19:59', NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:19:59', NULL),
(108, 108, 2, 9, '2025-12-19 09:54:00', '2025-12-19 09:54:00', '2025-12-19 12:24:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(109, 109, 4, 13, '2025-10-19 14:45:00', '2025-10-19 14:45:00', '2025-10-19 17:15:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(110, 110, 4, 1, '2025-09-08 11:41:00', '2025-09-08 11:41:00', '2025-09-08 14:11:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(111, 111, 4, 8, '2025-09-01 07:07:00', '2025-09-01 07:07:00', '2025-09-01 09:37:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(112, 112, 5, 15, '2025-09-13 06:11:00', '2025-09-13 06:11:00', '2025-09-13 08:41:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(113, 113, 2, 3, '2025-10-03 10:08:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(114, 114, 4, 10, '2025-12-21 06:07:00', '2025-12-21 06:07:00', '2025-12-21 08:37:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(115, 115, 4, 16, '2025-12-22 17:40:00', '2025-12-22 17:40:00', '2025-12-22 20:10:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(116, 116, 1, 6, '2025-11-17 09:18:00', '2025-11-17 09:18:00', '2025-11-17 11:48:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(117, 117, 2, 11, '2025-11-16 16:15:00', '2025-12-13 14:19:52', NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:19:52', NULL),
(118, 118, 1, 4, '2025-09-22 13:57:00', '2025-09-22 13:57:00', '2025-09-22 16:27:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(119, 119, 3, 12, '2025-11-13 08:40:00', '2025-11-13 08:40:00', '2025-11-13 11:10:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(120, 120, 5, 14, '2025-10-23 13:50:00', '2025-10-23 13:50:00', '2025-10-23 16:20:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(121, 121, 2, 10, '2025-11-19 15:42:00', '2025-11-19 15:42:00', '2025-11-19 18:12:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(122, 122, 2, 12, '2025-12-25 13:45:00', '2025-12-25 13:45:00', '2025-12-25 16:15:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(123, 123, 4, 7, '2025-11-24 11:21:00', '2025-11-24 11:21:00', '2025-11-24 13:51:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(124, 124, 1, 4, '2025-09-11 07:29:00', NULL, NULL, 'archived', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', '2025-12-17 07:52:57'),
(125, 125, 4, 8, '2025-12-23 14:04:00', '2025-12-23 14:04:00', '2025-12-23 16:34:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(126, 126, 2, 1, '2025-10-08 10:33:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(127, 127, 4, 5, '2025-09-06 15:40:00', '2025-09-06 15:40:00', '2025-09-06 18:10:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(128, 128, 2, 4, '2025-09-13 12:33:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(129, 129, 3, 13, '2025-09-10 17:14:00', '2025-09-10 17:14:00', '2025-09-10 19:44:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(130, 130, 3, 13, '2025-09-13 14:31:00', '2025-09-13 14:31:00', '2025-09-13 17:01:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(131, 131, 5, 8, '2025-09-29 14:35:00', '2025-09-29 14:35:00', '2025-09-29 17:05:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(132, 132, 2, 7, '2025-11-25 14:32:00', '2025-11-25 14:32:00', '2025-11-25 17:02:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(133, 133, 4, 12, '2025-10-19 17:42:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(134, 134, 5, 16, '2025-09-09 10:43:00', '2025-09-09 10:43:00', '2025-09-09 13:13:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(135, 135, 4, 11, '2025-12-01 12:38:00', '2025-12-01 12:38:00', '2025-12-01 15:08:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(136, 136, 1, 15, '2025-10-22 11:33:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(137, 137, 2, 6, '2025-09-21 14:04:00', '2025-09-21 14:04:00', '2025-09-21 16:34:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(138, 138, 1, 3, '2025-12-08 14:02:00', '2025-12-08 14:02:00', '2025-12-08 16:32:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(139, 139, 4, 15, '2025-11-07 06:33:00', '2025-11-07 06:33:00', '2025-11-07 09:03:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(140, 140, 5, 2, '2025-11-09 07:21:00', '2025-11-09 07:21:00', '2025-11-09 09:51:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(141, 141, 4, 15, '2025-12-21 16:04:00', '2025-12-21 16:04:00', '2025-12-21 18:34:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(142, 142, 1, 8, '2025-10-10 16:49:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(143, 143, 4, 12, '2025-09-14 10:32:00', '2025-09-14 10:32:00', '2025-09-14 13:02:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(144, 144, 1, 14, '2025-09-01 15:18:00', '2025-09-01 15:18:00', '2025-09-01 17:48:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(145, 145, 2, 7, '2025-09-22 09:27:00', NULL, NULL, 'in-transit', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(146, 146, 1, 9, '2025-11-04 11:27:00', '2025-11-04 11:27:00', '2025-11-04 13:57:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(147, 147, 4, 7, '2025-09-17 12:01:00', '2025-09-17 12:01:00', '2025-09-17 14:31:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(148, 148, 3, 9, '2025-11-07 14:38:00', '2025-11-07 14:38:00', '2025-11-07 17:08:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(149, 149, 4, 11, '2025-09-12 06:54:00', '2025-09-12 06:54:00', '2025-09-12 09:24:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL),
(150, 150, 1, 8, '2025-10-03 11:33:00', '2025-10-03 11:33:00', '2025-10-03 14:03:00', 'completed', NULL, '2025-12-13 06:24:19', '2025-12-13 06:24:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trip_updates`
--

CREATE TABLE `trip_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `trip_id` bigint(20) UNSIGNED NOT NULL,
  `update_type` enum('status','location','delay','incident','completed') NOT NULL,
  `message` text NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `reported_by` enum('driver','dispatcher') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trip_updates`
--

INSERT INTO `trip_updates` (`id`, `trip_id`, `update_type`, `message`, `location`, `reported_by`, `created_at`, `updated_at`) VALUES
(1, 41, 'status', 'Trip completed successfully', NULL, 'dispatcher', '2025-12-13 03:34:24', '2025-12-13 03:34:24'),
(2, 34, 'status', 'Trip started by dispatcher', NULL, 'dispatcher', '2025-12-13 06:16:08', '2025-12-13 06:16:08'),
(3, 59, 'status', 'Trip started by dispatcher', NULL, 'dispatcher', '2025-12-13 06:18:05', '2025-12-13 06:18:05'),
(4, 117, 'status', 'Trip started by dispatcher', NULL, 'dispatcher', '2025-12-13 06:19:52', '2025-12-13 06:19:52'),
(5, 107, 'status', 'Trip started by dispatcher', NULL, 'dispatcher', '2025-12-13 06:19:59', '2025-12-13 06:19:59'),
(6, 50, 'status', 'Trip started by dispatcher', NULL, 'dispatcher', '2025-12-13 06:22:42', '2025-12-13 06:22:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `google_id`, `name`, `email`, `avatar`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, '100481062570868569971', 'JUSTIN SAYSON', 'j.sayson.546917@umindanao.edu.ph', 'https://lh3.googleusercontent.com/a/ACg8ocL5hHp0VUhb_xy58UD-1AsaY3QrE1NucMLFb4bK-YmnpeF8NA=s96-c', 'admin', NULL, '$2y$12$7kTjarchr9hShz5kTLuPTuHemLf.ux4YlZo8pIQDKlDqZ4Tv0UBBq', 'u81yUM4dhA6VCXcw5LxWNofVCGif20usEAOKtzYW0S1Rx15a63MCQoHosha5', '2025-12-08 00:05:56', '2025-12-08 00:05:56'),
(4, '116826970709442543775', 'Sky Tear', 'skytear976@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIwohpAPvw0_NA432HQ5K7REKlUQuo3PoFrkUcfQNnbc2fExQ=s96-c', 'user', NULL, '$2y$12$NYnlRFNyaSEZLmQR53ED7.Fv7uaoJQyw4uzZi.OexMJdvV3VHg/9e', 'MsY6xHBUY7xWAYtKTLDnnAHizrKvEUHP25hClXYQ227T39y3v8DH2DIenEvf', '2025-12-18 01:46:46', '2025-12-18 01:46:46'),
(5, '100425015874412785921', 'Troy Lee', 'nosyasxd@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIdFNJ_4mW6Odn8SATEgR9mz9Xzl2gX0hRw9J1KVx5d1q8X1hgM=s96-c', 'user', NULL, '$2y$12$XppkanWeeZ/s4sGzkNI.wetCDk8LirwdU.PsbczZGz0HxuqpRMmZa', 'ONgOndRpVnLTrZBjWE4p9fOXyf7oJ7zClWNMn2ShM0w1UQNHS0TBINSvLadQ', '2025-12-08 20:10:26', '2025-12-13 05:01:50'),
(6, NULL, 'Test User', 'john@gmail.com', NULL, 'dispatch-user', '2025-12-08 23:16:31', '$2y$12$5A18MRb1jb0gkCf8osLlueSsJLZyqJJHJPwd0F8pb8nbiT7YOmlAW', NULL, '2025-12-08 23:16:32', '2025-12-08 23:16:32'),
(7, NULL, 'Head of Dispatch', 'admin@nvg.movers', NULL, 'admin', NULL, '$2y$12$s1mqz1GHTlOHrpJOOtXZZOlb9SZT9IY3.C0jy2K7Or0bO4j9va8J.', NULL, '2025-12-08 23:16:32', '2025-12-08 23:16:32'),
(8, NULL, 'Dispatch Officer 1', 'dispatch@nvg.movers', NULL, 'dispatch-user', NULL, '$2y$12$64Xm8y5KiVDsjImE7cAeO.1qY7yOyx5Xcwcdk6rA9iIH54Okf7bXS', NULL, '2025-12-08 23:16:33', '2025-12-08 23:16:33'),
(9, NULL, 'Dispatch Officer 2', 'dispatch2@nvg.movers', NULL, 'dispatch-user', NULL, '$2y$12$E8CsskTXvXMkAJRbWBlzzeFq45FlJTdlCdNBgGkJGGyPKt3mvNt2y', NULL, '2025-12-08 23:16:33', '2025-12-08 23:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plate_number` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `trailer_type` varchar(255) DEFAULT NULL,
  `status` enum('available','in-use','maintenance') NOT NULL DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `plate_number`, `vehicle_type`, `trailer_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 'NVG-1001', 'Tanker Truck', '40ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(2, 'NVG-819', 'Container Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(3, 'NVG-1050', 'Tanker Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(4, 'NVG-2001', 'Tanker Truck', '40ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(5, 'NVG-3001', 'Box Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(6, 'NVG-4001', 'Flatbed Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(7, 'NVG-7001', 'Tanker Truck', '40ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(8, 'NVG-8001', 'Tanker Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(9, 'NVG-9001', 'Flatbed Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(10, 'NVG-1010', 'Box Truck', '40ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(11, 'NVG-1011', 'Container Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(12, 'NVG-1012', 'Box Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(13, 'NVG-1013', 'Flatbed Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(14, 'NVG-1014', 'Prime Mover', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(15, 'NVG-1015', 'Tanker Truck', '20ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 03:34:24'),
(16, 'KAL-3650', 'Tanker Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(17, 'KAL-3652', 'Flatbed Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(18, 'NVG-1018', 'Box Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(19, 'LAI-6719', 'Tanker Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(20, 'NVG-2000', 'Prime Mover', '20ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(21, 'NFS-2081', 'Flatbed Truck', '20ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(22, 'KAL-9248', 'Tanker Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(23, 'NKF-9247', 'Container Truck', '20ft Container', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(24, 'NKF-9248', 'Container Truck', '40ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(25, 'NVG-5001', 'Flatbed Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(26, 'NVG-5002', 'Flatbed Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(27, 'NVG-2700', 'Flatbed Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(28, 'NVG-2800', 'Container Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(29, 'NVG-2900', 'Tanker Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(30, 'NVG-3000', 'Container Truck', '20ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(31, 'NVG-3100', 'Container Truck', 'Flatbed', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53'),
(32, 'NVG-3200', 'Tanker Truck', '40ft Reefer', 'available', '2025-12-13 11:27:53', '2025-12-13 11:27:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_notifications`
--
ALTER TABLE `client_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_notifications_trip_id_foreign` (`trip_id`),
  ADD KEY `client_notifications_client_id_foreign` (`client_id`);

--
-- Indexes for table `delivery_requests`
--
ALTER TABLE `delivery_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_requests_client_id_foreign` (`client_id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trips_delivery_request_id_foreign` (`delivery_request_id`),
  ADD KEY `trips_driver_id_foreign` (`driver_id`),
  ADD KEY `trips_vehicle_id_foreign` (`vehicle_id`);

--
-- Indexes for table `trip_updates`
--
ALTER TABLE `trip_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_updates_trip_id_foreign` (`trip_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_plate_number_unique` (`plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `client_notifications`
--
ALTER TABLE `client_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_requests`
--
ALTER TABLE `delivery_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5036;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `trip_updates`
--
ALTER TABLE `trip_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `client_notifications`
--
ALTER TABLE `client_notifications`
  ADD CONSTRAINT `client_notifications_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_notifications_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_requests`
--
ALTER TABLE `delivery_requests`
  ADD CONSTRAINT `delivery_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_delivery_request_id_foreign` FOREIGN KEY (`delivery_request_id`) REFERENCES `delivery_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trips_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_updates`
--
ALTER TABLE `trip_updates`
  ADD CONSTRAINT `trip_updates_trip_id_foreign` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
