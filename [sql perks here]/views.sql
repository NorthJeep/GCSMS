
-- ----------------------------
-- View structure for student_profiling
-- ----------------------------
create view student_profiling as
select
	Stud_NO as STUD_NO,
    concat(Stud_FNAME,' ',Stud_LNAME) as FULLNAME,
    concat(Stud_COURSE,' ',Stud_YEAR_LEVEL,'-',Stud_YEAR_LEVEL) as COURSE,
    Stud_STATUS as STUD_STATUS
from r_stud_profile
where Stud_STATUS = 'Regular' or Stud_STATUS = 'Irregular';

-- ----------------------------
-- View structure for student_counseling
-- ----------------------------
create view student_counseling
AS
select
    C.Couns_CODE as COUNSELING_CODE,
    date_format(C.Couns_DATE,'%W %M %d %Y') as COUNSELING_DATE,
    C.Couns_COUNSELING_TYPE as COUNSELING_TYPE,
    C.Couns_APPOINTMENT_TYPE as APPOINTMENT_TYPE,
    S.Stud_NO as STUD_NO,
    concat(S.Stud_FNAME,' ',S.Stud_LNAME) as STUD_NAME,
    concat(S.Stud_COURSE,' ',S.Stud_YEAR_LEVEL,' - ',S.Stud_SECTION) as COURSE,
    (select group_concat(Couns_APPROACH separator ', ') from t_couns_approach A where A.Couns_ID_REFERENCE = C.Couns_ID) as COUNSELING_APPROACH,
    C.Couns_BACKGROUND as COUNSELING_BG,
    C.Couns_GOALS as GOALS,
    C.Couns_COMMENT as COUNS_COMMENT,
    C.Couns_RECOMMENDATION as RECOMMENDATION
from t_counseling as C
inner join t_couns_details CD
on c.Couns_ID = CD.Couns_ID_REFERENCE
inner join r_stud_profile S
on S.Stud_NO = CD.Stud_NO

-- ----------------------------
-- View structure for visit_record
-- ----------------------------
create view visit_record
as
SELECT
    Visit_CODE,
    Visit_DATE,
    S.Stud_NO,
    concat(S.Stud_FNAME,' ',S.Stud_LNAME) as STUDENT,
    concat(S.Stud_COURSE,' ',S.Stud_YEAR_LEVEL,' - ',S.Stud_YEAR_LEVEL) as COURSE,
    Visit_PURPOSE,
    Visit_DETAILS
FROM `t_stud_visit` V
inner join r_stud_profile S
on S.Stud_NO = V.Stud_NO
ORDER BY Visit_DATE DESC