<?php
	session_start();
		include('config.php');

			$studno_ref = $_POST['studno_ref'];
 
			$result  =  mysqli_query($db, "SELECT  *  FROM  r_stud_educ_background  WHERE  Stud_NO_REFERENCE='$studno_ref'") or die (mysqli_error($db));
			$num_rows  =  mysqli_num_rows($result);
 
				if  ($num_rows)  
				{
					header("location:  profiling.php?remarks=failed");
				}
				else
				{
					$studno_ref = $_POST['studno_ref'];
					$nature_schooling = $_POST['nature_schooling'];
		 
					mysqli_query($db, "INSERT INTO r_stud_educ_background (Stud_NO_REFERENCE, Educ_NATURE_OF_SCHOOLING) 
							VALUES ('$studno_ref','$nature_schooling')") or die (mysqli_error($db));
					header("location:  profiling.php?remarks=success");
				}

				mysqli_close($db);
?>