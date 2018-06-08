<?php
session_start();
include("config.php");

	$NotifUser = $_SESSION['ID'];
	$NotifDetail = mysqli_real_escape_string($db, $_POST['NotifDetail']);

	// echo($NotifUser);
	// echo($NotifDetail);
	$NotifPostSQL = 'INSERT INTO t_notification(Notif_User,Notif_Details) VALUES ('.$NotifUser.',"'.$NotifDetail.'")';
	$NotifPostQuery = mysqli_query($db,$NotifPostSQL) or die (mysqli_error($db));
?>