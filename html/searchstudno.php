<?php
   $db = mysqli_connect("localhost","root","","g&csms_db");

    
    //get search term
    $searchTerm = $_GET['term'];

$query = $db->query("SELECT `STUD_NO` FROM `r_stud_profile` WHERE `STUD_NO`LIKE '%".$searchTerm."%'");
while ($row = $query->fetch_assoc())
{
       $data[] = $row['STUD_NO'];
}
   
    //return json data
    echo json_encode($data);
?>