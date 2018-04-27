<?php
	include('conn.php');
    $data = json_decode(file_get_contents("php://input"));

    $out = array('error' => false);

    $stud_firstname = $data->stud_firstname;
    $stud_lastname = $data->stud_lastname;
    $stud_course = $data->stud_course;
    $stud_yr_lvl = $data->stud_yr_lvl;
    $stud_section = $data->stud_section;
    $memid = $data->memid;

   	$sql = "UPDATE members SET stud_firstname = '$stud_firstname', stud_lastname = '$stud_lastname', 
                          stud_course = '$stud_course', stud_yr_lvl = '$stud_yr_lvl', stud_section = '$stud_section' WHERE memid = '$memid'";
   	$query = $conn->query($sql);

   	if($query){
   		$out['message'] = 'Member updated Successfully';
   	}
   	else{
   		$out['error'] = true;
   		$out['message'] = 'Cannot update Member';
   	}

    echo json_encode($out);
?>