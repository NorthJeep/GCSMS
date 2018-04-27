<!--<?php
//include("config.php");

/*$C_stud_id = $_POST['_id'];
$C_stud_name = $_POST['_name'];
$C_stud_no = $_POST['_no'];
$C_stud_course = $_POST['_course'];
$C_stud_yr = $_POST['_yr'];
$C_stud_sec = $_POST['_sec'];
$C_stud_email = $_POST['_email'];
$C_stud_add = $_POST['_add'];
$C_stud_cno = $_POST['_cno'];
$C_couns_bg = $_POST['_couns_bg1'];
$C_goals = $_POST['_goals'];
$C_comments = $_POST['_comments'];
$C_recomm = $_POST['_recomm'];
$C_app=$_POST['_app'];
$sql = "INSERT INTO `t_counseling` (`COUNSELING_TYPE_CODE`, `STUD_ID`, `STUD_NO`, `STUD_NAME`, `STUD_COURSE`, 
									`STUD_YR`, `STUD_SECTION`, `STUD_CONTACT`, `STUD_EMAIL`, `STUD_ADDRESS`, `COUNS_APPROACH`, 
									`COUNS_BG`, `COUNS_GOALS`, `COUNS_PREV_TEST`, `COUNS_PREV_PERSON`, `COUNS_COMMENTS`, `COUNS_RECOMM`, 
									`COUNS_APPOINTMENT_TYPE`, `COUNS_DATE`) 
			VALUES ('CT_Indiv', '$C_stud_id','$C_stud_no', '$C_stud_name', '$C_stud_course', '$C_stud_yr', '$C_stud_sec', 
				'$C_stud_cno', '$C_stud_email', '$C_stud_add', '$C_app', '$C_couns_bg', '$C_goals', NULL, NULL, '$C_comments', '$C_recomm', 'Walk-in', CURRENT_TIMESTAMP)";
		$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
									header('Location: counseling_page.php' . $redirect);
								}
								else 
								{
									echo "<p>Sira!</p>";
								}*/
?>-->
<?php

    $user2 = 'root';
    $pass2 = '';
    $dbnm2 = 'g&csms_db';

    try 
    {
        $dbh2 = new PDO('mysql:host=localhost;dbname='.$dbnm2, $user2, $pass2);
    } 
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    $stmt2 = $dbh2->prepare("INSERT INTO t_batch_group(BATCH_APPROACH, BATCH_bg,BATCH_goals,BATCH_comments,BATCH_recomm) VALUES (?,?,?,?,?)");
    $stmt2->bindParam(1, $app_type);
    $stmt2->bindParam(2, $bg);
    $stmt2->bindParam(3, $goals);
    $stmt2->bindParam(4, $comments);
    $stmt2->bindParam(5, $recomm);

    $app_type = $_POST['C_approach'];
    $bg = $_POST['C_couns_bg'];
    $goals = $_POST['C_goals'];
    $comments = $_POST['C_comments'];
    $recomm = $_POST['C_recomm'];
    $stmt2->execute();

    echo $app_type;
    echo $bg;
    echo $goals;
    echo $comments;
    echo $recomm;
    echo "<br>";
    
    $dbh2 = null;
?>


<?php  

    $user = 'root';
    $pass = '';
    $dbnm = 'g&csms_db';

    try 
    {
        $dbh = new PDO('mysql:host=localhost;dbname='.$dbnm, $user, $pass);
    } 
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    $stmt = $dbh->prepare("INSERT INTO t_counseling_group(grp_STUD_NO, grp_STUD_NAME, grp_id) VALUES (?, ?, ?)");
    $stmt->bindParam(1, $num);
    $stmt->bindParam(2, $name);
    $stmt->bindParam(3, $batch_id);


    $arr = $_POST; 
    for($i = 0; $i <= count($arr['stud_no'])-1;$i++)
    {
        $num = $arr['stud_no'][$i];
        $name = $arr['stud_name'][$i];
        $batch_id = $arr['batch_id'];  
        $stmt->execute();

        echo $name;
        echo $num;
        echo $batch_id;    
        echo "<br>";
    }

     header('Location: counseling_page_group.php');

?>