/*
Source Database       : pupqcdb
*/

-- ----------------------------
-- Table structure for r_batch_details
-- ----------------------------
drop table if exists r_batch_details;
create table r_batch_details (
  Batch_ID int not null auto_increment,
    primary key (Batch_ID),
  Batch_CODE varchar(15) not null,
  Batch_YEAR varchar(15) not null,
    unique key UNQ_Batch_YEAR (Batch_YEAR),
  Batch_DESC varchar(100) default null,
  Batch_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for r_courses
-- ----------------------------
drop table if exists r_courses;
create table r_courses (
  Course_ID int not null auto_increment,
    primary key (Course_ID),
  Course_CODE varchar(15) not null,
    unique key UNQ_Course_CODE (Course_CODE),
  Course_NAME varchar(100) not null,
  Course_DESC varchar(100) not null default 'Course Description',
  Course_CURR_YEAR varchar(15) default null,
    constraint FK_Course_CURR_YEAR FOREIGN KEY (Course_CURR_YEAR)
    references r_batch_details (Batch_YEAR)
    on delete cascade
    on update cascade,
  Course_DATE_MOD datetime not null default CURRENT_TIMESTAMP,
  Course_DATE_ADD datetime not null default CURRENT_TIMESTAMP,
  Course_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for r_stud_profile
-- ----------------------------
drop table if exists r_stud_profile;
create table r_stud_profile (
  Stud_ID int not null auto_increment,
    primary key (Stud_ID),
  Stud_NO varchar(15) not null,
    unique key PK_Stud_NO (Stud_NO),
  Stud_FNAME varchar(100) not null,
  Stud_MNAME varchar(100) not null,
  Stud_LNAME varchar(100) not null,
  Stud_GENDER enum('Male','Female') not null default 'Male',
  Stud_COURSE varchar(15) not null,
    constraint FK_COURSE foreign key (Stud_COURSE)
    references r_courses (Course_CODE)
    on delete cascade
    on update cascade,
  Stud_YEAR_LEVEL int not null default 1,
  Stud_SECTION varchar(5) not null default '1',
  Stud_BIRTH_DATE date not null,
  Stud_CITY_ADDRESS varchar(500) not null default 'Not Specify',
  Stud_PROVINCIAL_ADDRESS varchar(500) not null default 'Not Specify',
  Stud_TELEPHONE_NO varchar(20) not null default 'None',
  Stud_MOBILE_NO varchar(20) not null default 'None',
  Stud_EMAIL varchar(100) not null,
  Stud_BIRTH_PLACE varchar(500) default null,
  Stud_STATUS enum('Regular','Irregular','Disqualified','LOA','Transferee') default 'Regular',
  Stud_DATE_MOD datetime not null default current_timestamp,
  Stud_DATE_ADD datetime not null default current_timestamp,
  Stud_DATE_DEACTIVATE datetime default null,
  Stud_DISPLAY_STATUS enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for r_stud_personal_info
-- ----------------------------
drop table if exists r_stud_personal_info;
create table r_stud_personal_info (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_stdprsnlnfrfrnc_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Stud_HEIGHT double (9,2) not null,
  Stud_WEIGHT double (9,2) not null,
  Stud_COMPLEXION varchar(50) not null,
  Stud_HS_GEN_AVERAGE double (3,2) not null,
  Stud_RELIGION varchar(100) not null,
  Stud_CIVIL_STAT enum('Single','Married','Widowed','Separated') default 'Single',
  Stud_WORKING enum('Yes','No') default 'No',
  Employer_NAME varchar(300) default 'None',
  Employer_ADDRESS varchar(100) default 'None',
  Emergency_CONTACT_PERSON varchar(500) not null,
  Emergency_CONTACT_ADDRESS varchar(500) not null,
  Emergency_CONTACT_NUMBER varchar(20) not null default 'None',
  ContactPerson_RELATIONSHIP varchar(100) not null,
  Parents_MARITAL_RELATIONSHIP enum('Married and staying together','Not Married but Living Together','Single Parent','Married but Separated','Others') default 'Married and staying together',
  Others_SPECIFY varchar(100) default 'None',
  Stud_FAM_CHILDREN_NO int not null,
  Stud_BROTHER_NO int default 0,
  Stud_SISTER_NO int default 0,
  Employed_BS_NO int default 0,
  Stud_ORDINAL_POSITION varchar(50) not null,
  Stud_SCHOOLING_FINANCE enum('Parents','Brother/Sister','Spouse','Scholarship','Relatives','Self-supporting/working student') default 'Parents',
  Stud_WEEKLY_ALLOWANCE money not null,
  Parents_TOTAL_MONTHLY_INCOME varchar(100) not null,
  Stud_QUIET_PLACE enum('Yes','No') default 'Yes',
  Stud_ROOM_SHARE varchar(50) not null,
  Stud_RESIDENCE enum('family home','relative\'s house','share apartment with friends','share apartment with relatives','bed spacer','rented apartment','house of married brother/sister','dorm (including board & lodging)') default 'family home'
);

-- ----------------------------
-- Table structure for r_stud_family_background
-- ----------------------------
drop table if exists r_stud_family_background;
create table r_stud_family_bg_details (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_stdfmlybckgrndrfrnc_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  FamilyBG_INFO enum('Father','Mother','Guardian') not null,
  Info_FNAME varchar(100) not null,
  Info_MNAME varchar(100) not null,
  Info_LNAME varchar(100) not null,
  Info_AGE int not null,
  Info_STAT enum('Living','Deceased') default 'Living',
  Info_EDUC_ATTAINMENT varchar(100) not null,
  Info_OCCUPATION varchar(100) not null,
  Info_EMPLOYER_NAME varchar(300) default 'None',
  Info_EMPLOYER_ADDRESS varchar(500) default 'None'
);

-- ----------------------------
-- Table structure for r_stud_educ_background
-- ----------------------------
drop table if exists r_stud_educ_background;
create table r_stud_educ_background (
  Educ_BG_ID int not null auto_increment,
    primary key (Educ_BG_ID),
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_edcbckgrndrfrnc_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Educ_NATURE_OF_SCHOOLING enum('Continuous','Interrupted') default 'Continuous',
  Interrupted_REASON varchar(500) default 'None'
);

-- ----------------------------
-- Table structure for r_stud_educ_bg_details
-- ----------------------------
  drop table if exists r_stud_educ_bg_details;
  create table r_stud_educ_bg_details (
    Educ_BG_ID int not null,
      constraint FK_stdedcbgdtlsedcbg_ID foreign key (Educ_BG_ID)
      references r_stud_educ_background (Educ_BG_ID)
      on delete cascade
      on update cascade,
    Educ_LEVEL enum('Pre-elementary','Elementary','High School','Vocational','College if any') not null,
    Educ_SCHOOL_GRADUATED varchar(500) default 'None',
    Educ_SCHOOL_ADDRESS varchar(500) default 'None',
    Educ_SCHOOL_TYPE enum('Public','Private') default 'Public',
    Educ_DATES_OF_ATTENDANCE varchar(15) not null
  );

-- ----------------------------
-- Table structure for r_stud_honors_awards
-- ----------------------------
drop table if exists r_stud_honors_awards;
create table r_stud_honors_awards (
  Educ_BG_ID int not null,
    constraint FK_stdhnrsawrdsedcbg_ID foreign key (Educ_BG_ID)
    references r_stud_educ_background (Educ_BG_ID)
    on delete cascade
    on update cascade,
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_hnrsawrds_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Received_TYPE enum('Honors Received','Special Awards') default 'Honors Received',
  Received_Desc varchar(100) not null
);

-- ----------------------------
-- Table structure for r_stud_general_info
-- ----------------------------
drop table if exists r_stud_general_info;
create table r_stud_general_info (
  Student_NO varchar(15) not null,
    constraint FK_gnrlinf_STUD_NO foreign key (Stud_NO)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  General_Info_SUB_DETAILS enum('Favorite Subject/s','Subject/s Least Like','Club','Hobbies','Organization') not null,
  General_Info_CONTENT varchar(100) not null
);

-- ----------------------------
-- Table structure for r_stud_org_position
-- ----------------------------
drop table if exists r_stud_org_position;
create table if exists r_stud_org_position (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_orgpstn_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Stud_POSITION varchar(100) not null
);

-- ----------------------------
-- Table structure for r_stud_physical_rec
-- ----------------------------
drop table if exists r_stud_phys_rec;
create table r_stud_phys_rec (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_physrc_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  PhysicalRec_VISION varchar(1000) default 'No',
  PhysicalRec_HEARING varchar(1000) default 'No',
  PhysicalRec_SPEECH varchar(1000) default 'No',
  PhysicalRec_GEN_HEALTH varchar(1000) default 'No'
);

-- ----------------------------
-- Table structure for r_stud_psychology_rec
-- ----------------------------
drop table if exists r_stud_psych_rec;
create table r_stud_psych_rec (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_psychrc_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  PsychRec_PREV_CONSULTED enum('Psychiatrist','Psychologist','Counselor') not null,
  PsychRec_CONSULTED_WHEN varchar(1000) default 'None',
  PsychRec_REASON varchar(1000) default 'None'
);

-- ----------------------------
-- Table structure for r_guidance_counselor
-- ----------------------------
drop table if exists r_guidance_counselor;
create table r_guidance_counselor (
  Counselor_ID int not null auto_increment,
    primary key (Counselor_ID),
  Counselor_CODE varchar(15) not null,
  Counselor_FNAME varchar(100) not null,
  Counselor_MNAME varchar(100) not null,
  Counselor_LNAME varchar(100) not null
);

-- ----------------------------
-- Table structure for r_users
-- ----------------------------
drop table if exists r_users;
create table r_users (
  Users_ID int not null,
    primary key (Users_ID),
  Users_USERNAME varchar(50) not null,
    unique key UNQ_Users_USERNAME (Users_USERNAME),
  Users_REFERENCED varchar(15) not null,
  Users_PASSWORD blob not null,
  Users_ROLES enum('System Administrator','Administrator','OSAS HEAD','Guidance Counselor','Organization','Student','Student Assistant') not null,
  Users_PROFILE_PATH varchar(500) default null,
  Users_DATE_ADD datetime not null default current_timestamp,
  Users_DATE_MOD datetime not null default current_timestamp,
  Users_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for r_sanction_details
-- ----------------------------
drop table if exists r_sanction_details;
create table r_sanction_details (
  SancDetails_ID int not null auto_increment,
    primary key (SancDetails_ID),
  SancDetails_CODE varchar(100) not null,
    unique key UNQ_SancDetails_CODE (SancDetails_CODE),
  SancDetails_NAME varchar(100) not null,
  SancDetails_DESC varchar(1000) default 'Sanction Description',
  SancDetails_TIMEVAL int not null default 0,
  SancDetails_DATE_MOD datetime not null default current_timestamp,
  SancDetails_DATE_ADD datetime not null default current_timestamp,
  SancDetails_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for r_designated_offices_details
-- ----------------------------
drop table if exists r_designated_offices_details;
create table r_designated_offices_details (
  DesOffDetails_ID int not null auto_increment,
    primary key (DesOffDetails_ID),
  DesOffDetails_CODE varchar(15) not null,
    unique key UNQ_DesOffDetails_CODE (DesOffDetails_CODE),
  DesOffDetails_NAME varchar(100) not null,
  DesOffDetails_DESC varchar(100) not null,
  DesOffDetails_DATE_ADD datetime not null default current_timestamp,
  DesOffDetails_DATE_MOD datetime not null default current_timestamp,
  DesOffDetails_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Table structure for t_assign_stud_saction
-- ----------------------------
drop table if exists t_assign_stud_saction;
create table t_assign_stud_saction (
  AssSancStudStudent_ID int not null auto_increment,
    primary key (AssSancStudStudent_ID),
  AssSancStudStudent_STUD_NO varchar(15) not null,
    constraint FK_AssSancStudStudent_STUD_NO foreign key (AssSancStudStudent_STUD_NO)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  AssSancStudStudent_SancDetails_CODE varchar(15) not null,
    constraint FK_AssSancStudStudent_SancDetails_CODE foreign key (AssSancStudStudent_SancDetails_CODE)
    references r_sanction_details (SancDetails_CODE)
    on delete cascade
    on update cascade,
  AssSancStudStudent_DesOffDetails_CODE varchar(15) not null,
    constraint FK_AssSancStudStudent_DesOffDetails_CODE foreign key (AssSancStudStudent_DesOffDetails_CODE)
    references r_designated_offices_details (DesOffDetails_CODE)
    on delete cascade
    on update cascade,
  AssSancStudStudent_CONSUMED_HOURS int default 0,
  AssSancStudStudent_REMARKS varchar(100) default null,
  AssSancStudStudent_IS_FINISH enum('Processing','Finished') not null default 'Processing',
  AssSancStudStudent_TO_BE_DONE date not null,
  AssSancStudStudent_DATE_ADD datetime not null default current_timestamp,
  AssSancStudStudent_DATE_MOD datetime not null default current_timestamp,
  AssSancStudStudent_DISPLAY_STAT enum('Active','Inactive') default 'Active'
);

------------------------------------------------------------------------------------------------
------------------------------- NOT YET UPDATED TABLES -----------------------------------------
------------------------------------------------------------------------------------------------


-- ----------------------------
-- Table structure for r_counseling_type
-- ----------------------------
DROP TABLE IF EXISTS `r_counseling_type`;
CREATE TABLE `r_counseling_type` (
  `COUNS_TYPE_CODE` varchar(100) NOT NULL,
  `COUNS_TYPE_NAME` varchar(100) NOT NULL,
  PRIMARY KEY (`COUNS_TYPE_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for r_couns_approach
-- ----------------------------
DROP TABLE IF EXISTS `r_couns_approach`;
CREATE TABLE `r_couns_approach` (
  `COUNS_APPROACH_CODE` varchar(50) NOT NULL,
  `COUNS_APPROACH_NAME` varchar(100) NOT NULL,
  `COUNS_APPROACH_DETAILS` text,
  PRIMARY KEY (`COUNS_APPROACH_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for r_remarks_type
-- ----------------------------
DROP TABLE IF EXISTS `r_remarks_type`;
CREATE TABLE `r_remarks_type` (
  `REMARKS_CODE` varchar(100) NOT NULL,
  `REMARKS_NAME` varchar(100) NOT NULL,
  `REMARKS_DETAILS` text,
  PRIMARY KEY (`REMARKS_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for r_visit_type
-- ----------------------------
DROP TABLE IF EXISTS `r_visit_type`;
CREATE TABLE `r_visit_type` (
  `VISIT_CODE` varchar(100) NOT NULL,
  `VISIT_NAME` varchar(100) NOT NULL,
  `VISIT_DESC` text,
  PRIMARY KEY (`VISIT_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_batch_group
-- ----------------------------
DROP TABLE IF EXISTS `t_batch_group`;
CREATE TABLE `t_batch_group` (
  `BATCH_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BATCH_APPROACH` varchar(50) NOT NULL,
  `BATCH_bg` text,
  `BATCH_goals` text,
  `BATCH_comments` text,
  `BATCH_recomm` text,
  `BATCH_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`BATCH_ID`),
  KEY `BATCH_APPROACH` (`BATCH_APPROACH`),
  CONSTRAINT `t_batch_group_ibfk_1` FOREIGN KEY (`BATCH_APPROACH`) REFERENCES `r_couns_approach` (`COUNS_APPROACH_CODE`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_counseling
-- ----------------------------
DROP TABLE IF EXISTS `t_counseling`;
CREATE TABLE `t_counseling` (
  `COUNSELING_ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `COUNS_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`COUNSELING_ID`),
  KEY `FK_C_CT_CODE` (`COUNSELING_TYPE_CODE`),
  KEY `FK_C_STUD_ID` (`STUD_ID`),
  KEY `FK_C_APPROACH` (`COUNS_APPROACH`),
  CONSTRAINT `FK_C_APPROACH` FOREIGN KEY (`COUNS_APPROACH`) REFERENCES `r_couns_approach` (`COUNS_APPROACH_CODE`),
  CONSTRAINT `FK_C_CT_CODE` FOREIGN KEY (`COUNSELING_TYPE_CODE`) REFERENCES `r_counseling_type` (`COUNS_TYPE_CODE`),
  CONSTRAINT `FK_C_STUD_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_counseling_group
-- ----------------------------
DROP TABLE IF EXISTS `t_counseling_group`;
CREATE TABLE `t_counseling_group` (
  `grp_COUNSELING_ID` int(11) NOT NULL AUTO_INCREMENT,
  `grp_STUD_NO` varchar(100) NOT NULL,
  `grp_STUD_NAME` varchar(100) NOT NULL,
  `grp_id` int(11) NOT NULL,
  PRIMARY KEY (`grp_COUNSELING_ID`),
  KEY `grp_id` (`grp_id`),
  CONSTRAINT `t_counseling_group_ibfk_1` FOREIGN KEY (`grp_id`) REFERENCES `t_batch_group` (`BATCH_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_remarks
-- ----------------------------
DROP TABLE IF EXISTS `t_remarks`;
CREATE TABLE `t_remarks` (
  `REMARKS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `REMARKS_CODE` varchar(100) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `VISIT_CODE` varchar(100) DEFAULT NULL,
  `COUNS_ID` int(11) DEFAULT NULL,
  `REMARKS_DETAILS` text,
  `REMARKS_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`REMARKS_ID`),
  KEY `FK_R_CODE` (`REMARKS_CODE`),
  KEY `FK_S_ID` (`STUD_ID`),
  KEY `FK_V_CODE` (`VISIT_CODE`),
  KEY `FK_C_ID` (`COUNS_ID`),
  CONSTRAINT `FK_C_ID` FOREIGN KEY (`COUNS_ID`) REFERENCES `t_counseling` (`COUNSELING_ID`),
  CONSTRAINT `FK_R_CODE` FOREIGN KEY (`REMARKS_CODE`) REFERENCES `r_remarks_type` (`REMARKS_CODE`),
  CONSTRAINT `FK_S_ID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`),
  CONSTRAINT `FK_V_CODE` FOREIGN KEY (`VISIT_CODE`) REFERENCES `r_visit_type` (`VISIT_CODE`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_upload
-- ----------------------------
DROP TABLE IF EXISTS `t_upload`;
CREATE TABLE `t_upload` (
  `T_UPLOAD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `T_UPLOAD_NAME` varchar(200) NOT NULL,
  `T_UPLOAD_CATEGORY` varchar(50) NOT NULL,
  `T_UPLOAD_DATE` date NOT NULL,
  `T_UPLOAD_LOCATION` varchar(100) NOT NULL,
  `T_UPLOAD_TYPE` varchar(100) NOT NULL,
  PRIMARY KEY (`T_UPLOAD_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for t_visits
-- ----------------------------
DROP TABLE IF EXISTS `t_visits`;
CREATE TABLE `t_visits` (
  `VISIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `VISIT_CODE` varchar(100) NOT NULL,
  `STUD_ID` int(11) NOT NULL,
  `STUD_NO` varchar(100) NOT NULL,
  `STUD_NAME` varchar(100) NOT NULL,
  `STUD_COURSE` varchar(100) NOT NULL,
  `VISIT_DETAILS` text,
  `VISIT_DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `VISIT_REMARKS` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`VISIT_ID`),
  KEY `FK_V_CDE` (`VISIT_CODE`),
  KEY `FK_STUDID` (`STUD_ID`),
  KEY `FK_V_RMKS` (`VISIT_REMARKS`),
  CONSTRAINT `FK_STUDID` FOREIGN KEY (`STUD_ID`) REFERENCES `r_stud_profile` (`STUD_ID`),
  CONSTRAINT `FK_V_CDE` FOREIGN KEY (`VISIT_CODE`) REFERENCES `r_visit_type` (`VISIT_CODE`),
  CONSTRAINT `FK_V_RMKS` FOREIGN KEY (`VISIT_REMARKS`) REFERENCES `r_remarks_type` (`REMARKS_CODE`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
