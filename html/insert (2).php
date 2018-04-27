<?php
	
$stud_id = $_REQUEST['stud_id'];
$student_no = $_REQUEST['stud_no']	
$name = $_REQUEST['name'];
$type = $_REQUEST['type'];
$comments = $_REQUEST['comments'];


require("config.php");

mysqli_query($db, "INSERT INTO t_counseling_service(stud_id,student_no,student_name,counseling_type, counseling_details) 
							VALUES('$stud_id','$student_no','$name','$type', $comments')");

$result = mysqli_query($db, "SELECT * FROM comments ORDER BY counseling_id ASC");
while($row=mysqli_fetch_array($result)){
echo "<div class='comments_content'>";
echo "<h4><a href='delete.php?id=" . $row['counseling_id'] . "'> X</a></h4>";
echo "<h1>" . $row['student_name'] . "</h1>";
echo "<h2>" . $row['counseling_date'] . "</h2></br></br>";
echo "<h3>" . $row['counseling_details'] . "</h3>";
echo "</div>";
}
mysqli_close($con);
?>