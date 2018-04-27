<?php
	$conn = new mysqli('localhost', 'root', '', 'g&csms_db');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>