<?php
	
	// $stud_img = $_POST['stud_img'];
	$filename = $_POST['filename'];
	$stud_img_name = $_FILES["imageFile"]["name"];
	$exploded = explode(".", $_FILES["imageFile"]["name"]);
	$DirTarget = "images/".$filename.".".$exploded[1];

	move_uploaded_file($_FILES["imageFile"]["tmp_name"], $DirTarget);

	echo $DirTarget;
?>