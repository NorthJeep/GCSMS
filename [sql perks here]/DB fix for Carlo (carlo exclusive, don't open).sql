-- phpMyAdmin SQL Dump 
-- version 4.5.1 
-- http://www.phpmyadmin.net 
-- 
-- Host: 127.0.0.1 
-- Generation Time: May 27, 2018 at 11:56 AM 
-- Server version: 10.1.13-MariaDB 
-- PHP Version: 7.0.6 
 
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; 
SET time_zone = "+00:00"; 
 
 
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */; 
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */; 
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */; 
/*!40101 SET NAMES utf8mb4 */; 
 
 DROP DATABASE IF EXISTS pupqcdb;
 CREATE DATABASE pupqcdb;
 USE pupqcdb;
-- 
-- Database: `pupqcdb` 
-- 
 
DELIMITER $$ 
-- 
-- Procedures 
-- 
CREATE DEFINER=`root`@`localhost` PROCEDURE `counseling_type_add` (IN `type` VARCHAR(50))  NO SQL 
insert into r_couns_type (Couns_TYPE) values (type)$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `login_check` (IN `username` VARCHAR(50), IN `userpass` VARCHAR(100))  NO SQL 
select * from r_users where Users_USERNAME = username and AES_DECRYPT(Users_PASSWORD,password('GC&SMS')) = userpass$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `stud_counseling_add` (IN `visitRef` INT, IN `counsType` VARCHAR(50), IN `appmtType` VARCHAR(25), IN `studNo` VARCHAR(15), IN `bg` TEXT, IN `goal` TEXT, IN `commnt` TEXT, IN `recommendation` TEXT, IN `remarks` VARCHAR(50))  NO SQL 
begin 
set @counsCode = (select if(counsType = 'Individual Counseling',(select concat('IC',date_format(current_date,'%y-%c%d'),convert((select count(*) from t_counseling where Couns_COUNSELING_TYPE = 'Individual Counseling'),int)+1)),(select concat('GC',date_format(current_date,'%y-%c%d'),convert((select count(*) from t_counseling where Couns_COUNSELING_TYPE = 'Individual Counseling'),int)+1))) as CounselingCode); 
insert into t_counseling ( 
    Couns_CODE, 
    Visit_ID_REFERENCE, 
    Couns_COUNSELING_TYPE, 
    Couns_APPOINTMENT_TYPE, 
    Couns_BACKGROUND, 
    Couns_GOALS, 
    Couns_COMMENT, 
  Couns_RECOMMENDATION) 
values ( 
  @counsCode, 
  visitRef, 
  counsType, 
  appmtType, 
  if (bg = '',null,bg), 
  if (goal = '',null,bg), 
  if (commnt,null,commnt), 
  if (recommendation = '',null,recommendation)); 
insert into t_couns_details (Couns_ID_REFERENCE,Stud_NO,Couns_REMARKS) values (LAST_INSERT_ID(),studNo,if (remarks = '',null,remarks)); 
end$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `stud_profile_add` (IN `studNo` VARCHAR(15), IN `fname` VARCHAR(100), IN `mname` VARCHAR(100), IN `lname` VARCHAR(100), IN `gender` VARCHAR(10), IN `course` VARCHAR(15), IN `yearLevel` INT(11), IN `section` VARCHAR(5), IN `bdate` DATE, IN `cityAddress` VARCHAR(500), IN `provAddress` VARCHAR(500), IN `telNo` VARCHAR(20), IN `mobNo` VARCHAR(20), IN `email` VARCHAR(100), IN `birthplace` VARCHAR(500), IN `stat` VARCHAR(20))  NO SQL 
insert into r_stud_profile ( 
    Stud_NO, 
    Stud_FNAME, 
    Stud_MNAME, 
    Stud_LNAME, 
    Stud_GENDER, 
    Stud_COURSE, 
    Stud_YEAR_LEVEL, 
    Stud_SECTION, 
    Stud_BIRTH_DATE, 
  Stud_CITY_ADDRESS, 
  Stud_PROVINCIAL_ADDRESS, 
  Stud_TELEPHONE_NO, 
  Stud_MOBILE_NO, 
  Stud_EMAIL, 
  Stud_BIRTH_PLACE, 
  Stud_STATUS) 
values ( 
    studNo, 
    fname, 
    mname, 
    lname, 
    gender, 
    course, 
    yearLevel, 
    section, 
    bdate, 
  cityAddress, 
  provAddress, 
  telNo, 
  mobNo, 
  email, 
  birthplace, 
  stat)$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `stud_visit_add` (IN `studNo` VARCHAR(15), IN `purpose` VARCHAR(50), IN `details` TEXT)  NO SQL 
begin 
set @visitCode = (select concat('VS',(select date_format(CURRENT_TIMESTAMP,'%y-%c%d')),convert((select count(*) from t_stud_visit where date(Visit_DATE) = CURRENT_DATE),int)+1) as VisitCode); 
insert into t_stud_visit (Visit_CODE,Stud_NO,Visit_PURPOSE,Visit_DETAILS) 
values (@visitCode,studNo,purpose,details); 
end$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `upload_category_add` (IN `category` VARCHAR(100))  NO SQL 
insert into r_upload_category (Upload_FILE_CATEGORY) values (category)$$ 
 
CREATE DEFINER=`root`@`localhost` PROCEDURE `visit_type_add` (IN `type` VARCHAR(50), IN `visitDesc` TEXT)  NO SQL 
insert into r_visit (Visit_TYPE,Visit_DESC) 
values (type,if (visitDesc = '',null,visitDesc))$$ 
 
DELIMITER ; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_batch_details` 
-- 
 
CREATE TABLE `r_batch_details` ( 
  `Batch_ID` int(11) NOT NULL, 
  `Batch_CODE` varchar(15) NOT NULL, 
  `Batch_YEAR` varchar(15) NOT NULL, 
  `Batch_DESC` varchar(100) DEFAULT NULL, 
  `Batch_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_batch_details` 
-- 
 
INSERT INTO `r_batch_details` (`Batch_ID`, `Batch_CODE`, `Batch_YEAR`, `Batch_DESC`, `Batch_DISPLAY_STAT`) VALUES 
(1, 'BAT00001', '2011-2012', 'Batch descrzzzxczxciptions', 'Active'), 
(2, 'BAT00002', '2012-2013', 'Batch description', 'Active'), 
(3, 'BAT00003', '2013-2014', 'Batch description', 'Active'), 
(4, 'BAT00004', '2014-2015', 'Batch description', 'Active'), 
(5, 'BAT00005', '2015-2016', 'Batch description', 'Active'), 
(6, 'BAT00006', '2016-2017', 'Batch description', 'Active'), 
(7, 'BAT00007', '2017-2018', 'Batch description', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_civil_stat` 
-- 
 
CREATE TABLE `r_civil_stat` ( 
  `Stud_CIVIL_STATUS_ID` int(11) NOT NULL, 
  `Stud_CIVIL_STATUS` varchar(15) NOT NULL, 
  `Stud_CIVIL_STATUS_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_civil_stat` 
-- 
 
INSERT INTO `r_civil_stat` (`Stud_CIVIL_STATUS_ID`, `Stud_CIVIL_STATUS`, `Stud_CIVIL_STATUS_STAT`) VALUES 
(1, 'Single', 'Active'), 
(2, 'Married', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_couns_appointment_type` 
-- 
 
CREATE TABLE `r_couns_appointment_type` ( 
  `Appmnt_ID` int(11) NOT NULL, 
  `Appmnt_TYPE` varchar(25) NOT NULL, 
  `Appmnt_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_couns_appointment_type` 
-- 
 
INSERT INTO `r_couns_appointment_type` (`Appmnt_ID`, `Appmnt_TYPE`, `Appmnt_STAT`) VALUES 
(1, 'Walk-in', 'Inactive'), 
(2, 'Referral', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_couns_approach` 
-- 
 
CREATE TABLE `r_couns_approach` ( 
  `Couns_APPROACH_ID` int(11) NOT NULL, 
  `Couns_APPROACH` varchar(50) NOT NULL, 
  `COUNS_APPROACH_DESC` text 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_couns_type` 
-- 
 
CREATE TABLE `r_couns_type` ( 
  `Couns_TYPE_ID` int(11) NOT NULL, 
  `Couns_TYPE` varchar(50) NOT NULL, 
  `Couns_TYPE_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_couns_type` 
-- 
 
INSERT INTO `r_couns_type` (`Couns_TYPE_ID`, `Couns_TYPE`, `Couns_TYPE_STAT`) VALUES 
(1, 'Individual Counseling', 'Active'), 
(2, 'Group Counseling', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_courses` 
-- 
 
CREATE TABLE `r_courses` ( 
  `Course_ID` int(11) NOT NULL, 
  `Course_CODE` varchar(15) NOT NULL, 
  `Course_NAME` varchar(100) NOT NULL, 
  `Course_DESC` varchar(100) NOT NULL DEFAULT 'Course Description', 
  `Course_CURR_YEAR` varchar(15) DEFAULT NULL, 
  `Course_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Course_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Course_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_courses` 
-- 
 
INSERT INTO `r_courses` (`Course_ID`, `Course_CODE`, `Course_NAME`, `Course_DESC`, `Course_CURR_YEAR`, `Course_DATE_MOD`, `Course_DATE_ADD`, `Course_DISPLAY_STAT`) VALUES 
(1, 'BSIT', 'Bachelor of Science in Information Technology', 'Course Descriptions', '2011-2012', '2018-04-25 23:23:43', '2018-02-07 18:41:43', 'Active'), 
(2, 'DOMT', 'Diploma In Office Management Technology', 'Course Description', '2011-2012', '2018-02-09 17:54:51', '2018-02-09 17:54:51', 'Active'), 
(3, 'DICT', 'Diploma in Information Communication Technology', 'Diploma in Information Communication Technology', '2011-2012', '2018-03-11 20:40:22', '2018-03-11 20:40:22', 'Active'), 
(4, '5151', '312312', '3123123', '2017-2018', '2018-04-25 23:24:00', '2018-04-25 23:23:51', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_designated_offices_details` 
-- 
 
CREATE TABLE `r_designated_offices_details` ( 
  `DesOffDetails_ID` int(11) NOT NULL, 
  `DesOffDetails_CODE` varchar(15) NOT NULL, 
  `DesOffDetails_NAME` varchar(100) NOT NULL, 
  `DesOffDetails_DESC` varchar(100) NOT NULL, 
  `DesOffDetails_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `DesOffDetails_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `DesOffDetails_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_guidance_counselor` 
-- 
 
CREATE TABLE `r_guidance_counselor` ( 
  `Counselor_ID` int(11) NOT NULL, 
  `Counselor_CODE` varchar(15) NOT NULL, 
  `Counselor_FNAME` varchar(100) NOT NULL, 
  `Counselor_MNAME` varchar(100) NOT NULL, 
  `Counselor_LNAME` varchar(100) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_guidance_counselor` 
-- 
 
INSERT INTO `r_guidance_counselor` (`Counselor_ID`, `Counselor_CODE`, `Counselor_FNAME`, `Counselor_MNAME`, `Counselor_LNAME`) VALUES 
(1, 'GC-0001', 'Oliver', 'Juan', 'Gabriel'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_nature_of_case` 
-- 
 
CREATE TABLE `r_nature_of_case` ( 
  `Case_NAME` varchar(50) NOT NULL, 
  `Case_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_remarks` 
-- 
 
CREATE TABLE `r_remarks` ( 
  `Remarks_ID` int(11) NOT NULL, 
  `Remarks_TYPE` varchar(50) NOT NULL, 
  `Remarks_DESC` text, 
  `Remarks_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_sanction_details` 
-- 
 
CREATE TABLE `r_sanction_details` ( 
  `SancDetails_ID` int(11) NOT NULL, 
  `SancDetails_CODE` varchar(100) NOT NULL, 
  `SancDetails_NAME` varchar(100) NOT NULL, 
  `SancDetails_DESC` varchar(1000) DEFAULT 'Sanction Description', 
  `SancDetails_TIMEVAL` int(11) NOT NULL DEFAULT '0', 
  `SancDetails_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `SancDetails_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `SancDetails_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_batch` 
-- 
 
CREATE TABLE `r_stud_batch` ( 
  `Stud_BATCH_ID` int(11) NOT NULL, 
  `Stud_NO` varchar(15) NOT NULL, 
  `Batch_YEAR` varchar(15) NOT NULL, 
  `Stud_STATUS` enum('Regular','Irregular','Disqualified','LOA','Transferee') DEFAULT 'Regular' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_stud_batch` 
-- 
 
INSERT INTO `r_stud_batch` (`Stud_BATCH_ID`, `Stud_NO`, `Batch_YEAR`, `Stud_STATUS`) VALUES 
(1, '2015-00138-CM-0', '2017-2018', 'Regular'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_educ_background` 
-- 
 
CREATE TABLE `r_stud_educ_background` ( 
  `Educ_BG_ID` int(11) NOT NULL, 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `Educ_NATURE_OF_SCHOOLING` enum('Continuous','Interrupted') DEFAULT 'Continuous', 
  `Interrupted_REASON` varchar(500) DEFAULT 'None' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_educ_bg_details` 
-- 
 
CREATE TABLE `r_stud_educ_bg_details` ( 
  `Educ_BG_ID` int(11) NOT NULL, 
  `Educ_LEVEL` enum('Pre-elementary','Elementary','High School','Vocational','College if any') NOT NULL, 
  `Educ_SCHOOL_GRADUATED` varchar(500) DEFAULT 'None', 
  `Educ_SCHOOL_ADDRESS` varchar(500) DEFAULT 'None', 
  `Educ_SCHOOL_TYPE` enum('Public','Private') DEFAULT 'Public', 
  `Educ_DATES_OF_ATTENDANCE` varchar(15) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_family_bg_details` 
-- 
 
CREATE TABLE `r_stud_family_bg_details` ( 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `FamilyBG_INFO` enum('Father','Mother','Guardian') NOT NULL, 
  `Info_FNAME` varchar(100) NOT NULL, 
  `Info_MNAME` varchar(100) NOT NULL, 
  `Info_LNAME` varchar(100) NOT NULL, 
  `Info_AGE` int(11) NOT NULL, 
  `Info_STAT` enum('Living','Deceased') DEFAULT 'Living', 
  `Info_EDUC_ATTAINMENT` varchar(100) NOT NULL, 
  `Info_OCCUPATION` varchar(100) NOT NULL, 
  `Info_EMPLOYER_NAME` varchar(300) DEFAULT 'None', 
  `Info_EMPLOYER_ADDRESS` varchar(500) DEFAULT 'None' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_general_info` 
-- 
 
CREATE TABLE `r_stud_general_info` ( 
  `Stud_NO` varchar(15) NOT NULL, 
  `Gen_Info_DETAILS` enum('Favorite Subject/s','Subject/s Least Like','Club','Hobbies','Organization') NOT NULL, 
  `Gen_Info_CONTENT` varchar(100) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_honors_awards` 
-- 
 
CREATE TABLE `r_stud_honors_awards` ( 
  `Educ_BG_ID` int(11) NOT NULL, 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `Received_TYPE` enum('Honors Received','Special Awards') DEFAULT 'Honors Received', 
  `Received_Desc` varchar(100) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_org_position` 
-- 
 
CREATE TABLE `r_stud_org_position` ( 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `Stud_POSITION` text NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_personal_info` 
-- 
 
CREATE TABLE `r_stud_personal_info` ( 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `Stud_HEIGHT` double(9,2) NOT NULL, 
  `Stud_WEIGHT` double(9,2) NOT NULL, 
  `Stud_COMPLEXION` varchar(50) NOT NULL, 
  `Stud_HS_GEN_AVERAGE` double(3,2) NOT NULL, 
  `Stud_RELIGION` varchar(100) NOT NULL, 
  `Stud_CIVIL_STAT` varchar(15) NOT NULL, 
  `Stud_WORKING` enum('Yes','No') DEFAULT 'No', 
  `Employer_NAME` varchar(300) DEFAULT 'None', 
  `Employer_ADDRESS` varchar(100) DEFAULT 'None', 
  `Emergency_CONTACT_PERSON` varchar(500) NOT NULL, 
  `Emergency_CONTACT_ADDRESS` varchar(500) NOT NULL, 
  `Emergency_CONTACT_NUMBER` varchar(20) NOT NULL DEFAULT 'None', 
  `ContactPerson_RELATIONSHIP` varchar(100) NOT NULL, 
  `Parents_MARITAL_RELATIONSHIP` varchar(100) NOT NULL, 
  `Stud_FAM_CHILDREN_NO` int(11) NOT NULL, 
  `Stud_BROTHER_NO` int(11) DEFAULT '0', 
  `Stud_SISTER_NO` int(11) DEFAULT '0', 
  `Employed_BS_NO` int(11) DEFAULT '0', 
  `Stud_ORDINAL_POSITION` varchar(50) NOT NULL, 
  `Stud_SCHOOLING_FINANCE` enum('Parents','Brother/Sister','Spouse','Scholarship','Relatives','Self-supporting/working student') DEFAULT 'Parents', 
  `Stud_WEEKLY_ALLOWANCE` double(9,2) NOT NULL, 
  `Parents_TOTAL_MONTHLY_INCOME` varchar(100) NOT NULL, 
  `Stud_QUIET_PLACE` enum('Yes','No') DEFAULT 'Yes', 
  `Stud_ROOM_SHARE` varchar(50) NOT NULL, 
  `Stud_RESIDENCE` enum('family home','relative''s house','share apartment with friends','share apartment with relatives','bed spacer','rented apartment','house of married brother/sister','dorm (including board & lodging)') DEFAULT 'family home' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_phys_rec` 
-- 
 
CREATE TABLE `r_stud_phys_rec` ( 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `PhysicalRec_VISION` text NOT NULL, 
  `PhysicalRec_HEARING` text NOT NULL, 
  `PhysicalRec_SPEECH` text NOT NULL, 
  `PhysicalRec_GEN_HEALTH` text NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_profile` 
-- 
 
CREATE TABLE `r_stud_profile` ( 
  `Stud_ID` int(11) NOT NULL, 
  `Stud_NO` varchar(15) NOT NULL, 
  `Stud_FNAME` varchar(100) NOT NULL, 
  `Stud_MNAME` varchar(100) DEFAULT NULL, 
  `Stud_LNAME` varchar(100) NOT NULL, 
  `Stud_GENDER` enum('Male','Female') NOT NULL DEFAULT 'Male', 
  `Stud_COURSE` varchar(15) NOT NULL, 
  `Stud_YEAR_LEVEL` int(11) NOT NULL DEFAULT '1', 
  `Stud_SECTION` varchar(5) NOT NULL DEFAULT '1', 
  `Stud_BIRTH_DATE` date NOT NULL, 
  `Stud_CITY_ADDRESS` varchar(500) NOT NULL DEFAULT 'Not Specify', 
  `Stud_PROVINCIAL_ADDRESS` varchar(500) NOT NULL DEFAULT 'Not Specify', 
  `Stud_TELEPHONE_NO` varchar(20) NOT NULL DEFAULT 'None', 
  `Stud_MOBILE_NO` varchar(20) NOT NULL DEFAULT 'None', 
  `Stud_EMAIL` varchar(100) NOT NULL, 
  `Stud_BIRTH_PLACE` varchar(500) DEFAULT NULL, 
  `Stud_STATUS` enum('Regular','Irregular','Disqualified','LOA','Transferee') DEFAULT 'Regular', 
  `Stud_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Stud_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Stud_DATE_DEACTIVATE` datetime DEFAULT NULL, 
  `Stud_DISPLAY_STATUS` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_stud_profile` 
-- 
 
INSERT INTO `r_stud_profile` (`Stud_ID`, `Stud_NO`, `Stud_FNAME`, `Stud_MNAME`, `Stud_LNAME`, `Stud_GENDER`, `Stud_COURSE`, `Stud_YEAR_LEVEL`, `Stud_SECTION`, `Stud_BIRTH_DATE`, `Stud_CITY_ADDRESS`, `Stud_PROVINCIAL_ADDRESS`, `Stud_TELEPHONE_NO`, `Stud_MOBILE_NO`, `Stud_EMAIL`, `Stud_BIRTH_PLACE`, `Stud_STATUS`, `Stud_DATE_MOD`, `Stud_DATE_ADD`, `Stud_DATE_DEACTIVATE`, `Stud_DISPLAY_STATUS`) VALUES 
(1, '2015-00138-CM-0', 'Oliver', NULL, 'Gabriel', 'Male', 'BSIT', 3, '1', '1998-11-16', '24-D4 Oliveros Drive Apolonio Samson Quezon City', 'Not Specify', 'Not Specify', 'Not Specify', 'Not Specify', 'Not Specify', 'Regular', '2018-05-21 14:11:00', '2018-05-21 14:11:00', NULL, 'Active'), 
(2, '2017-00056', 'jennifer', '', 'sanchez', 'Female', 'BSIT', 3, '1', '2015-11-11', 'Quezon City', 'Ilocos', '12479837', '98765432187', 'jen@gmail.com', 'Ilocos', 'Regular', '2018-05-24 18:03:12', '2018-05-24 18:03:12', NULL, 'Active'), 
(3, '2017-00057', 'Bryan ', '', 'Cortesiano', 'Female', 'BSIT', 3, '1', '2015-11-12', 'Commonwealth', 'Agusan', '12479837', '90453567245', 'bry@gmail.com', 'Agusan', 'Regular', '2018-05-24 18:03:12', '2018-05-24 18:03:12', NULL, 'Active'), 
(4, '2017-00058', 'Francheska', 'Nillo', 'Ronquillo', 'Female', 'BSIT', 3, '1', '2015-11-13', 'Caloocan', 'Zambales', '12479837', '98765423465', 'ches@gmail.com', 'Zambales', 'Regular', '2018-05-24 18:03:12', '2018-05-24 18:03:12', NULL, 'Active'), 
(5, '2017-00059', 'Lkier', '', 'FFT', 'Female', 'BSIT', 3, '1', '2015-11-14', 'Fairview', 'Nueva Ecija', '12479837', '92929292923', 'fft@gmail.com', 'Nueva Ecija', 'Regular', '2018-05-24 18:03:13', '2018-05-24 18:03:13', NULL, 'Active'), 
(6, '2017-00060', 'Gfoor', '', 'CSSS', 'Female', 'BSIT', 3, '1', '2015-11-15', 'Novaliches', 'Bataan', '12479837', '98765432187', 'csss@gmail.com', 'Bataan', 'Regular', '2018-05-24 18:03:13', '2018-05-24 18:03:13', NULL, 'Active'), 
(7, '2017-00061', 'BFeg', '', 'POPOP', 'Female', 'BSIT', 3, '1', '2015-11-16', 'Bulacan', 'Tarlac', '12479837', '90453567245', 'popop@gmail.com', 'Tarlac', 'Regular', '2018-05-24 18:03:13', '2018-05-24 18:03:13', NULL, 'Active'), 
(8, '2017-00062', 'Rhea', '', 'Rios', 'Female', 'BSIT', 3, '1', '2015-11-17', 'Navotas', 'Cabanatuan', '12479837', '98765423465', 'rhe@gmail.com', 'Cabanatuan', 'Regular', '2018-05-24 18:03:13', '2018-05-24 18:03:13', NULL, 'Active'), 
(9, '2017-00063', 'Jean', '', 'Ramos', 'Female', 'BSIT', 3, '1', '2015-11-18', 'Valenzuela', 'Baguio', '12479837', '92929292923', 'jea@gmail.com', 'Baguio', 'Regular', '2018-05-24 18:03:13', '2018-05-24 18:03:13', NULL, 'Active'), 
(10, '2017-00064', 'Melissa', '', 'Delos', 'Female', 'BSIT', 3, '1', '2015-11-19', 'Quezon City', 'Cebu', '12479837', '98765432187', 'mel@gmail.com', 'Cebu', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(11, '2017-00065', 'Nikki', '', 'Castilo', 'Female', 'BSIT', 3, '1', '2015-11-20', 'Commonwealth', 'Samar', '12479837', '90453567245', 'nik@gmail.com', 'Samar', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(12, '2017-00066', 'Mami', '', 'Alejandria', 'Female', 'BSIT', 3, '1', '2015-11-21', 'Caloocan', 'Leyte', '12479837', '98765423465', 'mam@gmail.com', 'Leyte', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(13, '2017-0067', 'Jade', '', 'Ago', 'Female', 'BSIT', 3, '1', '2015-11-22', 'Fairview', 'Palawan', '12479837', '92929292923', 'jad@gmail.com', 'Palawan', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(14, '2017-0068', 'Kate', '', 'De Jesus', 'Female', 'BSIT', 3, '1', '2015-10-01', 'Novaliches', 'Aklan', '12479837', '98765432187', 'de jesus@gfail.cof', 'Aklan', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(15, '2017-0069', 'Andrea', '', 'Villajuan', 'Female', 'BSIT', 3, '1', '2015-10-02', 'Bulacan', 'Marinduque', '12479837', '90453567245', 'and@gmail.com', 'Marinduque', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(16, '2017-0070', 'Teri', '', 'De Castro', 'Female', 'BSIT', 3, '1', '2015-10-03', 'Navotas', 'Ilocos', '12479837', '98765423465', 'de castro@gfail.cof', 'Navotas', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(17, '2017-0071', 'Adrian', '', 'Geronilla', 'Female', 'BSIT', 3, '1', '2015-10-04', 'Valenzuela', 'Agusan', '12479837', '92929292923', 'adr@gmail.com', 'Valenzuela', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(18, '2017-0072', 'Sharlyn', '', 'Lee', 'Female', 'BSIT', 3, '1', '2015-10-05', 'Quezon City', 'Zambales', '12479837', '98765432187', 'sha@gmail.com', 'Quezon City', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(19, '2017-0073', 'Dave', '', 'Dogayo', 'Female', 'BSIT', 3, '1', '2015-10-06', 'Commonwealth', 'Nueva Ecija', '12479837', '90453567245', 'dav@gmail.com', 'Commonwealth', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(20, '2017-0074', 'Edward', 'Rito', 'Alfante', 'Female', 'BSIT', 3, '1', '2015-10-07', 'Caloocan', 'Bataan', '12479837', '98765423465', 'edward@gmail.com', 'Caloocan', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(21, '2017-0075', 'Christan', 'ocampo', 'Tan', 'Female', 'BSIT', 3, '1', '2015-10-08', 'Fairview', 'Tarlac', '12479837', '92929292923', 'stan@gmail.com', 'Fairview', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(22, '2017-0076', 'John', 'Duran', 'Albis', 'Female', 'BSIT', 3, '1', '2015-10-09', 'Novaliches', 'Cabanatuan', '12479837', '98765432187', 'john@gmail.com', 'Novaliches', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(23, '2017-0077', 'Luis', '', 'Lantape', 'Female', 'BSIT', 3, '1', '2015-10-10', 'Bulacan', 'Baguio', '12479837', '90453567245', 'lui@gmail.com', 'Bulacan', 'Regular', '2018-05-24 18:03:14', '2018-05-24 18:03:14', NULL, 'Active'), 
(24, '2017-0078', 'Romeo', '', 'Gonzales', 'Female', 'BSIT', 3, '1', '2015-10-11', 'Navotas', 'Cebu', '12479837', '98765423465', 'rom@gmail.com', 'Navotas', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(25, '2017-0079', 'Vin', '', 'Dacuag', 'Female', 'BSIT', 3, '1', '2015-10-12', 'Valenzuela', 'Samar', '12479837', '92929292923', 'vin@gmail.com', 'Valenzuela', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(26, '2017-0080', 'Lynne', '', 'Roque', 'Female', 'BSIT', 3, '1', '2015-10-13', 'Quezon City', 'Leyte', '12479837', '98765432187', 'lyn@gmail.com', 'Quezon City', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(27, '2017-0081', 'Sam', '', 'Foley', 'Female', 'BSIT', 3, '1', '2015-10-14', 'Commonwealth', 'Palawan', '12479837', '90453567245', 'sam@gmail.com', 'Commonwealth', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(28, '2017-0082', 'Hannah', '', 'Panaderya', 'Female', 'BSIT', 3, '1', '2015-10-15', 'Caloocan', 'Aklan', '12479837', '98765423465', 'han@gmail.com', 'Caloocan', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(29, '2017-0083', 'Jessica', '', 'Jones', 'Female', 'BSIT', 3, '1', '2015-10-16', 'Fairview', 'Marinduque', '12479837', '92929292923', 'jes@gmail.com', 'Fairview', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(30, '2017-0084', 'Ryan', '', 'Talledo', 'Female', 'BSIT', 3, '1', '2015-10-17', 'Novaliches', 'Ilocos', '12479837', '98765432187', 'rya@gmail.com', 'Novaliches', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(31, '2017-0085', 'Jasmine', '', 'Flores', 'Female', 'BSIT', 3, '1', '2015-10-18', 'Bulacan', 'Agusan', '12479837', '90453567245', 'jas@gmail.com', 'Bulacan', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(32, '2017-0086', 'Nikki', '', 'Arias', 'Female', 'BSIT', 3, '1', '2015-10-19', 'Navotas', 'Zambales', '12479837', '98765423465', 'nik@gmail.com', 'Navotas', 'Regular', '2018-05-24 18:03:15', '2018-05-24 18:03:15', NULL, 'Active'), 
(33, '2017-0087', 'Remmuel', 'Baniqued', 'Yapit', 'Female', 'BSIT', 3, '1', '2015-10-20', 'Valenzuela', 'Nueva Ecija', '12479837', '92929292923', 'u@gmail.com', 'Valenzuela', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(34, '2017-0088', 'Rina', 'Villanueva', 'Ong', 'Female', 'BSIT', 3, '1', '2015-10-21', 'Quezon City', 'Bataan', '12479837', '98765432187', 'rina@gmail.com', 'Quezon City', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(35, '2017-0089', 'Vallery', 'Santos', 'Melchor', 'Female', 'BSIT', 3, '1', '2015-10-22', 'Commonwealth', 'Tarlac', '12479837', '90453567245', 'e@gmail.com', 'Commonwealth', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(36, '2017-0090', 'Patrick', 'Moon', 'Bituin', 'Female', 'BSIT', 3, '1', '2015-10-23', 'Caloocan', 'Cabanatuan', '12479837', '98765423465', 'i@gmail.com', 'Caloocan', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(37, '2017-0091', 'Ina', 'Yan', 'Chan', 'Female', 'BSIT', 3, '1', '2015-10-24', 'Fairview', 'Baguio', '12479837', '92929292923', 'pat@gmail.com', 'Zambales', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(38, '2017-0092', 'Princess', 'Galicia', 'Kane', 'Female', 'BSIT', 3, '1', '2015-10-25', 'Novaliches', 'Cebu', '12479837', '98765432187', 'cess@gmail.com', 'Nueva Ecija', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(39, '2017-0093', 'Jasey', 'Flores', 'Rae', 'Female', 'BSIT', 3, '1', '2015-09-01', 'Bulacan', 'Samar', '12479837', '90453567245', 'jasey@gmail.com', 'Bataan', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(40, '2017-0094', 'Nadine ', 'Marquez', 'Fabray', 'Female', 'BSIT', 3, '1', '2015-09-02', 'Navotas', 'Leyte', '12479837', '98765423465', 'nadz@gmail.com', 'Tarlac', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(41, '2017-0095', 'Estella ', 'Dela Cruz', 'Baltimore', 'Female', 'BSIT', 3, '1', '2015-09-03', 'Valenzuela', 'Palawan', '12479837', '92929292923', 'estella@gmail.com', 'Cabanatuan', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(42, '2017-0096', 'Jim', 'Oracion', 'Santos', 'Female', 'BSIT', 3, '1', '2015-09-04', 'Dela Costa', 'Aklan', '12479837', '98765432187', 'jiim@gmail.com', 'Baguio', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(43, '2017-0097', 'Celine', 'Mallari', 'Balboa', 'Female', 'BSIT', 3, '1', '2015-09-05', 'Fairview', 'Marinduque', '12479837', '90453567245', 'celine@gmail.com', 'Cebu', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(44, '2017-00156', 'Mayhem', '', 'Lim', 'Female', 'BSIT', 2, '1', '2015-09-06', 'Fairview', 'Marinduque', '12479837', '90453567245', 'mayhem@gmail.com', 'Samar', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(45, '2017-00157', 'Teresita', '', 'Cortez', 'Female', 'BSIT', 2, '1', '2015-09-07', 'Novaliches', 'Ilocos', '12479837', '98765423465', 'teresita@gmail.com', 'Leyte', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(46, '2017-00158', 'Melba', '', 'Venga', 'Female', 'BSIT', 2, '1', '2015-09-08', 'Bulacan', 'Agusan', '12479837', '92929292923', 'melba@gmail.com', 'Valenzuela', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(47, '2017-00159', 'Cynthia', '', 'Bolzinco', 'Female', 'BSIT', 2, '1', '2015-09-09', 'Navotas', 'Zambales', '12479837', '98765432187', 'cynthia@gmail.com', 'Quezon City', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(48, '2017-00160', 'Celia', '', 'Perez', 'Female', 'BSIT', 2, '1', '2015-09-10', 'Valenzuela', 'Nueva Ecija', '12479837', '90453567245', 'celia@gmail.com', 'Commonwealth', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(49, '2017-00161', 'Francisco', '', 'Mendez', 'Female', 'BSIT', 2, '1', '2015-09-11', 'Quezon City', 'Bataan', '12479837', '98765423465', 'francisco@gmail.com', 'Caloocan', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(50, '2017-00162', 'Jeremiah', '', 'Rollo', 'Female', 'BSIT', 2, '1', '2015-09-12', 'Commonwealth', 'Tarlac', '12479837', '92929292923', 'jeremiah@gmail.com', 'Fairview', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(51, '2017-00163', 'John Michael', '', 'Roque', 'Female', 'BSIT', 2, '1', '2015-09-13', 'Caloocan', 'Cabanatuan', '12479837', '98765432187', 'john michael@gmail.com', 'Novaliches', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(52, '2017-00164', 'Michael', '', 'Delima', 'Female', 'BSIT', 2, '1', '2015-09-14', 'Fairview', 'Baguio', '12479837', '90453567245', 'michael@gmail.com', 'Bulacan', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(53, '2017-00165', 'Francis', '', 'Makalintal', 'Female', 'BSIT', 2, '1', '2015-09-15', 'Novaliches', 'Cebu', '12479837', '98765423465', 'francis@gmail.com', 'Navotas', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(54, '2017-00166', 'Joanne', '', 'Dimagiba', 'Female', 'BSIT', 2, '1', '2015-09-16', 'Bulacan', 'Samar', '12479837', '92929292923', 'joanne@gmail.com', 'Cebu', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(55, '2017-00145', 'Noel', '', 'Bulos', 'Female', 'BSIT', 2, '1', '2015-09-17', 'Navotas', 'Leyte', '12479837', '98765432187', 'noel@gmail.com', 'Samar', 'Regular', '2018-05-24 18:03:16', '2018-05-24 18:03:16', NULL, 'Active'), 
(56, '2017-00169', 'Joselito', '', 'Dimal', 'Female', 'BSIT', 2, '1', '2015-09-18', 'Valenzuela', 'Palawan', '12479837', '90453567245', 'joselito@gmail.com', 'Leyte', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(57, '2017-00171', 'Robert', '', 'Zorco', 'Female', 'BSIT', 2, '1', '2015-09-19', 'Dela Costa', 'Aklan', '12234567', '98765432187', 'robert@gmail.com', 'Palawan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(58, '2017-00140', 'Bianca', '', 'Ezra', 'Female', 'BSIT', 2, '1', '2015-09-20', 'Fairview', 'Marinduque', '12345678', '90453567245', 'bianca@gmail.com', 'Aklan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(59, '2017-00256', 'Donna', '', 'Pelo', 'Female', 'BSIT', 1, '1', '2015-09-21', 'Quezon City', 'Ilocos', '12479837', '98765432187', 'donna@gmail.com', 'Ilocos', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(60, '2017-00257', 'Brian', '', 'Ortiz', 'Female', 'BSIT', 1, '1', '2015-09-22', 'Commonwealth', 'Agusan', '12479837', '90453567245', 'brian@gmail.com', 'Agusan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(61, '2017-00258', 'Bianca', '', 'Yeti', 'Female', 'BSIT', 1, '1', '2015-09-23', 'Caloocan', 'Zambales', '12479837', '98765423465', 'bianca@gmail.com', 'Zambales', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(62, '2017-00259', 'Gino', '', 'Heusaff', 'Female', 'BSIT', 1, '1', '2015-09-24', 'Fairview', 'Nueva Ecija', '12479837', '92929292923', 'gino@gmail.com', 'Nueva Ecija', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(63, '2017-00260', 'Selena', '', 'Ayres', 'Female', 'BSIT', 1, '1', '2015-09-25', 'Novaliches', 'Bataan', '12479837', '98765432187', 'selena@gmail.com', 'Bataan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(64, '2017-00261', 'Joroz', '', 'Abott', 'Female', 'BSIT', 1, '1', '2015-09-26', 'Bulacan', 'Tarlac', '12479837', '90453567245', 'joroz@gmail.com', 'Tarlac', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(65, '2017-00262', 'Alexandra', '', 'Myers', 'Female', 'BSIT', 1, '1', '2015-07-12', 'Navotas', 'Cabanatuan', '12479837', '98765423465', 'alexandra@gmail.com', 'Cabanatuan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(66, '2017-00263', 'Christian', '', 'Dimatulac', 'Female', 'BSIT', 1, '1', '2015-07-13', 'Valenzuela', 'Baguio', '12479837', '92929292923', 'christian@gmail.com', 'Baguio', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(67, '2017-00264', 'Christene', '', 'Maykapa', 'Female', 'BSIT', 1, '1', '2015-07-14', 'Quezon City', 'Cebu', '12479837', '98765432187', 'christene@gmail.com', 'Cebu', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(68, '2017-00265', 'Polo', '', 'Woods', 'Female', 'BSIT', 1, '1', '2015-07-15', 'Commonwealth', 'Samar', '12479837', '90453567245', 'polo@gmail.com', 'Samar', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(69, '2017-00266', 'Lara', '', 'Agbayani', 'Female', 'BSIT', 1, '1', '2015-07-16', 'Caloocan', 'Leyte', '12479837', '98765423465', 'lara@gmail.com', 'Leyte', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(70, '2017-00245', 'Cholo', '', 'Abuang', 'Female', 'BSIT', 1, '1', '2015-07-17', 'Fairview', 'Palawan', '12479837', '92929292923', 'cholo@gmail.com', 'Palawan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(71, '2017-00269', 'Venice', '', 'Bocasan', 'Female', 'BSIT', 1, '1', '2015-07-18', 'Novaliches', 'Aklan', '12479837', '98765432187', 'venice@gmail.com', 'Aklan', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(72, '2017-00271', 'Xavier', '', 'Guipit', 'Female', 'BSIT', 1, '1', '2015-07-19', 'Bulacan', 'Marinduque', '12479837', '90453567245', 'xavier@gmail.com', 'Marinduque', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'), 
(73, '2017-00240', 'Zara', '', 'Sawit', 'Female', 'BSIT', 1, '1', '2015-07-20', 'Navotas', 'Ilocos', '12479837', '98765423465', 'zara@gmail.com', 'Navotas', 'Regular', '2018-05-24 18:03:17', '2018-05-24 18:03:17', NULL, 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_stud_psych_rec` 
-- 
 
CREATE TABLE `r_stud_psych_rec` ( 
  `Stud_NO_REFERENCE` varchar(15) NOT NULL, 
  `PsychRec_PREV_CONSULTED` enum('Psychiatrist','Psychologist','Counselor') NOT NULL, 
  `PsychRec_CONSULTED_WHEN` varchar(1000) DEFAULT 'None', 
  `PsychRec_REASON` varchar(1000) DEFAULT 'None' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_upload_category` 
-- 
 
CREATE TABLE `r_upload_category` ( 
  `Upload_CATEGORY_ID` int(11) NOT NULL, 
  `Upload_FILE_CATEGORY` varchar(100) NOT NULL, 
  `Upload_CATEGORY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_upload_category` 
-- 
 
INSERT INTO `r_upload_category` (`Upload_CATEGORY_ID`, `Upload_FILE_CATEGORY`, `Upload_CATEGORY_STAT`) VALUES 
(1, 'Excuse Letter', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_users` 
-- 
 
CREATE TABLE `r_users` ( 
  `Users_ID` int(11) NOT NULL, 
  `Users_USERNAME` varchar(50) NOT NULL, 
  `Users_REFERENCED` varchar(15) NOT NULL, 
  `Users_PASSWORD` blob NOT NULL, 
  `Users_ROLES` enum('System Administrator','Administrator','OSAS HEAD','Guidance Counselor','Organization','Student','Student Assistant') NOT NULL, 
  `Users_PROFILE_PATH` varchar(500) DEFAULT NULL, 
  `Users_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Users_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Users_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_users` 
-- 
 
INSERT INTO `r_users` (`Users_ID`, `Users_USERNAME`, `Users_REFERENCED`, `Users_PASSWORD`, `Users_ROLES`, `Users_PROFILE_PATH`, `Users_DATE_ADD`, `Users_DATE_MOD`, `Users_DISPLAY_STAT`) VALUES 
(2, 'admin', 'GC-0001', 0xa319451792b4248aeec7fb13966bc133, 'System Administrator', NULL, '2018-05-18 17:37:08', '2018-05-18 17:37:08', 'Active'), 
(3, 'counselor', 'GC-0001', 0x99f1a2f749673d62b4a7a431be68097e, 'Guidance Counselor', NULL, '2018-05-19 00:29:09', '2018-05-19 00:29:09', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `r_visit` 
-- 
 
CREATE TABLE `r_visit` ( 
  `Visit_ID` int(11) NOT NULL, 
  `Visit_TYPE` varchar(50) NOT NULL, 
  `Visit_DESC` text, 
  `Visit_TYPE_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `r_visit` 
-- 
 
INSERT INTO `r_visit` (`Visit_ID`, `Visit_TYPE`, `Visit_DESC`, `Visit_TYPE_STAT`) VALUES 
(1, 'Signing of Clearance', NULL, 'Active'), 
(3, 'Certificate of Candidacy', NULL, 'Active'), 
(4, 'Excuse Letter', NULL, 'Active'), 
(7, 'Counseling', '', 'Active'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Stand-in structure for view `student_counseling` 
-- 
CREATE TABLE `student_counseling` ( 
`COUNSELING_CODE` varchar(15) 
,`COUNSELING_DATE` varchar(137) 
,`COUNSELING_TYPE` varchar(50) 
,`APPOINTMENT_TYPE` varchar(25) 
,`STUD_NO` varchar(15) 
,`STUD_NAME` varchar(201) 
,`COURSE` varchar(35) 
,`COUNSELING_APPROACH` text 
,`COUNSELING_BG` text 
,`GOALS` text 
,`COUNS_COMMENT` text 
,`RECOMMENDATION` text 
); 
 
-- -------------------------------------------------------- 
 
-- 
-- Stand-in structure for view `student_profiling` 
-- 
CREATE TABLE `student_profiling` ( 
`STUD_NO` varchar(15) 
,`FULLNAME` varchar(302) 
,`COURSE` varchar(39) 
,`BATCH YEAR` varchar(15) 
,`GENDER` enum('Male','Female') 
,`BIRTH DATE` varchar(73) 
,`BIRTH PLACE` varchar(500) 
,`CITY ADDRESS` varchar(500) 
,`PROVINCIAL ADDRESS` varchar(500) 
,`TELEPHONE NO` varchar(20) 
,`MOBILE NO` varchar(20) 
,`EMAIL` varchar(100) 
,`STUD_STATUS` enum('Regular','Irregular','Disqualified','LOA','Transferee') 
); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_assign_stud_saction` 
-- 
 
CREATE TABLE `t_assign_stud_saction` ( 
  `AssSancStudStudent_ID` int(11) NOT NULL, 
  `AssSancStudStudent_STUD_NO` varchar(15) NOT NULL, 
  `AssSancStudStudent_SancDetails_CODE` varchar(15) NOT NULL, 
  `AssSancStudStudent_DesOffDetails_CODE` varchar(15) NOT NULL, 
  `AssSancStudStudent_CONSUMED_HOURS` int(11) DEFAULT '0', 
  `AssSancStudStudent_REMARKS` varchar(100) DEFAULT NULL, 
  `AssSancStudStudent_IS_FINISH` enum('Processing','Finished') NOT NULL DEFAULT 'Processing', 
  `AssSancStudStudent_TO_BE_DONE` date NOT NULL, 
  `AssSancStudStudent_DATE_ADD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `AssSancStudStudent_DATE_MOD` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `AssSancStudStudent_DISPLAY_STAT` enum('Active','Inactive') DEFAULT 'Active' 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_counseling` 
-- 
 
CREATE TABLE `t_counseling` ( 
  `Couns_ID` int(11) NOT NULL, 
  `Couns_CODE` varchar(15) NOT NULL, 
  `Visit_ID_REFERENCE` int(11) NOT NULL, 
  `Couns_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Couns_COUNSELING_TYPE` varchar(50) NOT NULL, 
  `Couns_APPOINTMENT_TYPE` varchar(25) NOT NULL, 
  `Nature_Of_Case` varchar(50) DEFAULT NULL, 
  `Couns_BACKGROUND` text, 
  `Couns_GOALS` text, 
  `Couns_COMMENT` text, 
  `Couns_RECOMMENDATION` text 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `t_counseling` 
-- 
 
INSERT INTO `t_counseling` (`Couns_ID`, `Couns_CODE`, `Visit_ID_REFERENCE`, `Couns_DATE`, `Couns_COUNSELING_TYPE`, `Couns_APPOINTMENT_TYPE`, `Nature_Of_Case`, `Couns_BACKGROUND`, `Couns_GOALS`, `Couns_COMMENT`, `Couns_RECOMMENDATION`) VALUES 
(1, 'IC18-5231', 2, '2018-05-22 17:54:41', 'Individual Counseling', 'Referral', NULL, 'null', 'null', 'null', NULL); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_couns_approach` 
-- 
 
CREATE TABLE `t_couns_approach` ( 
  `Couns_ID_REFERENCE` int(11) NOT NULL, 
  `Couns_APPROACH` varchar(50) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_couns_details` 
-- 
 
CREATE TABLE `t_couns_details` ( 
  `Couns_ID_REFERENCE` int(11) NOT NULL, 
  `Stud_NO` varchar(15) NOT NULL, 
  `Couns_REMARKS` varchar(50) DEFAULT NULL, 
  `Couns_REMARKS_DETAILS` text 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `t_couns_details` 
-- 
 
INSERT INTO `t_couns_details` (`Couns_ID_REFERENCE`, `Stud_NO`, `Couns_REMARKS`, `Couns_REMARKS_DETAILS`) VALUES 
(1, '2015-00138-CM-0', NULL, NULL); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_stud_visit` 
-- 
 
CREATE TABLE `t_stud_visit` ( 
  `Stud_VISIT_ID` int(11) NOT NULL, 
  `Visit_CODE` varchar(15) NOT NULL, 
  `Visit_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Stud_NO` varchar(15) NOT NULL, 
  `Visit_PURPOSE` varchar(50) NOT NULL, 
  `Visit_DETAILS` text 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- 
-- Dumping data for table `t_stud_visit` 
-- 
 
INSERT INTO `t_stud_visit` (`Stud_VISIT_ID`, `Visit_CODE`, `Visit_DATE`, `Stud_NO`, `Visit_PURPOSE`, `Visit_DETAILS`) VALUES 
(1, 'VS18-5231', '2018-05-22 16:10:38', '2015-00138-CM-0', 'Excuse Letter', 'Excuse Letter'), 
(2, 'VS18-5232', '2018-05-22 16:25:05', '2015-00138-CM-0', 'Counseling', 'nahuling natutulog sa klase'), 
(3, 'VS18-5233', '2018-05-22 18:03:58', '2015-00138-CM-0', 'Signing of Clearance', 'null'); 
 
-- -------------------------------------------------------- 
 
-- 
-- Table structure for table `t_upload` 
-- 
 
CREATE TABLE `t_upload` ( 
  `Upload_FILE_ID` int(11) NOT NULL, 
  `Upload_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, 
  `Upload_USER` varchar(15) NOT NULL, 
  `Upload_FILENAME` varchar(200) NOT NULL, 
  `Upload_CATEGORY` varchar(100) NOT NULL, 
  `Upload_TYPE` varchar(15) NOT NULL, 
  `Upload_FILETYPE` varchar(100) NOT NULL, 
  `Upload_FILEPATH` varchar(100) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=latin1; 
 
-- -------------------------------------------------------- 
 
-- 
-- Stand-in structure for view `visit_record` 
-- 
CREATE TABLE `visit_record` ( 
`Visit_CODE` varchar(15) 
,`Visit_DATE` timestamp 
,`Stud_NO` varchar(15) 
,`STUDENT` varchar(201) 
,`COURSE` varchar(41) 
,`Visit_PURPOSE` varchar(50) 
,`Visit_DETAILS` text 
); 
 
-- -------------------------------------------------------- 
 
-- 
-- Structure for view `student_counseling` 
-- 
DROP TABLE IF EXISTS `student_counseling`; 
 
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_counseling`  AS  select `c`.`Couns_CODE` AS `COUNSELING_CODE`,date_format(`c`.`Couns_DATE`,'%W %M %d %Y') AS `COUNSELING_DATE`,`c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,`c`.`Couns_APPOINTMENT_TYPE` AS `APPOINTMENT_TYPE`,`s`.`Stud_NO` AS `STUD_NO`,concat(`s`.`Stud_FNAME`,' ',`s`.`Stud_LNAME`) AS `STUD_NAME`,concat(`s`.`Stud_COURSE`,' ',`s`.`Stud_YEAR_LEVEL`,' - ',`s`.`Stud_SECTION`) AS `COURSE`,(select group_concat(`a`.`Couns_APPROACH` separator ', ') from `t_couns_approach` `a` where (`a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`)) AS `COUNSELING_APPROACH`,`c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,`c`.`Couns_GOALS` AS `GOALS`,`c`.`Couns_COMMENT` AS `COUNS_COMMENT`,`c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION` from ((`t_counseling` `c` join `t_couns_details` `cd` on((`c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`))) join `r_stud_profile` `s` on((`s`.`Stud_NO` = `cd`.`Stud_NO`))) ; 
 
-- -------------------------------------------------------- 
 
-- 
-- Structure for view `student_profiling` 
-- 
DROP TABLE IF EXISTS `student_profiling`; 
 
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_profiling`  AS  select `sp`.`Stud_NO` AS `STUD_NO`,if(isnull(`sp`.`Stud_MNAME`),concat(`sp`.`Stud_FNAME`,' ',`sp`.`Stud_LNAME`),concat(`sp`.`Stud_FNAME`,' ',`sp`.`Stud_MNAME`,' ',`sp`.`Stud_LNAME`)) AS `FULLNAME`,concat(`sp`.`Stud_COURSE`,' ',`sp`.`Stud_YEAR_LEVEL`,'-',`sp`.`Stud_YEAR_LEVEL`) AS `COURSE`,`sb`.`Batch_YEAR` AS `BATCH YEAR`,`sp`.`Stud_GENDER` AS `GENDER`,date_format(`sp`.`Stud_BIRTH_DATE`,'%M %d, %Y') AS `BIRTH DATE`,`sp`.`Stud_BIRTH_PLACE` AS `BIRTH PLACE`,`sp`.`Stud_CITY_ADDRESS` AS `CITY ADDRESS`,`sp`.`Stud_PROVINCIAL_ADDRESS` AS `PROVINCIAL ADDRESS`,`sp`.`Stud_TELEPHONE_NO` AS `TELEPHONE NO`,`sp`.`Stud_MOBILE_NO` AS `MOBILE NO`,`sp`.`Stud_EMAIL` AS `EMAIL`,`sb`.`Stud_STATUS` AS `STUD_STATUS` from (`r_stud_batch` `sb` join `r_stud_profile` `sp` on((`sp`.`Stud_NO` = `sb`.`Stud_NO`))) where (((`sb`.`Batch_YEAR` = (select `r_batch_details`.`Batch_YEAR` from `r_batch_details` order by `r_batch_details`.`Batch_ID` desc limit 1)) and (`sb`.`Stud_STATUS` = 'Regular')) or (`sb`.`Stud_STATUS` = 'Irregular')) ; 
 
-- -------------------------------------------------------- 
 
-- 
-- Structure for view `visit_record` 
-- 
DROP TABLE IF EXISTS `visit_record`; 
 
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `visit_record`  AS  select `v`.`Visit_CODE` AS `Visit_CODE`,`v`.`Visit_DATE` AS `Visit_DATE`,`s`.`Stud_NO` AS `Stud_NO`,concat(`s`.`Stud_FNAME`,' ',`s`.`Stud_LNAME`) AS `STUDENT`,concat(`s`.`Stud_COURSE`,' ',`s`.`Stud_YEAR_LEVEL`,' - ',`s`.`Stud_YEAR_LEVEL`) AS `COURSE`,`v`.`Visit_PURPOSE` AS `Visit_PURPOSE`,`v`.`Visit_DETAILS` AS `Visit_DETAILS` from (`t_stud_visit` `v` join `r_stud_profile` `s` on((`s`.`Stud_NO` = `v`.`Stud_NO`))) order by `v`.`Visit_DATE` desc ; 
 
-- 
-- Indexes for dumped tables 
-- 
 
-- 
-- Indexes for table `r_batch_details` 
-- 
ALTER TABLE `r_batch_details` 
  ADD PRIMARY KEY (`Batch_ID`), 
  ADD UNIQUE KEY `UNQ_Batch_YEAR` (`Batch_YEAR`); 
 
-- 
-- Indexes for table `r_civil_stat` 
-- 
ALTER TABLE `r_civil_stat` 
  ADD PRIMARY KEY (`Stud_CIVIL_STATUS_ID`), 
  ADD UNIQUE KEY `Stud_CIVIL_STATUS` (`Stud_CIVIL_STATUS`); 
 
-- 
-- Indexes for table `r_couns_appointment_type` 
-- 
ALTER TABLE `r_couns_appointment_type` 
  ADD PRIMARY KEY (`Appmnt_ID`), 
  ADD UNIQUE KEY `Appmnt_TYPE` (`Appmnt_TYPE`); 
 
-- 
-- Indexes for table `r_couns_approach` 
-- 
ALTER TABLE `r_couns_approach` 
  ADD PRIMARY KEY (`Couns_APPROACH_ID`), 
  ADD UNIQUE KEY `Couns_APPROACH` (`Couns_APPROACH`); 
 
-- 
-- Indexes for table `r_couns_type` 
-- 
ALTER TABLE `r_couns_type` 
  ADD PRIMARY KEY (`Couns_TYPE_ID`), 
  ADD UNIQUE KEY `Couns_TYPE` (`Couns_TYPE`); 
 
-- 
-- Indexes for table `r_courses` 
-- 
ALTER TABLE `r_courses` 
  ADD PRIMARY KEY (`Course_ID`), 
  ADD UNIQUE KEY `UNQ_Course_CODE` (`Course_CODE`), 
  ADD KEY `FK_Course_CURR_YEAR` (`Course_CURR_YEAR`); 
 
-- 
-- Indexes for table `r_designated_offices_details` 
-- 
ALTER TABLE `r_designated_offices_details` 
  ADD PRIMARY KEY (`DesOffDetails_ID`), 
  ADD UNIQUE KEY `UNQ_DesOffDetails_CODE` (`DesOffDetails_CODE`); 
 
-- 
-- Indexes for table `r_guidance_counselor` 
-- 
ALTER TABLE `r_guidance_counselor` 
  ADD PRIMARY KEY (`Counselor_ID`); 
 
-- 
-- Indexes for table `r_nature_of_case` 
-- 
ALTER TABLE `r_nature_of_case` 
  ADD UNIQUE KEY `Case_NAME` (`Case_NAME`); 
 
-- 
-- Indexes for table `r_remarks` 
-- 
ALTER TABLE `r_remarks` 
  ADD PRIMARY KEY (`Remarks_ID`), 
  ADD UNIQUE KEY `Remarks_TYPE` (`Remarks_TYPE`); 
 
-- 
-- Indexes for table `r_sanction_details` 
-- 
ALTER TABLE `r_sanction_details` 
  ADD PRIMARY KEY (`SancDetails_ID`), 
  ADD UNIQUE KEY `UNQ_SancDetails_CODE` (`SancDetails_CODE`); 
 
-- 
-- Indexes for table `r_stud_batch` 
-- 
ALTER TABLE `r_stud_batch` 
  ADD PRIMARY KEY (`Stud_BATCH_ID`), 
  ADD KEY `FK_stdbtchrfrnc_STUD_NO` (`Stud_NO`), 
  ADD KEY `FK_stdbtchyrrfrnc` (`Batch_YEAR`); 
 
-- 
-- Indexes for table `r_stud_educ_background` 
-- 
ALTER TABLE `r_stud_educ_background` 
  ADD PRIMARY KEY (`Educ_BG_ID`), 
  ADD KEY `FK_edcbckgrndrfrnc_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_stud_educ_bg_details` 
-- 
ALTER TABLE `r_stud_educ_bg_details` 
  ADD KEY `FK_stdedcbgdtlsedcbg_ID` (`Educ_BG_ID`); 
 
-- 
-- Indexes for table `r_stud_family_bg_details` 
-- 
ALTER TABLE `r_stud_family_bg_details` 
  ADD KEY `FK_stdfmlybckgrndrfrnc_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_stud_general_info` 
-- 
ALTER TABLE `r_stud_general_info` 
  ADD KEY `FK_gnrlinf_STUD_NO` (`Stud_NO`); 
 
-- 
-- Indexes for table `r_stud_honors_awards` 
-- 
ALTER TABLE `r_stud_honors_awards` 
  ADD KEY `FK_stdhnrsawrdsedcbg_ID` (`Educ_BG_ID`), 
  ADD KEY `FK_hnrsawrds_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_stud_org_position` 
-- 
ALTER TABLE `r_stud_org_position` 
  ADD KEY `FK_orgpstn_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_stud_personal_info` 
-- 
ALTER TABLE `r_stud_personal_info` 
  ADD KEY `FK_stdprsnlnfrfrnc_STUD_NO` (`Stud_NO_REFERENCE`), 
  ADD KEY `FK_stdprsnlnfcvlsttrfrnc` (`Stud_CIVIL_STAT`); 
 
-- 
-- Indexes for table `r_stud_phys_rec` 
-- 
ALTER TABLE `r_stud_phys_rec` 
  ADD KEY `FK_physrc_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_stud_profile` 
-- 
ALTER TABLE `r_stud_profile` 
  ADD PRIMARY KEY (`Stud_ID`), 
  ADD UNIQUE KEY `PK_Stud_NO` (`Stud_NO`), 
  ADD KEY `FK_COURSE` (`Stud_COURSE`); 
 
-- 
-- Indexes for table `r_stud_psych_rec` 
-- 
ALTER TABLE `r_stud_psych_rec` 
  ADD KEY `FK_psychrc_STUD_NO` (`Stud_NO_REFERENCE`); 
 
-- 
-- Indexes for table `r_upload_category` 
-- 
ALTER TABLE `r_upload_category` 
  ADD PRIMARY KEY (`Upload_CATEGORY_ID`), 
  ADD UNIQUE KEY `Upload_FILE_CATEGORY` (`Upload_FILE_CATEGORY`); 
 
-- 
-- Indexes for table `r_users` 
-- 
ALTER TABLE `r_users` 
  ADD PRIMARY KEY (`Users_ID`), 
  ADD UNIQUE KEY `UNQ_Users_USERNAME` (`Users_USERNAME`); 
 
-- 
-- Indexes for table `r_visit` 
-- 
ALTER TABLE `r_visit` 
  ADD PRIMARY KEY (`Visit_ID`), 
  ADD UNIQUE KEY `Visit_TYPE` (`Visit_TYPE`); 
 
-- 
-- Indexes for table `t_assign_stud_saction` 
-- 
ALTER TABLE `t_assign_stud_saction` 
  ADD PRIMARY KEY (`AssSancStudStudent_ID`), 
  ADD KEY `FK_AssSancStudStudent_STUD_NO` (`AssSancStudStudent_STUD_NO`), 
  ADD KEY `FK_AssSancStudStudent_SancDetails_CODE` (`AssSancStudStudent_SancDetails_CODE`), 
  ADD KEY `FK_AssSancStudStudent_DesOffDetails_CODE` (`AssSancStudStudent_DesOffDetails_CODE`); 
 
-- 
-- Indexes for table `t_counseling` 
-- 
ALTER TABLE `t_counseling` 
  ADD PRIMARY KEY (`Couns_ID`), 
  ADD UNIQUE KEY `Couns_CODE` (`Couns_CODE`), 
  ADD KEY `FK_cnslngvstidrfrnc` (`Visit_ID_REFERENCE`), 
  ADD KEY `FK_C_CT_REFERENCE` (`Couns_COUNSELING_TYPE`), 
  ADD KEY `FK_cnslngcnsppntmnttyp` (`Couns_APPOINTMENT_TYPE`), 
  ADD KEY `FK_cnslngntrfcsrfrnc` (`Nature_Of_Case`); 
 
-- 
-- Indexes for table `t_couns_approach` 
-- 
ALTER TABLE `t_couns_approach` 
  ADD KEY `FK_cnspprchcnsidrfrnc` (`Couns_ID_REFERENCE`), 
  ADD KEY `FK_C_CA_REFERENCE` (`Couns_APPROACH`); 
 
-- 
-- Indexes for table `t_couns_details` 
-- 
ALTER TABLE `t_couns_details` 
  ADD KEY `FK_CnsIDrfrnc` (`Couns_ID_REFERENCE`), 
  ADD KEY `FK_cnslngstdnrfrnc` (`Stud_NO`), 
  ADD KEY `FK_cnsdtlscnsrmrksrfrnc` (`Couns_REMARKS`); 
 
-- 
-- Indexes for table `t_stud_visit` 
-- 
ALTER TABLE `t_stud_visit` 
  ADD PRIMARY KEY (`Stud_VISIT_ID`), 
  ADD UNIQUE KEY `Visit_CODE` (`Visit_CODE`), 
  ADD KEY `FK_vst_STUD_NO` (`Stud_NO`), 
  ADD KEY `FK_stdvstprps_vstrfrnc` (`Visit_PURPOSE`); 
 
-- 
-- Indexes for table `t_upload` 
-- 
ALTER TABLE `t_upload` 
  ADD PRIMARY KEY (`Upload_FILE_ID`), 
  ADD KEY `FK_pldctgryrfrnc` (`Upload_CATEGORY`); 
 
-- 
-- AUTO_INCREMENT for dumped tables 
-- 
 
-- 
-- AUTO_INCREMENT for table `r_batch_details` 
-- 
ALTER TABLE `r_batch_details` 
  MODIFY `Batch_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8; 
-- 
-- AUTO_INCREMENT for table `r_civil_stat` 
-- 
ALTER TABLE `r_civil_stat` 
  MODIFY `Stud_CIVIL_STATUS_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3; 
-- 
-- AUTO_INCREMENT for table `r_couns_appointment_type` 
-- 
ALTER TABLE `r_couns_appointment_type` 
  MODIFY `Appmnt_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3; 
-- 
-- AUTO_INCREMENT for table `r_couns_approach` 
-- 
ALTER TABLE `r_couns_approach` 
  MODIFY `Couns_APPROACH_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `r_couns_type` 
-- 
ALTER TABLE `r_couns_type` 
  MODIFY `Couns_TYPE_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3; 
-- 
-- AUTO_INCREMENT for table `r_courses` 
-- 
ALTER TABLE `r_courses` 
  MODIFY `Course_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5; 
-- 
-- AUTO_INCREMENT for table `r_designated_offices_details` 
-- 
ALTER TABLE `r_designated_offices_details` 
  MODIFY `DesOffDetails_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `r_guidance_counselor` 
-- 
ALTER TABLE `r_guidance_counselor` 
  MODIFY `Counselor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 
-- 
-- AUTO_INCREMENT for table `r_remarks` 
-- 
ALTER TABLE `r_remarks` 
  MODIFY `Remarks_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `r_sanction_details` 
-- 
ALTER TABLE `r_sanction_details` 
  MODIFY `SancDetails_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `r_stud_batch` 
-- 
ALTER TABLE `r_stud_batch` 
  MODIFY `Stud_BATCH_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 
-- 
-- AUTO_INCREMENT for table `r_stud_educ_background` 
-- 
ALTER TABLE `r_stud_educ_background` 
  MODIFY `Educ_BG_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `r_stud_profile` 
-- 
ALTER TABLE `r_stud_profile` 
  MODIFY `Stud_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74; 
-- 
-- AUTO_INCREMENT for table `r_upload_category` 
-- 
ALTER TABLE `r_upload_category` 
  MODIFY `Upload_CATEGORY_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 
-- 
-- AUTO_INCREMENT for table `r_users` 
-- 
ALTER TABLE `r_users` 
  MODIFY `Users_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4; 
-- 
-- AUTO_INCREMENT for table `r_visit` 
-- 
ALTER TABLE `r_visit` 
  MODIFY `Visit_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8; 
-- 
-- AUTO_INCREMENT for table `t_assign_stud_saction` 
-- 
ALTER TABLE `t_assign_stud_saction` 
  MODIFY `AssSancStudStudent_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- AUTO_INCREMENT for table `t_counseling` 
-- 
ALTER TABLE `t_counseling` 
  MODIFY `Couns_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2; 
-- 
-- AUTO_INCREMENT for table `t_stud_visit` 
-- 
ALTER TABLE `t_stud_visit` 
  MODIFY `Stud_VISIT_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4; 
-- 
-- AUTO_INCREMENT for table `t_upload` 
-- 
ALTER TABLE `t_upload` 
  MODIFY `Upload_FILE_ID` int(11) NOT NULL AUTO_INCREMENT; 
-- 
-- Constraints for dumped tables 
-- 
 
-- 
-- Constraints for table `r_courses` 
-- 
ALTER TABLE `r_courses` 
  ADD CONSTRAINT `FK_Course_CURR_YEAR` FOREIGN KEY (`Course_CURR_YEAR`) REFERENCES `r_batch_details` (`Batch_YEAR`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_batch` 
-- 
ALTER TABLE `r_stud_batch` 
  ADD CONSTRAINT `FK_stdbtchrfrnc_STUD_NO` FOREIGN KEY (`Stud_NO`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_stdbtchyrrfrnc` FOREIGN KEY (`Batch_YEAR`) REFERENCES `r_batch_details` (`Batch_YEAR`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_educ_background` 
-- 
ALTER TABLE `r_stud_educ_background` 
  ADD CONSTRAINT `FK_edcbckgrndrfrnc_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_educ_bg_details` 
-- 
ALTER TABLE `r_stud_educ_bg_details` 
  ADD CONSTRAINT `FK_stdedcbgdtlsedcbg_ID` FOREIGN KEY (`Educ_BG_ID`) REFERENCES `r_stud_educ_background` (`Educ_BG_ID`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_family_bg_details` 
-- 
ALTER TABLE `r_stud_family_bg_details` 
  ADD CONSTRAINT `FK_stdfmlybckgrndrfrnc_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_general_info` 
-- 
ALTER TABLE `r_stud_general_info` 
  ADD CONSTRAINT `FK_gnrlinf_STUD_NO` FOREIGN KEY (`Stud_NO`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_honors_awards` 
-- 
ALTER TABLE `r_stud_honors_awards` 
  ADD CONSTRAINT `FK_hnrsawrds_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_stdhnrsawrdsedcbg_ID` FOREIGN KEY (`Educ_BG_ID`) REFERENCES `r_stud_educ_background` (`Educ_BG_ID`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_org_position` 
-- 
ALTER TABLE `r_stud_org_position` 
  ADD CONSTRAINT `FK_orgpstn_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_personal_info` 
-- 
ALTER TABLE `r_stud_personal_info` 
  ADD CONSTRAINT `FK_stdprsnlnfcvlsttrfrnc` FOREIGN KEY (`Stud_CIVIL_STAT`) REFERENCES `r_civil_stat` (`Stud_CIVIL_STATUS`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_stdprsnlnfrfrnc_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_phys_rec` 
-- 
ALTER TABLE `r_stud_phys_rec` 
  ADD CONSTRAINT `FK_physrc_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_profile` 
-- 
ALTER TABLE `r_stud_profile` 
  ADD CONSTRAINT `FK_COURSE` FOREIGN KEY (`Stud_COURSE`) REFERENCES `r_courses` (`Course_CODE`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `r_stud_psych_rec` 
-- 
ALTER TABLE `r_stud_psych_rec` 
  ADD CONSTRAINT `FK_psychrc_STUD_NO` FOREIGN KEY (`Stud_NO_REFERENCE`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_assign_stud_saction` 
-- 
ALTER TABLE `t_assign_stud_saction` 
  ADD CONSTRAINT `FK_AssSancStudStudent_DesOffDetails_CODE` FOREIGN KEY (`AssSancStudStudent_DesOffDetails_CODE`) REFERENCES `r_designated_offices_details` (`DesOffDetails_CODE`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_AssSancStudStudent_STUD_NO` FOREIGN KEY (`AssSancStudStudent_STUD_NO`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_AssSancStudStudent_SancDetails_CODE` FOREIGN KEY (`AssSancStudStudent_SancDetails_CODE`) REFERENCES `r_sanction_details` (`SancDetails_CODE`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_counseling` 
-- 
ALTER TABLE `t_counseling` 
  ADD CONSTRAINT `FK_C_CT_REFERENCE` FOREIGN KEY (`Couns_COUNSELING_TYPE`) REFERENCES `r_couns_type` (`Couns_TYPE`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnslngcnsppntmnttyp` FOREIGN KEY (`Couns_APPOINTMENT_TYPE`) REFERENCES `r_couns_appointment_type` (`Appmnt_TYPE`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnslngntrfcsrfrnc` FOREIGN KEY (`Nature_Of_Case`) REFERENCES `r_nature_of_case` (`Case_NAME`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnslngvstidrfrnc` FOREIGN KEY (`Visit_ID_REFERENCE`) REFERENCES `t_stud_visit` (`Stud_VISIT_ID`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_couns_approach` 
-- 
ALTER TABLE `t_couns_approach` 
  ADD CONSTRAINT `FK_C_CA_REFERENCE` FOREIGN KEY (`Couns_APPROACH`) REFERENCES `r_couns_approach` (`Couns_APPROACH`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnspprchcnsidrfrnc` FOREIGN KEY (`Couns_ID_REFERENCE`) REFERENCES `t_counseling` (`Couns_ID`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_couns_details` 
-- 
ALTER TABLE `t_couns_details` 
  ADD CONSTRAINT `FK_CnsIDrfrnc` FOREIGN KEY (`Couns_ID_REFERENCE`) REFERENCES `t_counseling` (`Couns_ID`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnsdtlscnsrmrksrfrnc` FOREIGN KEY (`Couns_REMARKS`) REFERENCES `r_remarks` (`Remarks_TYPE`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_cnslngstdnrfrnc` FOREIGN KEY (`Stud_NO`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_stud_visit` 
-- 
ALTER TABLE `t_stud_visit` 
  ADD CONSTRAINT `FK_stdvstprps_vstrfrnc` FOREIGN KEY (`Visit_PURPOSE`) REFERENCES `r_visit` (`Visit_TYPE`) ON DELETE CASCADE ON UPDATE CASCADE, 
  ADD CONSTRAINT `FK_vst_STUD_NO` FOREIGN KEY (`Stud_NO`) REFERENCES `r_stud_profile` (`Stud_NO`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
-- 
-- Constraints for table `t_upload` 
-- 
ALTER TABLE `t_upload` 
  ADD CONSTRAINT `FK_pldctgryrfrnc` FOREIGN KEY (`Upload_CATEGORY`) REFERENCES `r_upload_category` (`Upload_FILE_CATEGORY`) ON DELETE CASCADE ON UPDATE CASCADE; 
 
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */; 
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */; 
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;