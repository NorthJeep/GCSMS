USE pupqcdb;
SELECT
  *
FROM
  `t_counseling` AS Couns
  JOIN `t_couns_details` AS CounsD ON Couns.Couns_ID = CounsD.Couns_ID_REFERENCE
  JOIN `r_stud_profile` AS StudP ON StudP.STUD_NO = CounsD.Stud_NO
  JOIN `r_courses` AS Course ON StudP.Stud_COURSE = Course.Course_CODE