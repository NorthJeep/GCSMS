<?php
require("config.php");

if(isset($_GET['id'])){
	$id = $_GET['id'];
mysqli_query($db,"DELETE FROM T_COUNSELING_SERVICE WHERE id='$id'");
header("location: counseling1.php");
}
mysqli_close($db);
?>