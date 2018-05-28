<!DOCTYPE html>
<?php
	session_start();
	if($_SESSION != array())
	{
		header('Location:index.php');
		exit;
	}
?>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Register</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
  <body class="login-body">

    <div class="container">

        <div class="form-signin" >
            <h2 class="form-signin-heading">registration now</h2>
            <div class="login-wrap">
                <p>Enter your personal details below</p>
                <form action="SignUpSession.php" method="POST">
                <input type="text" name="USER_FNAME" class="form-control" placeholder="First Name" required="required" autofocus>
                <input type="text" name="USER_MNAME" class="form-control" placeholder="Middle Name">
                <input type="text" name="USER_LNAME" class="form-control" placeholder="Last Name" required="required">
                <input type="text" name="USERNAME" class="form-control" placeholder="User Name" required="required">
                <input type="password" name="USER_PASSWORD" class="form-control" placeholder="Password" required="required">
                <div class="form-control">
                    <label>
                        Upload a Profile Picture: 
                    </label>
                    <input type="file" accept="image/*"/>
                </div>
                <label class="checkbox">
                    <input type="checkbox" value="agree this condition"> I agree to the Terms of Service and Privacy Policy
                </label>
                <button class="btn btn-lg btn-login btn-block" type="submit" name="Save">Submit</button>
                </form>
                <div class="registration">
                    Already Registered?
                    <a class="" href="login.php">
                        Login
                    </a>
                </div>
            </div>
        </div>

    </div>


    <!-- Placed js at the end of the document so the pages load faster -->

    <!--Core js-->
    <script src="js/jquery.js"></script>
    <script src="bs3/js/bootstrap.min.js"></script>

    </body>
</html>