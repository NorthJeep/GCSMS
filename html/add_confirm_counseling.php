<?php
include("config.php");

$C_stud_id = $_POST['_id'];
$C_stud_name = $_POST['_name'];
$C_stud_no = $_POST['_no'];
$C_stud_course = $_POST['_course'];
$C_stud_yr = $_POST['_yr'];
$C_stud_sec = $_POST['_sec'];
$C_stud_email = $_POST['_email'];
$C_stud_add = $_POST['_add'];
$C_stud_cno = $_POST['_cno'];
$C_couns_bg = $_POST['_couns_bg1'];
$C_goals = $_POST['_goals'];
$C_comments = $_POST['_comments'];
$C_recomm = $_POST['_recomm'];
$C_app=$_POST['_app'];
$sql = "INSERT INTO `t_counseling` (`COUNSELING_TYPE_CODE`, `STUD_ID`, `STUD_NO`, `STUD_NAME`, `STUD_COURSE`, 
									`STUD_YR`, `STUD_SECTION`, `STUD_CONTACT`, `STUD_EMAIL`, `STUD_ADDRESS`, `COUNS_APPROACH`, 
									`COUNS_BG`, `COUNS_GOALS`, `COUNS_PREV_TEST`, `COUNS_PREV_PERSON`, `COUNS_COMMENTS`, `COUNS_RECOMM`, 
									`COUNS_APPOINTMENT_TYPE`, `COUNS_DATE`) 
			VALUES ('CT_Indiv', '$C_stud_id','$C_stud_no', '$C_stud_name', '$C_stud_course', '$C_stud_yr', '$C_stud_sec', 
				'$C_stud_cno', '$C_stud_email', '$C_stud_add', '$C_app', '$C_couns_bg', '$C_goals', NULL, NULL, '$C_comments', '$C_recomm', 'Walk-in', CURRENT_TIMESTAMP)";
		$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
									header('Location: counseling_page.php' . $redirect);
								}
								else 
								{
									echo "<p>Sira!</p>";
								}
?>