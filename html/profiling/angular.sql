-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2018 at 03:29 AM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `angular`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `memid` int(11) NOT NULL,
  `stud_no` varchar(30) NOT NULL,
  `stud_firstname` varchar(30) NOT NULL,
  `stud_lastname` varchar(30) NOT NULL,
  `stud_course` varchar(30) NOT NULL,
  `stud_yr_lvl` varchar(30) NOT NULL,
  `stud_section` varchar(30) NOT NULL,
  `stud_gender` varchar(30) NOT NULL,
  `stud_email` varchar(30) NULL,
  `stud_contact_no` INT NULL,
  `stud_birthplace` varchar(30) NULL,
  `stud_birthdate` varchar(30) NULL,
  `address` text NOT NULL,
  `stud_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`memid`, `stud_no`,`stud_firstname`, `stud_lastname`,`stud_course`,`stud_yr_lvl`,`stud_section`,`stud_gender`, 
						`stud_email`,`stud_contact_no`,`stud_birthplace`,`stud_birthdate`,`address`,`stud_status`) VALUES
(1, '2015-00394-CM-0','Malene', 'Dizon','BSIT','3','1','Female','malenedizon@gmail.com','091111111','QC','QC', 'Silay City','Regular'),
(2, '2015-00124-CM-0','Francheska', 'Ronquillo','BSIT','3','1','Female','malenedizon@gmail.com','091111111','QC','QC', 'Silay City','Regular'),
(3, '2015-04444-CM-0','Jayson Paul', 'Azul','BSENT','4','1','Male','malenedizon@gmail.com','091111111','QC','QC', 'Silay City','Regular'),
(4, '2015-06475-CM-0','Blabla', 'DTT','BSIT','3','1','Female','malenedizon@gmail.com','091111111','QC','QC', 'Silay City','Regular'),
(5, '2015-11002-CM-0','blaakkkk', 'Huhu','BSIT','3','1','Female','malenedizon@gmail.com','091111111','QC','QC', 'Silay City','Regular');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`memid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `memid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
