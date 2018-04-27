<?php
include('conn.php');

$stud_no=$_POST['stud_number'];

$sql="INSERT INTO practice (student_id) VALUES ('".$stud_no."')";
$result=mysqli_query($conn,$sql);
if($result == 1) 
{
	echo 
	"<p>Nice!</p>";
}
else 
{
	echo "<p>Sira!</p>";
}
?>