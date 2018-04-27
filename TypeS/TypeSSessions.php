<?php 
	session_start();
	$db = mysqli_connect('localhost', 'root', '', 'g&csms_db');

	// initialize variables
	$userfname = "";
	$usermname = "";
	$userlname = "";
	$userrole = "";
	$username = "";
	$userpassword = "";
	$id = 0;
	$update = false;

	if (isset($_POST['save'])) {
		$userfname = $_POST['USER_FNAME'];
		$usermname = $_POST['USER_MNAME'];
		$userlname = $_POST['USER_LNAME'];
		$userrole = $_POST['USER_ROLE'];
		$username = $_POST['USERNAME'];
		$userpassword = $_POST['USER_PASSWORD'];

		mysqli_query($db, "INSERT INTO R_USER(USER_FNAME,USER_MNAME,USER_LNAME,USER_ROLE,USERNAME,USER_PASSWORD) 
								values('".$userfname."','".$usermname."','".$userlname."','".$userrole."','".$username."','".$userpassword."')");
		$_SESSION['message'] = "Address saved"; 
		header('location: TypeSManagement.php');
	}


	if (isset($_POST['update'])) {
		$id = $_POST['USER_ID'];
		$username = $_POST['USERNAME'];
		$userpassword = $_POST['USER_PASSWORD'];

		mysqli_query($db, "UPDATE R_USER SET USERNAME='$username', USER_PASSWORD='$userpassword' WHERE USER_ID=$id");
		$_SESSION['message'] = "Address updated!"; 
		header('location: TypeSManagement.php');
	}

if (isset($_GET['del'])) {
	$id = $_GET['del'];
	mysqli_query($db, "DELETE FROM R_USER WHERE USER_ID=$id");
	$_SESSION['message'] = "Address deleted!"; 
	header('location: TypeSManagement.php');
}


	$results = mysqli_query($db, "SELECT * FROM R_USER");


?>