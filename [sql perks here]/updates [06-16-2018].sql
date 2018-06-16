
ALTER TABLE `r_stud_family_bg_details` CHANGE `Info_MNAME` `Info_MNAME` VARCHAR(100) NULL;

ALTER TABLE `r_stud_phys_rec` DROP `PhysicalRec_VISION`, DROP `PhysicalRec_HEARING`, DROP `PhysicalRec_SPEECH`, DROP `PhysicalRec_GEN_HEALTH`;

ALTER TABLE `r_stud_phys_rec` ADD `PhysicalRec_CHECK` ENUM('Vision','Hearing','Speech', 'General Health') NOT NULL AFTER `Stud_NO_REFERENCE`, ADD `PhysicalRec_RESULT` TEXT NOT NULL AFTER `PhysicalRec_CHECK`;

ALTER TABLE `r_stud_guardian` CHANGE `Guardian_MNAME` `Guardian_MNAME` VARCHAR(100) NULL;

ALTER TABLE `r_stud_personal_info` ADD `Stud_BS_SUPPORT` enum('None','family', 'your studies', 'his/her own family') NOT NULL DEFAULT 'None' AFTER Stud_ORDINAL_POSITION;

ALTER TABLE `t_stud_visit` ADD `Visit_ACADEMIC_YEAR` VARCHAR(50) AFTER `Stud_NO`, ADD `Visit_SEMESTER` VARCHAR(50) AFTER `Visit_ACADEMIC_YEAR`;

ALTER TABLE `t_stud_visit` ADD CONSTRAINT FK_vstcdmcyr_rfrnc FOREIGN KEY(`Visit_ACADEMIC_YEAR`) REFERENCES `r_batch_details` (`Batch_YEAR`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `t_stud_visit` ADD CONSTRAINT FK_vstsmstr_rfrnc FOREIGN KEY(`Visit_SEMESTER`) REFERENCES `r_semester` (`Semestral_NAME`) ON DELETE CASCADE ON UPDATE CASCADE;

UPDATE `t_stud_visit` SET `Visit_ACADEMIC_YEAR` = (SELECT `ActiveAcadYear_BATCH_YEAR` FROM `active_academic_year` WHERE `ActiveAcadYear_IS_ACTIVE` = 1 ORDER BY `ActiveAcadYear_ID` LIMIT 1);

UPDATE `t_stud_visit` SET `Visit_SEMESTER` = (SELECT `ActiveSemester_SEMESTRAL_NAME` FROM `active_semester` WHERE `ActiveSemester_IS_ACTIVE` = 1 ORDER BY `ActiveSemester_ID` LIMIT 1);

ALTER TABLE `t_stud_visit` CHANGE `Visit_ACADEMIC_YEAR` `Visit_ACADEMIC_YEAR` VARCHAR(50) NOT NULL, CHANGE `Visit_SEMESTER` `Visit_SEMESTER` VARCHAR(50) NOT NULL;


-- ----------------------------
-- Table structure for t_counseling_visit
-- ----------------------------
drop table if exists t_counseling_visit;
create table t_counseling_visit (
	Couns_ID_REFERENCE int not null,
    constraint FK_tcnslngvst_cidrfrnc foreign key (Couns_ID_REFERENCE)
    	references t_counseling (Couns_ID)
    	on update cascade
    	on delete cascade,
    Visit_ID_REFERENCE int not null,
    constraint FK_tcnslngvst_vidrfrnc foreign key (Visit_ID_REFERENCE)
    	references t_stud_visit (Stud_Visit_ID)
    	on update cascade
    	on delete cascade
);

insert into t_counseling_visit values (1,2);


ALTER TABLE `t_counseling` DROP FOREIGN KEY `FK_cnslngvstidrfrnc`;
ALTER TABLE `t_counseling` DROP INDEX `FK_cnslngvstidrfrnc`;
ALTER TABLE `t_counseling` DROP COLUMN `Visit_ID_REFERENCE`;



-- ----------------------------
-- View for student_counseling
-- ----------------------------
drop view student_counseling;
create view student_counseling as
SELECT
	C.Couns_CODE as `COUNSELING_CODE`,
    DATE_FORMAT(C.Couns_DATE, '%M %d %Y (%W)') as `COUNSELING_DATE`,
    C.Couns_ACADEMIC_YEAR as `ACADEMIC YEAR`,
    C.Couns_SEMESTER as `SEMESTER`,
    C.Couns_COUNSELING_TYPE as `COUNSELING_TYPE`,
    C.Couns_APPOINTMENT_TYPE as `APPOINTMENT_TYPE`,
    (SELECT GROUP_CONCAT(Stud_NO SEPARATOR ', \n') FROM t_couns_details WHERE Couns_ID_REFERENCE = C.Couns_ID) as `STUD_NO`,
    (SELECT GROUP_CONCAT(CONCAT(IF(S.Stud_MNAME IS NULL, CONCAT(S.Stud_FNAME,' ',S.Stud_LNAME), CONCAT(S.Stud_FNAME,' ',S.Stud_MNAME,' ',S.Stud_LNAME)),' (',S.Stud_COURSE,' ',S.Stud_YEAR_LEVEL,' - ',S.Stud_SECTION,')') SEPARATOR ',\n') FROM t_couns_details CD INNER JOIN r_stud_profile S ON S.Stud_NO = CD.Stud_NO WHERE CD.Couns_ID_REFERENCE = C.Couns_ID) as `STUD_NAME`,
    C.Nature_Of_Case as `NATURE OF THE CASE`,
    C.Couns_BACKGROUND as `COUNSELING_BG`,
    C.Couns_GOALS as `GOALS`,
    C.Couns_COMMENT as `COUNS_COMMENT`,
    C.Couns_RECOMMENDATION as `RECOMMENDATION`
FROM t_counseling C
INNER JOIN t_counseling_visit CV
	ON C.Couns_ID = CV.Couns_ID_REFERENCE
WHERE C.Couns_ACADEMIC_YEAR = (SELECT ActiveAcadYear_BATCH_YEAR FROM active_academic_year LIMIT 1);