<?php
	
$name = $_REQUEST['name'];
$comments = $_REQUEST['comments'];


require("config.php");

mysqli_query($db, "INSERT INTO T_COUNSELING_SERVICE(STUD_ID, STUDENT_NO,STUDENT_NAME,COUNSELING_TYPE, COUNSELING_DETAILS) 
								VALUES('2','2015','MALENE','$name','$comments')");

$result = mysqli_query($db, "SELECT * FROM T_COUNSELING_SERVICE ORDER BY COUNSELING_ID ASC");
while($row=mysqli_fetch_array($result)){
echo "<div class='comments_content'>";
echo "<h4><a href='delete.php?id=" . $row['COUNSELING_ID'] . "'> X</a></h4>";
echo "<h1>" . $row['COUNSELING_TYPE'] . "</h1>";
echo "<h2>" . $row['COUNSELING_DATE'] . "</h2></br></br>";
echo "<h3>" . $row['COUNSELING_DETAILS'] . "</h3>";
echo "</div>";
}
mysqli_close($db);
?>