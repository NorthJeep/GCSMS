
create table r_nature_of_case (
  Case_NAME varchar(50) not null,
    unique key (Case_NAME),
  Case_STAT enum('Active','Inactive') default 'Active'
);

ALTER TABLE `t_counseling` ADD `Nature_Of_Case` VARCHAR(50) NOT NULL AFTER `Couns_APPOINTMENT_TYPE`;
ALTER TABLE `t_counseling` ADD CONSTRAINT `FK_cnslngntrfcsrfrnc` FOREIGN KEY (`Nature_Of_Case`) REFERENCES `r_nature_of_case` (`Case_NAME`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `r_visit` ADD `Visit_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Visit_TYPE_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `Visit_DESC`, ADD PRIMARY KEY (`Visit_ID`);

ALTER TABLE `r_couns_approach` ADD `Couns_APPROACH_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Couns_APPROACH_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `COUNS_APPROACH_DESC`, ADD PRIMARY KEY (`Couns_APPROACH_ID`);

ALTER TABLE `r_couns_type` ADD `Couns_TYPE_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Couns_TYPE_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `Couns_TYPE`, ADD PRIMARY KEY (`Couns_TYPE_ID`);

ALTER TABLE `r_couns_appointment_type` ADD `Appmnt_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Appmnt_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `Appmnt_TYPE`, ADD PRIMARY KEY (`Appmnt_ID`);

ALTER TABLE `r_remarks` ADD `Remarks_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Remarks_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `Remarks_DESC`, ADD PRIMARY KEY (`Remarks_ID`);

ALTER TABLE `r_civil_stat` ADD `Stud_CIVIL_STATUS_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD `Stud_CIVIL_STATUS_STAT` ENUM('Active','Inactive') NULL DEFAULT 'Active' AFTER `Stud_CIVIL_STATUS`, ADD PRIMARY KEY (`Stud_CIVIL_STATUS_ID`);