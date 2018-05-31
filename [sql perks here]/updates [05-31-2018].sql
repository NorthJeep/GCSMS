
ALTER TABLE `r_stud_family_bg_details` CHANGE `FamilyBG_INFO` `FamilyBG_INFO` enum('Father','Mother') not null;

CREATE TABLE r_stud_guardian (
	Stud_NO varchar(15) not null,
		constraint FK_stdgrdnrfrnc_STUD_NO foreign key (Stud_NO)
		references r_stud_profile (Stud_NO)
		on delete cascade
		on update cascade,
  	Guardian_FNAME varchar(100) not null,
  	Guardian_MNAME varchar(100) not null,
  	Guardian_LNAME varchar(100) not null,
  	Guardian_AGE int not null,
  	Stud_GUARDIAN_RELATION varchar(50) not null,
  	Guardian_EDUC_ATTAINMENT varchar(100) not null,
  	Guardian_OCCUPATION varchar(100) not null,
  	Guardian_EMPLOYER_NAME varchar(300) default 'None',
  	Guardian_EMPLOYER_ADDRESS varchar(500) default 'None'
);

ALTER TABLE `r_stud_educ_background` DROP `Interrupted_REASON`, CHANGE `Educ_NATURE_OF_SCHOOLING` `Educ_NATURE_OF_SCHOOLING` TEXT NOT NULL;

DROP TABLE `r_stud_honors_awards`;

ALTER TABLE `r_stud_educ_bg_details` CHANGE `Educ_BG_Details_ID` `Educ_BG_Details_ID` INT NOT NULL;
ALTER TABLE `r_stud_educ_bg_details` DROP PRIMARY KEY;
ALTER TABLE `r_stud_educ_bg_details` DROP `Educ_BG_Details_ID`, ADD `Received_AWARDS_DESC` TEXT NOT NULL;

-- ----------------------------
-- Table structure for active_academic_year
-- ----------------------------
DROP TABLE IF EXISTS `active_academic_year`;
CREATE TABLE `active_academic_year`  (
  `ActiveAcadYear_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ActiveAcadYear_Batch_YEAR` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ActiveAcadYear_IS_ACTIVE` enum('1','0') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `ActiveAcadYear_DATE_ADD` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ActiveAcadYear_DATE_MOD` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`ActiveAcadYear_ID`) USING BTREE,
  INDEX `FK_ActiveAcadYear_Batch_YEAR`(`ActiveAcadYear_Batch_YEAR`) USING BTREE,
  CONSTRAINT `FK_ActiveAcadYear_Batch_YEAR` FOREIGN KEY (`ActiveAcadYear_Batch_YEAR`) REFERENCES `r_batch_details` (`Batch_YEAR`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of active_academic_year
-- ----------------------------
INSERT INTO `active_academic_year` VALUES (5, '2017-2018', '1', '2018-05-21 00:38:41', NULL);

-- ----------------------------
-- Table structure for r_semester
-- ----------------------------
DROP TABLE IF EXISTS `r_semester`;
CREATE TABLE `r_semester`  (
  `Semestral_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Semestral_CODE` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Semestral_NAME` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `Semestral_DESC` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Semester Description',
  `Semestral_DATE_ADD` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Semestral_DATE_MOD` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Semestral_DISPLAY_STAT` enum('Active','Inactive') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Active',
  PRIMARY KEY (`Semestral_ID`) USING BTREE,
  UNIQUE INDEX `UNQ_Semstral_NAME`(`Semestral_NAME`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of r_semester
-- ----------------------------
INSERT INTO `r_semester` VALUES (7, 'SEM00001', 'First Semester', 'First Semester', '2018-05-21 00:14:49', '2018-05-21 00:14:49', 'Active');
INSERT INTO `r_semester` VALUES (8, 'SEM00002', 'Second Semester', 'Second Semester', '2018-05-21 00:14:57', '2018-05-21 00:14:57', 'Active');
INSERT INTO `r_semester` VALUES (9, 'SEM00003', 'Third Semester', 'Third Semester', '2018-05-21 00:15:08', '2018-05-21 00:15:08', 'Active');
INSERT INTO `r_semester` VALUES (10, 'SEM00004', 'Fourth Semester', 'Fourth Semester', '2018-05-21 00:15:16', '2018-05-21 00:15:16', 'Active');
INSERT INTO `r_semester` VALUES (11, 'SEM00005', 'Summer Semester', 'Summer Semester', '2018-05-21 00:15:25', '2018-05-21 00:15:25', 'Active');
