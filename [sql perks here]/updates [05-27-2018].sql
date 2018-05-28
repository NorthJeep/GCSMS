
-- ----------------------------
-- Table structure for r_stud_batch
-- ----------------------------
drop table if exists r_stud_batch;
create table `r_stud_batch` (
	Stud_BATCH_ID int not null auto_increment,
		primary key (Stud_BATCH_ID),
	Stud_NO varchar(15) not null,
		constraint FK_stdbtchrfrnc_STUD_NO foreign key(Stud_NO)
		references r_stud_profile (Stud_NO)
		on delete cascade
		on update cascade,
	Batch_YEAR varchar(15) not null,
		constraint FK_stdbtchyrrfrnc foreign key (Batch_YEAR)
		references r_batch_details (Batch_YEAR)
		on delete cascade
		on update cascade,
  	Stud_STATUS enum('Regular','Irregular','Disqualified','LOA','Transferee') default 'Regular'
);

INSERT INTO `r_stud_batch` (`Stud_NO`,`Batch_YEAR`,`Stud_STATUS`) VALUES
('2015-00138-CM-0', '2017-2018', 'Regular');

-- ----------------------------
-- View structure for student_profiling
-- ----------------------------
drop view student_profiling;
create view student_profiling as
select
    SP.Stud_NO as STUD_NO,
    if(SP.Stud_MNAME is null,concat(SP.Stud_FNAME,' ',SP.Stud_LNAME),concat(SP.Stud_FNAME,' ',SP.Stud_MNAME,' ',SP.Stud_LNAME)) as FULLNAME,
    concat(SP.Stud_COURSE,' ',SP.Stud_YEAR_LEVEL,'-',SP.Stud_YEAR_LEVEL) as COURSE,
    SB.Batch_YEAR as `BATCH YEAR`,
    SP.Stud_GENDER as GENDER,
    date_format(SP.Stud_BIRTH_DATE,'%M %d, %Y') as `BIRTH DATE`,
    SP.Stud_BIRTH_PLACE as `BIRTH PLACE`,
    SP.Stud_CITY_ADDRESS as `CITY ADDRESS`,
    SP.Stud_PROVINCIAL_ADDRESS as `PROVINCIAL ADDRESS`,
    SP.Stud_TELEPHONE_NO as `TELEPHONE NO`,
    SP.Stud_MOBILE_NO as `MOBILE NO`,
    SP.Stud_EMAIL as EMAIL,
    SB.Stud_STATUS as STUD_STATUS
from  r_stud_batch SB
inner join r_stud_profile SP
    on SP.Stud_NO = SB.Stud_NO
where SB.Batch_YEAR = (select Batch_YEAR from r_batch_details order by Batch_ID desc limit 1)
    and SB.Stud_STATUS = 'Regular' or SB.Stud_STATUS = 'Irregular'
