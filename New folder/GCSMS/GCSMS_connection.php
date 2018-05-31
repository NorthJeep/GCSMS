<?php

	$hostname = "localhost";
    $username = "root";
    $password = "";
    $dbName = "pupqcdb";

     $connect = mysqli_connect($hostname,$username,$password,$dbName);

	    if(!$connect)
	    {
	        echo ' <script>alert("Sorry but you are not connected to the server");</script> ';
	    }

?>