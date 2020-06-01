-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2019 at 07:09 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deped_library`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_backup`
--

CREATE TABLE `tbl_backup` (
  `backup_id` int(11) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_booklogs`
--

CREATE TABLE `tbl_booklogs` (
  `booklogs_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `book_id` varchar(200) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `borrower_id` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_books`
--

CREATE TABLE `tbl_books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `pages` int(11) NOT NULL,
  `fund` varchar(100) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `isbn` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `classification` varchar(100) NOT NULL,
  `qty_in` int(11) NOT NULL,
  `qty_out` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrowed`
--

CREATE TABLE `tbl_borrowed` (
  `borrowed_id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `purpose` varchar(100) NOT NULL,
  `date_borrowed` date NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `borrower_id` varchar(200) NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `received_userid` varchar(200) DEFAULT NULL,
  `date_returned` date DEFAULT NULL,
  `booklogs_id` int(11) DEFAULT NULL,
  `booklogs_id2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrowers`
--

CREATE TABLE `tbl_borrowers` (
  `borrower_id` varchar(200) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `contactno` varchar(100) NOT NULL,
  `schoolname` varchar(100) NOT NULL,
  `grade_level` varchar(100) DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `date_created` date NOT NULL,
  `approval` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_copy`
--

CREATE TABLE `tbl_copy` (
  `copy_id` int(11) NOT NULL,
  `account_no` varchar(100) NOT NULL,
  `copy` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `booklogs` int(11) NOT NULL,
  `remarks` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

CREATE TABLE `tbl_employee` (
  `user_id` varchar(200) NOT NULL,
  `image` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `gender` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `contactno` varchar(100) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_employee`
--

INSERT INTO `tbl_employee` (`user_id`, `image`, `firstname`, `lastname`, `gender`, `position`, `address`, `contactno`, `date_created`) VALUES
('RC-001', 'Male.jpg', 'Admin', 'Admin', 'Male', 'Administrator', 'Escalante City', '09123456789', '2019-01-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `school_name` varchar(1000) NOT NULL,
  `system_name` varchar(100) NOT NULL,
  `bg_image` varchar(100) NOT NULL,
  `left_logo` varchar(100) NOT NULL,
  `right_logo` varchar(100) NOT NULL,
  `line1` varchar(100) NOT NULL,
  `line2` varchar(100) NOT NULL,
  `line3` varchar(100) NOT NULL,
  `line4` varchar(100) NOT NULL,
  `line5` varchar(100) NOT NULL,
  `tel_no` varchar(100) NOT NULL,
  `telefax_no` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `web` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `ip_address`, `logo`, `school_name`, `system_name`, `bg_image`, `left_logo`, `right_logo`, `line1`, `line2`, `line3`, `line4`, `line5`, `tel_no`, `telefax_no`, `email`, `web`) VALUES
(1, 'localhost', 'logo.png', 'DepED Division Escalante City', 'E-Library System', 'bg.jpg', 'left_logo.png', 'right_logo.png', 'Republic of the Philippines', 'Department of Education', 'Region VI - Western Visayas', 'DEPARTMENT OF EDUCATION', 'Escalante City, Negros Occidental', '+63-34-454-0746', '+63-34-454-076', 'deped_escalante001@deped.gov.ph', 'www.deped.tk');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_temp`
--

CREATE TABLE `tbl_temp` (
  `temp_id` int(11) NOT NULL,
  `account_no` varchar(200) NOT NULL,
  `user_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `token` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `email`, `password`, `role`, `status`, `token`) VALUES
('RC-001', 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 1, 'Active', 'be3ta2bu5ro0qe1da6qu9mo8ne3ha6ju5');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_userlogs`
--

CREATE TABLE `tbl_userlogs` (
  `log_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `user_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_backup`
--
ALTER TABLE `tbl_backup`
  ADD PRIMARY KEY (`backup_id`);

--
-- Indexes for table `tbl_booklogs`
--
ALTER TABLE `tbl_booklogs`
  ADD PRIMARY KEY (`booklogs_id`);

--
-- Indexes for table `tbl_books`
--
ALTER TABLE `tbl_books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `tbl_borrowed`
--
ALTER TABLE `tbl_borrowed`
  ADD PRIMARY KEY (`borrowed_id`);

--
-- Indexes for table `tbl_borrowers`
--
ALTER TABLE `tbl_borrowers`
  ADD PRIMARY KEY (`borrower_id`);

--
-- Indexes for table `tbl_copy`
--
ALTER TABLE `tbl_copy`
  ADD PRIMARY KEY (`copy_id`);

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_temp`
--
ALTER TABLE `tbl_temp`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `tbl_userlogs`
--
ALTER TABLE `tbl_userlogs`
  ADD PRIMARY KEY (`log_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_copy`
--
ALTER TABLE `tbl_copy`
  MODIFY `copy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
