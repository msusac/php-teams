-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2020 at 08:18 PM
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
(1, 'Testuser Project First', 'Testuser Project First\r\n\r\nUpdated!', 'testuser', 'testuser', '2020-11-19 17:36:28', '2020-11-19 17:37:26', ''),
(2, 'Testuser Project Second', 'Testuser Project Second', 'testuser', NULL, '2020-11-19 17:36:40', NULL, 'tumblr_mw3hfwEsBz1s8xkbjo1_500.jpg'),
(3, 'Admin Hotel', 'Admin Hotel', 'administrator', 'administrator', '2020-11-19 17:44:10', '2020-11-19 17:46:15', 'tumblr_oq06mlSBM11vubw39o1_500.gif'),
(4, 'Web Shop', 'Web Shop', 'administrator', 'administrator', '2020-11-19 17:44:30', '2020-11-19 18:00:16', ''),
(5, 'Admins Garden Project', 'Add tomato!', 'administrator', 'testuser', '2020-11-19 17:44:55', '2020-11-19 18:03:59', 'tumblr_mw3hfwEsBz1s8xkbjo1_500.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `request_table`
--

CREATE TABLE `request_table` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `user_from_id` int(11) NOT NULL,
  `user_to_id` int(11) NOT NULL,
  `date_send` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_reply` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `project_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request_table`
--

INSERT INTO `request_table` (`id`, `name`, `description`, `status`, `user_from_id`, `user_to_id`, `date_send`, `date_reply`, `project_id`) VALUES
(1, 'Web Shop Request', 'This is first request.', 'PENDING', 1, 4, '2020-11-19 18:01:03', NULL, 4),
(2, 'Web Shop Request', 'Web Shop Request', 'ACCEPTED', 1, 3, '2020-11-19 18:01:30', '2020-11-19 18:04:48', 4),
(3, 'Garden Project Request', 'Garden Project Request', 'ACCEPTED', 1, 2, '2020-11-19 18:01:48', '2020-11-19 18:03:15', 5),
(4, 'Web Shop Request', 'Web Shop Request', 'REJECTED', 1, 2, '2020-11-19 18:02:36', '2020-11-19 18:03:18', 4);

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
(1, 'Bulding Hotel', 'Empty Description', 'REVERSED', 'administrator', 'administrator', NULL, NULL, '2020-11-19 17:46:15', '2020-11-19 17:56:26', 3),
(2, 'Building Garden', 'Add Tomato!', 'NOT_STARTED', 'administrator', NULL, '2020-11-16 18:56:00', '2020-11-30 06:00:00', '2020-11-19 17:57:24', NULL, 5),
(3, 'Bulding Water Sink', 'Bulding Water Sink\r\nHello!', 'IN_PROGRESS', 'administrator', 'testuser', NULL, NULL, '2020-11-19 17:58:09', '2020-11-19 18:04:15', 5),
(4, 'Web Shop Preparations', 'Web Shop Preparations', 'NOT_STARTED', 'administrator', NULL, '2020-11-30 00:00:00', '2020-12-31 00:00:00', '2020-11-19 18:00:16', NULL, 4),
(5, 'Building Front', 'Building Front', 'NOT_STARTED', 'testuser', NULL, NULL, NULL, '2020-11-19 18:03:59', NULL, 5);

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
(2, 2),
(3, 2),
(4, 2);

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
(2, 1, 'testuser', 'Testuser Project First', 'CREATOR'),
(2, 2, 'testuser', 'Testuser Project Second', 'CREATOR'),
(1, 3, 'administrator', 'Admin Hotel', 'CREATOR'),
(1, 4, 'administrator', 'Web Shop', 'CREATOR'),
(1, 5, 'administrator', 'Admins Garden Project', 'CREATOR'),
(2, 5, 'testuser', 'Admins Garden Project', 'HELPER'),
(3, 4, 'testuser2', 'Web Shop', 'HELPER');

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
(1, 'administrator', 'admin@admin.com', 'Mr. Admin O', '200ceb26807d6bf99fd6f4f0d1ca54d4', '2020-10-31 20:13:22'),
(2, 'testuser', 'test@test.com', NULL, '5d9c68c6c50ed3d02a2fcf54f63993b6', '2020-11-19 17:29:40'),
(3, 'testuser2', 'test2@test.com', NULL, '58dd024d49e1d1b83a5d307f09f32734', '2020-11-19 17:33:22'),
(4, 'testuser3', 'test3@test.com', NULL, '1e4332f65a7a921075fbfb92c7c60cce', '2020-11-19 17:33:30'),
(5, 'testuser4', 'test4@test.com', NULL, '8147e2c0496d91393d3c270a5c38ed61', '2020-11-19 17:34:16');

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
-- Indexes for table `request_table`
--
ALTER TABLE `request_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_from` (`user_from_id`),
  ADD KEY `fk_user_to` (`user_to_id`),
  ADD KEY `fk_request_project` (`project_id`);

--
-- Indexes for table `task_table`
--
ALTER TABLE `task_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_task_project` (`project_id`);

--
-- Indexes for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD KEY `fk_ua_authority` (`authority_id`),
  ADD KEY `fk_ua_user` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `request_table`
--
ALTER TABLE `request_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task_table`
--
ALTER TABLE `task_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `request_table`
--
ALTER TABLE `request_table`
  ADD CONSTRAINT `fk_request_project` FOREIGN KEY (`project_id`) REFERENCES `project_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_from` FOREIGN KEY (`user_from_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user_to` FOREIGN KEY (`user_to_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `task_table`
--
ALTER TABLE `task_table`
  ADD CONSTRAINT `fk_task_project` FOREIGN KEY (`project_id`) REFERENCES `project_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD CONSTRAINT `fk_ua_authority` FOREIGN KEY (`authority_id`) REFERENCES `authority_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ua_user` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

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
