<?php
include("config.php");

$visitCode = $_POST["txtcode"];
$studNo=$_POST["V_s_no"];
$purpose=$_POST["V_s_course"];
$details=$_POST["txtdetails"];


$sql="call stud_visit_add('$visitCode', '$studNo, '$purpose', '$details')";

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