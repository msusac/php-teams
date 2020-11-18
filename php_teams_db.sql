-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2020 at 05:25 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php_teams_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `authority_table`
--

CREATE TABLE `authority_table` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `authority_table`
--

INSERT INTO `authority_table` (`id`, `name`) VALUES
(1, 'ROLE_ADMIN'),
(2, 'ROLE_USER');

-- --------------------------------------------------------

--
-- Table structure for table `project_table`
--

CREATE TABLE `project_table` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `project_table`
--

INSERT INTO `project_table` (`id`, `name`, `description`, `created_by`, `updated_by`, `date_created`, `date_updated`, `image`) VALUES
(12, 'My Hotel', 'This is test project! Updated!\r\n\r\n-Add potato', 'administrator', 'administrator', '2020-11-12 18:17:27', '2020-11-18 16:22:04', 'ba178ec5b3dca4f4afde4baa0446dcf7.jpg'),
(15, 'Test User Project', 'Test User Project', 'testuser', 'testuser', '2020-11-12 18:22:49', '2020-11-17 19:27:56', ''),
(17, 'Project Test Two', 'Project Test Two', 'administrator', 'administrator', '2020-11-13 18:49:59', '2020-11-16 12:50:35', 'tumblr_mw3hfwEsBz1s8xkbjo1_500.jpg'),
(19, 'Java Hotel V2', 'Testtesttest', 'administrator', 'administrator', '2020-11-13 18:58:39', '2020-11-18 16:22:25', 'tumblr_oq06mlSBM11vubw39o1_500.gif'),
(20, 'NewBie Project', 'This is my newbie project!', 'testuser3', 'testuser3', '2020-11-13 19:22:23', '2020-11-13 19:24:53', 'tumblr_mw3hfwEsBz1s8xkbjo1_500.jpg'),
(22, 'Test User Project Second', 'Test User Project Second', 'testuser2', 'testuser2', '2020-11-16 13:09:43', '2020-11-16 13:09:51', 'tumblr_oq06mlSBM11vubw39o1_500.gif');

-- --------------------------------------------------------

--
-- Table structure for table `task_table`
--

CREATE TABLE `task_table` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `date_start` datetime DEFAULT NULL,
  `date_end` datetime DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_updated` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_table`
--

INSERT INTO `task_table` (`id`, `name`, `description`, `status`, `created_by`, `updated_by`, `date_start`, `date_end`, `date_created`, `date_updated`, `project_id`) VALUES
(6, 'Java This V2', 'asdasdasdasdasdas', 'REVERSED', 'administrator', 'administrator', NULL, NULL, '2020-11-16 12:10:54', '2020-11-17 14:33:52', 19),
(7, 'My Task', 'My Task', 'IN_PROGRESS', 'testuser2', 'testuser2', NULL, NULL, '2020-11-16 13:10:16', '2020-11-17 14:34:10', 22),
(8, 'Task with time', 'Testing', 'NOT_STARTED', 'administrator', NULL, NULL, NULL, '2020-11-17 14:31:16', NULL, 19),
(12, 'Java Hotel', 'sdfsdf', 'REVERSED', 'administrator', 'administrator', '2020-11-17 07:00:00', '2020-11-20 08:45:00', '2020-11-17 15:35:33', '2020-11-18 15:42:51', 12),
(14, 'Java Hotel', 'asdasdassdadas', 'IN_PROGRESS', 'administrator', 'administrator', NULL, NULL, '2020-11-17 15:58:52', '2020-11-17 16:06:10', 19),
(15, 'First Project Task Without Time', 'First Project Task Without Time', 'IN_PROGRESS', 'administrator', 'administrator', '2020-11-17 07:00:00', '2020-11-25 16:00:00', '2020-11-17 16:29:05', '2020-11-17 17:44:11', 12),
(16, 'Second Project With Time', 'Second Project With Time', 'NOT_STARTED', 'administrator', NULL, '2020-11-03 17:34:00', '2020-11-25 17:34:00', '2020-11-17 16:34:22', NULL, 17),
(17, 'Test User Project', 'Test User Project', 'NOT_STARTED', 'testuser', NULL, '2020-11-25 20:27:00', '2020-11-30 07:00:00', '2020-11-17 19:27:56', NULL, 15);

-- --------------------------------------------------------

--
-- Table structure for table `user_authority_table`
--

CREATE TABLE `user_authority_table` (
  `user_id` int(11) NOT NULL,
  `authority_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_authority_table`
--

INSERT INTO `user_authority_table` (`user_id`, `authority_id`) VALUES
(1, 1),
(41, 2),
(43, 2),
(42, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_project_table`
--

CREATE TABLE `user_project_table` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `project` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_project_table`
--

INSERT INTO `user_project_table` (`user_id`, `project_id`, `user`, `project`, `role`) VALUES
(1, 12, 'administrator', 'My Hotel', 'CREATOR'),
(41, 15, 'testuser', 'Test User Project', 'CREATOR'),
(1, 17, 'administrator', 'Project Test Two', 'CREATOR'),
(1, 19, 'administrator', 'Java Hotel V2', 'CREATOR'),
(43, 20, 'testuser3', 'NewBie Project', 'CREATOR'),
(42, 22, 'testuser2', 'Test User Project Second', 'CREATOR');

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`id`, `username`, `email`, `fullname`, `password`, `date_created`) VALUES
(1, 'administrator', 'admin@admin.com', 'Mr. Admin', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2020-10-31 20:13:22'),
(41, 'testuser', 'test@test.com', NULL, '5d9c68c6c50ed3d02a2fcf54f63993b6', '2020-11-12 18:20:43'),
(42, 'testuser2', 'test2@test.com', NULL, '58dd024d49e1d1b83a5d307f09f32734', '2020-11-12 18:23:14'),
(43, 'testuser3', 'test3@test.com', 'Newbie', '1e4332f65a7a921075fbfb92c7c60cce', '2020-11-13 19:20:04'),
(44, 'testuser4', 'test4@test.com', NULL, '8147e2c0496d91393d3c270a5c38ed61', '2020-11-16 13:08:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authority_table`
--
ALTER TABLE `authority_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `project_table`
--
ALTER TABLE `project_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_table`
--
ALTER TABLE `task_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_project` (`project_id`);

--
-- Indexes for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD KEY `fk_authority` (`authority_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `user_project_table`
--
ALTER TABLE `user_project_table`
  ADD KEY `fk_up_project` (`project_id`),
  ADD KEY `fk_up_user` (`user_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authority_table`
--
ALTER TABLE `authority_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `project_table`
--
ALTER TABLE `project_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `task_table`
--
ALTER TABLE `task_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_table`
--
ALTER TABLE `task_table`
  ADD CONSTRAINT `fk_project` FOREIGN KEY (`project_id`) REFERENCES `project_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD CONSTRAINT `fk_authority` FOREIGN KEY (`authority_id`) REFERENCES `authority_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_project_table`
--
ALTER TABLE `user_project_table`
  ADD CONSTRAINT `fk_up_project` FOREIGN KEY (`project_id`) REFERENCES `project_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_up_user` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
