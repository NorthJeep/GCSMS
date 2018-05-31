
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