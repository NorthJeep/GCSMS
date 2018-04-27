<?php
include("config.php");

$Stud_no = $_POST['Stud_no'];
$Stud_email=$_POST['Stud_email'];
$Stud_contact=$_POST['Stud_contact'];
$Stud_fname=$_POST['Stud_fname'];
$Stud_mname=$_POST['Stud_mname'];
$Stud_lname=$_POST['Stud_lname'];
$Stud_course=$_POST['Stud_course'];
$Stud_section=$_POST['Stud_section'];
$Stud_gender=$_POST['Stud_gender'];
$Stud_bdate=$_POST['Stud_bdate'];
$Stud_year=$_POST['Stud_year'];
$Stud_status=$_POST['Stud_status'];
$Stud_address=$_POST['Stud_address'];

//if(!isset($Stud_no))//$Stud_email,$Stud_contact,$Stud_fname,$Stud_lname,$Stud_course,$Stud_year,$Stud_section,$Stud_gender,$Stud_bdate,$Stud_status,$Stud_address))
		//{
		//	echo 'You need to fill out all fields<br/><br/>';
		//}
		//else{
			$sql="INSERT INTO r_stud_profile (STUD_NO, STUD_FNAME, STUD_MNAME, STUD_LNAME, STUD_COURSE, STUD_YR_LVL, STUD_SECTION, STUD_GENDER, STUD_EMAIL, 
					STUD_CONTACT_NO, STUD_BIRTHDATE, STUD_ADDRESS, STUD_STATUS) 
					VALUES ('$Stud_no', '$Stud_fname', '$Stud_mname', '$Stud_lname', '$Stud_course', '$Stud_year', '$Stud_section', '$Stud_gender', 
					'$Stud_email', '$Stud_contact', '$Stud_bdate', '$Stud_address', '$Stud_status')";
							
							$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
									header('Location: profiling.php');
								}
								else 
								{
									echo "<p>Sira!</p>";
								}
		//						}
?>