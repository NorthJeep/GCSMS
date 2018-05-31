
ALTER TABLE `r_stud_educ_bg_details` ADD `Educ_BG_Details_ID` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`Educ_BG_Details_ID`);

ALTER TABLE `r_stud_honors_awards` DROP FOREIGN KEY `FK_hnrsawrds_STUD_NO`;
ALTER TABLE `r_stud_honors_awards` DROP INDEX `FK_hnrsawrds_STUD_NO`;

ALTER TABLE `r_stud_honors_awards` DROP `Stud_NO_REFERENCE`, ADD `Educ_BG_Details_ID` INT NOT NULL AFTER `Educ_BG_ID`;
ALTER TABLE `r_stud_honors_awards` ADD CONSTRAINT `FK_stdhnrwrdsdcbgdtlsrfrnc` FOREIGN KEY (`Educ_BG_Details_ID`) REFERENCES `r_stud_educ_bg_details` (`Educ_BG_Details_ID`) ON DELETE CASCADE ON UPDATE CASCADE;