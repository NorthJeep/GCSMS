<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Counseling Services</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
   
    <link rel="stylesheet" href="css/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-tags-input/jquery.tagsinput.css" />

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <!--icheck-->
    <link href="js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/red.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/green.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/blue.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/yellow.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/purple.css" rel="stylesheet">

    <!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php 
$currentPage ='G&CSMS-Counseling Services';
include('header.php');
include('sidebarnav.php');
?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
        <style>
            .twt-feeds {
    border-radius:4px 4px 0 0;
    -webkit-border-radius:4px 4px 0 0;
    color:#FFFFFF;
    padding:40px 10px 10px;
    position:relative;
    min-height:220px;
    height: 60px;
}
        </style><div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        </li><li>
                            <a href="counseling_page.php"><i class="fa fa-edit"></i> Counseling Services</a>
                        </li>
                        <li>
                            <a href="counseling_services.php"><i class="fa fa-user"></i> Individual Counseling</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-check"></i> Finalization</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    
                    <form action="add_confirm_counseling.php" method="POST" style='padding-left:10px'>
         <?php
include("config.php");

$C_stud_id = $_POST['_id'];
$C_stud_name = $_POST['_name'];
$C_stud_no = $_POST['_no'];
$C_stud_course = $_POST['_course'];
$C_stud_yr = $_POST['_yr'];
$C_stud_sec = $_POST['_sec'];
$C_stud_email = $_POST['_email'];
$C_stud_add = $_POST['_add'];
$C_stud_cno = $_POST['_cno'];
$C_couns_bg = $_POST['C_couns_bg'];
$C_goals = $_POST['C_goals'];
$C_comments = $_POST['C_comments'];
$C_recomm = $_POST['C_recomm'];
$C_app=$_POST['C_approach'];

date_default_timezone_set("Singapore");
		echo "  <div class='well col-lg-12'>
				<center><strong><h2 id='C_stud_name' name='C_stud_name' value=".$C_stud_name."><i class='fa fa-user'> ".$C_stud_name."</i></h2></strong>
                            <h5 id='C_stud_no' name='C_stud_no'><i> ".$C_stud_no."</i></h4>
                            <h5 id='C_stud_course' name='C_stud_course'><i>".$C_stud_course."</i></h4></center><br>
                            <h4 class='col-lg-8' id='C_stud_no' name='C_stud_no' style='padding-left:20px'><i class='fa fa-pencil'> ".$C_app."</i></h4>
                            <h4 class='col-lg-8' id='C_stud_no' name='C_stud_no' style='padding-left:20px'><i class='fa fa-thumb-tack'> Walk-in</i></h4>
                            <h5 id='C_stud_no' name='C_stud_no' style='padding-left:800px'><i class='fa fa-calendar'> Date/Time Counseled:<br><br>".date("Y/m/d h:i a")."</i></h4><br>
                            <div class='col-lg-16'><h5 style='padding-left:20px'><i class='fa fa-info-circle'> Background of the Case </i>
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<i class='fa fa-info-circle'> Counseling Goals</i></h5></div>
                            <div class='col-sm-6'>
                                    <textarea class='form-control' name='_couns_bg1'id='_couns_bg1' rows='6'>".$C_couns_bg."</textarea>
                                </div>
                            <div class='col-sm-6'>
                                    <textarea class='form-control' name='_goals' id='_goals'  rows='6'>".$C_goals."</textarea>
                                </div>
                            <div class='col-lg-16'><h5 style='padding-left:20px'><i class='fa fa-info-circle'> Comments</i>
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                            &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<i class='fa fa-info-circle'> Recommendations</i></h5>
                            <div class='col-sm-6'>
                                    <textarea class='form-control' name='_comments' id='_couns_comments' rows='6'>".$C_comments."</textarea>
                                </div></div>
                            <div class='col-sm-6'>
                                    <textarea class='form-control' name='_recomm' id='_couns_recomm' rows='6'>".$C_recomm."</textarea>
                                </div>
                                </div>
                
						<!--Hidden FIelds-->
											<div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_id' value='$C_stud_id'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_no' value='$C_stud_no'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_name'value='$C_stud_name'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_course' value='$C_stud_course'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_yr' value='$C_stud_yr'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_sec' value='$C_stud_sec'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_email' value='$C_stud_email'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_add'value='$C_stud_add'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_cno' value='$C_stud_cno'> </div>
                                            <div class='form-group' style='display:none'>
                                                <input type='text' class='form-control' name='_app' value='$C_app'> </div>
						<!--end HF-->
						";

?>
							<br><div class="" style="padding-top:40px">
							<center><button type="submit" class="btn btn-primary"><i class="fa fa-pencil" disabled> EDIT</i></button>
							<a data-toggle="modal" name="insert" class="btnInsert btn btn-primary" href="#Continue" type="submit"> CONFIRM</i></a>
							</div>
							</center>
							</div>
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Continue" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header"style="background-color:#07847d; color:white">
                                                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                 <h5>Final Indivindual Counseling</h5>
                                                            </div>
                                                            <h5 style="padding-top:20px; text-align: center;">
                                                            Save the following information?</h5>
                                                            <br>
                                                            <div class="panel" style="height: 50%; width: 100%; text-align: center;">
                                                                <br>
                                                                <button type="submit" class="btn btn-primary btn-lg" name="insertonly">
                                                                <i class="fa fa-check"></i>   Yes   </button> &nbsp&nbsp&nbsp&nbsp
                                                                <button data-dismiss="modal" class="btn btn-error btn-lg">
                                                                <i class="fa fa-ban"></i>   No</button>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                            </div>
							</form>
                                <!--</div>
                                <?php
                    include("config.php");
                        $stud_no = $_POST["student_no"];

                        $sql="SELECT `STUD_ID`, `STUD_NO`, CONCAT(`STUD_FNAME`,' ', `STUD_LNAME`) AS FULLNAME,`STUD_YR_LVL`,`STUD_SECTION`,
                                        `STUD_COURSE`,' ',`STUD_YR_LVL`,'-',`STUD_SECTION`,
                                        `STUD_ADDRESS`,`STUD_CONTACT_NO`,`STUD_EMAIL` FROM r_stud_profile where stud_no = '$stud_no'";

                        $query=mysqli_query($db,$sql);

                         if (!$query) {
                            die ('SQL Error: ' . mysqli_error($db));
                        }

                        $row=mysqli_fetch_array($query,MYSQLI_ASSOC);
                    ?>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_id" value="<?php echo $row['STUD_ID']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_no" value="<?php echo$row['STUD_NO']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_name" value="<?php echo $row['FULLNAME']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_course" value="<?php echo $row['STUD_COURSE']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_yr" value="<?php echo $row['STUD_YR_LVL']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_sec" value="<?php echo $row['STUD_SECTION']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_email" value="<?php echo$row['STUD_EMAIL']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_add" value="<?php echo$row['STUD_ADDRESS']?>"> </div>
                                            <div class="form-group" style="display:none">
                                                <input type="text" class="form-control" name="_cno" value="<?php echo$row['STUD_CONTACT_NO']?>"> </div>
                                <div style="text-align:center">
                                <button name="insert" class="btnInsert btn btn-primary" type="submit">Save</button>
                                <button data-dismiss="modal" class="btn btn-cancel" type="button">Cancel</button>
                                </form>
                                </div>
                                </div>
                            <!--END-->
                    </form>
                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
    <!--main content end-->
<!--right sidebar start-->
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->

<script src="js/jquery.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/easypiechart/jquery.easypiechart.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<script src="js/iCheck/jquery.icheck.js"></script>


<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--common script init for all pages-->
<script src="js/scripts.js"></script>

<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<script src="js/advanced-form.js"></script>
<script src="js/toggle-init.js"></script>
<!--icheck init -->
<script src="js/icheck-init.js"></script>

</body>
</html>
