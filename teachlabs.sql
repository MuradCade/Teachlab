-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 03:09 PM
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
-- Database: `teachlabs`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignmententries`
--

CREATE TABLE `assignmententries` (
  `stdid` int(10) NOT NULL,
  `stdfullname` varchar(100) NOT NULL,
  `uploadedfile` varchar(100) NOT NULL,
  `coursename` varchar(100) NOT NULL,
  `marks` int(50) NOT NULL DEFAULT 0,
  `formid` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `filesize` varchar(50) DEFAULT NULL,
  `assignmentid` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignmentform`
--

CREATE TABLE `assignmentform` (
  `title` varchar(600) NOT NULL,
  `description` varchar(600) NOT NULL,
  `coursename` varchar(50) NOT NULL,
  `template` varchar(80) NOT NULL,
  `uploadedfilename` varchar(100) NOT NULL,
  `formstatus` varchar(80) NOT NULL,
  `teacherid` int(10) NOT NULL,
  `formid` int(6) NOT NULL,
  `marks` int(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `courseid` int(10) NOT NULL,
  `coursename` varchar(80) NOT NULL,
  `teacherid` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_verification`
--

CREATE TABLE `email_verification` (
  `id` int(11) NOT NULL,
  `userid` int(10) NOT NULL,
  `email_code` varchar(80) DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forgetpwd`
--

CREATE TABLE `forgetpwd` (
  `id` int(4) NOT NULL,
  `userid` int(10) DEFAULT NULL,
  `recoverstatus` varchar(10) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `markattendence`
--

CREATE TABLE `markattendence` (
  `stdid` int(10) NOT NULL,
  `stdfullname` varchar(80) NOT NULL,
  `coursename` varchar(80) NOT NULL,
  `teacherid` int(10) NOT NULL,
  `attendedmarks` int(80) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `present_column` int(1) DEFAULT NULL,
  `absent_column` int(1) DEFAULT NULL,
  `id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stdid` int(10) NOT NULL,
  `stdfullname` varchar(80) NOT NULL,
  `coursename` varchar(100) NOT NULL,
  `teacherid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` varchar(10) NOT NULL,
  `fullname` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `pwd` text NOT NULL,
  `role` varchar(50) NOT NULL,
  `verified_status` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignmententries`
--
ALTER TABLE `assignmententries`
  ADD PRIMARY KEY (`assignmentid`);

--
-- Indexes for table `assignmentform`
--
ALTER TABLE `assignmentform`
  ADD PRIMARY KEY (`formid`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`courseid`);

--
-- Indexes for table `email_verification`
--
ALTER TABLE `email_verification`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `forgetpwd`
--
ALTER TABLE `forgetpwd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `markattendence`
--
ALTER TABLE `markattendence`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stdid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignmententries`
--
ALTER TABLE `assignmententries`
  MODIFY `assignmentid` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseid` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forgetpwd`
--
ALTER TABLE `forgetpwd`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `markattendence`
--
ALTER TABLE `markattendence`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
