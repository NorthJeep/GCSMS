<?php
	include('conn.php');
    $data = json_decode(file_get_contents("php://input"));

    $out = array('error' => false);

    $memid = $data->memid;

   	$sql = "DELETE FROM members WHERE memid = '$memid'";
   	$query = $conn->query($sql);

   	if($query){
   		$out['message'] = 'Student deleted Successfully';
   	}
   	else{
   		$out['error'] = true;
   		$out['message'] = 'Cannot delete Student';
   	}

    echo json_encode($out);
?>