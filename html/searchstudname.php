<?php
   $db = mysqli_connect("localhost","root","","g&csms_db");

    
    //get search term
    $searchTerm = $_GET['term'];

$query = $db->query("SELECT CONCAT(`STUD_FNAME`,' ',`STUD_MNAME`,' ',`STUD_LNAME`)as Name FROM `r_stud_profile` HAVING Name Like '%".$searchTerm."%'");
while ($row = $query->fetch_assoc())
{
       $data[] = $row['Name'];
}
    //get matched data from skills table
   /* $query = $db->query("SELECT * FROM skills WHERE skill LIKE '%".$searchTerm."%' ORDER BY skill ASC");
    while ($row = $query->fetch_assoc()) {
       
    }*/
    
    //return json data
    echo json_encode($data);
?>