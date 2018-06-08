<?php
 include("config.php");

if(isset($_POST['view']))
{
	if($_POST['view'] != '')
	{
		$UpdateNotifSQL = 'UPDATE t_notification SET Notif_Status=0 WHERE Notif_Status=1';
		$UpdateNotifQuery = mysqli_query($db,$UpdateNotifSQL) or die (mysqli_error($db));
	}


	$NotifOut = '<li>
                    <p class="red">Messages</p>
                </li>';

	$NotifLoadSQL = 'SELECT NOTIF.Notif_Details,
							NOTIF.Notif_Status,
							CONCAT(GUIDE.Counselor_FNAME," ",GUIDE.Counselor_MNAME," ",GUIDE.Counselor_LNAME) AS Name
					FROM t_notification AS NOTIF
					INNER JOIN r_users AS USER
						ON NOTIF.Notif_User = USER.Users_ID
					LEFT JOIN r_guidance_counselor AS GUIDE
						ON USER.Users_REFERENCED = GUIDE.Counselor_CODE
					ORDER BY NOTIF.Notif_ID DESC 
                    LIMIT 5';
	$NotifLoadQuery = mysqli_query($db,$NotifLoadSQL) or die (mysqli_error($db));

	if(mysqli_num_rows($NotifLoadQuery) > 0)
	{
		while($row = mysqli_fetch_assoc($NotifLoadQuery))
		{
			if($row['Notif_Status'] == 1)
			{
				$Status = "Unread";
			}
			else
			{
				$Status = "Read";
			}
			$NotifOut .= '	<li>
							<a href="#">
                                    <span class="subject">
                                    <span class="from">'.$row['Name'].'</span>
                                    <span class="time">'.$Status.'</span>
                                    </span>
                                    <span class="message">
                                        '.$row['Notif_Details'].'
                                    </span>
                        	</a>
                        	</li>';
		}
	}
	else
	{
		$NotifOut .= '<a href="#">
                                <span class="message">
                                    No Notifications Found.
                                </span>
                    </a>';
	}

	$NotifCountSQL = 'SELECT COUNT(Notif_ID) AS NotifCount FROM t_notification WHERE Notif_Status=1';
	$NotifCountQuery = mysqli_query($db,$NotifCountSQL) or die (mysqli_error($db));
	$row = mysqli_fetch_assoc($NotifCountQuery);
	$NotifCount = $row['NotifCount'];

	$DataArray = array(
		'Notification' => $NotifOut,
		'NotificationCount' => $NotifCount
	);

	echo json_encode($DataArray);
}
else
{
	$NotifOut = '<li>
                        <p class="red">Messages</p>
                    </li>
                    <li>
						<a href="#">
                                <span class="message">
                                    No Notifications Found.
                                </span>
                    	</a>
                    </li>';

    $NotifCountSQL = 'SELECT COUNT(Notif_ID) AS NotifCount FROM t_notification WHERE Notif_Status=1';
	$NotifCountQuery = mysqli_query($db,$NotifCountSQL) or die (mysqli_error($db));
	$row = mysqli_fetch_assoc($NotifCountQuery);
	$NotifCount = $row['NotifCount'];

	$DataArray = array(
		'Notification' => $NotifOut,
		'NotificationCount' => $NotifCount
	);

	echo json_encode($DataArray);
}

?>