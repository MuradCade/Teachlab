-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 12:02 PM
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
  `uploadedfile` text NOT NULL,
  `coursename` varchar(100) NOT NULL,
  `marks` int(50) NOT NULL DEFAULT 0,
  `formid` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `filesize` varchar(50) DEFAULT NULL,
  `assignmentid` int(4) NOT NULL,
  `pdf_file` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assignmententries`
--

INSERT INTO `assignmententries` (`stdid`, `stdfullname`, `uploadedfile`, `coursename`, `marks`, `formid`, `date`, `filesize`, `assignmentid`, `pdf_file`) VALUES
(26420, 'mustafe', 'Assignment one.docx', 'database', 5, 432749, '2025-04-05 13:25:35', '12.3466796875', 22, 'Assignment_one.pdf'),
(63183, 'ahmed', 'Course_Description -Cultral policy.docx', 'database', 4, 432749, '2025-04-05 13:28:28', '19.236328125', 23, 'Course_Description_Cultral_policy.pdf');

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
  `marks` int(50) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `assignmentform`
--

INSERT INTO `assignmentform` (`title`, `description`, `coursename`, `template`, `uploadedfilename`, `formstatus`, `teacherid`, `formid`, `marks`, `date`) VALUES
('test1', 'test12', 'database', 'default_template', 'word_document', 'active', 37717, 432749, 1, '2025-03-31 09:58:08');

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

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseid`, `coursename`, `teacherid`, `date`) VALUES
(8, 'database', 37717, '2025-03-31 09:48:33'),
(15, 'test', 37717, '2025-04-05 13:29:09'),
(17, 'mm', 97315, '2025-04-07 12:50:24');

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

--
-- Dumping data for table `email_verification`
--

INSERT INTO `email_verification` (`id`, `userid`, `email_code`, `email_verified`) VALUES
(16, 10071, '06t7fS', 0),
(11, 12526, 'LoMxuT', 0),
(2, 19698, 'kM0DmF', 0),
(1, 19788, 'Kg5Ebj', 0),
(15, 27819, 'e9aFmc', 0),
(19, 31557, 'Ft4yw5', 1),
(4, 31590, 'Jryo7V', 0),
(9, 31717, 'RV8OFr', 0),
(3, 33847, '0BGtYZ', 0),
(13, 37648, 'Eh7yn1', 0),
(10, 46620, 'c5lpYN', 0),
(18, 48369, 'EA15ho', 1),
(8, 49440, '75fGpr', 0),
(6, 50465, '6ytZ1Y', 0),
(5, 55003, 'Rs5qdk', 1),
(12, 55468, 'i6Sx0y', 0),
(7, 63584, 'Lmtlvy', 0),
(14, 94542, 'pl4kxq', 0),
(17, 97315, 'YSHLDN', 1);

-- --------------------------------------------------------

--
-- Table structure for table `examform`
--

CREATE TABLE `examform` (
  `examformid` int(4) NOT NULL,
  `examtitle` text NOT NULL,
  `examdesc` text NOT NULL,
  `coursename` varchar(50) NOT NULL,
  `exam_created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `examstatus` varchar(50) NOT NULL,
  `teacherid` int(10) NOT NULL,
  `number_of_questions` varchar(50) NOT NULL,
  `examtype` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examform`
--

INSERT INTO `examform` (`examformid`, `examtitle`, `examdesc`, `coursename`, `exam_created_date`, `examstatus`, `teacherid`, `number_of_questions`, `examtype`) VALUES
(9583, 'exam 1', ' c cd d d d ', 'database', '2025-04-05 10:50:14', 'active', 37717, '5', 'singlechoicequestion');

-- --------------------------------------------------------

--
-- Table structure for table `examoptions`
--

CREATE TABLE `examoptions` (
  `optionid` int(4) NOT NULL,
  `questionid` int(10) NOT NULL,
  `option_one` text NOT NULL,
  `option_two` text NOT NULL,
  `option_three` text NOT NULL,
  `is_correct_option` varchar(50) NOT NULL,
  `examformid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examoptions`
--

INSERT INTO `examoptions` (`optionid`, `questionid`, `option_one`, `option_two`, `option_three`, `is_correct_option`, `examformid`) VALUES
(21, 1, 'test1', 'test2', 'test3', 'a', 9583),
(22, 2, 'test3', 'test4', 'test5', 'a', 9583),
(23, 3, 'test6', 'test7', 'test8', 'a', 9583),
(24, 4, 'test9', 'test10', 'test11', 'a', 9583),
(25, 5, 'test12', 'test13', 'test14', 'a', 9583);

-- --------------------------------------------------------

--
-- Table structure for table `examquestions`
--

CREATE TABLE `examquestions` (
  `questionid` int(10) NOT NULL,
  `questiontext` text NOT NULL,
  `examformid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `examquestions`
--

INSERT INTO `examquestions` (`questionid`, `questiontext`, `examformid`) VALUES
(1, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 1', 9583),
(2, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 2', 9583),
(3, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 3', 9583),
(4, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 4', 9583),
(5, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. 5', 9583);

-- --------------------------------------------------------

--
-- Table structure for table `examtrue_false_options`
--

CREATE TABLE `examtrue_false_options` (
  `optionid` int(4) NOT NULL,
  `option_one` text NOT NULL,
  `option_two` text NOT NULL,
  `examformid` int(11) NOT NULL,
  `is_correct_option` text NOT NULL,
  `questionid` int(5) NOT NULL
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

--
-- Dumping data for table `forgetpwd`
--

INSERT INTO `forgetpwd` (`id`, `userid`, `recoverstatus`, `date`, `code`) VALUES
(1, 39105, '0', '2024-12-05 12:56:58', 'HZWc5J'),
(2, 37717, '1', '2025-02-16 17:48:23', 'obfalZ');

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

--
-- Dumping data for table `markattendence`
--

INSERT INTO `markattendence` (`stdid`, `stdfullname`, `coursename`, `teacherid`, `attendedmarks`, `date`, `present_column`, `absent_column`, `id`) VALUES
(26420, 'mustafes', 'database', 37717, 2, '2025-04-05 13:21:22', 1, 0, 43),
(63183, 'ahmed', 'database', 37717, 2, '2025-04-05 13:21:22', 1, 0, 44);

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_one` text DEFAULT NULL,
  `option_two` text DEFAULT NULL,
  `option_three` text DEFAULT NULL,
  `quizformid` int(10) DEFAULT NULL,
  `is_correct_option` varchar(50) DEFAULT NULL,
  `questionid` int(10) DEFAULT NULL,
  `optionid` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questiontext` text DEFAULT NULL,
  `quizformid` int(10) DEFAULT NULL,
  `questionid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`questiontext`, `quizformid`, `questionid`) VALUES
('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 1', 3313, 1),
('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 2', 3313, 2),
('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 3', 3313, 3),
('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 4', 3313, 4),
('It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. 5', 3313, 5);

-- --------------------------------------------------------

--
-- Table structure for table `quizform`
--

CREATE TABLE `quizform` (
  `quizformid` int(4) NOT NULL,
  `quiztitle` text DEFAULT NULL,
  `quizdesc` text DEFAULT NULL,
  `coursename` varchar(50) DEFAULT NULL,
  `quiz_created_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quizstatus` varchar(50) DEFAULT NULL,
  `teacherid` int(10) DEFAULT NULL,
  `number_of_questions` varchar(50) DEFAULT NULL,
  `quiztype` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizform`
--

INSERT INTO `quizform` (`quizformid`, `quiztitle`, `quizdesc`, `coursename`, `quiz_created_date`, `quizstatus`, `teacherid`, `number_of_questions`, `quiztype`) VALUES
(3313, 'quiz 1', 'cdcdfvdfv', 'database', '2025-04-05 11:13:04', 'active', 37717, '5', 'trueandfalse');

-- --------------------------------------------------------

--
-- Table structure for table `studentexam`
--

CREATE TABLE `studentexam` (
  `id` int(10) NOT NULL,
  `stdfullname` varchar(50) NOT NULL,
  `question_id` int(10) NOT NULL,
  `exam_taken_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `examformid` int(10) NOT NULL,
  `selected_option` text NOT NULL,
  `stdid` int(10) NOT NULL,
  `exammarks` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentexam`
--

INSERT INTO `studentexam` (`id`, `stdfullname`, `question_id`, `exam_taken_date`, `examformid`, `selected_option`, `stdid`, `exammarks`) VALUES
(71, 'mustafe', 1, '2025-04-05 14:13:52', 9583, 'a', 26420, '15'),
(72, 'mustafe', 2, '2025-04-05 14:13:52', 9583, 'a', 26420, '15'),
(73, 'mustafe', 3, '2025-04-05 14:13:52', 9583, 'a', 26420, '15'),
(74, 'mustafe', 4, '2025-04-05 14:13:52', 9583, 'a', 26420, '15'),
(75, 'mustafe', 5, '2025-04-05 14:13:52', 9583, 'b', 26420, '15'),
(76, 'ahmed', 1, '2025-04-05 14:13:17', 9583, 'b', 63183, '10'),
(77, 'ahmed', 2, '2025-04-05 14:13:17', 9583, 'b', 63183, '10'),
(78, 'ahmed', 3, '2025-04-05 14:13:17', 9583, 'b', 63183, '10'),
(79, 'ahmed', 4, '2025-04-05 14:13:17', 9583, 'b', 63183, '10'),
(80, 'ahmed', 5, '2025-04-05 14:13:17', 9583, 'a', 63183, '10');

-- --------------------------------------------------------

--
-- Table structure for table `studentquiz`
--

CREATE TABLE `studentquiz` (
  `stdfullname` varchar(50) DEFAULT NULL,
  `question_id` int(10) DEFAULT NULL,
  `quiz_taken_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quizformid` int(10) DEFAULT NULL,
  `selected_option` text DEFAULT NULL,
  `stdid` int(10) DEFAULT NULL,
  `quizmarks` varchar(50) DEFAULT NULL,
  `id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentquiz`
--

INSERT INTO `studentquiz` (`stdfullname`, `question_id`, `quiz_taken_date`, `quizformid`, `selected_option`, `stdid`, `quizmarks`, `id`) VALUES
('mustafe', 1, '2025-04-05 14:13:40', 3313, 'a', 26420, '30', 31),
('mustafe', 2, '2025-04-05 14:13:40', 3313, 'a', 26420, '30', 32),
('mustafe', 3, '2025-04-05 14:13:40', 3313, 'a', 26420, '30', 33),
('mustafe', 4, '2025-04-05 14:13:40', 3313, 'a', 26420, '30', 34),
('mustafe', 5, '2025-04-05 14:13:40', 3313, 'b', 26420, '30', 35),
('ahmed', 1, '2025-04-05 14:13:37', 3313, 'b', 63183, '20', 36),
('ahmed', 2, '2025-04-05 14:13:37', 3313, 'b', 63183, '20', 37),
('ahmed', 3, '2025-04-05 14:13:37', 3313, 'b', 63183, '20', 38),
('ahmed', 4, '2025-04-05 14:13:37', 3313, 'b', 63183, '20', 39),
('ahmed', 5, '2025-04-05 14:13:37', 3313, 'a', 63183, '20', 40);

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

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stdid`, `stdfullname`, `coursename`, `teacherid`) VALUES
(101, 'maxamed', 'test01', 97315),
(102, 'omar', 'test01', 97315),
(103, 'adan', 'test01', 97315),
(104, 'nour', 'test01', 97315),
(105, 'mustafe', 'test01', 97315),
(101, 'm', 'mm', 97315),
(102, 'y', 'mm', 97315),
(103, 'a', 'mm', 97315),
(104, 's', 'mm', 97315),
(105, 't', 'mm', 97315);

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(1) NOT NULL,
  `userid` int(10) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expire_date` text DEFAULT NULL,
  `subsatus` varchar(50) DEFAULT NULL,
  `subplan` text DEFAULT NULL,
  `subamount` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`id`, `userid`, `date`, `expire_date`, `subsatus`, `subplan`, `subamount`) VALUES
(4, 37717, '2025-04-07 18:00:04', '2025-05-08', 'active', 'pro', '10'),
(16, 97315, '2025-04-09 21:00:00', '2025-05-10', 'pending', 'free', '0'),
(17, 48369, '2025-04-07 17:32:08', '2025-04-03', 'active', 'one-time-purches', 'infinite'),
(18, 31557, '2025-03-03 13:41:25', '2025-04-03', 'active', 'pro', '30');

-- --------------------------------------------------------

--
-- Table structure for table `true_false_options`
--

CREATE TABLE `true_false_options` (
  `optionid` int(4) NOT NULL,
  `option_one` text NOT NULL,
  `option_two` text NOT NULL,
  `quizformid` int(10) NOT NULL,
  `is_correct_option` text NOT NULL,
  `questionid` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `true_false_options`
--

INSERT INTO `true_false_options` (`optionid`, `option_one`, `option_two`, `quizformid`, `is_correct_option`, `questionid`) VALUES
(21, 'true', 'false', 3313, 'a', 1),
(22, 'true', 'false', 3313, 'a', 2),
(23, 'true', 'false', 3313, 'a', 3),
(24, 'true', 'false', 3313, 'a', 4),
(25, 'true', 'false', 3313, 'a', 5);

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `fullname`, `email`, `pwd`, `role`, `verified_status`) VALUES
('31557', 'TeachLab', 'tenijiy903@lassora.com', '$2y$10$vAzU1hqqgkUYxb/zyN2JYO.ewujDL1tVv6Gjx6Z8QgC3PRftSuKIC', 'admin', '1'),
('37717', 'Murad Cade', 'bitawo2398@prorsd.com', '$2y$10$SHwTapVDn9yD3IAk/aFAFugnVBKEkTHLJUsXMYprPlcyryH7UHx46', 'teacher', '1'),
('48369', 'mustafe', 'yalap83779@hartaria.com', '$2y$10$vVA9Ho.OJEjHwPZDst2xd.CWb1LuDvf34PKJyDm4qH7D37sKF2QG2', 'teacher', '1'),
('97315', 'yahyecade', 'lodam71742@jomspar.com', '$2y$10$PEfR4RwTkU0W7xkh.TuMFuqFFb28J4SkaqqXggPHsLBF6ptu4hBBG', 'teacher', '1');

-- --------------------------------------------------------

--
-- Table structure for table `usersubscription_manager`
--

CREATE TABLE `usersubscription_manager` (
  `id` int(4) NOT NULL,
  `userid` int(10) NOT NULL,
  `fullname` text NOT NULL,
  `number` text NOT NULL,
  `subscription_plan` varchar(50) NOT NULL,
  `amount` text NOT NULL,
  `started_date` date NOT NULL DEFAULT current_timestamp(),
  `expire_date` text NOT NULL,
  `days_left` varchar(50) NOT NULL,
  `subscription_status` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usersubscription_manager`
--

INSERT INTO `usersubscription_manager` (`id`, `userid`, `fullname`, `number`, `subscription_plan`, `amount`, `started_date`, `expire_date`, `days_left`, `subscription_status`, `payment_status`) VALUES
(15, 37717, 'Murad Cade', '0633558027', 'pro', '10', '2025-04-08', '2025-05-08', '30', 'active', 'paid'),
(16, 97315, 'yahye cade', '9876545678', 'pro', '10', '2025-04-10', '2025-05-10', '30', 'active', 'paid'),
(20, 97315, 'yahye cade', '9876545678', 'pro', '10', '2025-05-10', '2025-06-10', '30', 'expire', 'paid');

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
-- Indexes for table `examform`
--
ALTER TABLE `examform`
  ADD PRIMARY KEY (`examformid`);

--
-- Indexes for table `examoptions`
--
ALTER TABLE `examoptions`
  ADD PRIMARY KEY (`optionid`);

--
-- Indexes for table `examtrue_false_options`
--
ALTER TABLE `examtrue_false_options`
  ADD PRIMARY KEY (`optionid`);

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
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionid`);

--
-- Indexes for table `quizform`
--
ALTER TABLE `quizform`
  ADD PRIMARY KEY (`quizformid`);

--
-- Indexes for table `studentexam`
--
ALTER TABLE `studentexam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studentquiz`
--
ALTER TABLE `studentquiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `true_false_options`
--
ALTER TABLE `true_false_options`
  ADD PRIMARY KEY (`optionid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `usersubscription_manager`
--
ALTER TABLE `usersubscription_manager`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignmententries`
--
ALTER TABLE `assignmententries`
  MODIFY `assignmentid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `examoptions`
--
ALTER TABLE `examoptions`
  MODIFY `optionid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `examtrue_false_options`
--
ALTER TABLE `examtrue_false_options`
  MODIFY `optionid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `forgetpwd`
--
ALTER TABLE `forgetpwd`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `markattendence`
--
ALTER TABLE `markattendence`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `optionid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `studentexam`
--
ALTER TABLE `studentexam`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `studentquiz`
--
ALTER TABLE `studentquiz`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `true_false_options`
--
ALTER TABLE `true_false_options`
  MODIFY `optionid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `usersubscription_manager`
--
ALTER TABLE `usersubscription_manager`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
