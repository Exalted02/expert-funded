-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 17, 2025 at 01:58 PM
-- Server version: 10.11.11-MariaDB-cll-lve
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `srv80615_expert_funded_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `challenge_types`
--

CREATE TABLE `challenge_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` double(8,2) DEFAULT NULL,
  `percent` varchar(255) DEFAULT NULL,
  `amount_paid` double(8,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '0=inactive, 1=active, 2=delete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenge_types`
--

INSERT INTO `challenge_types` (`id`, `title`, `amount`, `percent`, `amount_paid`, `status`, `created_at`, `updated_at`) VALUES
(1, '25K 1 Phase', 25000.00, NULL, 329.00, 1, '2025-03-28 13:16:18', NULL),
(2, '50K 1 Phase', 50000.00, NULL, 350.00, 1, '2025-03-27 18:30:00', NULL),
(3, '100K 1 Phase', 100000.00, NULL, 550.00, 1, '0000-00-00 00:00:00', NULL),
(4, '200K 1 Phase', 200000.00, NULL, 1050.00, 1, '2025-03-27 18:30:00', NULL),
(5, '300K 1 Phase', 300000.00, NULL, 1600.00, 1, '2025-03-27 18:30:00', NULL),
(6, '400K 1 Phase', 400000.00, NULL, 2100.00, 1, '2025-03-27 18:30:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `challenge_types`
--
ALTER TABLE `challenge_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `challenge_types`
--
ALTER TABLE `challenge_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
