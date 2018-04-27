<?php
	include ("config.php");

	session_start();
	if(!$_SESSION['Logged_In'])
	{
		header('Location:LogIn.php');
		exit;
	}
	
		$username = $_POST['USERNAME'];
		$password = $_POST['USER_PASSWORD'];
		
		$query = "SELECT * FROM R_USER where USER_ID = '".$_SESSION['User_ID']."'";
			$result = mysqli_query($connect,$query) or die(mysqli_error());
			if (mysqli_num_rows($result)== 1)
			{
				while($row = mysqli_fetch_assoc($result))
				{
					$user_password = $row['USER_PASSWORD'];
				}
			}
		if($password == $user_password)
		{
			$updatequery = "UPDATE R_USER SET USERNAME = '".$username."',USER_PASSWORD = '".$password."' WHERE USER_ID = '".$_SESSION['User_ID']."'";
			mysqli_query($connect,$updatequery) or die(mysqli_error());	
			header('Location:Profile.php?ID='.$_SESSION['User_ID'].'&user='.$_SESSION['Logged_In']);
		}
		else
		{
			header('Location:TypeSManagement.php?ID='.$_SESSION['User_ID'].'&user='.$_SESSION['Logged_In'].'');
		}
?>


