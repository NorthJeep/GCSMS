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

    <title>G&CSMS-Students Profiles</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" href="css/jquery.steps.css?1">

    <!-- Custom styles for this template -->
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!--Intellisence-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           

</head>
<body>
<?php 
$currentPage ='G&CSMS-Profiling';
include('header.php');
include('sidebarnav.php');
?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
        <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="#"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-user"></i> Profiling</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Students Profile
                    </header>
                    <div class="panel-body">
                    <div>
                    <button data-toggle="modal" href="#Add" class="btn btn-primary">
                                <i class="fa fa-plus"></i>   Add</button>
                    <button href="#ImportModal" data-toggle="modal" class="btn btn-warning">
                                <i class="fa fa-plus"></i> Import</button>
                    </div>

                    <br></br>

                    <!--REMARKS PARA SA MODAL BACKGROUND CHENES-->
                    
                    <?php 
                        $remarks  =  isset($_GET['remarks'])  ?  $_GET['remarks']  :  '';

                            if  ($remarks=='failed')
                                {
                                    echo    '<div class="alert alert-block alert-danger fade in">
                                                <button data-dismiss="alert" class="close close-sm" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <h4>
                                                    <i class="icon-ok-sign"></i>
                                                    <b>Oh snap!</b>
                                                </h4>
                                                <p> MAY GANYANG RECORD NA SIYA SA DATABASE!<p>
                                            </div>';
                                }

                            if  ($remarks=='failed2')
                                {
                                    echo    '<div class="alert alert-block alert-danger fade in">
                                                <button data-dismiss="alert" class="close close-sm" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <h4>
                                                    <i class="icon-ok-sign"></i>
                                                    <b>Oh snap!</b>
                                                </h4>
                                                <p> GAWIN MUNA YUNG NATURE OF SCHOOLING!<p>
                                            </div>';
                                }

                            if  ($remarks=='success')
                                {
                                    echo    '<div class="alert alert-success alert-block fade in">
                                                <button data-dismiss="alert" class="close close-sm" type="button">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                                <h4>
                                                    <i class="icon-ok-sign"></i>
                                                    <b>Success!</b>
                                                </h4>
                                                <p>AYIIIEEE NA SAVE NA!</p>
                                            </div>';
                                }
                    ?>

                    <!--REMARKS PARA SA MODAL BACKGROUND CHENES-->
                    
                    <div class="adv-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Student Number</th>
                        <th class="hidden-phone">Student Name</th>
                        <th class="hidden-phone">Course/Year/Section</th>
                        <th class="hidden-phone">Status</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                    <?php
include('config.php');

  $sql= "SELECT * FROM student_profiling";

$query = mysqli_query($db, $sql);

if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_assoc($query)) 
    {
                // $ID =$row['ID'];
                $NO =$row['STUD_NO'];
                $FULLNAME=$row['FULLNAME'];
                $COURSE=$row['COURSE'];
                $STATUS=$row['STUD_STATUS'];

                  ?>

                    <tr>
                    <td><?php echo $NO; ?></td>
                    <td><?php echo $FULLNAME; ?></td>
                    <td><?php echo $COURSE; ?></td>
                    <td><?php echo $STATUS; ?></td>
                    <td><button class="btn btn-primary action-button stud_id" name="view" value="View" data-toggle="modal" href="#myModal<?php echo $NO; ?>" />
                    <i class="fa fa-eye"> View</i></button></td>  
               </tr>
                    </tfoot>
                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
                <!-- Modal -->
                            <div class="modal fade" id="myModal<?php echo $NO; ?>" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#07847d; color:#fff">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Student Details</h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class='twt-feed' style="background-color:#07847d; padding:15px;">
                                        <div class="row">
                                            <div class="col-md-4 modal-con">

                                                <input type="text" class="imgText" id="stud-img-id<?php echo $NO; ?>" name="stud_img_id" style="display:none" value="<?php echo $NO;?>">
                                                <input type="file" class="imgupload" style="display:none" accept="image/*" onchange="showMyImage(this)" name="stud_img"/> 
                                                <?php 
                                                    if(file_exists("images/".$NO.".png"))
                                                    {
                                                        echo ' <img src="images/'.$NO.'.png" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                    elseif(file_exists("images/".$NO.".jpg"))
                                                    {
                                                        echo ' <img src="images/'.$NO.'.jpg" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                    else
                                                    {
                                                        echo ' <img src="images\user.ico" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                ?>

                                               


                                                <h3><span id="FULLNAME">  <?php echo $FULLNAME?></span></h3>
                                                <h5> <?php echo $NO; ?> </h5>
                                                <h5> <?php echo $COURSE; ?> </h5>
                                                Status: 
                                                <form class="form-inline" method="POST" action="update_status.php">
                                                <div>
                                                <input type="text" id="status_id" name="status_id" style="display:none" value="<?php echo $NO;?>">
                                                <input type="text" id="stud_status" name="stud_status" 
                                                class="form-control" style="width:80px" placeholder="<?php echo $STATUS; ?>" disabled> 
                                                <button type="submit" class="btn btn-success" name="ok_status" id="ok_status" style="display:none"><i class="fa fa-check"></i>
                                                </form>
                                                <button class="btn btn-warning" id="edit_status"onclick="EditStatus()"><i class="fa fa-pencil"></i></button>
                                                </div>
                                            </div>
                                        <div class="col-md-8">
                                            <blockquote style="background-color:#03605b; height:100px;">
                                                <h4>Sanction:</h4>
                                                <span class="label label-warning"><i class="fa fa-exclamation"></i> Warning: 18hrs</span>
                                            </blockquote>
                                            <blockquote style="background-color:#03605b; height:150px">
                                                <h4>Counseling Remarks:</h4>
                                                <h5 id="remarkstxt" name="remarkstxt">Follow Up</h5>
                                                <br/>
                                                <form method="POST" action="counseling_services.php">
                                                  <input type="text" id="student_no" name="student_no" style="display:none;" value="<?php echo $NO; ?>"></p>
                                                  <input type="text" id="student_name" name="student_name" style="display:none;" value="<?php echo $FULLNAME; ?>"></p>
                                            <button type="submit" class="btn btn-success" href="counseling_services.php"><i class="fa fa-edit"></i> Start Counseling</button>
                                            </form>
                                            <button class="btn btn-info" id="viewCouns" href="visit_logs.php"><i class="fa fa-eye"></i> View History</button>
                                            </blockquote>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="panel-group" id="accordion">
                                          <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading"  style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color:#FFF">
                                                Visits</a>
                                              </h4>
                                            </div>
                                            <div id="collapse1" class="panel-collapse collapse in">
                                              <div class="panel-body">
                                              <form action="addVisit.php" method="POST">
                                                  <input type="text" id="V_s_ID" name="V_s_ID" style="display:none;" value="<?php echo $ID; ?>"></p>
                                                  <input type="text" id="V_s_name" name="V_s_name" style="display:none;" value="<?php echo $FULLNAME; ?>"></p>
                                                  <input type="text" id="V_s_no" name="V_s_no" style="display:none;" value="<?php echo $NO; ?>"></p>
                                                  <input type="text" id="V_s_course" name="V_s_course" style="display:none;"value="<?php echo $COURSE; ?>"></p>
                                                    <div class="col-lg-5">
                                                    <select style="width:180px" name="txtcode" id="e9" class="populate" required="">
                                    
                                        <option value="Excuse">Excuse Letter</option>
                                        <option value="CoC">Cert of Candidacy</option>
                                        <option value="Clearance">Clearance</option>
                                    </select>    
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input type="text" id="txtdetails" name="txtdetails" class="form-control col-lg-2" placeholder="Other Details...">
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <button type="submit" class="btn btn-success" value=""><i class="fa fa-plus"></button></i>
                                                    </div>
                                              </form>
                                                    <div class="col-lg-1">
                                                        <button type="" class="btn btn-primary" id="viewVisit"><i class="fa fa-eye"></button></i>
                                                    </div>
                                              </div>
                                            </div>
                                          </div>

                                        <!----------B      R        E       A       K-------->

                                    <!--START NG EDUCATIONAL BACKGROUND-->
                                        <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading" style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a style="color:#FFF">
                                                Educational Background</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <!--Button-->
                                                    <button type="submit" class="btn btn-success" value="" data-toggle="collapse" data-parent="#accordion" href="#collapse3" ><i class="fa fa-plus"></button></i>

                                                    &nbsp;&nbsp;

                                                    <button type="" class="btn btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse4" ><i class="fa fa-eye"></button></i>
                                                <!--Button-->
                                              </h4>
                                            </div>
                                            <!--START NG LAMAN NG ADD/UPDATE-->
                                                <div id="collapse3" class="panel-collapse collapse">
                                                    <!--LAMAN  NG EDUCATIONAL BACKGROUND START-->
                                                            <div class="panel-body">

                                                                <!--START PART 1-->
                                                                    <form name="educ_bg"  action="educational_background.php"  onsubmit="return  validateForm()"  method="post">

                                                                    <!--<input name="studno_ref" value="$NO" hidden=""></input>-->

                                                                    <?php echo '<input name="studno_ref" value="'.$NO.'" hidden=""></input>'?>

                                                                    <!--nature of schooling-->
                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Nature of Schooling:</label>
                                                                        <div class="col-lg-7">
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio" name="nature_schooling" id="continous" value="Continous" onclick="disablewhy()" required="">Continous
                                                                                </label>
                                                                                &nbsp;&nbsp;
                                                                                <label>
                                                                                    <input type="radio" name="nature_schooling" id="interrupted" value="Interrupted" onclick="enablewhy()">Interrupted
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <script language="javascript" type="text/javascript">
                                                                        function disablewhy(){
                                                                            document.getElementById('why').disabled = true;
                                                                        }

                                                                        function enablewhy(){
                                                                            document.getElementById('why').disabled = false;
                                                                        }
                                                                    </script>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Why?:</label>
                                                                        <div class="col-sm-10">
                                                                            <textarea class="form-control" rows="3" disabled="" id="why" name="nature_schooling" required=""></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <!--nature of schooling-->

                                                                    <br></br>
                                                                    <br></br>

                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-success" type="submit" name="submit">Save</button>
                                                                    </div>
                                                                    </form>

                                                                <!--END PART 1-->

                                                                <!--START PART 2-->
                                                                    <form name="educ_bgdt"  action="educational_backgrounddetails.php"  onsubmit="return  validateForm()"  method="post">

                                                                    <?php
                                                                        $sql_educ_id = "SELECT * FROM r_stud_educ_background WHERE Stud_NO_REFERENCE ='$NO'";
                                                                        $query_educ_id = mysqli_query($db, $sql_educ_id);
                                                                        $row_educ_id = mysqli_fetch_assoc($query_educ_id);
                                                                        $educ_id = $row_educ_id['Educ_BG_ID'];

                                                                        echo '<input name="educ_id_ref" value="'.$educ_id.'" hidden=""></input>';
                                                                    ?>

                                                                    <div class="form-group">
                                                                        <div class="col-lg-12">
                                                                        <!--SELECTION OF GAGAWAN NG RECORD-->
                                                                            <select class="form-control m-bot15" name="educ_level" id="educ_level" required="">
                                                                                <option value="Pre-Elementary">Pre-Elementary</option>
                                                                                <option value="Elementary">Elementary</option>
                                                                                <option value="High School">High School</option>
                                                                                <option value="Vocational">Vocational</option>
                                                                                <option value="College if any">College if any</option>
                                                                            </select>
                                                                        <!--SELECTION OF GAGAWAN NG RECORD-->
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">School Graduated:</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" name="schoolgrad" id="schoolgrad" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">School Address:</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" name="schooladd" id="schooladd" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <div class="col-lg-12">
                                                                            <select class="form-control m-bot15" name="schooltype" id="schooltype" required="">
                                                                                <option value="Public">Public</option>
                                                                                <option value="Public">Private</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">Dates of Attendance:</label>
                                                                        <div class="col-sm-9">
                                                                            <textarea class="form-control" rows="2" name="datesofattendance" id="datesofattendance" required=""></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <br></br>
                                                                    &nbsp;

                                                                    <div class="form-group">
                                                                        <label class="col-sm-3 control-label">Honors Received/Special Awards:</label>
                                                                        <div class="col-sm-9">
                                                                            <textarea class="form-control" rows="3" name="awards" id="awards" required=""></textarea>
                                                                        </div>
                                                                    </div>

                                                                    <br></br>
                                                                    <br></br>

                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-success" type="submit" name="submit">Save</button>
                                                                    </div>

                                                                    </form>
                                                                <!--END PART 2-->
                                                            </div>
                                                    <!--LAMAN NG EDUCATIONAL BACKGROUND END-->
                                                </div>
                                            <!--END NG LAMAN NG ADD/UPDATE-->

                                        </div>
                                    <!--END NG EDUCATIONAL BACKGROUND-->

                                    <!----------B      R       E       A       K-------->

                                    <!--START NG HOME AND FAMILY BACKGROUND-->
                                        <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading" style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a style="color:#FFF">
                                                Home and Family Background</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <!--Button-->
                                                    <button type="submit" class="btn btn-success" value="" data-toggle="collapse" data-parent="#accordion" href="#collapse5" ><i class="fa fa-plus"></button></i>

                                                    &nbsp;&nbsp;

                                                    <button type="" class="btn btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse6" ><i class="fa fa-eye"></button></i>
                                                <!--Button-->
                                              </h4>
                                            </div>
                                            <!--START NG LAMAN NG ADD/UPDATE-->
                                                <div id="collapse5" class="panel-collapse collapse">
                                                    <!--LAMAN NG HOME AND FAMILY BACKGROUND START-->
                                                            <div class="panel-body">

                                                                <!--START PART 1-->
                                                            
                                                                <form name="homefam_bg"  action="homeandfamily_background.php"  onsubmit="return  validateForm()"  method="post">

                                                                    <?php echo '<input name="studno_ref" value="'.$NO.'" hidden=""></input>'?>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Name of:</label>
                                                                        <div class="col-sm-10">    
                                                                            <select class="form-control m-bot15" name="family_parent" id="family_parent" required="">
                                                                                <option value="Mother">Mother</option>
                                                                                <option value="Father">Father</option>
                                                                                <option value="Guardian">Guardian</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" name="fam_fname" id="fam_fname" placeholder="First Name" required="">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" name="fam_mname" id="fam_mname" placeholder="Middle Name" required="">
                                                                        </div>
                                                                        <div class="col-sm-4">
                                                                            <input type="text" class="form-control" name="fam_lname" id="fam_lname" placeholder="Last Name" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <script language="javascript" type="text/javascript">
                                                                        $(document).ready(function () {
                                                                            $("#family_parent").change(function () {
                                                                                if ($(this).find("option:selected").val() == "Guardian") {
                                                                                    $("#relation").removeAttr("disabled")
                                                                                } else {
                                                                                    $("#relation").attr("disabled","disabled")
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>

                                                                    <script language="javascript" type="text/javascript">
                                                                        $(document).ready(function () {
                                                                            $("#family_parent").change(function () {
                                                                                if ($(this).find("option:selected").val() == "Guardian") {
                                                                                    $("#lifestats").attr("disabled","disabled")
                                                                                    && $("#lifestats2").attr("disabled","disabled")
                                                                                } else {
                                                                                    $("#lifestats").removeAttr("disabled") 
                                                                                    && $("#lifestats2").removeAttr("disabled")
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-4 control-label">Relation (for Guardian only):</label>
                                                                        <div class="col-sm-8">
                                                                            <input type="text" class="form-control" disabled="" id="relation" name="relation" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Age:</label>
                                                                        <div class="radio">
                                                                            <!--AGE TEXT INPUT-->
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" class="form-control" name="fam_age" id="fam_age">
                                                                                </div>
                                                                            <!--AGE TEXT INPUT-->
                                                                            &nbsp;&nbsp;
                                                                            <label class="col-sm-4 control-label">For parents only:</label>
                                                                            <label>
                                                                                <input type="radio" name="fam_lifestats" id="lifestats" value="Living" required="">Living
                                                                            </label>
                                                                            &nbsp;&nbsp;
                                                                            <label>
                                                                                <input type="radio" name="fam_lifestats" id="lifestats2" value="Deceased">Deceased
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Educational Attainment:</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" name="fam_educattain" id="fam_educattain" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Occupation:</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" name="fam_occu" id="fam_occu" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Name of Employer:</label>
                                                                        <div class="col-sm-10">
                                                                            <input type="text" class="form-control" name="fam_employname" id="fam_employname" required="">
                                                                        </div>
                                                                    </div>

                                                                    <br></br>

                                                                    <div class="form-group">
                                                                        <label class="col-sm-2 control-label">Address of Employer:</label>
                                                                        <div class="col-sm-10">
                                                                            <textarea class="form-control" rows="4" name="fam_employadd" id="fam_employadd" required=""></textarea>
                                                                        </div>
                                                                    </div>

                                                                <br></br>
                                                                <br></br>
                                                                <br></br>

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="submit">Save</button>
                                                                </div>

                                                                </form>
                                                                
                                                                <!--END PART 1-->

                                                            </div>
                                                    <!--LAMAN NG HOME AND FAMILY BACKGROUND END-->
                                                </div>
                                            <!--END NG LAMAN NG ADD/UPDATE-->
                                        </div>
                                    <!--END NG HOME AND FAMILY BACKGROUND-->

                                    <!----------B      R        E       A       K-------->

                                    <!--START NG HEALTH-->
                                        <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading" style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a style="color:#FFF">
                                                Health</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <!--Button-->
                                                    <button type="submit" class="btn btn-success" value="" data-toggle="collapse" data-parent="#accordion" href="#collapse7" ><i class="fa fa-plus"></button></i>

                                                    &nbsp;&nbsp;

                                                    <button type="" class="btn btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse8" ><i class="fa fa-eye"></button></i>
                                                <!--Button-->
                                              </h4>
                                            </div>
                                            <!--START NG LAMAN NG ADD/UPDATE-->
                                                <div id="collapse7" class="panel-collapse collapse">
                                                    <!--LAMAN NG HEALTH START-->
                                                        <form name="health_bg"  action="health_background.php"  onsubmit="return  validateForm()"  method="post">
                                                            <div class="panel-body">

                                                                <!--START NG PHYSICAL-->
                                                                    <div class="panel panel-default" style=" padding-top:5px;">
                                                                        <div class="panel-heading" style="background-color:#07847d">
                                                                          <h4 class="panel-title">
                                                                            <a data-toggle="collapse" href="#physical" style="color:#FFF">
                                                                            Physical</a>
                                                                          </h4>
                                                                        </div>
                                                                        <div id="physical" class="panel-collapse collapse">
                                                                            <!--LAMAN START-->
                                                                                <div class="panel-body">

                                                                                    <!--vision-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Do you have problems with your vision</label>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="form-control m-bot15" name="vision" id="vision" onchange="enableothers()">
                                                                                                <option value="No">No</option>     
                                                                                                <option value="yes2">Yes</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        $(document).ready(function () {
                                                                                            $("#vision").change(function () {
                                                                                                if ($(this).find("option:selected").val() == "yes2") {
                                                                                                    $("#yep2").removeAttr("disabled")
                                                                                                } else {
                                                                                                    $("#yep2").attr("disabled","disabled")
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>

                                                                                    <br></br>

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="yep2" name="vision_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--vision-->

                                                                                    <br></br>

                                                                                    <!--hearing-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Do you have problems with your hearing?</label>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="form-control m-bot15" name="hearing" id="hearing" onchange="enableothers()">
                                                                                                <option value="No">No</option>   
                                                                                                <option value="yes3">Yes</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        $(document).ready(function () {
                                                                                            $("#hearing").change(function () {
                                                                                                if ($(this).find("option:selected").val() == "yes3") {
                                                                                                    $("#yep3").removeAttr("disabled")
                                                                                                } else {
                                                                                                    $("#yep3").attr("disabled","disabled")
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>

                                                                                    <br></br>

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="yep3" name="hearing_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--hearing-->

                                                                                    <br></br>

                                                                                    <!--speech-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Do you have problems with your speech?</label>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="form-control m-bot15" name="speech" id="speech" onchange="enableothers()">
                                                                                                <option value="No">No</option>   
                                                                                                <option value="yes4">Yes</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        $(document).ready(function () {
                                                                                            $("#speech").change(function () {
                                                                                                if ($(this).find("option:selected").val() == "yes4") {
                                                                                                    $("#yep4").removeAttr("disabled")
                                                                                                } else {
                                                                                                    $("#yep4").attr("disabled","disabled")
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>

                                                                                    <br></br>

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="yep4" name="speech_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--speech-->

                                                                                    <br></br>

                                                                                    <!--genhealth-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Do you have problems with your general health?</label>
                                                                                        <div class="col-lg-7">
                                                                                            <select class="form-control m-bot15" name="generalhealth" id="generalhealth" onchange="enableothers()">
                                                                                                <option value="No">No</option>   
                                                                                                <option value="yes5">Yes</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        $(document).ready(function () {
                                                                                            $("#generalhealth").change(function () {
                                                                                                if ($(this).find("option:selected").val() == "yes5") {
                                                                                                    $("#yep5").removeAttr("disabled")
                                                                                                } else {
                                                                                                    $("#yep5").attr("disabled","disabled")
                                                                                                }
                                                                                            });
                                                                                        });
                                                                                    </script>

                                                                                    <br></br>

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="yep5" name="generalhealth_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--genhealth-->
                                                                                    
                                                                                </div>
                                                                            <!--LAMAN END-->
                                                                        </div>
                                                                    </div>
                                                                <!--END NG PHYSICAL-->

                                                                <!--START NG PSYCHOLOGICAL-->
                                                                    <div class="panel panel-default" style=" padding-top:5px;">
                                                                        <div class="panel-heading" style="background-color:#07847d">
                                                                          <h4 class="panel-title">
                                                                            <a data-toggle="collapse" href="#psychological" style="color:#FFF">
                                                                            Psychological</a>
                                                                          </h4>
                                                                        </div>
                                                                        <div id="psychological" class="panel-collapse collapse">
                                                                            <!--LAMAN START-->
                                                                                <div class="panel-body">

                                                                                    <!--START NG PSYCHIATRIST-->
                                                                                        <div class="panel panel-default" style=" padding-top:5px;">
                                                                                            <div class="panel-heading" style="background-color:#07847d">
                                                                                              <h4 class="panel-title">
                                                                                                <a data-toggle="collapse" href="#psychiatrist" style="color:#FFF">
                                                                                                Has been consulted to  a Psychiatrist</a>
                                                                                              </h4>
                                                                                            </div>
                                                                                            <div id="psychiatrist" class="panel-collapse collapse">
                                                                                                <!--LAMAN START-->
                                                                                                    <div class="panel-body">

                                                                                                        <br></br>

                                                                                                        <!--psychiatrist-->
                                                                                                        <div class="form-group">
                                                                                                            <div class="col-lg-12">
                                                                                                                <select class="form-control m-bot15" name="psychiatrist" id="psychiatrist" onchange="enableothers()">
                                                                                                                    <option value="No">No</option>   
                                                                                                                    <option value="yes6">Yes</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <script language="javascript" type="text/javascript">
                                                                                                            $(document).ready(function () {
                                                                                                                $("#psychiatrist").change(function () {
                                                                                                                    if ($(this).find("option:selected").val() == "yes6") {
                                                                                                                        $("#yep6").removeAttr("disabled") && $("#yepyep6").removeAttr("disabled")
                                                                                                                    } else {
                                                                                                                        $("#yep6").attr("disabled","disabled") && $("#yepyep6").attr("disabled","disabled")
                                                                                                                    }
                                                                                                                });
                                                                                                            });
                                                                                                        </script>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">When?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yep6" name="psychiatrist_when">
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">For what?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yepyep6" name="psychiatrist_forwhat">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!--psychiatrist-->
                                                                                                        
                                                                                                    </div>
                                                                                                <!--LAMAN END-->
                                                                                            </div>
                                                                                        </div>
                                                                                    <!--END NG PSYCHIATRIST-->

                                                                                    <!--START NG PSYCHOLOGIST-->
                                                                                        <div class="panel panel-default" style=" padding-top:5px;">
                                                                                            <div class="panel-heading" style="background-color:#07847d">
                                                                                              <h4 class="panel-title">
                                                                                                <a data-toggle="collapse" href="#psychologist" style="color:#FFF">
                                                                                                Has been consulted to  a Psychologist</a>
                                                                                              </h4>
                                                                                            </div>
                                                                                            <div id="psychologist" class="panel-collapse collapse">
                                                                                                <!--LAMAN START-->
                                                                                                    <div class="panel-body">

                                                                                                        <br></br>

                                                                                                        <!--psychologist-->
                                                                                                        <div class="form-group">
                                                                                                            <div class="col-lg-12">
                                                                                                                <select class="form-control m-bot15" name="psychologist" id="psychologist" onchange="enableothers()">
                                                                                                                    <option value="No">No</option>   
                                                                                                                    <option value="yes7">Yes</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <script language="javascript" type="text/javascript">
                                                                                                            $(document).ready(function () {
                                                                                                                $("#psychologist").change(function () {
                                                                                                                    if ($(this).find("option:selected").val() == "yes7") {
                                                                                                                        $("#yep7").removeAttr("disabled") && $("#yepyep7").removeAttr("disabled")
                                                                                                                    } else {
                                                                                                                        $("#yep7").attr("disabled","disabled") && $("#yepyep7").attr("disabled","disabled")
                                                                                                                    }
                                                                                                                });
                                                                                                            });
                                                                                                        </script>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">When?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yep7" name="psychologist_when">
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">For what?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yepyep7" name="psychologist_forwhat">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!--psychologist-->
                                                                                                        
                                                                                                    </div>
                                                                                                <!--LAMAN END-->
                                                                                            </div>
                                                                                        </div>
                                                                                    <!--END NG PSYCHOLOGIST-->

                                                                                    <!--START NG COUNSELOR-->
                                                                                        <div class="panel panel-default" style=" padding-top:5px;">
                                                                                            <div class="panel-heading" style="background-color:#07847d">
                                                                                              <h4 class="panel-title">
                                                                                                <a data-toggle="collapse" href="#counselor" style="color:#FFF">
                                                                                                Has been consulted to  a Counselor</a>
                                                                                              </h4>
                                                                                            </div>
                                                                                            <div id="counselor" class="panel-collapse collapse">
                                                                                                <!--LAMAN START-->
                                                                                                    <div class="panel-body">

                                                                                                        <br></br>

                                                                                                        <!--counselor-->
                                                                                                        <div class="form-group">
                                                                                                            <div class="col-lg-12">
                                                                                                                <select class="form-control m-bot15" name="counselor" id="counselor" onchange="enableothers()">
                                                                                                                    <option value="No">No</option>   
                                                                                                                    <option value="yes8">Yes</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <script language="javascript" type="text/javascript">
                                                                                                            $(document).ready(function () {
                                                                                                                $("#counselor").change(function () {
                                                                                                                    if ($(this).find("option:selected").val() == "yes8") {
                                                                                                                        $("#yep8").removeAttr("disabled") && $("#yepyep8").removeAttr("disabled")
                                                                                                                    } else {
                                                                                                                        $("#yep8").attr("disabled","disabled") && $("#yepyep8").attr("disabled","disabled")
                                                                                                                    }
                                                                                                                });
                                                                                                            });
                                                                                                        </script>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">When?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yep8" name="counselor_when">
                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <br></br>

                                                                                                        <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">For what?</label>
                                                                                                            <div class="col-sm-10">
                                                                                                                <input type="text" class="form-control" disabled="" id="yepyep8" name="counselor_forwhat">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <!--counselor-->
                                                                                                        
                                                                                                    </div>
                                                                                                <!--LAMAN END-->
                                                                                            </div>
                                                                                        </div>
                                                                                    <!--END NG COUNSELOR-->
                                                                                    
                                                                                </div>
                                                                            <!--LAMAN END-->
                                                                        </div>
                                                                    </div>
                                                                <!--END NG PSYCHOLOGICAL-->

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="submit">Save</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    <!--LAMAN NG HEALTH END-->
                                                </div>
                                            <!--END NG LAMAN NG ADD/UPDATE-->
                                        </div>
                                    <!--END NG HEALTH-->

                                    <!----------B      R        E       A       K-------->

                                    <!--START NG INTERESTS AND HOBBIES-->
                                        <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading" style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse9" style="color:#FFF">
                                                Interests and Hobbies</a>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <!--Button-->
                                                    <button type="submit" class="btn btn-success" value="" data-toggle="collapse" data-parent="#accordion" href="#collapse9" ><i class="fa fa-plus"></button></i>

                                                    &nbsp;&nbsp;

                                                    <button type="" class="btn btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse10" ><i class="fa fa-eye"></button></i>
                                                <!--Button-->
                                              </h4>
                                            </div>
                                            <!--START NG LAMAN NG ADD/UPDATE-->
                                                <div id="collapse9" class="panel-collapse collapse">
                                                    <!--LAMAN NG INTERESTS AND HOBBIES START-->
                                                        <form name="int_hobbies"  action="inthobbies_background.php"  onsubmit="return  validateForm()"  method="post">
                                                            <div class="panel-body">
                                                                
                                                                <!--START NG ACADEMIC-->
                                                                    <div class="panel panel-default" style=" padding-top:5px;">
                                                                        <div class="panel-heading" style="background-color:#07847d">
                                                                          <h4 class="panel-title">
                                                                            <a data-toggle="collapse" href="#academic" style="color:#FFF">
                                                                            Academic</a>
                                                                          </h4>
                                                                        </div>
                                                                        <div id="academic" class="panel-collapse collapse">
                                                                            <!--LAMAN START-->
                                                                                <div class="panel-body">

                                                                                    <!--CLUBCHENES-->
                                                                                    <div class="form-group">
                                                                                        <div class="col-lg-12">
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="math_club" id="math_club" value="Math Club">Math Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="debating_club" id="debating_club" value="Debating Club">Debating Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="science_club" id="science_club" value="Science Club">Science Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="quizzer_club" id="quizzer_club" value="Quizzer's Club">Quizzer's Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="other_club" id="other_club" value="vision_specify" onclick="ibapa()">Others
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        function ibapa(){
                                                                                            document.getElementById('iba').disabled = false;
                                                                                        }
                                                                                        function ibapa2(){
                                                                                            document.getElementById('iba').disabled =  true;
                                                                                        }
                                                                                    </script>

                                                                                    <br></br>
                                                                                    &nbsp;

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="iba" name="club_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--CLUBCHENES-->

                                                                                    <br></br>

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label">What is/are your favorite subject/s?</label>
                                                                                        <div class="col-sm-8">
                                                                                            <textarea class="form-control" rows="3" name="fave_subj" id="fave_subj"></textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                    <br></br>
                                                                                    &nbsp;

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-4 control-label">What is/are the subject/s you like least?</label>
                                                                                        <div class="col-sm-8">
                                                                                            <textarea class="form-control" rows="3" name="least_subj" id="least_subj"></textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                </div>
                                                                            <!--LAMAN END-->
                                                                        </div>
                                                                    </div>
                                                                <!--END NG ACADEMIC-->

                                                                <!--START NG EXTRA-CURRICULAR-->
                                                                    <div class="panel panel-default" style=" padding-top:5px;">
                                                                        <div class="panel-heading" style="background-color:#07847d">
                                                                          <h4 class="panel-title">
                                                                            <a data-toggle="collapse" href="#extra" style="color:#FFF">
                                                                            Extra-Curricular</a>
                                                                          </h4>
                                                                        </div>
                                                                        <div id="extra" class="panel-collapse collapse">
                                                                            <!--LAMAN START-->
                                                                                <div class="panel-body">

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-12 control-label">What are your hobbies? Write them in oder of  your preferences.</label>
                                                                                        <div class="col-sm-12">
                                                                                            <input placeholder="1" type="text" class="form-control" name="pref_one" id="pref_one">
                                                                                        </div>
                                                                                        <br></br>
                                                                                        &nbsp;
                                                                                        <div class="col-sm-12">
                                                                                            <input placeholder="2" type="text" class="form-control" name="pref_two" id="pref_two">
                                                                                        </div>
                                                                                        <br></br>
                                                                                        &nbsp;
                                                                                        <div class="col-sm-12">
                                                                                            <input placeholder="3" type="text" class="form-control" name="pref_three" id="pref_three">
                                                                                        </div>
                                                                                        <br></br>
                                                                                        &nbsp;
                                                                                        <div class="col-sm-12">
                                                                                            <input placeholder="4" type="text" class="form-control" name="pref_four" id="pref_four">
                                                                                        </div>
                                                                                    </div>

                                                                                    <br></br>

                                                                                    <!--CLUBCHENES-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Which of the following organizaitons have you participated in and which interest you most?</label>
                                                                                        <div class="col-lg-7">
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="athletics" id="athletics" value="Athletics">Athletics
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="dramatics" id="dramatics" value="Dramatics">Dramatics
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="reli_org" id="reli_org" value="Religious Organization">Religious Organization
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="chess_club" id="chess_club" value="Chess Club">Chess Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="glee_club" id="glee_club" value="Glee Club">Glee Club
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="scouting" id="scouting" value="Scouting">Scouting
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="other_acts" id="other_acts" value="other_acts" onclick="ibapa2()">Others
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        function ibapa2(){
                                                                                            document.getElementById('iba2').disabled = false;
                                                                                        }
                                                                                        function ibapa3(){
                                                                                            document.getElementById('iba2').disabled =  true;
                                                                                        }
                                                                                    </script>

                                                                                    <br></br>
                                                                                    &nbsp;

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="iba2" name="acts_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--CLUBCHENES-->

                                                                                    <br></br>
                                                                                    &nbsp;

                                                                                    <!--ORGNAMAN-->
                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-3 control-label col-lg-5" for="inputSuccess">Occupational position in the organization:</label>
                                                                                        <div class="col-lg-7">
                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="officer" id="officer" value="Officer">Officer
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="member" id="member" value="Member">Member
                                                                                                </label>
                                                                                            </div>

                                                                                            <div class="checkbox">
                                                                                                <label>
                                                                                                    <input type="checkbox" name="other_pos" id="other_pos" value="" onclick="ibapaasin()">Others
                                                                                                </label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <script language="javascript" type="text/javascript">
                                                                                        function ibapaasin(){
                                                                                            document.getElementById('ibaiba').disabled = false;
                                                                                        }
                                                                                        function ibapa3(){
                                                                                            document.getElementById('ibaiba').disabled =  true;
                                                                                        }
                                                                                    </script>

                                                                                    <br></br>
                                                                                    &nbsp;

                                                                                    <div class="form-group">
                                                                                        <label class="col-sm-2 control-label">Please Specify:</label>
                                                                                        <div class="col-sm-10">
                                                                                            <input type="text" class="form-control" disabled="" id="ibaiba" name="pos_specify">
                                                                                        </div>
                                                                                    </div>
                                                                                    <!--ORGNAMAN-->
                                                                                    
                                                                                </div>
                                                                            <!--LAMAN END-->
                                                                        </div>
                                                                    </div>
                                                                <!--END NG EXTRA-CURRICULAR-->

                                                                <div class="modal-footer">
                                                                    <button class="btn btn-success" type="submit" name="submit">Save</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    <!--LAMAN NG INTERESTS AND HOBBIES END-->
                                                </div>
                                            <!--END NG LAMAN NG ADD/UPDATE-->
                                        </div>
                                    <!--END NG INTERESTS AND HOBBIES-->

                                    <!----------B      R        E       A       K-------->

                                        </div>
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
    
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Add" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color:#07847d; color:#fff">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="color:#fff">&times;</button>
                    <h4 class="modal-title">Add Student</h4>
                </div>
                <div class="modal-body">
                    <br>
                    <p>You are now adding student data</p><br>
                    <form action="add_student.php" method="POST" >
                    <div class="row">
                        <div class="col-md-4 form-group">
                            *Student Number <input name="Stud_NO" type="text" class="form-control" placeholder="ex. 2015-00001-CM-0" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Email Address<input name="Stud_EMAIL" type="text" class="form-control" placeholder="ex. email@email.com" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Contact Number<input name="Stud_MOBILE_NO" type="text" class="form-control" placeholder="ex. 099999999" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Telephone Number<input name="Stud_TELEPHONE_NO" type="text" class="form-control" placeholder="ex. 099999999" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *First Name <input name="Stud_FNAME" type="text" class="form-control" placeholder="First Name" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            Middle Name<input name="Stud_MNAME" type="text" class="form-control" placeholder="Middle Name">
                        </div>
                        <div class="col-md-4 form-group">
                            *Last Name<input name="Stud_LNAME" type="text" class="form-control" placeholder="Last Number" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Course
                            <select name="Stud_COURSE" type="text" class="form-control m-bot15" required>
                              <?php
                            $db = mysqli_connect("localhost", "root", "", "pupqcdb");
                            $sql= mysqli_query($db, "SELECT `Course_CODE` FROM `r_courses` WHERE `Course_CODE` != ' '");?>
                            <?php
                            while ($row = mysqli_fetch_array($sql))
                            {
                            $course= $row['Course_CODE'];
                            echo"<option value ='$course'>$course</option>";
                             }?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            *Year<input name="Stud_YEAR_LEVEL" type="number" class="form-control" placeholder="Section" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Section<input name="Stud_SECTION" type="number" class="form-control" placeholder="Section" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Gender<select name="Stud_GENDER" type="text" class="form-control m-bot15">
                            <option value="Male">Male</option>    
                            <option value="Female">Female</option>    
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            *Birth Date<input name="Stud_BIRTH_DATE" type="Date" class="form-control" required/>
                        </div>
                        <div class="col-md-12 form-group">
                            *Birth Place<input name="Stud_BIRTH_PLACE" type="text" class="form-control" placeholder="enter your birth place">
                        </div>
                        <div class="col-md-4 form-group">
                            *Student Status<select name="Stud_DISPLAY_STATUS" class="form-control" required>
                                <option value="Regular">Regular Student</option>
                                <option value="Irregular">Irregular Student</option>
                                <option value="Disqualified">Disqualified Student</option>
                                <option value="LOA">Leave of Absence</option>
                                <option value="Transferee">Transferee Student</option>
                                </select>
                        </div>
                        <div class="col-md-12 form-group">
                            *City Address<input name="Stud_CITY_ADDRESS" type="text" class="form-control" placeholder="enter your city address">
                        </div>
                        <div class="col-md-12 form-group">
                            *Provincial Address<input name="Stud_PROVINCIAL_ADDRESS" type="text" class="form-control" placeholder="enter your provincial address">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button name="insert" class="btnInsert btn btn-success" type="submit">Submit</button>
                        <button data-dismiss="modal" class="btn btn-cancel" type="button">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL-->
             <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ImportModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#07847d; color:#fff">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">Import Students</h4>
                                    </div>
                                    <div class="modal-body">
                                         <form method="post" action="profiling\import_excel.php" enctype="multipart/form-data">

                                                 <div class="form-group">
                                                <br/><input type="file" name="excelfile" id="excelfile"><br/>
                                                </div>
                                                 <div class="modal-footer">
                                               <button class="btn btn-primary"><i class="fa fa-check"></i> Upload</button>
                                                 </div>

                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- modal -->
    <!--main content end-->
<!--right sidebar start-->

<div class="right-stat-bar">
<ul class="right-side-accordion">
<li class="widget-collapsible">
    
    <ul class="widget-container" style="display:none;">
        <li>
            <div class="prog-row side-mini-stat clearfix">
                
                <div class="side-mini-graph">
                    <div class="target-sell">
                    </div>
                </div>
            </div>
           
        </li>
    </ul>
</li>

</ul>
</div>
</div>
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>

<script>
    $(document).ready(function(){

        function load_unseen_notification(view = '')
        {
            $.ajax({
                url:"NotifLoad.php",
                method:"POST",
                data:{view:view},
                dataType:"json",
                success:function(data)
                {
                    $('.dropdown-menu').html(data.Notification);

                    if(data.NotificationCount > 0)
                    {
                        $('.count').html(data.NotificationCount);
                    }
                }
            });
        }

        load_unseen_notification();

        $(document).on('click','.dropdown-toggle', function(){
        $('.count').html('');
        load_unseen_notification('read');
        });

        setInterval(function(){
            load_unseen_notification();  
        }, 5000);
        
    });

</script>

<!-- <script src="bs3/js/bootstrap.min.js"></script> -->
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>


<script src="js/iCheck/jquery.icheck.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<!-- <script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script> -->

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--common script init for all pages-->
<!-- <script src="js/scripts.js"></script> -->

<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<script>
    var global_href = '';
    var global_img_id = '';
    var global_txt = '';

    $(".stud_id").on('click', function(){  
          
           console.log($(this).attr("href"));
           var modalhref = $(this).attr("href");
           global_href = $(this).attr("href");
           $(modalhref).find('.OpenImg').click(function(){ $(modalhref).find('.imgupload').trigger('click'); });
           global_img_id =  $(modalhref).find('.OpenImg').attr('id');
           global_txt =  $(modalhref).find('.imgText').attr('id');
           // if(stud_id != '')  
           // {  
           //      $.ajax({  
           //           url:"viewprofile.php",  
           //           method:"POST",  
           //           data:{stud_id:stud_id},  
           //           success:function(data){  
           //                $('#employee_detail').html(data);  
           //                $('#dataModal').modal('show');  
           //           }  
           //      });  
           // }            
      });  
</script>
<script>
    function EditStatus(){
        document.getElementById("stud_status").removeAttribute("disabled");
        document.getElementById("ok_status").style.display="";
        document.getElementById("edit_status").style.display="none";
    }
</script>
<script>
    // function showDetails(button){
    //     var stud_id = button.id;
    //     $.ajax ({
    //         url:"viewprofile.php",
    //         method:"GET",
    //         data: ("stud_id":stud_id),
    //         success:function(response){
    //             var student = JSON.parse(response);
    //             $("#FULLNAME").text(student.STUD_FNAME);
    //             $("#title").text(student.STUD_FNAME);
    //         }
    //     });
    // }
</script>
<script>
    var btn = document.getElementById('viewVisit');
btn.addEventListener('click', function() {
  document.location.href = 'visit_logs.php';
});
</script>
<script>
    var btn = document.getElementById('viewCouns');
btn.addEventListener('click', function() {
  document.location.href = 'counseling_page.php';
});
</script>

<script>
    // 
    // $('.OpenImg').click(function(){ $('.imgupload').trigger('click'); });
</script>

<script>
$(document).ready(function()
    {
        $(".action-button").click(function()
        {
            // $("#editOrdID").val($(this).closest("tbody tr").find("td:eq(0)").html());
            // $("#editOrdTitle").val($(this).closest("tbody tr").find("td:eq(1)").html());

        });
    });



function showMyImage(fileInput)
{
    var files = fileInput.files;

    var FileName = document.getElementById(global_txt).value;

    var file = fileInput.files[0];
    var fd = new FormData();
    fd.append('imageFile', file);
    fd.append('filename', FileName);

    $.ajax({
            type: 'POST',
            url: 'stud_img.php',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) 
            {
                
            }
        });

    for (var i = 0; i < files.length; i++) 
    { 
        var file = files[i];
        var imageType = /image.*/; 
        if (!file.type.match(imageType)) 
        {
            continue;
        }
        
        
        // var img = document.getElementsByClassName('OpenImg'); 
        var img = document.getElementById(global_img_id);
        img.file = file; 
        var reader = new FileReader();
        reader.onload = (function(aImg) 
        { 
            return function(e) 
                { 
                
                    aImg.src = e.target.result; 


                }; 
        })(img);
        reader.readAsDataURL(file);
    } 
}
</script>
            
</script>
</body>
</html>
