-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 01:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `memory`
--

-- --------------------------------------------------------

--
-- Table structure for table `cartes`
--

CREATE TABLE `cartes` (
  `id` int NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image_recto` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cartes`
--

INSERT INTO `cartes` (`id`, `nom`, `image_recto`) VALUES
(1, 'snoopy', '/assets/images/snoopy.png'),
(2, 'lucy', '/assets/images/lucy.png'),
(3, 'sally', '/assets/images/sally.png'),
(4, 'charlie_brown', '/assets/images/charlie_brown.png'),
(5, 'linus', '/assets/images/linus.png'),
(6, 'woodstock', '/assets/images/woodstock.png'),
(8, 'peppermint_patty', '/assets/images/peppermint_patty.png'),
(11, 'franklin', '/assets/images/franklin.png'),
(12, 'marcie', '/assets/images/marcie.png'),
(13, 'schroeder', '/assets/images/schroeder.png'),
(14, 'rerun', '/assets/images/rerun.png'),
(15, 'shermy', '/assets/images/shermy.png');

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE `scores` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `coups` int NOT NULL,
  `date_partie` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `user_id`, `coups`, `date_partie`) VALUES
(1, 7, 95, '2025-11-24 13:39:13'),
(2, 7, 33, '2025-11-24 13:51:41'),
(3, 7, 28, '2025-11-24 14:11:28'),
(4, 5, 4, '2025-11-25 11:05:56'),
(5, 5, 5, '2025-11-25 11:11:11'),
(6, 5, 4, '2025-11-25 11:22:58'),
(7, 5, 6, '2025-11-25 11:27:02'),
(8, 5, 13, '2025-11-25 11:28:27'),
(9, 5, 27, '2025-11-25 11:53:57'),
(10, 5, 5, '2025-11-25 11:54:41'),
(11, 5, 5, '2025-11-26 09:16:25'),
(12, 5, 5, '2025-11-26 09:19:13'),
(13, 5, 5, '2025-11-26 09:20:10'),
(14, 5, 4, '2025-11-26 09:21:44'),
(15, 5, 4, '2025-11-26 09:38:33'),
(16, 5, 5, '2025-11-26 09:50:45'),
(17, 5, 4, '2025-11-26 09:51:57'),
(18, 5, 5, '2025-11-26 09:53:06'),
(19, 5, 5, '2025-11-26 09:54:11'),
(20, 5, 4, '2025-11-26 09:55:07'),
(21, 5, 6, '2025-11-26 09:56:43'),
(22, 5, 8, '2025-11-26 09:58:32'),
(23, 5, 7, '2025-11-26 10:03:29'),
(24, 5, 5, '2025-11-26 11:15:16'),
(25, 5, 3, '2025-11-26 11:18:02'),
(26, 1, 5, '2025-11-26 13:16:06'),
(27, 1, 6, '2025-11-26 13:27:34'),
(28, 1, 13, '2025-11-26 13:30:18'),
(29, 1, 8, '2025-11-26 14:24:50'),
(30, 1, 9, '2025-11-26 14:26:39'),
(31, 1, 8, '2025-11-26 14:29:53'),
(32, 1, 5, '2025-11-26 14:33:31'),
(33, 1, 10, '2025-11-26 14:49:19'),
(34, 1, 17, '2025-11-26 14:55:20'),
(35, 1, 7, '2025-11-26 14:59:00'),
(36, 1, 15, '2025-11-26 15:11:54'),
(37, 19, 10, '2025-11-26 15:21:40'),
(38, 19, 18, '2025-11-26 15:25:38'),
(39, 19, 9, '2025-11-26 15:29:45'),
(40, 20, 7, '2025-11-26 15:35:33'),
(41, 20, 13, '2025-11-26 15:56:34'),
(42, 20, 17, '2025-11-26 15:58:25'),
(43, 20, 7, '2025-11-26 16:00:51'),
(44, 20, 8, '2025-11-26 16:20:25'),
(45, 20, 10, '2025-11-26 16:24:34'),
(46, 1, 10, '2025-11-26 16:33:21'),
(47, 1, 7, '2025-11-26 16:38:17'),
(48, 1, 10, '2025-11-26 16:39:57'),
(49, 1, 7, '2025-11-27 09:39:26'),
(50, 1, 11, '2025-11-27 09:44:21'),
(51, 1, 32, '2025-11-27 09:48:27'),
(52, 1, 4, '2025-11-27 09:49:48'),
(53, 1, 6, '2025-11-27 10:58:03'),
(54, 1, 7, '2025-11-27 10:58:48'),
(55, 1, 6, '2025-11-27 11:46:27'),
(56, 24, 11, '2025-11-27 11:48:03'),
(57, 24, 5, '2025-11-27 13:24:11'),
(58, 24, 6, '2025-11-27 13:25:03'),
(59, 1, 11, '2025-11-27 13:29:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `created_at`) VALUES
(1, 'Manux', '2025-11-21 15:24:22'),
(2, 'Nina', '2025-11-21 15:24:51'),
(3, 'Stéphane', '2025-11-21 15:25:40'),
(4, 'Emmanuelle', '2025-11-21 15:44:18'),
(5, 'Emma', '2025-11-24 08:40:14'),
(6, 'Emmanu', '2025-11-24 10:19:07'),
(7, 'Emma', '2025-11-24 10:39:24'),
(8, 'Tatou', '2025-11-24 15:31:33'),
(9, 'Emma', '2025-11-24 15:54:18'),
(10, 'Emma', '2025-11-25 08:51:02'),
(11, 'Emma', '2025-11-25 09:44:16'),
(12, 'Emma', '2025-11-25 09:55:06'),
(13, 'Emma', '2025-11-25 10:10:47'),
(14, 'Emma', '2025-11-25 10:26:34'),
(15, 'Emma', '2025-11-26 08:15:59'),
(16, 'Emma', '2025-11-26 08:49:12'),
(17, 'Manux', '2025-11-26 12:08:17'),
(18, 'Manux', '2025-11-26 13:23:05'),
(19, 'JM', '2025-11-26 14:14:02'),
(20, 'Joueur1', '2025-11-26 14:35:04'),
(21, 'Manux', '2025-11-26 15:29:39'),
(22, 'Manux', '2025-11-26 15:32:28'),
(23, 'Manux', '2025-11-27 08:38:51'),
(24, 'Marsiou', '2025-11-27 10:46:54'),
(25, 'Manux', '2025-11-27 12:26:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartes`
--
ALTER TABLE `cartes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartes`
--
ALTER TABLE `cartes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
