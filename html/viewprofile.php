<?php
include("config.php");

$stud_id=$_GET["stud_id"];

$result=mysqli_query($db,"SELECT * FROM `r_stud_profile` where `STUD_ID`='$stud_id'");

$student=mysqli_fetch_object($result);
echo json_encode($student);

?>