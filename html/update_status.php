<?php
include("config.php");

$status=$_POST["stud_status"];
$stat_id=$_POST["status_id"];

$sql="UPDATE `r_stud_profile` SET `STUD_STATUS` = '$status' WHERE `r_stud_profile`.`STUD_ID` = '$stat_id'";

$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
									header('Location: profiling.php' . $redirect);
								}
								else 
								{
									echo "<p>Sira!</p>";
								}
?>