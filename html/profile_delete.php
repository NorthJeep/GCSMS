<?php
	include('config.php');
    $data = json_decode(file_get_contents("php://input"));

    $out = array('error' => false);

    $stud_id = $data->stud_id;

   	$sql = "DELETE FROM r_stud_profile WHERE stud_id = '$stud_id'";
   	$query = $conn->query($sql);

   	if($query){
   		$out['message'] = 'Member deleted Successfully';
   	}
   	else{
   		$out['error'] = true;
   		$out['message'] = 'Cannot delete Member';
   	}

    echo json_encode($out);
?>