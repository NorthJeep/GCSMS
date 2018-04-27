<?php
require("config.php");

if(isset($_GET['counseling_id'])){
	$id = $_GET['counseling_id'];
mysqli_query($con,"DELETE FROM t_counseling_service WHERE id='$id'");
header("location: index.php");
}
mysqli_close($con);
?>