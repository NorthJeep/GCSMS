<!DOCTYPE html>
<?php
    session_start();
    if(!$_SESSION['Logged_In'])
    {
        header('Location:LogIn.php');
        exit;
    }
    include ("config.php");
?>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>G&CSMS-Counseling Service</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->

<!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" href="css/jquery.steps.css?1">

    <!-- Custom styles for this template -->
    <link href="css/style-responsive.css" rel="stylesheet" />

</head>
<body>
<?php 
$currentPage ='G&CSMS-SysConfig';
include('TypeSHeader.php');
include('TypeSNavBar.php');
?>
    <!--main content start-->

    <section id="main-content">
        <section class="wrapper">
         
<div class="row">
    <div class="col-md-5">
        <div class="mini-stat clearfix">
            <span class="mini-stat-icon orange"><i class="fa  fa-wrench"></i></span>
            <h2>options</h2>
            <br>                             </br>
            <a href="#mycousemodal" data-toggle="modal" class="btn btn-success"><i class =" fa fa-plus-square"></i> edit course</a>
            <br>                             </br>
            <a href="#myyrlvlmodal" data-toggle="modal" class="btn btn-success"><i class =" fa fa-plus-square"></i> edit Year Level</a>
            </div>
        </div>
    </div>

                   
             </div> 
                    </div>
                </div>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mycousemodal" class="modal fade" method="Post" >
                    <form role="form" action="syscon.php" method="post">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Add Course</h4>
                                    <div class="form-group">
                                        <form role="form">
                                             <?php
$db = mysqli_connect("localhost", "root", "", "g&csms_db");

/* check connection */
$sql= mysqli_query($db, "SELECT `course` FROM `sys_con_drp` WHERE `course` != ' '");?>
<label>Courses</label>
<select class="form-control" style="width: 500px">
<?php
    while ($row = mysqli_fetch_array($sql))
    {
        $course= $row['course'];
echo"<option value ='$course'>$course</option>";
        }?>
                        <input type="text" class="form-control" name="course" id="coursename">
                                        </div>                               
                                 <button type="submit" class="btn btn-success" name="addcourse" id="addcourse">Submit</button>
                                </div>
                            </form>
               

<?php
$mysqli = new mysqli("localhost", "root", "", "g&csms_db");
 

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 

if(isset($_POST['addcourse']))
{
$course = $mysqli->real_escape_string($_POST['course']);


 

$sql = "INSERT INTO `sys_con_drp`(`course`) VALUES ('$course')";
if($mysqli->query($sql) === true){
    echo "<script type='text/javascript'>window.alert('Succesfully Updated');
    window.location.href='http://localhost:78/GCSMS-clone/syscon.php'</script>";   
    
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
}

$mysqli->close();

?>


                <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Course
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th hidden>sys ID</th>
                                    <th>course</th>
                                    <th>delete</th>
                                </tr>
                                </thead>
<?php

$conn = mysqli_connect("localhost","root","","g&csms_db");

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql =  mysqli_query ($conn,"SELECT `sys_id`,`course`  FROM `sys_con_drp` WHERE `course` != ' '");?>

<?php while ($row = mysqli_fetch_assoc($sql)) { ?>
<form role="form" action="syscon.php" method="post">
        <tbody>
        <tr>
            <td hidden><?php echo $row['sys_id']; ?></td>
            <td><?php echo $row['course']; ?></td>
<td><button type="submit" class="btn btn-info" value= "<?php echo $row['sys_id']; ?>" name="delcourse" id="delcourse">Delete</button></td>
        </tr>
    <?php }?>
    </tbody>
</table>
</form>
   </section>     
         </div>
<?php
$mysqli = new mysqli("localhost", "root", "", "g&csms_db");
 

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 

if(isset($_POST['delcourse']))
{
$course = $mysqli->real_escape_string($_POST['delcourse']);


 

$sql = "DELETE FROM `sys_con_drp` WHERE `sys_id` = '$course'";
if($mysqli->query($sql) === true){
    echo "<script type='text/javascript'>window.alert('Succesfully Updated');
    window.location.href='http://localhost:78/GCSMS-clone/syscon.php'</script>";   
    
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
}

$mysqli->close();

?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</table>
</section>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myyrlvlmodal" class="modal fade" method="Post" >
                    <form role="form" action="syscon.php" method="post">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Year Level information</h4>
                                    <div class="form-group">
                                        <form role="form">
                                             <?php
                            $db = mysqli_connect("localhost", "root", "", "g&csms_db");

/* check connection */
$sql= mysqli_query($db, "SELECT`year_sec` FROM `sys_con_drp` WHERE `year_sec` != ' '");?>

<label>Courses</label>
<select class="form-control" style="width: 500px">
<?php
    while ($row = mysqli_fetch_array($sql))
    {
        $yearsec= $row['year_sec'];
echo"<option value ='$yearsec'>$yearsec</option>";
        }?>
                        <input type="text" class="form-control" name="yrl_lvl" id="yr_lvl">
                                        </div>                               
                                 <button type="submit" class="btn btn-success" name="addyrlvl" id="addyrlvl">Submit</button>
                                </div>
                            </form>
<?php
$mysqli = new mysqli("localhost", "root", "", "g&csms_db");
 

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 

if(isset($_POST['addyrlvl']))
{
$course = $mysqli->real_escape_string($_POST['yrl_lvl']);


 

$sql = "INSERT INTO `sys_con_drp`(`year_sec`) VALUES ('$course')";
if($mysqli->query($sql) === true){
    echo "<script type='text/javascript'>window.alert('Succesfully Updated');
    window.location.href='http://localhost:78/GCSMS-clone/syscon.php'</script>";   
    
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
}

$mysqli->close();

?>
                <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Year lvl Information
                    </header>
                    <div class="panel-body">
                        <section id="unseen">
                            <table class="table table-bordered table-striped table-condensed">
                                <thead>
                                <tr>
                                    <th hidden>sys ID</th>
                                    <th>Year Level</th>
                                    <th>delete</th>
                                </tr>
                                </thead>
<?php

$conn = mysqli_connect("localhost","root","","g&csms_db");

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql =  mysqli_query ($conn,"SELECT `sys_id`,`year_sec`  FROM `sys_con_drp` WHERE `year_sec` != ' '");?>

<?php while ($row = mysqli_fetch_assoc($sql)) { ?>
<form role="form" action="syscon.php" method="post">
        <tbody>
        <tr>
            <td hidden><?php echo $row['sys_id']; ?></td>
            <td><?php echo $row['year_sec']; ?></td>
<td><button type="submit" class="btn btn-info" value= "<?php echo $row['sys_id']; ?>" name="delyrl" id="delyrl">Delete</button></td>
        </tr>
    <?php }?>
    </tbody>
</table>
</form>
   </section>     
         </div>
<?php
$mysqli = new mysqli("localhost", "root", "", "g&csms_db");
 

if($mysqli === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
 

if(isset($_POST['delyrl']))
{
$yrlvl = $mysqli->real_escape_string($_POST['delyrl']);


 

$sql = "DELETE FROM `sys_con_drp` WHERE `sys_id` = '$yrlvl'";
if($mysqli->query($sql) === true){
    echo "<script type='text/javascript'>window.alert('Succesfully Updated');
    window.location.href='http://localhost:78/GCSMS-clone/syscon.php'</script>";   
    
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
}

$mysqli->close();

?>


 </section>
</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/jquery-steps/jquery.steps.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script>

<script src="js/iCheck/jquery.icheck.js"></script>

<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>

<!--common script init for all pages-->
<script src="js/scripts.js"></script>

<!--icheck init -->
<script src="js/icheck-init.js"></script>

<!--common script init for all pages-->
<script src="js/scripts.js"></script>
<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>

<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>


</body>
</html>
