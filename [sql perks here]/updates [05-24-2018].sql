
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
	tellNo,
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
-- Dumping data for table `r_guidance_counselor`
--

INSERT INTO `r_guidance_counselor` (`Counselor_ID`, `Counselor_CODE`, `Counselor_FNAME`, `Counselor_MNAME`, `Counselor_LNAME`) VALUES
(1, 'GC-0001', 'Oliver', 'Juan', 'Gabriel');

-- --------------------------------------------------------

--
-- Dumping data for table `r_users`
--

INSERT INTO `r_users` (`Users_ID`, `Users_USERNAME`, `Users_REFERENCED`, `Users_PASSWORD`, `Users_ROLES`, `Users_PROFILE_PATH`, `Users_DATE_ADD`, `Users_DATE_MOD`, `Users_DISPLAY_STAT`) VALUES
(2, 'admin', 'GC-0001', 0xa319451792b4248aeec7fb13966bc133, 'System Administrator', NULL, '2018-05-18 17:37:08', '2018-05-18 17:37:08', 'Active'),
(3, 'counselor', 'GC-0001', 0x99f1a2f749673d62b4a7a431be68097e, 'Guidance Counselor', NULL, '2018-05-19 00:29:09', '2018-05-19 00:29:09', 'Active');

-- --------------------------------------------------------
