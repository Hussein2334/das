-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2025 at 11:49 PM
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
-- Database: `das`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','blocked') DEFAULT 'pending',
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `message`, `created_at`, `created_by`, `image`, `status`, `department_id`) VALUES
(3, 'message', '  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Fugiat voluptatem sint iste velit ex in cumque animi nihil voluptatibus harum deleniti eum, tempore, consequuntur pariatur veritatis soluta enim corrupti alias?', '2025-05-28 12:39:30', 1, '1748450177_ROMA2.pdf', 'approved', 1),
(4, 'dfjighh', 'gokgjkig', '2025-05-28 20:57:13', 1, '1748459130_3.jpg', 'approved', 1),
(5, 'tunajaribu', 'matangazo mbali mbali', '2025-05-28 21:23:44', 1, '1748459113_4.jpg', 'approved', 1),
(7, 'images', 'display images ', '2025-05-28 21:38:38', 1, '1748457518_2.jpg', 'pending', 1),
(8, 'image 3', 'image 3 ', '2025-05-28 21:46:30', 1, '1748457990_3.jpg', 'pending', 1),
(9, 'anothe image ', 'display images ', '2025-05-28 21:54:31', 1, '1748458471_1.jpg', 'pending', 1),
(10, 'Abuu', 'Acha Kelele ', '2025-05-28 22:36:44', 1, '1748461004_IMG-20250528-WA0049.jpg', 'approved', 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(1, 'Accounting and Finance'),
(4, 'Human Resource Management'),
(2, 'Information Technology'),
(3, 'Marketing and Public Relations');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `emp` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','blocked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `emp`, `phone`, `gender`, `department_id`, `department`, `password`, `role`, `create_at`, `status`) VALUES
(1, 'Hussein Ali Abdulrahman', 'husseinali2334@gmail.com', 'BIT-01-0011-2023', '0658216348', 'M', 1, 'Information Technology', '$2y$10$Z2K1CnhP1pLPFzQYtaBV2u0Fzk8tyNy2IxhKLfgVIfSMH0FIsHyqy', 'admin', '2025-05-28 12:07:36', 'blocked'),
(2, 'Abubakar Mohammed Othman', 'abubakar@gmail.com', 'BEPM-01-0028-2023', '567890', 'F', 1, 'Library and Documentation', '$2y$10$TdswqflKx3WuoqGLMbGelOSsl3FdtRlsTG0a7RKRJlZbMADsk10sa', 'user', '2025-05-28 13:38:01', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
