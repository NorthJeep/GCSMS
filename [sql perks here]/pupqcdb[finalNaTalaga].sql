-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2018 at 08:26 PM
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
-- Database: `pupqcdb`
--

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
  `Stud_CIVIL_STATUS` varchar(15) NOT NULL,
  `Stud_CS_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_couns_appointment_type`
--

CREATE TABLE `r_couns_appointment_type` (
  `Appmnt_TYPE` varchar(25) NOT NULL,
  `Appmnt_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_couns_approach`
--

CREATE TABLE `r_couns_approach` (
  `Couns_APPROACH` varchar(50) NOT NULL,
  `Couns_APPROACH_DESC` text,
  `Couns_APPROACH_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_couns_type`
--

CREATE TABLE `r_couns_type` (
  `Couns_TYPE` varchar(50) NOT NULL,
  `Couns_TYPE_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Counselor_LNAME` varchar(100) NOT NULL,
  `Counselor_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_guidance_counselor`
--

INSERT INTO `r_guidance_counselor` (`Counselor_ID`, `Counselor_CODE`, `Counselor_FNAME`, `Counselor_MNAME`, `Counselor_LNAME`, `Counselor_STAT`) VALUES
(1, 'CNSLR_001', 'Malene', 'Garido', 'Dizon', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `r_remarks`
--

CREATE TABLE `r_remarks` (
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
  `Stud_MNAME` varchar(100) NOT NULL,
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
  `Upload_FILE_CATEGORY` varchar(100) NOT NULL,
  `Upload_CATEGORY_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `r_visit`
--

CREATE TABLE `r_visit` (
  `Visit_TYPE` varchar(50) NOT NULL,
  `Visit_DESC` text,
  `Visit_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Couns_BACKGROUND` text,
  `Couns_GOALS` text,
  `Couns_COMMENT` text,
  `Couns_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Couns_REMARKS` varchar(50) NOT NULL,
  `Couns_REMARKS_DETAILS` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Visit_DETAILS` text,
  `Visit_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Upload_FILETYPE` varchar(100) NOT NULL,
  `Upload_FILEPATH` varchar(100) NOT NULL,
  `Upload_STAT` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  ADD UNIQUE KEY `Stud_CIVIL_STATUS` (`Stud_CIVIL_STATUS`);

--
-- Indexes for table `r_couns_appointment_type`
--
ALTER TABLE `r_couns_appointment_type`
  ADD UNIQUE KEY `Appmnt_TYPE` (`Appmnt_TYPE`);

--
-- Indexes for table `r_couns_approach`
--
ALTER TABLE `r_couns_approach`
  ADD UNIQUE KEY `Couns_APPROACH` (`Couns_APPROACH`);

--
-- Indexes for table `r_couns_type`
--
ALTER TABLE `r_couns_type`
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
-- Indexes for table `r_remarks`
--
ALTER TABLE `r_remarks`
  ADD UNIQUE KEY `Remarks_TYPE` (`Remarks_TYPE`);

--
-- Indexes for table `r_sanction_details`
--
ALTER TABLE `r_sanction_details`
  ADD PRIMARY KEY (`SancDetails_ID`),
  ADD UNIQUE KEY `UNQ_SancDetails_CODE` (`SancDetails_CODE`);

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
  ADD KEY `FK_cnslngcnsppntmnttyp` (`Couns_APPOINTMENT_TYPE`);

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
-- AUTO_INCREMENT for table `r_sanction_details`
--
ALTER TABLE `r_sanction_details`
  MODIFY `SancDetails_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_stud_educ_background`
--
ALTER TABLE `r_stud_educ_background`
  MODIFY `Educ_BG_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_stud_profile`
--
ALTER TABLE `r_stud_profile`
  MODIFY `Stud_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_users`
--
ALTER TABLE `r_users`
  MODIFY `Users_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_assign_stud_saction`
--
ALTER TABLE `t_assign_stud_saction`
  MODIFY `AssSancStudStudent_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_counseling`
--
ALTER TABLE `t_counseling`
  MODIFY `Couns_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_stud_visit`
--
ALTER TABLE `t_stud_visit`
  MODIFY `Stud_VISIT_ID` int(11) NOT NULL AUTO_INCREMENT;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
