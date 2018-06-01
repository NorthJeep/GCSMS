<?php
include("config.php");

$stud_no = $_POST["Stud_NO"];
$stud_firstname = $_POST["Stud_FNAME"];
$stud_mname = $_POST["Stud_MNAME"];
$stud_lname = $_POST["Stud_LNAME"];
$stud_gender = $_POST["Stud_GENDER"];
$stud_course = $_POST["Stud_COURSE"];
$stud_yr_lvl = $_POST["Stud_YEAR_LEVEL"];
$stud_section = $_POST["Stud_SECTION"];
$stud_bday = $_POST["Stud_BIRTH_DATE"];
$stud_address = $_POST["Stud_CITY_ADDRESS"];
$stud_prov_add = $_POST["Stud_PROVINCIAL_ADDRESS"];
$stud_tel_no = $_POST["Stud_TELEPHONE_NO"];
$stud_cp_no = $_POST["Stud_MOBILE_NO"];
$stud_email = $_POST["Stud_EMAIL"];
$stud_birthplace = $_POST["Stud_BIRTH_PLACE"];
$stud_status = $_POST["Stud_DISPLAY_STATUS"];

//if(!isset($Stud_no))//$Stud_email,$Stud_contact,$Stud_fname,$Stud_lname,$Stud_course,$Stud_year,$Stud_section,$Stud_gender,$Stud_bdate,$Stud_status,$Stud_address))
		//{
		//	echo 'You need to fill out all fields<br/><br/>';
		//}
		//else{
			$qry = "call stud_profile_add('$stud_no','$stud_firstname','$stud_mname', '$stud_lname','$stud_gender','$stud_course','$stud_yr_lvl','$stud_section','$stud_bday', '$stud_address', '$stud_prov_add','$stud_tel_no', '$stud_cp_no', '$stud_email', '$stud_birthplace', '$stud_status')";

							$res = mysqli_query($db,$qry);
								if($res == 1) 
								{
									header('Location: profiling.php');
								}
								else 
								{
									echo "<p>Sira!</p>";
								}
		//						}
?>