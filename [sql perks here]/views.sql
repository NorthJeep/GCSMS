


create view Student_Profiling as
select
	Stud_NO as STUD_NO,
    concat(Stud_FNAME,' ',Stud_LNAME) as FULLNAME,
    concat(Stud_COURSE,' ',Stud_YEAR_LEVEL,'-',Stud_YEAR_LEVEL) as COURSE,
    Stud_STATUS as STUD_STATUS
from r_stud_profile
where Stud_STATUS = 'Regular' or Stud_STATUS = 'Irregular';

