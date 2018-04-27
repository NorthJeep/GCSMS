<?php
	include ("config.php");

		$userfname = $_POST['USER_FNAME'];
		$usermname = $_POST['USER_MNAME'];
		$userlname = $_POST['USER_LNAME'];
		$username = $_POST['USERNAME'];
		$userpassword = $_POST['USER_PASSWORD'];
		
		if(!isset($userfname,$usermname,$userlname,$username,$userpassword))
		{
			echo 'You need to fill out all fields<br/><br/>';
		}
		else
		{
			$checkquery = "SELECT * FROM R_USER WHERE USERNAME = '".$username."' AND USER_ROLE ='".$userrole."'"; 
			$checkdb = mysqli_query($db,$checkquery) or die(mysqli_error());
			if  (mysqli_num_rows($checkdb) > 0)
			{
				header('Location:/Fail.php');
				exit;
			}
			else
			{
				$query = "INSERT INTO R_USER(USER_FNAME,USER_MNAME,USER_LNAME,USER_ROLE,USERNAME,USER_PASSWORD) 
								values('".$userfname."','".$usermname."','".$userlname."','Student Assistant','".$username."','".$userpassword."')";
				$result = mysqli_query($db,$query) or die(mysqli_error());
				if ($result == 1)
				{
					echo
						header('Location: index.php');
				}
				else
				{
					echo   'There is an error in the database<br/><br/>';
					print_r($result);
				}
			}
			
		}
		
?>


