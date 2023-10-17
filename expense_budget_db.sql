-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 16, 2023 at 01:11 PM
-- Server version: 8.0.30
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `expense_budget_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `category` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `balance` float NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `description`, `status`, `balance`, `date_created`, `date_updated`) VALUES
(1, 'Sen. Bong Go', 'Malasakit Budget', 1, 974000, '2023-10-14 22:59:54', '2023-10-16 00:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `running_balance`
--

CREATE TABLE `running_balance` (
  `id` int NOT NULL,
  `control_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `balance_type` tinyint(1) DEFAULT NULL COMMENT '1=budget, 2=expense',
  `category_id` int DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `amount_in_words` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `lname` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fname` varchar(32) COLLATE utf8mb4_general_ci NOT NULL,
  `mname` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `age` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sex` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `referred_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disposition` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `doctors` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `status` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'To be Claimed',
  `date_claimed` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `date_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `running_balance`
--

INSERT INTO `running_balance` (`id`, `control_number`, `balance_type`, `category_id`, `amount`, `amount_in_words`, `lname`, `fname`, `mname`, `age`, `sex`, `address`, `referred_to`, `disposition`, `doctors`, `remarks`, `status`, `date_claimed`, `user_id`, `date_created`, `date_updated`) VALUES
(3, NULL, 1, 1, 1000000, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'To be Claimed', NULL, '1', '2023-10-14 23:24:14', NULL),
(5, 'A001-20231016', 2, 1, 12000, 'Twelve  Thousand', 'DELA CRUZ', 'JUAN', 'TWO', '25', 'Male', 'Brgy. Ligtasan, Tarlac City', 'JOSE B. LINGAD MEMORIAL REGIONAL HOSPITAL', 'MRI Plain, CT Scan Plain, 2D Echo Plain, Ultrasound Plain, Chemotherapy, Implant', 'Dr. Willie Ong', '<p>yes</p>', 'To be Claimed', NULL, '1', '2023-10-14 23:40:51', NULL),
(6, 'A001-20231016', 2, 1, 14000, 'Fourteen  Thousand', 'DELA CRUZA', 'JUANA', 'ATOO', '27', 'Female', 'Brgy. Ligtasan, Tarlac City', 'JOSE B. LINGAD MEMORIAL REGIONAL HOSPITAL', 'MRI Plain, CT Scan Plain, 2D Echo Plain, Ultrasound Plain, Laboratory', 'Dr. Willie Ong', '', 'Claimed', '2023-10-16 21:09:59', '6', '2023-10-16 00:05:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int NOT NULL,
  `meta_field` text COLLATE utf8mb4_general_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'CGT(JBL)'),
(6, 'short_name', 'CGT'),
(11, 'logo', 'uploads/1627606920_modeylogo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `lastname` varchar(250) COLLATE utf8mb4_general_ci NOT NULL,
  `username` text COLLATE utf8mb4_general_ci NOT NULL,
  `password` text COLLATE utf8mb4_general_ci NOT NULL,
  `avatar` text COLLATE utf8mb4_general_ci,
  `last_login` datetime DEFAULT NULL,
  `login_type` tinyint(1) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `password`, `avatar`, `last_login`, `login_type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/1624240500_avatar.png', NULL, 1, '2021-01-20 14:02:37', '2021-06-21 09:55:07'),
(6, 'User', 'Ako', 'asd', '7815696ecbf1c96e6894b779456d330e', 'uploads/1693892280_png-transparent-profile-logo-computer-icons-user-user-blue-heroes-logo-thumbnail.png', NULL, 0, '2023-08-30 23:18:26', '2023-09-05 13:39:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `running_balance`
--
ALTER TABLE `running_balance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `running_balance`
--
ALTER TABLE `running_balance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `running_balance`
--
ALTER TABLE `running_balance`
  ADD CONSTRAINT `running_balance_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
