<?php
	session_start();
		include('config.php');

			$family_parent=$_POST['family_parent'];

			if($family_parent == "Guardian")
			{
				$studno_ref = $_POST['studno_ref'];

				$result  =  mysqli_query($db, "SELECT  *  FROM  r_stud_guardian  WHERE  Stud_NO='$studno_ref'") or die (mysqli_error($db));
				$num_rows  =  mysqli_num_rows($result);
 
				if  ($num_rows)  
				{
					header("location:  profiling.php?remarks=failed");
				}
				else
				{
					$studno_ref = $_POST['studno_ref'];
					$fam_fname =$_POST['fam_fname'];
					$fam_mname =$_POST['fam_mname'];
					$fam_lname =$_POST['fam_lname'];
					$fam_age =$_POST['fam_age'];
					$relation =$_POST['relation'];
					$fam_lifestats =$_POST['fam_lifestats'];
					$fam_educattain =$_POST['fam_educattain'];
					$fam_occu =$_POST['fam_occu'];
					$fam_employname =$_POST['fam_employname'];
					$fam_employadd =$_POST['fam_employadd'];
										
					mysqli_query($db, "INSERT  INTO  r_stud_guardian(Stud_NO, Guardian_FNAME, Guardian_MNAME, Guardian_LNAME, Guardian_AGE, Stud_GUARDIAN_RELATION, Guardian_EDUC_ATTAINMENT, Guardian_OCCUPATION, Guardian_EMPLOYER_NAME, Guardian_EMPLOYER_ADDRESS)
						VALUES('$studno_ref', '$fam_fname', '$fam_mname', '$fam_lname', '$fam_age', '$fam_lifestats', '$fam_educattain', '$fam_occu', '$fam_employname', '$fam_employadd')");
					header("location:  profiling.php?remarks=success");
				}
			}
			else{
				$family_parent = $_POST['family_parent'];

				$result  =  mysqli_query($db, "SELECT  *  FROM  r_stud_family_bg_details  WHERE  FamilyBG_INFO='$family_parent'") or die (mysqli_error($db));
				$num_rows  =  mysqli_num_rows($result);
 
				if  ($num_rows)  
				{
					header("location:  profiling.php?remarks=failed");
				}
				else
				{
					$studno_ref = $_POST['studno_ref'];
					$family_parent = $_POST['family_parent'];
					$fam_fname =$_POST['fam_fname'];
					$fam_mname =$_POST['fam_mname'];
					$fam_lname =$_POST['fam_lname'];
					$fam_age =$_POST['fam_age'];
					$fam_lifestats =$_POST['fam_lifestats'];
					$fam_educattain =$_POST['fam_educattain'];
					$fam_occu =$_POST['fam_occu'];
					$fam_employname =$_POST['fam_employname'];
					$fam_employadd =$_POST['fam_employadd'];
										
					mysqli_query($db, "INSERT  INTO  r_stud_family_bg_details(Stud_NO_REFERENCE, FamilyBG_INFO, Info_FNAME, Info_MNAME, Info_LNAME, Info_AGE, Info_STAT, Info_EDUC_ATTAINMENT, Info_OCCUPATION, Info_EMPLOYER_NAME, Info_EMPLOYER_ADDRESS)
						VALUES('$studno_ref', '$family_parent', '$fam_fname', '$fam_mname', '$fam_lname', '$fam_age', '$fam_lifestats', '$fam_educattain', '$fam_occu', '$fam_employname', '$fam_employadd')") or die (mysqli_error($db));
					header("location:  profiling.php?remarks=success");
				}
			}
?>