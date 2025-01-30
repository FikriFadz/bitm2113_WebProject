-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 08:13 AM
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
-- Database: `webassgn`
--

-- --------------------------------------------------------

--
-- Table structure for table `cleanerdata`
--

CREATE TABLE `cleanerdata` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `block` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanerdata`
--

INSERT INTO `cleanerdata` (`id`, `name`, `block`, `level`) VALUES
('b032210654', 'Arif Syazwan Bin Razak', 'Tuah', '3, 4, 5'),
('b032210876', 'Aina Zahra Binti Zulkifli', 'Lekiu', '7, 8, 9'),
('b032210983\r\n', 'Nazmie Shah Bin Khairul', 'Kasturi', '1, 2, 3'),
('b032319856\r\n', 'Nurul Amirah binti Hanafi', 'Lekir', '1, 2, 3');

-- --------------------------------------------------------

--
-- Table structure for table `cleanerreport`
--

CREATE TABLE `cleanerreport` (
  `reportID` int(11) NOT NULL,
  `cleanerId` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `time` varchar(100) NOT NULL,
  `cleanToilet` varchar(100) NOT NULL,
  `cleanSink` varchar(100) NOT NULL,
  `cleanShower` varchar(100) NOT NULL,
  `wipeMirrors` varchar(100) NOT NULL,
  `cleanFloor` varchar(100) NOT NULL,
  `block` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanerreport`
--

INSERT INTO `cleanerreport` (`reportID`, `cleanerId`, `name`, `date`, `time`, `cleanToilet`, `cleanSink`, `cleanShower`, `wipeMirrors`, `cleanFloor`, `block`, `level`) VALUES
(1, 'b032210983', 'Nazmie Shah Bin Khairul', '11-01-2025', '18:40', 'done', 'done', 'done', 'done', 'done', 'Kasturi', '1, 2, 3');

-- --------------------------------------------------------

--
-- Table structure for table `cleanertask`
--

CREATE TABLE `cleanertask` (
  `cleanerTaskID` int(11) NOT NULL,
  `cleanerID` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `roomNumber` varchar(100) NOT NULL,
  `block` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `dateReport` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `visibility` varchar(10) NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanertask`
--

INSERT INTO `cleanertask` (`cleanerTaskID`, `cleanerID`, `name`, `roomNumber`, `block`, `level`, `dateReport`, `status`, `visibility`) VALUES
(1, 'b032210654', 'Arif Syazwan bin Razak', 'S-K-9-3-B', 'Kolej Kediaman Kasturi', '9', '2025-01-11', 'Pending', 'Yes'),
(2, 'b032210983', 'Nazmie Shah Bin Khairul', 'S-K-9-3-B', 'Kolej Kediaman Kasturi', '9', '2025-01-13', 'Pending', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `cleanliness`
--

CREATE TABLE `cleanliness` (
  `cleanID` int(11) NOT NULL,
  `studentName` varchar(100) NOT NULL,
  `roomNumber` varchar(100) NOT NULL,
  `roomCleanliness` varchar(100) NOT NULL,
  `roomHygiene` varchar(100) NOT NULL,
  `additionalComments` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'Pending',
  `visibility` varchar(100) NOT NULL DEFAULT 'Yes',
  `checkInOrOut` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanliness`
--

INSERT INTO `cleanliness` (`cleanID`, `studentName`, `roomNumber`, `roomCleanliness`, `roomHygiene`, `additionalComments`, `status`, `visibility`, `checkInOrOut`) VALUES
(1, 'Mohamad Fikri Bin Ahmad Fadzil', 'S-K-9-3-B', 'clean', 'hygienic', '', 'Pending', 'Yes', 'checkin');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `reportID` int(11) NOT NULL,
  `studentName` varchar(100) NOT NULL,
  `roomNumber` varchar(100) NOT NULL,
  `roomIssues` varchar(100) NOT NULL,
  `issuesDescription` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'Pending',
  `dateReport` date DEFAULT NULL,
  `visibility` varchar(50) NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`reportID`, `studentName`, `roomNumber`, `roomIssues`, `issuesDescription`, `status`, `dateReport`, `visibility`) VALUES
(1, 'Mohamad Fikri Bin Ahmad Fadzil', 'S-K-9-3-B', 'Furniture Issues', 'Sofa patah', 'Pending', '2025-01-11', 'Yes'),
(2, 'Mohamad Fikri Bin Ahmad Fadzil', 'S-K-9-3-B', 'Wifi Issues', 'Wifi rosak', 'Pending', '2025-01-11', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `studentdata`
--

CREATE TABLE `studentdata` (
  `studentID` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `roomNumber` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentdata`
--

INSERT INTO `studentdata` (`studentID`, `name`, `email`, `roomNumber`) VALUES
('b032210107', 'Mohamad Fikri Bin Ahmad Fadzil', 'b032210107@student.utem.edu.my', 'S-K-9-3-B'),
('b032210467', 'Nur Aisyah binti Ahmad', 'b032210467@student.utem.edu.my', 'S-L-8-4-C'),
('b032211905', 'Muhammad Hafiz bin Ahmad', 'b032211905@student.utem.edu.my', 'S-J-4-10-D'),
('b032220029', 'Yasrizal Hakim Bini Yaresham', 'b032220029@student.utem.edu.my', 'S-T-1-2-A');

-- --------------------------------------------------------

--
-- Table structure for table `techniciantask`
--

CREATE TABLE `techniciantask` (
  `technicianTaskID` int(11) NOT NULL,
  `technicianID` varchar(100) NOT NULL,
  `technicianname` varchar(100) NOT NULL,
  `studentName` varchar(100) NOT NULL,
  `roomNumber` varchar(100) NOT NULL,
  `roomIssues` varchar(100) NOT NULL,
  `issuesDescription` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  `dateReport` varchar(100) NOT NULL,
  `visibility` varchar(100) NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `techniciantask`
--

INSERT INTO `techniciantask` (`technicianTaskID`, `technicianID`, `technicianname`, `studentName`, `roomNumber`, `roomIssues`, `issuesDescription`, `status`, `dateReport`, `visibility`) VALUES
(1, 'b032210101', 'Muhammad Zarif Bin Hisham', 'Mohamad Fikri Bin Ahmad Fadzil', 'S-K-9-3-B', 'Furniture Issues', 'Sofa patah', 'Pending', '2025-01-13', 'Yes'),
(2, 'b032210102', 'Muhammad Hafiz Bin Hanif', 'Mohamad Fikri Bin Ahmad Fadzil', 'S-K-9-3-B', 'Wifi Issues', 'Wifi rosak', 'Pending', '2025-01-17', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  `visibility` varchar(100) NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `visibility`) VALUES
('b032210101', 'Muhammad Zarif Bin Hisham', 'b032210101@technician.utem.edu.my', '12345678', 'technician', 'Yes'),
('b032210102', 'Muhammad Hafiz Bin Hanif', 'b032210102@technician.utem.edu.my', '12345678', 'technician', 'Yes'),
('b032210107', 'Mohamad Fikri Bin Ahmad Fadzil', 'b032210107@student.utem.edu.my', '12345678', 'student', 'Yes'),
('b032210467', 'Nur Aisyah Binti Ahmad', 'b032210467@student.utem.edu.my', '12345678', 'student', 'Yes'),
('b032210654', 'Arif Syazwan bin Razak', 'b032210654@cleaner.utem.edu.my', '12345678', 'cleaner', 'Yes'),
('b032210876', 'Aina Zahra binti Zulkifli', 'b032210876@cleaner.utem.edu.my', '12345678', 'cleaner', 'Yes'),
('b032210983', 'Nazmie Shah Bin Khairul', 'b032210983@cleaner.utem.edu.my', '12345678', 'cleaner', 'Yes'),
('b032211034', 'Puteri Qistina binti Mohd Razak', 'b032211034@staff.utem.edu.my', '12345678', 'staff', 'Yes'),
('b032211138', 'Khairul Irfan bin Salleh', 'b032211138@technician.utem.edu.my', '12345678', 'technician', 'Yes'),
('b032211209', 'Zaid Harith bin Rahman', 'b032211209@technician.utem.edu.my', '12345678', 'technician', 'Yes'),
('b032211642', 'Ahmad Danish bin Ismail', 'b032211642@staff.utem.edu.my', '12345678', 'staff', 'Yes'),
('b032211905', 'Muhammad Hafiz bin Ahmad', 'b032211905@student.utem.edu.my', '12345678', 'student', 'Yes'),
('b032220021', 'Nurnadhirah Natasya Binti Ahmad Daud', 'b032220021@staff.utem.edu.my', '12345678', 'staff', 'Yes'),
('b032220029', 'Yasrizal Hakim Bin Yaresham', 'b032220029@student.utem.edu.my', '12345678', 'student', 'Yes'),
('b032319856', 'Nurul Amirah binti Hanafi', 'b032319856@cleaner.utem.edu.my', '12345678', 'cleaner', 'Yes'),
('b032320077', 'Nur Humaira Binti Mohd Nazri', 'b032320077@staff.utem.edu.my', '12345678', 'staff', 'Yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cleanerdata`
--
ALTER TABLE `cleanerdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cleanerreport`
--
ALTER TABLE `cleanerreport`
  ADD PRIMARY KEY (`reportID`);

--
-- Indexes for table `cleanertask`
--
ALTER TABLE `cleanertask`
  ADD PRIMARY KEY (`cleanerTaskID`);

--
-- Indexes for table `cleanliness`
--
ALTER TABLE `cleanliness`
  ADD PRIMARY KEY (`cleanID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`reportID`);

--
-- Indexes for table `studentdata`
--
ALTER TABLE `studentdata`
  ADD PRIMARY KEY (`studentID`);

--
-- Indexes for table `techniciantask`
--
ALTER TABLE `techniciantask`
  ADD PRIMARY KEY (`technicianTaskID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cleanerreport`
--
ALTER TABLE `cleanerreport`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cleanertask`
--
ALTER TABLE `cleanertask`
  MODIFY `cleanerTaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cleanliness`
--
ALTER TABLE `cleanliness`
  MODIFY `cleanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `reportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
