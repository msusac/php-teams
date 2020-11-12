-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2020 at 07:25 PM
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
(12, 'My Hotel', 'This is test project! Updated!', 'administrator', 'administrator', '2020-11-12 18:17:27', '2020-11-12 18:18:18', 'tumblr_oq06mlSBM11vubw39o1_500.gif'),
(13, 'New Project', 'Hello world!', 'administrator', 'administrator', '2020-11-12 18:18:57', '2020-11-12 18:19:10', 'tumblr_nv31djuBNL1tojh0do1_400.gif'),
(15, 'Test User Project', 'Test User Project', 'testuser', NULL, '2020-11-12 18:22:49', NULL, '');

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
(41, 2);

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
(1, 13, 'administrator', 'New Project', 'CREATOR'),
(41, 15, 'testuser', 'Test User Project', 'CREATOR');

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
(42, 'testuser2', 'test2@test.com', NULL, '58dd024d49e1d1b83a5d307f09f32734', '2020-11-12 18:23:14');

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
-- Indexes for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_authority` (`authority_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_authority_table`
--
ALTER TABLE `user_authority_table`
  ADD CONSTRAINT `fk_authority` FOREIGN KEY (`authority_id`) REFERENCES `authority_table` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user_table` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
