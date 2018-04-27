-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2018 at 10:46 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g&csms_db`
--
CREATE DATABASE IF NOT EXISTS `g&csms_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `g&csms_db`;

-- --------------------------------------------------------

--
-- Table structure for table `r_counseling_type`
--

DROP TABLE IF EXISTS `r_counseling_type`;
CREATE TABLE `r_counseling_type` (
  `COUNS_TYPE_CODE` varchar(100) NOT NULL,
  `COUNS_TYPE_NAME` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_counseling_type`
--

INSERT INTO `r_counseling_type` (`COUNS_TYPE_CODE`, `COUNS_TYPE_NAME`) VALUES
('CT_Grp', 'Group Counseling'),
('CT_Indiv', 'Individual Counseling');

-- --------------------------------------------------------

--
-- Table structure for table `r_couns_approach`
--

DROP TABLE IF EXISTS `r_couns_approach`;
CREATE TABLE `r_couns_approach` (
  `COUNS_APPROACH_CODE` varchar(50) NOT NULL,
  `COUNS_APPROACH_NAME` varchar(100) NOT NULL,
  `COUNS_APPROACH_DETAILS` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_couns_approach`
--

INSERT INTO `r_couns_approach` (`COUNS_APPROACH_CODE`, `COUNS_APPROACH_NAME`, `COUNS_APPROACH_DETAILS`) VALUES
('Behavior Therapy', 'Behavior Therapy', NULL),
('Cognitive Therapy', 'Cognitive Therapy', NULL),
('Educational Counseling', 'Educational Counseling', NULL),
('Holistic Therapy', 'Holistic Therapy', NULL),
('Humanistic Therapy', 'Humanistic Therapy', NULL),
('Mental Health Counseling', 'Mental Health Counseling', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `r_remarks_type`
--

DROP TABLE IF EXISTS `r_remarks_type`;
CREATE TABLE `r_remarks_type` (
  `REMARKS_CODE` varchar(100) NOT NULL,
  `REMARKS_NAME` varchar(100) NOT NULL,
  `REMARKS_DETAILS` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_remarks_type`
--

INSERT INTO `r_remarks_type` (`REMARKS_CODE`, `REMARKS_NAME`, `REMARKS_DETAILS`) VALUES
('Sobra Kulit', 'SOBRANG KULIT', NULL),
('Suspended', '1 week Suspension', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `r_stud_educbg`
--

DROP TABLE IF EXISTS `r_stud_educbg`;
CREATE TABLE `r_stud_educbg` (
  `STUD_EDUCBG_ID` int(11) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_EDUC_PRIMARY` varchar(500) DEFAULT NULL,
  `STUD_EDUC_SECONDARY` varchar(500) DEFAULT NULL,
  `STUD_EDUC_TERTIARY` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_stud_i_hab_acad`
--

DROP TABLE IF EXISTS `r_stud_i_hab_acad`;
CREATE TABLE `r_stud_i_hab_acad` (
  `STUD_IH_ACAD_ID` int(11) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_IH_ACADEMIC` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_stud_physical_rec`
--

DROP TABLE IF EXISTS `r_stud_physical_rec`;
CREATE TABLE `r_stud_physical_rec` (
  `STUD_PHYS_ID` int(11) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_HEARING_STATS` text,
  `STUD_SIGHT_STATS` text,
  `STUDSPEECH_STATS` text,
  `STUD_GEN_HEALTH` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_stud_profile`
--

DROP TABLE IF EXISTS `r_stud_profile`;
CREATE TABLE `r_stud_profile` (
  `STUD_ID` int(11) NOT NULL,
  `STUD_NO` varchar(500) NOT NULL,
  `STUD_FNAME` varchar(500) NOT NULL,
  `STUD_MNAME` varchar(500) NOT NULL,
  `STUD_LNAME` varchar(500) NOT NULL,
  `STUD_COURSE` varchar(500) NOT NULL,
  `STUD_YR_LVL` int(11) NOT NULL,
  `STUD_SECTION` varchar(500) NOT NULL,
  `STUD_GENDER` varchar(500) DEFAULT NULL,
  `STUD_EMAIL` varchar(500) DEFAULT NULL,
  `STUD_CONTACT_NO` int(11) DEFAULT NULL,
  `STUD_BIRTHDATE` varchar(500) DEFAULT NULL,
  `STUD_BIRTHPLACE` varchar(500) DEFAULT NULL,
  `STUD_ADDRESS` varchar(1000) NOT NULL,
  `STUD_STATUS` varchar(500) NOT NULL,
  `STUD_DISPLAY_STAT` int(11) DEFAULT NULL,
  `STUD_DATE_ADD` datetime NOT NULL,
  `STUD_DATE_MOD` datetime NOT NULL,
  `STUD_DATE_DEACT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_stud_profile`
--

INSERT INTO `r_stud_profile` (`STUD_ID`, `STUD_NO`, `STUD_FNAME`, `STUD_MNAME`, `STUD_LNAME`, `STUD_COURSE`, `STUD_YR_LVL`, `STUD_SECTION`, `STUD_GENDER`, `STUD_EMAIL`, `STUD_CONTACT_NO`, `STUD_BIRTHDATE`, `STUD_BIRTHPLACE`, `STUD_ADDRESS`, `STUD_STATUS`, `STUD_DISPLAY_STAT`, `STUD_DATE_ADD`, `STUD_DATE_MOD`, `STUD_DATE_DEACT`) VALUES
(6, '000993', 'Dizon', 'Maline', 'Ramos', 'SchoolAdministrator', 0, '', NULL, NULL, NULL, NULL, NULL, '', 'archived', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(7, '2015-11111', 'sq', 'aa', 'qq', 'BBTE', 1, '1', 'Female', 'jj@wa.com', 1212121, '2004-Feb-Sat', 'a', 'a', 'archived', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(11, '2015-00394-CM-0', 'Malene', 'Garrido', 'Dizon', 'BSIT', 3, '1', 'Female', 'malenedizon@gmail.com', 97766855, '1998-Jun-10', 'qc', 'Quezon City', '', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(12, '2015-000000', 'Francheska', 'Nilo', 'Ronquillo', 'BSIT', 3, '1', 'Female', 'as', 2147483647, '1998-Nov-Sun', 'qc', 'qc', '', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(14, '2015-444', 'Vincent Ian', 'M', 'Montes', 'BSIT', 3, '1', 'Male', 'vince@www', 11111, '1998-Jan-Thu', 'qc', 'qcq', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(15, '2014-00098-CM-0', 'Amity Faith', '', 'Arcega', 'BSIT', 1, '1', 'Female', 'gadenamityfaith@yahoo.com', 2147483647, '1996-Dec-Thu', 'qc', '122 jhfasdf', 'Irregular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(30, '2017-00028', 'Plou', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(31, '2017-00029', 'Bfer', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(32, '2017-00030', 'GHt', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(33, '2017-00031', 'Bbe', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(34, '2017-00032', 'hyw', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(35, '2017-00033', 'FEW', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(36, '2017-00034', 'NER', '', 'huhu', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(37, '2017-00056', 'jennifer', '', 'sanchez', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(38, '2017-00057', 'Bryan ', '', 'Cortesiano', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(39, '2017-00058', 'Francheska', '', 'Ronquillo', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(40, '2017-00059', 'Lkier', '', 'FFT', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(41, '2017-00060', 'Gfoor', '', 'CSSS', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(42, '2017-00061', 'BFeg', '', 'POPOP', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(43, '2017-00062', 'Rhea', '', 'Rios', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(44, '2017-00063', 'Jean', '', 'Ramos', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(54, '3017', 'Frit', 'H', 'Giiil', 'BSIT', 2, '1', 'Male', 'huhu@we', 9000, '1994-11-22', NULL, 'qc', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(61, '87654321', 'JUN', 'A', 'Dizon', 'BBTE', 3, '3', 'Male', 'wer@qs', 876000, '1978-03-03', NULL, 'qc', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(62, '2015-0004-CM-0', 'Alyanna', 'M', 'Apo', 'BSIT', 3, '1', 'Female', 'ayano@gmail.com', 9123568, '1991-01-08', NULL, 'Tandang Sora', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(63, '2011-00333-CM-0', 'Ralph', 'Cheif', 'Wiggum', 'BSBA-HR', 5, '4', 'Male', 'thus_email@email.com', 923433564, '1999-08-19', NULL, '777 North Direction Rd Springfield OH ', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(64, '2015-00567-CM-0', 'Jayson Paul', 'Tayer', 'Azul', 'BSIT', 4, '1', 'Male', 'jaysonpaul0678@gmail.com', 2147483647, '1996-12-19', NULL, 'Bulacan City', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(65, '2015-00192-CM', 'Anne', 'Nic', 'Kim', 'BSBA-HR', 2, '1', 'Female', 'kneecowl36@gmail.com', 2147483647, '2009-08-11', NULL, 'dsgresfdtdvrsvfvbbkyfestrtdby', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(66, '2014-11111-CM-0', '1', '', '1', 'BBTE', 1, '1', 'Male', '111@www', 11111111, '1991-01-01', NULL, 'qc', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(67, '2017-12345-CM-0', 'Malen', 'Gar', 'Diz', 'BSIT', 2, '1', 'Female', 'kuku@gmail.com', 9878787, '1999-11-06', NULL, 'Quezon City', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(76, '2017-00064', 'Jocer', '', 'Donos', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(77, '2017-00065', 'Daniel', '', 'Balmoria', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(78, '2017-00066', 'Eric', '', 'Valdez', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(79, '2017-00067', 'Bryan', '', 'Cortesiano', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(80, '2017-00068', 'Oliven', '', 'Tiu', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(81, '2017-00069', 'Mc', '', 'Cordero', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(82, '2017-00070', 'Sarah', '', 'Bautista', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(83, '2017-00071', 'James', '', 'Nicholas', 'BSIT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(84, '2017-00072', 'Vincent', '', 'Montes', 'BBTE', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(85, '2017-00073', 'Glen', '', 'Ten', 'BSBAMM', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(86, '2017-00074', 'Raven', '', 'Labayen', 'BSBAHRDM', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(87, '2017-00075', 'Peter', '', 'Velez', 'DICT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(88, '2017-00076', 'Joseph', '', 'San Juan', 'BSIT', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(89, '2017-00077', 'Jufor', '', 'Manaron', 'BSBAMM', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(90, '2017-00078', 'Janlex', '', 'Baydal', 'BSIT', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(91, '2017-00079', 'Kim', '', 'Rasdas', 'BSIT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(92, '2017-00080', 'Ruffa', '', 'Cristobal', 'DOMT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(93, '2017-00081', 'James', '', 'Diaz', 'DOMT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(94, '2017-00082', 'Arhizz', '', 'Dizon', 'DICT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(95, '2017-00083', 'Kolin', '', 'Mabanes', 'BSBAMM', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(96, '2017-00084', 'James', '', 'Casol', 'BSBAMM', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(97, '2017-00085', 'King', '', 'Sambo', 'BSIT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(98, '2017-00086', 'Richwell', '', 'Sambo', 'DICT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(99, '2017-00087', 'John', '', 'Sambo', 'BSBAMM', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(100, '2017-00088', 'Emmanuel', '', 'Sambo', 'BBTE', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(101, '2017-00089', 'Jervey', '', 'Cruz', 'DOMT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(102, '2017-00090', 'Don', '', 'Fabian', 'BSBAMM', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(103, '2017-00091', 'Don', '', 'Antonio', 'DOMT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(104, '2017-00092', 'Dona', '', 'Carmen', 'BBTE', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(105, '2017-00093', 'San', '', 'Pascual', 'BSENT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(106, '2017-00094', 'Peter', '', 'Parish', 'BSENT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(107, '2017-00095', 'Arwind', '', 'Santos', 'DOMT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(108, '2017-00096', 'Robert', '', 'Bayless', 'BSENT', 4, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(109, '2017-00097', 'Princess', '', 'Faustina', 'BSBAMM', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(110, '2017-00098', 'Vina', '', 'Dran', 'BSENT', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(111, '2017-00099', 'Blake', '', 'Delacruz', 'BSBAMM', 4, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(112, '2017-00100', 'Micheal', '', 'Jordan', 'BBTE', 4, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(113, '2017-00101', 'Anne', '', 'Curtis', 'DOMT', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(114, '2017-00102', 'Vhong', '', 'Navvaro', 'DOMT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(115, '2017-00103', 'Jhong', '', 'Hillario', 'BSBAMM', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(116, '2017-00104', 'Vice', '', 'Ganda', 'BBTE', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(117, '2017-00105', 'Raky', '', 'Mabait', 'BSBAMM', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(118, '2017-00106', 'Jeuz', '', 'Adviento', 'BSENT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(119, '2017-00107', 'Steve', '', 'Stanley', 'BSENT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(120, '2017-00108', 'Patrick', '', 'Briones', 'BBTE', 1, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(121, '2017-00109', 'Genna', '', 'Rodriguez', 'DOMT', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(122, '2017-00110', 'Stephanie', '', 'Keh', 'BSENT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(123, '2017-00111', 'Diane', '', 'Valdez', 'BBTE', 2, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(124, '2017-00112', 'Honey', '', 'Caalim', 'DOMT', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(125, '2017-00113', 'Mary', '', 'Oliver', 'BSBAMM', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(126, '2017-00114', 'Carlo', '', 'Villalon', 'DOMT', 4, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(127, '2017-00115', 'Loise', '', 'Saavedra', 'BSENT', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(128, '2017-00116', 'Yanee', '', 'Deguzman', 'BBTE', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(129, '2017-00117', 'Ernest', '', 'Santos', 'BSBAMM', 3, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(130, '2017-00118', 'Patty', '', 'Gunda', 'BSBAMM', 3, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(131, '2017-00119', 'Meg ', '', 'Parian', 'DICT', 2, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(132, '2017-00120', 'Malene', '', 'Dizon', 'BSIT', 2, '2', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(133, '2017-00121', 'Chaina', '', 'Solomon', 'BBTE', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(134, '2017-00122', 'Rowen', '', 'Icoy', 'BBTE', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(135, '2017-00123', 'John', '', 'Mangulabnan', 'BBTE', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(136, '2017-00124', 'Patricia', '', 'Honrales', 'BBTE', 1, '1', NULL, NULL, NULL, NULL, NULL, '', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(137, '2013-78978-CM-0', 'Mark', 'Garrido', 'Dizon', 'BBTE', 2, '1', 'Male', 'markdiz@gmail.com', 11111111, '1996-06-15', NULL, 'Quezon City', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(139, '33', 'fffffuuuuuu', 'uuuuufff', 'uuufff', 'DICT', 2, '1', 'Female', 're@re', 56565656, '1990-11-26', NULL, 'QC', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(140, '12112', 'huuuuuuuu', 'huuuuuuuuuuuu', 'huuuuuuuu', 'BSENT', 1, '2', 'Female', 're@re', 56565656, '2001-11-27', NULL, 'QC', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(144, '2016-00120-Cm-0', 'Ednalyn ', 'Buenagua', 'Carillo', 'BBTE', 2, '1', 'Female', 'carillo.ednalyn@yahoo.com', 2147483647, '1990-10-18', NULL, '207 Don Fabian St. Commonwealth Quezon City', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(145, '2013-76546-CM-0', 'Malyn', 'Garrido', 'Dizun', 'BSMM', 2, '1', 'Female', 'hutttt@gmail.com', 2147483647, '2004-07-23', NULL, 'Quezon City ', 'Regular', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `r_stud_psych_rec`
--

DROP TABLE IF EXISTS `r_stud_psych_rec`;
CREATE TABLE `r_stud_psych_rec` (
  `STUD_PSYCH_ID` int(11) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_PSYHCIATRIST` varchar(500) DEFAULT NULL,
  `STUD_PSYCHOLOGIST` varchar(500) DEFAULT NULL,
  `STUD_COUNSELOR` varchar(500) DEFAULT NULL,
  `STUD_DATE_COUNSULT` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_user`
--

DROP TABLE IF EXISTS `r_user`;
CREATE TABLE `r_user` (
  `USER_ID` int(11) NOT NULL,
  `USER_FNAME` varchar(500) NOT NULL,
  `USER_MNAME` varchar(500) NOT NULL,
  `USER_LNAME` varchar(500) NOT NULL,
  `USER_ROLE` varchar(200) NOT NULL,
  `USERNAME` varchar(500) NOT NULL,
  `USER_PASSWORD` varbinary(1000) NOT NULL,
  `USER_PICTURE` blob
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_user`
--

INSERT INTO `r_user` (`USER_ID`, `USER_FNAME`, `USER_MNAME`, `USER_LNAME`, `USER_ROLE`, `USERNAME`, `USER_PASSWORD`, `USER_PICTURE`) VALUES
(1, 'Melanie', 'F', 'Bactasa', 'Counselor', 'admin', 0x61646d696e, NULL),
(6, 'hhhhh', 'hhhhh', 'hhhhh', ' ', ' GuidanceCounselor', 0x686868, 0x37353838365f3134393233353234383435363232345f3235383834315f6e2e6a7067),
(7, 'mae', 'wer', 'mae', ' ', ' GuidanceCounselor', '', ''),
(8, 'Malene', 'Garrido', 'Dizon', 'Student Assistant', 'sa', 0x7361, NULL),
(9, 'a', 'a', 'a', 'Student Assistant', 'admin', 0x717171, NULL),
(10, 'wq', 'qwq', 'qwq', 'Student Assistant', 'aaa', 0x6161, NULL),
(11, 'hi', 'q', 'qq', 'Student Assistant', 'a', 0x61, NULL),
(12, 'aq', 'aq', 'aq', 'Admin', 'a', 0x61, NULL),
(13, 'Amity Faith', '', 'Arcega', '', 'AmityFaith', 0x313233343536, NULL),
(14, 'a', 'a', 'a', 'a', 'admin', 0x61646d696e, NULL),
(15, 'Rhea', 'Fernandez', 'Rios', '', 'rhea', 0x72696f73, NULL),
(16, 'huhu', 'hu', 'hu', 'System Admin', 'sysadmin', 0x73797361646d696e, NULL),
(17, 'h', 'h', 'h', 'Student Assistant', 'h', 0x68, NULL),
(18, 'Malene', 'Garrido', 'Dizon', '', 'studassist', 0x73747564617373697374, NULL),
(19, 'Jayson', 'Tayer', 'Azul', 'Student Assistant', 'jayson', 0x6a6179736f6e, NULL),
(20, 'Jayson', 'Tayer', 'Azul', 'Student Assistant', 'jayjay', 0x6a61796a6179, NULL),
(21, 'AAAAAAAAA', 'aaaa', 'aaaaaaaaaaa', 'Student Assistant', 'aaaaaaa', 0x61, NULL),
(22, 'Ian', 'Badal', 'Avena', 'Student Assistant', 'ian', 0x69616e, NULL),
(23, 'Malene', 'Garrido', 'Dizon', 'Student Assistant', 'malene', 0x6d616c656e65, NULL),
(24, 'Ednalyn', 'Buenagua', 'Carillo', 'Student Assistant', 'edna', 0x65646e61313233, NULL),
(25, 'Malyn', 'Garrido', 'Dizun', 'Student Assistant', 'malyn', 0x6d616c796e, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `r_visit_type`
--

DROP TABLE IF EXISTS `r_visit_type`;
CREATE TABLE `r_visit_type` (
  `VISIT_CODE` varchar(100) NOT NULL,
  `VISIT_NAME` varchar(100) NOT NULL,
  `VISIT_DESC` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_visit_type`
--

INSERT INTO `r_visit_type` (`VISIT_CODE`, `VISIT_NAME`, `VISIT_DESC`) VALUES
('Clearance', 'Signing of Clearance', NULL),
('CoC', 'Certificate of Candidacy', NULL),
('Excuse', 'Excuse Letter', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sys_con_drp`
--

DROP TABLE IF EXISTS `sys_con_drp`;
CREATE TABLE `sys_con_drp` (
  `sys_id` int(11) NOT NULL,
  `course` varchar(250) NOT NULL,
  `year_sec` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sys_con_drp`
--

INSERT INTO `sys_con_drp` (`sys_id`, `course`, `year_sec`) VALUES
(1, 'BSBA-HR', '1-1'),
(2, 'BSMM', '2-1'),
(3, 'BSIT', '3-1'),
(4, 'BBTE', '4-1'),
(5, 'BSEM', '5-1'),
(6, 'DOMT', '1-N'),
(7, '', '2-N'),
(8, '', '3-N'),
(9, '', '4-N'),
(10, '', '5-N'),
(33, '', ''),
(34, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_batch_group`
--

DROP TABLE IF EXISTS `t_batch_group`;
CREATE TABLE `t_batch_group` (
  `BATCH_ID` int(11) NOT NULL,
  `BATCH_APPROACH` varchar(50) NOT NULL,
  `BATCH_bg` text,
  `BATCH_goals` text,
  `BATCH_comments` text,
  `BATCH_recomm` text,
  `BATCH_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_batch_group`
--

INSERT INTO `t_batch_group` (`BATCH_ID`, `BATCH_APPROACH`, `BATCH_bg`, `BATCH_goals`, `BATCH_comments`, `BATCH_recomm`, `BATCH_DATE`) VALUES
(3, 'Educational Counseling', '', '', '', '', '2018-03-23 08:37:09'),
(4, 'Educational Counseling', '', '', '', '', '2018-03-23 08:37:51'),
(5, 'Educational Counseling', '', '', '', '', '2018-03-23 08:38:32'),
(6, 'Educational Counseling', '', '', '', '', '2018-03-23 08:39:26'),
(7, 'Educational Counseling', '', '', '', '', '2018-03-23 08:39:31'),
(8, 'Educational Counseling', '', '', '', '', '2018-03-23 08:39:33'),
(12, 'Mental Health Counseling', '', '', '', '', '2018-03-23 08:55:26'),
(13, 'Holistic Therapy', '', '', '', '', '2018-03-23 09:05:44'),
(14, 'Behavior Therapy', '', '', '', '', '2018-03-23 09:09:23'),
(15, 'Behavior Therapy', '', '', '', '', '2018-03-23 09:11:19'),
(16, 'Cognitive Therapy', '', '', '', '', '2018-03-23 09:12:13'),
(17, 'Educational Counseling', '', '', '', '', '2018-03-23 09:17:39'),
(18, 'Holistic Therapy', '', '', '', '', '2018-03-23 09:20:24'),
(19, 'Mental Health Counseling', '', '', '', '', '2018-03-23 09:25:26'),
(20, 'Mental Health Counseling', '', '', '', '', '2018-03-23 09:27:24'),
(21, 'Cognitive Therapy', '', '', '', '', '2018-03-23 10:41:59'),
(22, 'Mental Health Counseling', '', '', '', '', '2018-03-23 18:56:16'),
(23, 'Cognitive Therapy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', '2018-03-23 18:58:42'),
(24, 'Cognitive Therapy', 'HIIIIIIIIIIIIIIIIIIIIILorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', '', '', '', '2018-03-23 19:02:47'),
(25, 'Holistic Therapy', 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', '2018-03-23 19:18:37'),
(26, 'Cognitive Therapy', 'Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.', 'Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.', 'Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.', 'Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.', '2018-03-23 19:50:46'),
(27, 'Educational Counseling', ' Background of the Case', ' Background of the Case', ' Background of the Case', ' Background of the Case', '2018-03-24 04:47:19');

-- --------------------------------------------------------

--
-- Table structure for table `t_counseling`
--

DROP TABLE IF EXISTS `t_counseling`;
CREATE TABLE `t_counseling` (
  `COUNSELING_ID` int(11) NOT NULL,
  `COUNSELING_TYPE_CODE` varchar(50) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_NO` varchar(100) NOT NULL,
  `STUD_NAME` varchar(100) NOT NULL,
  `STUD_COURSE` varchar(100) NOT NULL,
  `STUD_YR` varchar(50) NOT NULL,
  `STUD_SECTION` varchar(50) NOT NULL,
  `STUD_CONTACT` int(11) DEFAULT NULL,
  `STUD_EMAIL` varchar(100) DEFAULT NULL,
  `STUD_ADDRESS` varchar(100) DEFAULT NULL,
  `COUNS_APPROACH` varchar(50) NOT NULL,
  `COUNS_BG` text,
  `COUNS_GOALS` text,
  `COUNS_PREV_TEST` text,
  `COUNS_PREV_PERSON` varchar(500) DEFAULT NULL,
  `COUNS_COMMENTS` text,
  `COUNS_RECOMM` text,
  `COUNS_APPOINTMENT_TYPE` varchar(100) NOT NULL,
  `COUNS_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_counseling`
--

INSERT INTO `t_counseling` (`COUNSELING_ID`, `COUNSELING_TYPE_CODE`, `STUD_ID`, `STUD_NO`, `STUD_NAME`, `STUD_COURSE`, `STUD_YR`, `STUD_SECTION`, `STUD_CONTACT`, `STUD_EMAIL`, `STUD_ADDRESS`, `COUNS_APPROACH`, `COUNS_BG`, `COUNS_GOALS`, `COUNS_PREV_TEST`, `COUNS_PREV_PERSON`, `COUNS_COMMENTS`, `COUNS_RECOMM`, `COUNS_APPOINTMENT_TYPE`, `COUNS_DATE`) VALUES
(68, 'CT_Indiv', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT', '3', '1', 97766855, 'malenedizon@gmail.com', 'Quezon City', 'Cognitive Therapy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Walk-in', '2018-03-22 21:54:08'),
(69, 'CT_Indiv', 64, '2015-00567-CM-0', 'Jayson Paul Azul', 'BSIT', '4', '1', 2147483647, 'jaysonpaul0678@gmail.com', 'Bulacan City', 'Mental Health Counseling', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Walk-in', '2018-03-22 21:55:21'),
(70, 'CT_Indiv', 14, '2015-444', 'Vincent Ian Montes', 'BSIT', '3', '1', 11111, 'vince@www', 'qcq', 'Behavior Therapy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.Nullam quis risus eget urna mollis ornare vel eu leo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nullam id dolor id nibh ultricies vehicula ut id elit.Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec sed odio dui.', 'Walk-in', '2018-03-23 02:21:53'),
(71, 'CT_Indiv', 12, '2015-000000', 'Francheska Ronquillo', 'Bachelor of Science in Information Technology', '3', 'BBA', 2147483647, 'as', 'qc', 'Educational Counseling', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Walk-in', '2018-03-23 18:01:51'),
(72, 'CT_Indiv', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT', '3', '1', 97766855, 'malenedizon@gmail.com', 'Quezon City', 'Mental Health Counseling', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', NULL, NULL, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur bibendum ornare dolor, quis ullamcorper ligula sodales at. Nulla tellus elit, varius non commodo eget, mattis vel eros. In sed ornare nulla.', 'Walk-in', '2018-03-24 00:59:27'),
(73, 'CT_Indiv', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT', '3', '1', 97766855, 'malenedizon@gmail.com', 'Quezon City', 'Educational Counseling', 'Nagdedefense nag-error', 'Motivate the student', NULL, NULL, 'Mahirap huhu', 'Aja', 'Walk-in', '2018-03-24 04:45:00'),
(74, 'CT_Indiv', 15, '2014-00098-CM-0', 'Amity Faith Arcega', 'BSIT', '1', '1', 2147483647, 'gadenamityfaith@yahoo.com', '122 jhfasdf', 'Educational Counseling', '', '', NULL, NULL, '', 'hahahahahhaha', 'Walk-in', '2018-04-01 23:50:43'),
(75, 'CT_Indiv', 144, '2016-00120-Cm-0', 'Ednalyn  Carillo', 'BBTE', '2', '1', 2147483647, 'carillo.ednalyn@yahoo.com', '207 Don Fabian St. Commonwealth Quezon City', 'Educational Counseling', 'asdfghasdfghsdfg', 'asdfghjasdfg', NULL, NULL, 'sdfghjsdfg', 'asdfghs', 'Walk-in', '2018-04-02 07:23:25'),
(76, 'CT_Indiv', 64, '2015-00567-CM-0', 'Jayson Paul Azul', 'BSIT', '4', '1', 2147483647, 'jaysonpaul0678@gmail.com', 'Bulacan City', 'Behavior Therapy', 'Case Background', 'This is a goal', NULL, NULL, 'Comment', 'Recomendation', 'Walk-in', '2018-04-02 08:25:38'),
(77, 'CT_Indiv', 145, '2013-76546-CM-0', 'Malyn Dizun', 'BSMM', '2', '1', 2147483647, 'hutttt@gmail.com', 'Quezon City ', 'Behavior Therapy', 'Napagalitan ng prof kasi late pumasok', 'Maguide ang student', NULL, NULL, 'Mahirap. Lagi sya late.', 'Maybe next come early', 'Walk-in', '2018-04-02 09:42:57'),
(78, 'CT_Indiv', 145, '2013-76546-CM-0', 'Malyn Dizun', 'BSMM', '2', '1', 2147483647, 'hutttt@gmail.com', 'Quezon City ', 'Cognitive Therapy', '', '', NULL, NULL, '', '', 'Walk-in', '2018-04-23 04:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `t_counseling_group`
--

DROP TABLE IF EXISTS `t_counseling_group`;
CREATE TABLE `t_counseling_group` (
  `grp_COUNSELING_ID` int(11) NOT NULL,
  `grp_STUD_NO` varchar(100) NOT NULL,
  `grp_STUD_NAME` varchar(100) NOT NULL,
  `grp_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_counseling_group`
--

INSERT INTO `t_counseling_group` (`grp_COUNSELING_ID`, `grp_STUD_NO`, `grp_STUD_NAME`, `grp_id`) VALUES
(10, '2015-00394-CM-0', 'Malene Dizon', 4),
(12, '2015-444', 'Vincent Ian Montes', 5),
(13, '2015-00394-CM-0', 'Malene Dizon', 16),
(14, '2015-444', 'Vincent Ian Montes', 16),
(15, '2015-00394-CM-0', 'Malene Dizon', 17),
(16, '2015-444', 'Vincent Ian Montes', 17),
(17, '2017-00028', 'Plou Huhu', 18),
(18, '2015-00394-CM-0', 'Malene Dizon', 19),
(19, '2015-00394-CM-0', 'Malene Dizon', 20),
(20, '2014-00098-CM-0', 'Amithy Faith Arcega', 21),
(21, '2015-00394-CM-0', 'Malene Dizon', 21),
(22, '2015-444', 'Vincent Ian Montes', 21),
(23, '2015-00394-CM-0', 'Malene Dizon', 22),
(24, '2015-00394-CM-0', 'Malene Dizon', 24),
(25, '2014-00098-CM-0', 'Amithy Faith Arcega', 25),
(26, '2015-00394-CM-0', 'Malene Dizon', 26),
(27, '2014-00098-CM-0', 'Amithy Faith Arcega', 26),
(28, '2014-00098-CM-0', 'Amithy Faith Arcega', 27),
(29, '2015-00394-CM-0', 'Malene Dizon', 27);

-- --------------------------------------------------------

--
-- Table structure for table `t_remarks`
--

DROP TABLE IF EXISTS `t_remarks`;
CREATE TABLE `t_remarks` (
  `REMARKS_ID` int(11) NOT NULL,
  `REMARKS_CODE` varchar(100) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `VISIT_CODE` varchar(100) DEFAULT NULL,
  `COUNS_ID` int(11) DEFAULT NULL,
  `REMARKS_DETAILS` text,
  `REMARKS_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_remarks`
--

INSERT INTO `t_remarks` (`REMARKS_ID`, `REMARKS_CODE`, `STUD_ID`, `VISIT_CODE`, `COUNS_ID`, `REMARKS_DETAILS`, `REMARKS_DATE`) VALUES
(1, 'Suspended', 11, NULL, NULL, NULL, '2018-03-15 20:09:59'),
(2, 'Suspended', 11, NULL, NULL, NULL, '2018-03-15 21:50:27'),
(3, 'Suspended', 11, NULL, NULL, NULL, '2018-03-15 21:51:52'),
(4, 'Sobra Kulit', 14, NULL, NULL, NULL, '2018-03-15 22:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `t_upload`
--

DROP TABLE IF EXISTS `t_upload`;
CREATE TABLE `t_upload` (
  `T_UPLOAD_ID` int(11) NOT NULL,
  `T_UPLOAD_NAME` varchar(200) NOT NULL,
  `T_UPLOAD_CATEGORY` varchar(50) NOT NULL,
  `T_UPLOAD_DATE` date NOT NULL,
  `T_UPLOAD_LOCATION` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_upload`
--

INSERT INTO `t_upload` (`T_UPLOAD_ID`, `T_UPLOAD_NAME`, `T_UPLOAD_CATEGORY`, `T_UPLOAD_DATE`, `T_UPLOAD_LOCATION`) VALUES
(23, 'Sample', 'Excuse Letter', '2018-03-24', 'Files/abstract.docx'),
(24, 'HUHU', 'Excuse Letter', '2018-03-24', 'Files/MaleneBIO.docx'),
(25, 'Excuse Letter', 'Excuse Letter', '2018-03-24', 'Files/BSIT_Students.xlsx'),
(26, 'Subukan', 'Excuse Letter', '2018-04-02', 'Files/Solicitation letter.docx'),
(27, 'Sample File Upload', 'Excuse Letter', '2018-04-02', 'Files/GUIDANCE_RESEARCH.docx');

-- --------------------------------------------------------

--
-- Table structure for table `t_visits`
--

DROP TABLE IF EXISTS `t_visits`;
CREATE TABLE `t_visits` (
  `VISIT_ID` int(11) NOT NULL,
  `VISIT_CODE` varchar(100) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_NO` varchar(100) NOT NULL,
  `STUD_NAME` varchar(100) NOT NULL,
  `STUD_COURSE` varchar(100) NOT NULL,
  `VISIT_DETAILS` text,
  `VISIT_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `VISIT_REMARKS` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_visits`
--

INSERT INTO `t_visits` (`VISIT_ID`, `VISIT_CODE`, `STUD_ID`, `STUD_NO`, `STUD_NAME`, `STUD_COURSE`, `VISIT_DETAILS`, `VISIT_DATE`, `VISIT_REMARKS`) VALUES
(3, 'Clearance', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', NULL, '2018-03-15 22:49:59', NULL),
(20, 'CoC', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', 'huhu please gumana ka naman', '2018-03-15 23:53:04', NULL),
(21, 'Excuse ', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', 'Bwahahahaha YES!!!!', '2018-03-15 23:53:19', NULL),
(24, 'CoC', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', '', '2018-03-16 00:00:02', NULL),
(25, 'CoC', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', '', '2018-03-16 00:00:25', NULL),
(26, 'CoC', 30, '2017-00028', 'Plou huhu', 'BSENT 4-1', '', '2018-03-16 00:30:16', NULL),
(27, 'Excuse ', 12, '2015-000000', 'Francheska Ronquillo', 'Bachelor of Science in Information Technology 3-BBA', '', '2018-03-16 03:52:23', NULL),
(28, 'Excuse', 32, '2017-00030', 'GHt huhu', 'BSENT 4-1', '', '2018-03-16 08:20:59', NULL),
(29, 'Excuse ', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', 'gumana ka pls', '2009-08-19 16:23:15', NULL),
(31, 'Excuse', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', '', '2009-08-19 16:06:22', NULL),
(32, 'CoC', 11, '2015-00394-CM-0', 'Malene Dizon', 'BSIT 3-1', '', '2018-03-24 04:40:14', NULL),
(34, 'Clearance', 15, '2014-00098-CM-0', 'Amity Faith Arcega', 'Bachelor of Science in Information Technology 1-1', '', '2018-03-29 13:23:56', NULL),
(35, 'CoC', 144, '2016-00120-Cm-0', 'Ednalyn  Carillo', 'BBTE 2-1', '', '2018-04-02 07:16:14', NULL),
(36, 'Clearance', 145, '2013-76546-CM-0', 'Malyn Dizun', 'BSMM 2-1', '', '2018-04-02 09:40:21', NULL),
(37, 'Clearance', 12, '2015-000000', 'Francheska Ronquillo', 'BSIT 3-1', '', '2018-04-23 03:58:13', NULL),
(38, 'Clearance', 14, '2015-444', 'Vincent Ian Montes', 'BSIT 3-1', '', '2018-04-27 08:43:46', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `r_counseling_type`
--
ALTER TABLE `r_counseling_type`
  ADD PRIMARY KEY (`COUNS_TYPE_CODE`);

--
-- Indexes for table `r_couns_approach`
--
ALTER TABLE `r_couns_approach`
  ADD PRIMARY KEY (`COUNS_APPROACH_CODE`);

--
-- Indexes for table `r_remarks_type`
--
ALTER TABLE `r_remarks_type`
  ADD PRIMARY KEY (`REMARKS_CODE`);

--
-- Indexes for table `r_stud_educbg`
--
ALTER TABLE `r_stud_educbg`
  ADD PRIMARY KEY (`STUD_EDUCBG_ID`),
  ADD KEY `FK_STUD_EDUCBG_ID` (`STUD_ID`);

--
-- Indexes for table `r_stud_i_hab_acad`
--
ALTER TABLE `r_stud_i_hab_acad`
  ADD PRIMARY KEY (`STUD_IH_ACAD_ID`),
  ADD KEY `FK_STUD_ID` (`STUD_ID`);

--
-- Indexes for table `r_stud_physical_rec`
--
ALTER TABLE `r_stud_physical_rec`
  ADD PRIMARY KEY (`STUD_PHYS_ID`),
  ADD KEY `FK_STUD_PHYS_ID` (`STUD_ID`);

--
-- Indexes for table `r_stud_profile`
--
ALTER TABLE `r_stud_profile`
  ADD PRIMARY KEY (`STUD_ID`),
  ADD UNIQUE KEY `STUD_NO` (`STUD_NO`);

--
-- Indexes for table `r_stud_psych_rec`
--
ALTER TABLE `r_stud_psych_rec`
  ADD PRIMARY KEY (`STUD_PSYCH_ID`),
  ADD KEY `FK_STUD_PSYCH_ID` (`STUD_ID`);

--
-- Indexes for table `r_user`
--
ALTER TABLE `r_user`
  ADD PRIMARY KEY (`USER_ID`);

--
-- Indexes for table `r_visit_type`
--
ALTER TABLE `r_visit_type`
  ADD PRIMARY KEY (`VISIT_CODE`);

--
-- Indexes for table `sys_con_drp`
--
ALTER TABLE `sys_con_drp`
  ADD PRIMARY KEY (`sys_id`);

--
-- Indexes for table `t_batch_group`
--
ALTER TABLE `t_batch_group`
  ADD PRIMARY KEY (`BATCH_ID`),
  ADD KEY `BATCH_APPROACH` (`BATCH_APPROACH`);

--
-- Indexes for table `t_counseling`
--
ALTER TABLE `t_counseling`
  ADD PRIMARY KEY (`COUNSELING_ID`),
  ADD KEY `FK_C_CT_CODE` (`COUNSELING_TYPE_CODE`),
  ADD KEY `FK_C_STUD_ID` (`STUD_ID`),
  ADD KEY `FK_C_APPROACH` (`COUNS_APPROACH`);

--
-- Indexes for table `t_counseling_group`
--
ALTER TABLE `t_counseling_group`
  ADD PRIMARY KEY (`grp_COUNSELING_ID`),
  ADD KEY `grp_id` (`grp_id`);

--
-- Indexes for table `t_remarks`
--
ALTER TABLE `t_remarks`
  ADD PRIMARY KEY (`REMARKS_ID`),
  ADD KEY `FK_R_CODE` (`REMARKS_CODE`),
  ADD KEY `FK_S_ID` (`STUD_ID`),
  ADD KEY `FK_V_CODE` (`VISIT_CODE`),
  ADD KEY `FK_C_ID` (`COUNS_ID`);

--
-- Indexes for table `t_upload`
--
ALTER TABLE `t_upload`
  ADD PRIMARY KEY (`T_UPLOAD_ID`);

--
-- Indexes for table `t_visits`
--
ALTER TABLE `t_visits`
  ADD PRIMARY KEY (`VISIT_ID`),
  ADD KEY `FK_V_CDE` (`VISIT_CODE`),
  ADD KEY `FK_STUDID` (`STUD_ID`),
  ADD KEY `FK_V_RMKS` (`VISIT_REMARKS`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `r_stud_educbg`
--
ALTER TABLE `r_stud_educbg`
  MODIFY `STUD_EDUCBG_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_stud_i_hab_acad`
--
ALTER TABLE `r_stud_i_hab_acad`
  MODIFY `STUD_IH_ACAD_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_stud_physical_rec`
--
ALTER TABLE `r_stud_physical_rec`
  MODIFY `STUD_PHYS_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_stud_profile`
--
ALTER TABLE `r_stud_profile`
  MODIFY `STUD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `r_stud_psych_rec`
--
ALTER TABLE `r_stud_psych_rec`
  MODIFY `STUD_PSYCH_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_user`
--
ALTER TABLE `r_user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `sys_con_drp`
--
ALTER TABLE `sys_con_drp`
  MODIFY `sys_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `t_batch_group`
--
ALTER TABLE `t_batch_group`
  MODIFY `BATCH_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `t_counseling`
--
ALTER TABLE `t_counseling`
  MODIFY `COUNSELING_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `t_counseling_group`
--
ALTER TABLE `t_counseling_group`
  MODIFY `grp_COUNSELING_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `t_remarks`
--
ALTER TABLE `t_remarks`
  MODIFY `REMARKS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_upload`
--
ALTER TABLE `t_upload`
  MODIFY `T_UPLOAD_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `t_visits`
--
ALTER TABLE `t_visits`
  MODIFY `VISIT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `r_stud_educbg`
--
ALTER TABLE `r_stud_educbg`
  ADD CONSTRAINT `FK_STUD_EDUCBG_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`);

--
-- Constraints for table `r_stud_i_hab_acad`
--
ALTER TABLE `r_stud_i_hab_acad`
  ADD CONSTRAINT `FK_STUD_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`);

--
-- Constraints for table `r_stud_physical_rec`
--
ALTER TABLE `r_stud_physical_rec`
  ADD CONSTRAINT `FK_STUD_PHYS_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`);

--
-- Constraints for table `r_stud_psych_rec`
--
ALTER TABLE `r_stud_psych_rec`
  ADD CONSTRAINT `FK_STUD_PSYCH_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`);

--
-- Constraints for table `t_batch_group`
--
ALTER TABLE `t_batch_group`
  ADD CONSTRAINT `t_batch_group_ibfk_1` FOREIGN KEY (`BATCH_APPROACH`) REFERENCES `r_couns_approach` (`COUNS_APPROACH_CODE`);

--
-- Constraints for table `t_counseling`
--
ALTER TABLE `t_counseling`
  ADD CONSTRAINT `FK_C_APPROACH` FOREIGN KEY (`COUNS_APPROACH`) REFERENCES `r_couns_approach` (`COUNS_APPROACH_CODE`),
  ADD CONSTRAINT `FK_C_CT_CODE` FOREIGN KEY (`COUNSELING_TYPE_CODE`) REFERENCES `r_counseling_type` (`COUNS_TYPE_CODE`),
  ADD CONSTRAINT `FK_C_STUD_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`);

--
-- Constraints for table `t_counseling_group`
--
ALTER TABLE `t_counseling_group`
  ADD CONSTRAINT `t_counseling_group_ibfk_1` FOREIGN KEY (`grp_id`) REFERENCES `t_batch_group` (`BATCH_ID`);

--
-- Constraints for table `t_remarks`
--
ALTER TABLE `t_remarks`
  ADD CONSTRAINT `FK_C_ID` FOREIGN KEY (`COUNS_ID`) REFERENCES `t_counseling` (`COUNSELING_ID`),
  ADD CONSTRAINT `FK_R_CODE` FOREIGN KEY (`REMARKS_CODE`) REFERENCES `r_remarks_type` (`REMARKS_CODE`),
  ADD CONSTRAINT `FK_S_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`),
  ADD CONSTRAINT `FK_V_CODE` FOREIGN KEY (`VISIT_CODE`) REFERENCES `r_visit_type` (`VISIT_CODE`);

--
-- Constraints for table `t_visits`
--
ALTER TABLE `t_visits`
  ADD CONSTRAINT `FK_STUDID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`),
  ADD CONSTRAINT `FK_V_CDE` FOREIGN KEY (`VISIT_CODE`) REFERENCES `r_visit_type` (`VISIT_CODE`),
  ADD CONSTRAINT `FK_V_RMKS` FOREIGN KEY (`VISIT_REMARKS`) REFERENCES `r_remarks_type` (`REMARKS_CODE`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
