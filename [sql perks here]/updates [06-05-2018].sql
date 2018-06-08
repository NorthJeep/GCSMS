

-- ----------------------------
-- Table structure for r_user_roles
-- ----------------------------
drop table if exists r_user_roles;
create table r_user_roles (
    User_ROLE_ID int not null auto_increment,
        primary key (User_ROLE_ID),
    User_ROLE varchar(25) not null,
        unique key (User_ROLE),
    User_ROLE_STAT enum('Active','Inactive') default 'Active'
);

-- ----------------------------
-- Records of r_user_roles
-- ----------------------------
INSERT INTO `r_user_roles` VALUES (1, 'System Administrator', 'Active');
INSERT INTO `r_user_roles` VALUES (2, 'Guidance Counselor', 'Active');

-- ----------------------------
-- Table updates
-- ----------------------------
ALTER TABLE `t_counseling` ADD `Couns_ACADEMIC_YEAR` VARCHAR(50) NOT NULL AFTER `Couns_DATE`, ADD `Couns_SEMESTER` VARCHAR(50) NOT NULL AFTER `Couns_ACADEMIC_YEAR`;

UPDATE `t_counseling` SET `Couns_ACADEMIC_YEAR` = '2017-2018';

UPDATE `t_counseling` SET `Couns_SEMESTER` = 'Summer Semester';

ALTER TABLE `t_counseling` ADD CONSTRAINT FK_cnslngcdmcyrrfrnc FOREIGN KEY (`Couns_ACADEMIC_YEAR`) REFERENCES `r_batch_details` (`Batch_YEAR`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `t_counseling` ADD CONSTRAINT FK_cnslngsmstrrfrnc FOREIGN KEY (`Couns_SEMESTER`) REFERENCES `r_semester` (`Semestral_NAME`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `r_guidance_counselor` CHANGE `Counselor_MNAME` `Counselor_MNAME` VARCHAR(100) NULL;

-- ----------------------------
-- Stored procedure for counselor_admin_add
-- ----------------------------
CREATE DEFINER=`root`@`localhost` PROCEDURE `counselor_admin_add`(IN `fname` VARCHAR(100), IN `mname` VARCHAR(100), IN `lname` VARCHAR(100))
    NO SQL
BEGIN
set @code = (SELECT concat('GC',date_format(CURRENT_DATE,'%y-%m%d'),cast(count(*) as int) + 1) FROM r_guidance_counselor);

insert into r_guidance_counselor (
    Counselor_CODE,
    Counselor_FNAME,
    Counselor_MNAME,
    Counselor_LNAME)
values (
    @code,
    fname,
    if(mname = '',NULL,mname),
    lname);

insert into r_users (
    Users_USERNAME,
    Users_REFERENCED,
    Users_PASSWORD,
    Users_ROLES)
values (
    fname,
    @code,
    AES_ENCRYPT(fname,password('GC&SMS')),
    'Guidance Counselor'),
    (concat(fname,' ',lname),
    @code,
    AES_ENCRYPT(fname,password('GC&SMS')),
    'System Administrator');
END

-- ----------------------------
-- Stored procedure for student_assistant_add
-- ----------------------------
CREATE DEFINER=`root`@`localhost` PROCEDURE `student_assistant_add`(IN `studNo` VARCHAR(15))
    NO SQL
BEGIN
set @name = (select concat(Stud_FNAME,' ',Stud_LNAME) from r_stud_profile where stud_NO = studNo);
insert into r_users (
    Users_USERNAME,
    Users_REFERENCED,
    Users_PASSWORD,
    Users_ROLES)
values (
    studNo,
    studNo,
    AES_ENCRYPT(LCASE(@name),password('GC&SMS')),
    'Student Assistant');
END

