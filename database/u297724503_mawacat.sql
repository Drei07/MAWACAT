-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 23, 2024 at 11:31 AM
-- Server version: 10.11.8-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u297724503_mawacat`
--

-- --------------------------------------------------------

--
-- Table structure for table `analyzing_time`
--

CREATE TABLE `analyzing_time` (
  `id` int(14) NOT NULL,
  `user_id` int(14) NOT NULL,
  `time` int(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `analyzing_time`
--

INSERT INTO `analyzing_time` (`id`, `user_id`, `time`, `created_at`, `updated_at`) VALUES
(1, 1, 50000, '2024-04-04 02:50:45', '2024-05-14 14:09:32');

-- --------------------------------------------------------

--
-- Table structure for table `email_config`
--

CREATE TABLE `email_config` (
  `id` int(145) NOT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_config`
--

INSERT INTO `email_config` (`id`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'ecket2023@gmail.com', 'vnvbophnoxickore\n', '2023-02-19 19:25:24', '2023-07-11 18:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `google_recaptcha_api`
--

CREATE TABLE `google_recaptcha_api` (
  `Id` int(11) NOT NULL,
  `site_key` varchar(145) DEFAULT NULL,
  `site_secret_key` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `google_recaptcha_api`
--

INSERT INTO `google_recaptcha_api` (`Id`, `site_key`, `site_secret_key`, `created_at`, `updated_at`) VALUES
(1, '6LdiQZQhAAAAABpaNFtJpgzGpmQv2FwhaqNj2azh', '6LdiQZQhAAAAAByS6pnNjOs9xdYXMrrW2OeTFlrm', '2023-02-19 08:57:18', '2023-08-12 08:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` varchar(145) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `activity`, `created_at`, `updated_at`) VALUES
(1, 1, 'Has successfully signed in', '2024-05-21 07:28:15', NULL),
(2, 1, 'Has successfully signed in', '2024-05-21 07:33:35', NULL),
(3, 1, 'Has successfully signed in', '2024-05-21 07:34:25', NULL),
(4, 1, 'Has successfully signed in', '2024-05-21 10:27:46', NULL),
(5, 1, 'Has successfully signed in', '2024-05-28 02:04:59', NULL),
(6, 1, 'Has successfully signed in', '2024-05-29 06:31:29', NULL),
(7, 1, 'Has successfully signed in', '2024-05-29 09:35:14', NULL),
(8, 1, 'Has successfully signed in', '2024-05-29 09:36:11', NULL),
(9, 1, 'Has successfully signed in', '2024-05-30 01:52:30', NULL),
(10, 1, 'Has successfully signed in', '2024-05-30 09:53:51', NULL),
(11, 1, 'Has successfully signed in', '2024-05-31 13:36:43', NULL),
(12, 1, 'Has successfully signed in', '2024-06-02 04:40:25', NULL),
(13, 1, 'Has successfully signed in', '2024-06-02 07:21:58', NULL),
(14, 1, 'Has successfully signed in', '2024-06-02 11:39:56', NULL),
(15, 1, 'Has successfully signed in', '2024-06-02 11:43:01', NULL),
(16, 1, 'Has successfully signed in', '2024-06-02 11:43:31', NULL),
(17, 1, 'Has successfully signed in', '2024-06-02 11:46:28', NULL),
(18, 1, 'Has successfully signed in', '2024-06-02 11:53:42', NULL),
(19, 1, 'Has successfully signed in', '2024-06-02 23:47:59', NULL),
(20, 1, 'Has successfully signed in', '2024-06-03 00:06:30', NULL),
(21, 1, 'Has successfully signed in', '2024-06-03 06:57:06', NULL),
(22, 1, 'Has successfully signed in', '2024-06-05 07:29:58', NULL),
(23, 1, 'Has successfully signed in', '2024-06-12 15:32:35', NULL),
(24, 1, 'Has successfully signed in', '2024-06-14 06:00:04', NULL),
(25, 1, 'Has successfully signed in', '2024-06-17 06:21:14', NULL),
(26, 1, 'Has successfully signed in', '2024-06-17 06:47:05', NULL),
(27, 1, 'Has successfully signed in', '2024-06-18 00:57:41', NULL),
(28, 1, 'Has successfully signed in', '2024-06-20 03:28:53', NULL),
(29, 1, 'Has successfully signed in', '2024-06-20 06:40:24', NULL),
(30, 1, 'Has successfully signed in', '2024-06-20 09:25:04', NULL),
(31, 1, 'Has successfully signed in', '2024-06-20 09:48:51', NULL),
(32, 1, 'Has successfully signed in', '2024-06-20 10:00:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_config`
--

CREATE TABLE `system_config` (
  `id` int(14) NOT NULL,
  `system_name` varchar(145) DEFAULT NULL,
  `system_phone_number` varchar(145) DEFAULT NULL,
  `system_email` varchar(145) DEFAULT NULL,
  `system_logo` varchar(145) DEFAULT NULL,
  `system_favicon` varchar(145) DEFAULT NULL,
  `system_color` varchar(145) DEFAULT NULL,
  `system_copy_right` varchar(145) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_config`
--

INSERT INTO `system_config` (`id`, `system_name`, `system_phone_number`, `system_email`, `system_logo`, `system_favicon`, `system_color`, `system_copy_right`, `created_at`, `updated_at`) VALUES
(1, 'MAWACAT AQUA SENSE', '9776621925', 'aqua.sense2024@gmail.com', 'aqua-sense.svg', 'aqua-sense-favicon-color.png', NULL, 'COPYRIGHT © 2024 - MAWACAT AQUA SENSE. ALL RIGHTS RESERVED.', '2023-02-19 16:16:44', '2024-05-28 02:36:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(145) DEFAULT NULL,
  `middle_name` varchar(145) DEFAULT NULL,
  `last_name` varchar(145) DEFAULT NULL,
  `sex` varchar(145) DEFAULT NULL COMMENT 'male=1, female=2',
  `date_of_birth` varchar(145) DEFAULT NULL,
  `age` varchar(145) DEFAULT NULL,
  `civil_status` varchar(145) DEFAULT NULL,
  `phone_number` varchar(145) DEFAULT NULL,
  `email` varchar(145) DEFAULT NULL,
  `password` varchar(145) DEFAULT NULL,
  `profile` varchar(1145) NOT NULL DEFAULT 'profile.png',
  `status` enum('Y','N','D') DEFAULT 'N',
  `tokencode` varchar(145) DEFAULT NULL,
  `account_status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `user_type` varchar(14) DEFAULT NULL COMMENT 'superadmin=0,\r\nadmin=1,\r\nagent=2,\r\nuser=3',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `middle_name`, `last_name`, `sex`, `date_of_birth`, `age`, `civil_status`, `phone_number`, `email`, `password`, `profile`, `status`, `tokencode`, `account_status`, `user_type`, `created_at`, `updated_at`) VALUES
(1, 'Mary Rose', 'Cerenio', 'Valencia', 'FEMALE', NULL, NULL, 'SINGLE', '9776621929', 'mcvalencia@dhvsu.edu.ph', '42f749ade7f9e195bf475f37a44cafcb', 'profile.png', 'Y', '174b66675b0e6170e83b8f4fbd7ba02f', 'active', '1', '2023-11-19 20:14:08', '2024-05-21 05:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `water_metrics`
--

CREATE TABLE `water_metrics` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `temperature_level` decimal(10,2) DEFAULT NULL,
  `ph_level` decimal(14,2) DEFAULT NULL,
  `tds_level` int(14) DEFAULT NULL,
  `turbidity_level` int(14) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_metrics`
--

INSERT INTO `water_metrics` (`id`, `user_id`, `temperature_level`, `ph_level`, `tds_level`, `turbidity_level`, `created_at`, `updated_at`) VALUES
(1, 1, '31.00', '7.83', 154, 0, '2024-05-21 07:37:03', NULL),
(2, 1, '31.00', '8.06', 152, 0, '2024-05-21 07:39:05', NULL),
(3, 1, '31.00', '7.75', 158, 0, '2024-05-21 07:40:17', NULL),
(4, 1, '31.00', '7.81', 155, 0, '2024-05-21 07:41:37', NULL),
(5, 1, '31.00', '8.18', 159, 0, '2024-05-21 07:43:44', NULL),
(6, 1, '33.00', '7.69', 161, 0, '2024-05-21 08:16:10', NULL),
(7, 1, '33.00', '7.88', 151, 0, '2024-05-21 08:18:11', NULL),
(8, 1, '33.00', '7.91', 158, 0, '2024-05-21 08:19:10', NULL),
(9, 1, '33.00', '7.86', 161, 0, '2024-05-21 08:20:07', NULL),
(10, 1, '33.00', '7.93', 162, 0, '2024-05-21 08:21:15', NULL),
(11, 1, '29.00', '7.87', 154, 0, '2024-05-21 08:30:50', NULL),
(12, 1, '30.00', '8.09', 151, 0, '2024-05-21 08:31:48', NULL),
(13, 1, '30.00', '8.04', 159, 0, '2024-05-21 08:32:50', NULL),
(14, 1, '29.00', '8.05', 152, 0, '2024-05-21 08:33:51', NULL),
(15, 1, '30.00', '7.80', 154, 0, '2024-05-21 08:33:54', NULL),
(16, 1, '31.00', '8.15', 154, 0, '2024-05-21 08:50:25', NULL),
(17, 1, '31.00', '7.90', 168, 0, '2024-05-21 08:51:46', NULL),
(18, 1, '31.00', '8.11', 168, 0, '2024-05-21 08:52:46', NULL),
(19, 1, '31.00', '8.16', 164, 0, '2024-05-21 08:53:59', NULL),
(20, 1, '31.00', '7.93', 162, 0, '2024-05-21 08:54:57', NULL),
(21, 1, '32.00', '7.36', 200, 0, '2024-05-21 10:33:23', NULL),
(22, 1, '32.00', '7.37', 196, 0, '2024-05-21 10:34:19', NULL),
(23, 1, '32.00', '7.00', 197, 0, '2024-05-21 10:35:19', NULL),
(24, 1, '32.00', '7.05', 205, 0, '2024-05-21 10:36:36', NULL),
(25, 1, '32.00', '7.31', 200, 0, '2024-05-21 10:37:34', NULL),
(26, 1, '32.94', '6.49', 0, 0, '2024-06-02 11:45:06', NULL),
(27, 1, '32.88', '6.48', 0, 0, '2024-06-02 11:47:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `water_quality_parameter`
--

CREATE TABLE `water_quality_parameter` (
  `id` int(11) NOT NULL,
  `sensor` varchar(145) NOT NULL,
  `low` float DEFAULT NULL,
  `high` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `water_quality_parameter`
--

INSERT INTO `water_quality_parameter` (`id`, `sensor`, `low`, `high`, `created_at`, `updated_at`) VALUES
(1, 'pH', 6.5, 8.5, '2024-05-30 02:01:15', '2024-05-30 06:52:52'),
(2, 'TDS', 0, 600, '2024-05-30 02:01:21', '2024-05-30 06:56:27'),
(3, 'Turbidity', 0, 5, '2024-05-30 02:01:32', '2024-05-30 02:38:58'),
(4, 'Temperature', 26, 30, '2024-05-30 02:01:37', '2024-05-30 06:54:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `analyzing_time`
--
ALTER TABLE `analyzing_time`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `email_config`
--
ALTER TABLE `email_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `google_recaptcha_api`
--
ALTER TABLE `google_recaptcha_api`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_config`
--
ALTER TABLE `system_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `water_metrics`
--
ALTER TABLE `water_metrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `water_quality_parameter`
--
ALTER TABLE `water_quality_parameter`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `analyzing_time`
--
ALTER TABLE `analyzing_time`
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `water_metrics`
--
ALTER TABLE `water_metrics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `water_quality_parameter`
--
ALTER TABLE `water_quality_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analyzing_time`
--
ALTER TABLE `analyzing_time`
  ADD CONSTRAINT `analyzing_time_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `water_metrics`
--
ALTER TABLE `water_metrics`
  ADD CONSTRAINT `water_metrics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
