-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 12:05 PM
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

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`courseid`, `coursename`, `teacherid`, `date`) VALUES
(3, 'test', 39105, '2024-12-06 11:01:38'),
(4, 'yoo', 39105, '2024-12-06 15:52:14');

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
(1, 18911, 'bOEde6', 1),
(2, 39105, '07OqDp', 1),
(3, 48483, '7ZTiVL', 1);

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
(1, 39105, '0', '2024-12-05 12:56:58', 'HZWc5J');

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

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_one`, `option_two`, `option_three`, `quizformid`, `is_correct_option`, `questionid`, `optionid`) VALUES
('Edjvbakvecjl', 'Eyrcifi', 'Lgvdnfzkmoupi', 7333, 'a', 1, 1),
('Dzsxs', 'Ubkacviq', 'Ohy', 7333, 'a', 2, 2),
('Tv', 'Qxteahyl', 'Eiktaszvzvxq', 7333, 'b', 3, 3),
('Yhvbijyqrq', 'Vamu', 'Roxx', 7333, 'b', 4, 4),
('Looe', 'Fxxu', 'Jbruiqjo', 7333, 'b', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `questionid` int(10) NOT NULL,
  `questiontext` text DEFAULT NULL,
  `quizformid` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`questionid`, `questiontext`, `quizformid`) VALUES
(1, 'Tulhoib rasavi ezji ruog ceblap tu wi majke biizo joekpo vinadrip so zu zec tevke aj romvecec. Hohavabid dug jo wanwolac ugoj law kekmul co latala nipawbit bemi titowco fuwaffaj ha. Nukwaj noruvla wis wofiszi kiko zatamez tugom kehpislo fumjama fipbu gukuwuze orejalom vu likmu. Oc loba lormaski soajsek siji ozuzabac beod vivjatvow hogik wogmon ujbedce ezedude gubauk fej kic re sefo. Itiva vezzovce daspon tuwhub tebajif etka cocu feb momo', 7333),
(2, 'Nut babo hupa jiger saldu atluoc vad rek mifutvak ker zusob wicnik. Huag la ej ur am ro bontebek jat esogogni bettifo ub teve awe magopih. Recgohmu tecujnus uw uhosukor mic cuk alozerewa gikuji muotese du lauhi bom jolcu fujnebho. Zorowut gilzodu inaj rifedtu cofik nepafal zebbi hizihbu fu no nedbalipu nuuwuet ut', 7333),
(3, 'Ra lofibvak omtu cojleuwi giluz kokpu runkojwo fa pohisca motis hedgezmu buzonmal fiomih. Asme zasoke ocoli vad amafo ako erlu saej zete tikavo tike tibajuza iko palvufen. Boboraw ijejiewa furkan enira omiwirvic da', 7333),
(4, 'Kimkipa vari do mil ewi ducko dawna lu rojaz zompif hetahar meaj. Woig je wufwuf ebo jej mu wepe muz gewezos le ugiiro howe oguru ju za. Nihezhi reredpi uvi vannopu pe hos narritpo peges ric he guuzakaj cu. Pulah gofrewak gon akeezi pu merum ni laf rufbinki dotlabed ja poabauha. Fa', 7333),
(5, 'Kanwon fohikobo katane hepmew how jofnowoti mile ke inusa mu fino rigaloz da. Cottucog dib cifewouhu zidi ke mudejagum tiivi tafokmo zadat bokefa cup fe rubuf itof refcace ve dagiku sarin. Jovsel jezmew fiepigu wewupsad be zi golet tomew soknaklaj zo gekihi otodi sabpec. Urevoki mig rorupba feve caupo ijcihni senzu sehejmu levep leborim fehnel fal ov. Zusug kubabbag ca da cuvtian vi wobzo suko ad ', 7333);

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
  `number_of_questions` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizform`
--

INSERT INTO `quizform` (`quizformid`, `quiztitle`, `quizdesc`, `coursename`, `quiz_created_date`, `quizstatus`, `teacherid`, `number_of_questions`) VALUES
(7333, 'php quiz form', 'cscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsdcscsdcsdcsd', 'test', '2024-12-07 08:01:48', 'active', 39105, '5');

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
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `id` int(1) NOT NULL,
  `userid` int(10) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `subsatus` varchar(50) DEFAULT NULL,
  `subplan` varchar(15) DEFAULT NULL,
  `subamount` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`id`, `userid`, `date`, `subsatus`, `subplan`, `subamount`) VALUES
(1, 18911, '2024-11-30 10:38:54', 'active', 'free_trial', '7days'),
(2, 39105, '2024-12-04 14:33:47', 'active', 'free_trial', '7days'),
(3, 48483, '2024-12-05 13:07:51', 'active', 'free_trial', '7days');

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
  `verified_status` varchar(1) NOT NULL DEFAULT '0',
  `subscription_status` varchar(50) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `fullname`, `email`, `pwd`, `role`, `verified_status`, `subscription_status`) VALUES
('39105', 'murad cade', 'tayotechcompany@gmail.com', '$2y$10$4GWqXUrXJh0.WXg5ccr9IOnck//AEzt1EIpqn8ywlPMaDcE1oj./S', 'teacher', '1', 'active'),
('48483', 'test12', 'yegaram620@datingel.com', '$2y$10$YjnalAnK2.tv/7YNJ1KcbeY9343.OxukN./U92DtR8u/Ia.VzZsCm', 'teacher', '1', 'active');

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
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`optionid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionid`);

--
-- Indexes for table `quizform`
--
ALTER TABLE `quizform`
  ADD PRIMARY KEY (`quizformid`);

--
-- Indexes for table `studentquiz`
--
ALTER TABLE `studentquiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stdid`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `assignmentid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `courseid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_verification`
--
ALTER TABLE `email_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forgetpwd`
--
ALTER TABLE `forgetpwd`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `markattendence`
--
ALTER TABLE `markattendence`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `optionid` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `studentquiz`
--
ALTER TABLE `studentquiz`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
