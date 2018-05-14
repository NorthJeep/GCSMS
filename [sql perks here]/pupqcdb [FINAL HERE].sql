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

INSERT INTO `r_batch_details` (`Batch_ID`, `Batch_CODE`, `Batch_YEAR`, `Batch_DESC`, `Batch_DISPLAY_STAT`) VALUES
(1, 'BAT00001', '2011-2012', 'Batch descrzzzxczxciptions', 'Active'),
(2, 'BAT00002', '2012-2013', 'Batch description', 'Active'),
(3, 'BAT00003', '2013-2014', 'Batch description', 'Active'),
(4, 'BAT00004', '2014-2015', 'Batch description', 'Active'),
(5, 'BAT00005', '2015-2016', 'Batch description', 'Active'),
(6, 'BAT00006', '2016-2017', 'Batch description', 'Active'),
(7, 'BAT00007', '2017-2018', 'Batch description', 'Active');

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

INSERT INTO `r_courses` (`Course_ID`, `Course_CODE`, `Course_NAME`, `Course_DESC`, `Course_CURR_YEAR`, `Course_DATE_MOD`, `Course_DATE_ADD`, `Course_DISPLAY_STAT`) VALUES
(1, 'BSIT', 'Bachelor of Science in Information Technology', 'Course Descriptions', '2011-2012', '2018-04-25 23:23:43', '2018-02-07 18:41:43', 'Active'),
(2, 'DOMT', 'Diploma In Office Management Technology', 'Course Description', '2011-2012', '2018-02-09 17:54:51', '2018-02-09 17:54:51', 'Active'),
(3, 'DICT', 'Diploma in Information Communication Technology', 'Diploma in Information Communication Technology', '2011-2012', '2018-03-11 20:40:22', '2018-03-11 20:40:22', 'Active'),
(4, '5151', '312312', '3123123', '2017-2018', '2018-04-25 23:24:00', '2018-04-25 23:23:51', 'Active');

-- ----------------------------
-- Table structure for r_stud_profile
-- ----------------------------
drop table if exists r_civil_stat;
create table r_civil_stat (
  Stud_CIVIL_STATUS varchar(15) not null,
    unique key (Stud_CIVIL_STATUS)
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
  Stud_CIVIL_STAT varchar(15) not null,
    constraint FK_stdprsnlnfcvlsttrfrnc foreign key (Stud_CIVIL_STAT)
    references r_civil_stat (Stud_CIVIL_STATUS)
    on delete cascade
    on update cascade,
  Stud_WORKING enum('Yes','No') default 'No',
  Employer_NAME varchar(300) default 'None',
  Employer_ADDRESS varchar(100) default 'None',
  Emergency_CONTACT_PERSON varchar(500) not null,
  Emergency_CONTACT_ADDRESS varchar(500) not null,
  Emergency_CONTACT_NUMBER varchar(20) not null default 'None',
  ContactPerson_RELATIONSHIP varchar(100) not null,
  Parents_MARITAL_RELATIONSHIP varchar(100) not null,
  Stud_FAM_CHILDREN_NO int not null,
  Stud_BROTHER_NO int default 0,
  Stud_SISTER_NO int default 0,
  Employed_BS_NO int default 0,
  Stud_ORDINAL_POSITION varchar(50) not null,
  Stud_SCHOOLING_FINANCE enum('Parents','Brother/Sister','Spouse','Scholarship','Relatives','Self-supporting/working student') default 'Parents',
  Stud_WEEKLY_ALLOWANCE double(9,2) not null,
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
  Stud_NO varchar(15) not null,
    constraint FK_gnrlinf_STUD_NO foreign key (Stud_NO)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Gen_Info_DETAILS enum('Favorite Subject/s','Subject/s Least Like','Club','Hobbies','Organization') not null,
  Gen_Info_CONTENT varchar(100) not null
);

-- ----------------------------
-- Table structure for r_stud_org_position
-- ----------------------------
drop table if exists r_stud_org_position;
create table r_stud_org_position (
  Stud_NO_REFERENCE varchar(15) not null,
    constraint FK_orgpstn_STUD_NO foreign key (Stud_NO_REFERENCE)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Stud_POSITION text not null
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
  PhysicalRec_VISION text not null,
  PhysicalRec_HEARING text not null,
  PhysicalRec_SPEECH text not null,
  PhysicalRec_GEN_HEALTH text not null
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
  Users_ID int not null auto_increment,
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
-- Table structure for t_upload
-- ----------------------------
drop table if exists r_upload_category;
create table r_upload_category (
  Upload_FILE_CATEGORY varchar(100) not null,
    unique key (Upload_FILE_CATEGORY)
);

-- ----------------------------
-- Table structure for t_upload
-- ----------------------------
drop table if exists t_upload;
create table t_upload (
  Upload_FILE_ID int not null auto_increment,
    primary key (Upload_FILE_ID),
  Upload_DATE timestamp not null default current_timestamp,
  Upload_USER varchar(15) not null,
  Upload_FILENAME varchar(200) not null,
  Upload_CATEGORY varchar(100) not null,
    constraint FK_pldctgryrfrnc foreign key (Upload_CATEGORY)
    references r_upload_category (Upload_FILE_CATEGORY)
    on delete cascade
    on update cascade,
  Upload_FILETYPE varchar(100) not null,
  Upload_FILEPATH varchar(100) not null
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

-- ----------------------------
-- Table structure for r_remarks
-- ----------------------------
drop table if exists r_remarks;
create table r_remarks (
  Remarks_TYPE varchar(50) not null,
    unique key (Remarks_TYPE),
  Remarks_DESC text
);

-- ----------------------------
-- Table structure for r_visit
-- ----------------------------
drop table if exists r_visit;
create table r_visit (
  Visit_TYPE varchar(50) not null,
    unique key (Visit_TYPE),
  Visit_DESC text
);

-- ----------------------------
-- Table structure for t_stud_visit
-- ----------------------------
drop table if exists t_stud_visit;
create table t_stud_visit (
  Stud_VISIT_ID int not null auto_increment,
    primary key (Stud_VISIT_ID),
  Visit_CODE varchar(15) not null,
    unique key (Visit_CODE),
  Visit_DATE timestamp not null default current_timestamp,
  Stud_NO varchar(15) not null,
    constraint FK_vst_STUD_NO foreign key (Stud_NO)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Visit_PURPOSE varchar(50) not null,
    constraint FK_stdvstprps_vstrfrnc foreign key (Visit_PURPOSE)
    references r_visit (Visit_TYPE)
    on delete cascade
    on update cascade,
  Visit_DETAILS text
);

-- ----------------------------
-- Table structure for r_couns_appointment_type
-- ----------------------------
drop table if exists r_couns_appointment_type;
create table r_couns_appointment_type (
  Appmnt_TYPE varchar(25) not null,
    unique key (Appmnt_TYPE)
);

-- ----------------------------
-- Table structure for r_couns_type
-- ----------------------------
drop table if exists r_couns_type;
create table r_couns_type (
  Couns_TYPE varchar(50) not null,
    unique key (Couns_TYPE)
);

-- ----------------------------
-- Table structure for r_couns_approach
-- ----------------------------
drop table if exists r_couns_approach;
create table r_couns_approach (
  Couns_APPROACH varchar(50) not null,
    unique key (Couns_APPROACH),
  COUNS_APPROACH_DESC text
);

-- ----------------------------
-- Table structure for t_counseling
-- ----------------------------
drop table if exists t_counseling;
create table t_counseling (
  Couns_ID int not null auto_increment,
    primary key (Couns_ID),
  Couns_CODE varchar(15) not null,
    unique key (Couns_CODE),
  Visit_ID_REFERENCE int not null,
    constraint FK_cnslngvstidrfrnc foreign key (Visit_ID_REFERENCE)
    references t_stud_visit (Stud_VISIT_ID)
    on delete cascade
    on update cascade,
  Couns_DATE timestamp not null default current_timestamp,
  Couns_COUNSELING_TYPE varchar(50) not null,
    constraint FK_C_CT_REFERENCE foreign key (Couns_COUNSELING_TYPE)
    references r_couns_type (Couns_TYPE)
    on delete cascade
    on update cascade,
  Couns_APPOINTMENT_TYPE varchar(25) not null,
    constraint FK_cnslngcnsppntmnttyp foreign key (Couns_APPOINTMENT_TYPE)
    references r_couns_appointment_type (Appmnt_TYPE)
    on delete cascade
    on update cascade,
  Couns_BACKGROUND text,
  Couns_GOALS text,
  Couns_COMMENT text
);

-- ----------------------------
-- Table structure for t_couns_details
-- ----------------------------
drop table if exists t_couns_details;
create table t_couns_details (
  Couns_ID_REFERENCE int not null,
    constraint FK_CnsIDrfrnc foreign key (Couns_ID_REFERENCE)
    references t_counseling (Couns_ID)
    on delete cascade
    on update cascade,
  Stud_NO varchar(15) not null,
    constraint FK_cnslngstdnrfrnc foreign key (Stud_NO)
    references r_stud_profile (Stud_NO)
    on delete cascade
    on update cascade,
  Couns_REMARKS varchar(50) not null,
    constraint FK_cnsdtlscnsrmrksrfrnc foreign key (Couns_REMARKS)
    references r_remarks (Remarks_TYPE)
    on delete cascade
    on update cascade,
  Couns_REMARKS_DETAILS text
);

-- ----------------------------
-- Table structure for t_couns_approach
-- ----------------------------
drop table if exists t_couns_approach;
create table t_couns_approach (
  Couns_ID_REFERENCE int not null,
    constraint FK_cnspprchcnsidrfrnc foreign key (Couns_ID_REFERENCE)
    references t_counseling (Couns_ID)
    on delete cascade
    on update cascade,
  Couns_APPROACH varchar(50) not null,
    constraint FK_C_CA_REFERENCE foreign key (Couns_APPROACH)
    references r_couns_approach (Couns_APPROACH)
    on delete cascade
    on update cascade
);
