<?php
	include('config.php');
	
	$output = array();
	$sql = "SELECT * FROM r_stud_profile";
	$query=$conn->query($sql);
	while($row=$query->fetch_array()){
		$output[] = $row;
	}

	echo json_encode($output);
?>