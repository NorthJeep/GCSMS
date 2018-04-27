<?php
include("config.php");

$code = $_POST['txtcode'];
$id=$_POST['V_s_ID'];
$no=$_POST['V_s_no'];
$name=$_POST['V_s_name'];
$course=$_POST['V_s_course'];
$details=$_POST['txtdetails'];


$sql="INSERT INTO `t_visits` (VISIT_CODE, STUD_ID, STUD_NO, STUD_NAME, STUD_COURSE, VISIT_DETAILS, VISIT_REMARKS) 
		VALUES ('$code', '$id', '$no', '$name', '$course', '$details', NULL)";
		$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
					
									header('Location: profiling.php');
								}
								else 
								{
									echo "<p>Sira!</p>";
								}

?>