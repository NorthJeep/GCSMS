<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$mysqli = new mysqli("localhost", "root", "", "g&csms_db");
 
// Check connection
if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 
// Escape user inputs for security
$StudentNo = $mysqli->real_escape_string($_REQUEST['Student_Number']);
$FirstName = $mysqli->real_escape_string($_REQUEST['First_Name']);
$MiddleName = $mysqli->real_escape_string($_REQUEST['Middle_Name']);
$LastName = $mysqli->real_escape_string($_REQUEST['Last_Name']);
$Course = $mysqli->real_escape_string($_REQUEST['Course']);
 
// attempt insert query execution
$sql = "INSERT INTO `r_stud_profile` (STUD_NO, STUD_FNAME, STUD_MNAME, STUD_LNAME, STUD_COURSE)
			VALUES ( '$StudentNo', '$FirstName', '$MiddleName', '$LastName', '$Course')";
if($mysqli->query($sql) === true){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
 
// Close connection
$mysqli->close();
?>
