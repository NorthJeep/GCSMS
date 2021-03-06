<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Student Profile</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />


           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  

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
$currentPage ='G&CSMS-Profiling';
include('header.php');
include('sidebarnav.php');
?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Students Profile
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div  style="padding:10px; padding-left:0px;">
                    <button data-toggle="modal" href="#Add" class="btn btn-primary">
                                <i class="fa fa-plus"></i>   Add</button>
                    <button href="#ImportModal" data-toggle="modal" class="btn btn-warning">
                                <i class="fa fa-plus"></i> Import</button>
                    </div>
                    <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Student Number</th>
                        <th>Student Name</th>
                        <th>Course/Year/Section</th>
                        <th>Status</th>
                        <th class="hidden-phone">Action</th>
                        <th style="display:none;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
include("config.php");

  $sql= "SELECT `STUD_ID` as ID, `STUD_NO`, CONCAT(`STUD_FNAME`,' ', `STUD_LNAME`) AS FULLNAME,
        `STUD_COURSE`,`STUD_STATUS`FROM `r_stud_profile`WHERE STUD_STATUS='REGULAR'";

$query = mysqli_query($db, $sql);
    
if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {



                  ?>
                  <tbody>
                    <tr>
                    <td><?php echo $row['STUD_NO']?></td>
                    <td><?php echo $row['FULLNAME']?></td>
                    <td><?php echo $row['STUD_COURSE']?></td>
                    <td><?php echo $row['STUD_STATUS']?></td>
                   <td><button class="btn btn-primary" name="view" value="View" id="<?php echo $row['STUD_ID']?>" data-toggle="modal" href="#ViewModal" 
                            onclick="showDetails(this);" />VIEW</button></td>  
                    <td style="display:none" id="stud_id" name="stud_id"><?php echo $row['ID']?></td>
                </tr>
                </tbody>            
    <!--free result set */
                    /*<td>
                        <div class="btn-group" >
                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" type="button" style="height:35px">More <span class="caret"></span></button>
                            <ul role="menu" class="dropdown-menu" style="min-width:80px;">
                                <li><a value="// echo $row[0]" data-toggle="modal" href="#ViewModal"  style="width:80px">  View</button></li>
                                /*<li><a data-toggle="modal" href="#ViewModal"  style="width:80px"> Add Info</button></li>
                            </ul>
                        </div>
                    </td>

/* close connection */-->

                    </tbody>
                    </table>

                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!--page end-->
        </section>
    </section>
    <!--TEST MODAL-->

    <!-- VIEW MODAL -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ViewModal<?php echo $row['STUD_ID']?>" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="title">Student Details</h4>
                </div>
                <div class="modal-body">
                <div class='twt-feed' style="background-color:#07847d; padding:15px;">
                <div class="row">
                    <div class="col-md-4">
                        <img src="images\user.ico" style=" height:140px;padding-left:10px; padding-top:10px;"></img>
                        <h3><span id="FULLNAME">  <?php echo $row['FULLNAME']?></span></h3>
                        <h5> <?php echo $row['STUD_NO']?> </h5>
                        <h5> <?php echo $row['COURSE']?> </h5>
                    </div>
                     <?php } ?>
                <div class="col-md-8">
                    <blockquote style="background-color:#03605b; height:100px;">
                        <h4>Sanction:</h4>
                        <span class="label label-warning">Warning: 18hrs</span>
                    </blockquote>
                    <blockquote style="background-color:#03605b; height:150px">
                        <h4>Counseling Remarks:</h4>
                        <h5>Follow up</h5>
                        <br/>
                    <button type="button" class="btn btn-success" href="counseling_page.php">Start Counseling</button>
                    <button type="button" class="btn btn-info">View History</button>
                    </blockquote>
                    </div>
                    </div>
                </div>
                <div class="panel-group" id="accordion">
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading"  style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color:#FFF">
        Visit History</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">
          <table class="display table table-bordered table-striped" id="dynamic-table">
            <thead>
                <tr>
                    <th>Visit Purpose</th>
                    <th>Visit Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Clearance</td>
                <td>January 28, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
                <tr>
                <td>Exuse Letter</td>
                <td>February 10, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
                <tr>
                <td>CoC</td>
                <td>March 1, 2018</td>
                <td><button id="btnView" data-toggle="modal" href="#ViewModal" class="btn btn-primary"> <i class="fa  fa-eye"></i> View Details</button></td>
                </tr>
            </tbody>
          </table>
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="color:#FFF">
        Educational Background</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
        <h4 class="text-info">Primary:</h4>
            <p>Peacemaker International Christian Academy Branch</p>
        <h4 class="text-info">Secondary:</h4>
            <p>Peacemaker International Christian Academy Main</p>
        <h4 class="text-info">Tertiary:</h4>
            <p>Polytechnic University of the Philippines Quezon City</p>
        <h4 class="text-info">Others:</h4>
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color:#FFF">
        Home and Family Background</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
          
      </div>
    </div>
  </div>
  <div class="panel panel-default" style=" padding-top:5px;">
    <div class="panel-heading" style="background-color:#07847d">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="color:#FFF">
        Health Background</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
        <h4 class="text-info">Physical Health:</h4>
        <h4 class="text-info">Psychological Health:</h4>
      </div>
    </div>
  </div>
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
                            *Student Number <input name="Stud_no" type="text" class="form-control" placeholder="ex. 2015-00001-CM-0" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Email Address<input name="Stud_email" type="text" class="form-control" placeholder="ex. email@email.com" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Contact Number<input name="Stud_contact" type="text" class="form-control" placeholder="ex. 099999999" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *First Name <input name="Stud_fname" type="text" class="form-control" placeholder="First Name" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            Middle Name<input name="Stud_mname" type="text" class="form-control" placeholder="Middle Name">
                        </div>
                        <div class="col-md-4 form-group">
                            *Last Name<input name="Stud_lname" type="text" class="form-control" placeholder="Last Number" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Course
                            <select name="Stud_course" type="text" class="form-control m-bot15" required>
                              <?php
                            $db = mysqli_connect("localhost", "root", "", "g&csms_db");
                            $sql= mysqli_query($db, "SELECT `course` FROM `sys_con_drp` WHERE `course` != ' '");?>
                            <?php
                            while ($row = mysqli_fetch_array($sql))
                            {
                            $course= $row['course'];
                            echo"<option value ='$course'>$course</option>";
                             }?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            *Year<input name="Stud_year" type="number" class="form-control" placeholder="Section" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Section<input name="Stud_section" type="number" class="form-control" placeholder="Section" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Gender<select name="Stud_gender" type="text" class="form-control m-bot15">
                            <option value="Male">Male</option>    
                            <option value="Female">Female</option>    
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            *Birth Date<input name="Stud_bdate" type="Date" class="form-control" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Student Status<select name="Stud_status" class="form-control" required>
                                <option value="Regular">Regular Student</option>
                                <option value="Irregular">Irregular Student</option>
                                <option value="Disqualified">Disqualified Student</option>
                                <option value="LOA">Leave of Absence</option>
                                <option value="Transferee">Transferee Student</option>
                                </select>
                        </div>
                        <div class="col-md-12 form-group">
                            *Address<input name="Stud_address" type="text" class="form-control" placeholder="enter your home/ permanent address">
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
    <div id="dataModal" class="modal fade">  
      <div class="modal-dialog">  
           <div class="modal-content">  
                <div class="modal-header">  
                     <button type="button" class="close" data-dismiss="modal">&times;</button>  
                     <h4 class="modal-title">Student Details</h4>  
                </div>  
                <div class="modal-body" id="employee_detail">  
                </div>  
                <div class="modal-footer">  
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>  
                </div>  
           </div>  
      </div>  
 </div>  
    <!--MODAL-->
             <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ImportModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">Import Students</h4>
                                    </div>
                                    <div class="modal-body">
                                         <form method="post" action="profiling\import_excel.php" enctype="multipart/form-data">

                                                 <div class="form-group">
                                                <input type="file" name="excelfile" id="excelfile">
                                                </div>
                                                 <div class="form-group">
                                               <button class="btn btn-info">Upload</button>
                                                 </div>

                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- modal -->
    <!--main content end-->
<!--right sidebar start-->
<div class="right-sidebar">
<div class="search-row">
    <input type="text" placeholder="Search" class="form-control">
</div>
<div class="right-stat-bar">
<ul class="right-side-accordion">
<li class="widget-collapsible">
    <a href="#" class="head widget-head red-bg active clearfix">
        <span class="pull-left">work progress (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row side-mini-stat clearfix">
                <div class="side-graph-info">
                    <h4>Target sell</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="target-sell">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>product delivery</h4>
                    <p>
                        55%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-delivery">
                        <div class="sparkline" data-type="bar" data-resize="true" data-height="30" data-width="90%" data-bar-color="#39b7ab" data-bar-width="5" data-data="[200,135,667,333,526,996,564,123,890,564,455]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info payment-info">
                    <h4>payment collection</h4>
                    <p>
                        25%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="p-collection">
						<span class="pc-epie-chart" data-percent="45">
						<span class="percent"></span>
						</span>
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="side-graph-info">
                    <h4>delivery pending</h4>
                    <p>
                        44%, Deadline 12 june 13
                    </p>
                </div>
                <div class="side-mini-graph">
                    <div class="d-pending">
                    </div>
                </div>
            </div>
            <div class="prog-row side-mini-stat">
                <div class="col-md-12">
                    <h4>total progress</h4>
                    <p>
                        50%, Deadline 12 june 13
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div style="width: 50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-info">
                            <span class="sr-only">50% Complete</span>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head terques-bg active clearfix">
        <span class="pull-left">contact online (5)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Jonathan Smith</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Anjelina Joe</a></h4>
                    <p>
                        Available
                    </p>
                </div>
                <div class="user-status text-success">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/chat-avatar2.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">John Doe</a></h4>
                    <p>
                        Away from Desk
                    </p>
                </div>
                <div class="user-status text-warning">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Mark Henry</a></h4>
                    <p>
                        working
                    </p>
                </div>
                <div class="user-status text-info">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb">
                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                </div>
                <div class="user-details">
                    <h4><a href="#">Shila Jones</a></h4>
                    <p>
                        Work for fun
                    </p>
                </div>
                <div class="user-status text-danger">
                    <i class="fa fa-comments-o"></i>
                </div>
            </div>
            <p class="text-center">
                <a href="#" class="view-btn">View all Contacts</a>
            </p>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head purple-bg active">
        <span class="pull-left"> recent activity (3)</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        just now
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        2 min ago
                    </p>
                    <p>
                        <a href="#">Jane Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
            <div class="prog-row">
                <div class="user-thumb rsn-activity">
                    <i class="fa fa-clock-o"></i>
                </div>
                <div class="rsn-details ">
                    <p class="text-muted">
                        1 day ago
                    </p>
                    <p>
                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                    </p>
                </div>
            </div>
        </li>
    </ul>
</li>
<li class="widget-collapsible">
    <a href="#" class="head widget-head yellow-bg active">
        <span class="pull-left"> shipment status</span>
        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
    </a>
    <ul class="widget-container">
        <li>
            <div class="col-md-12">
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                            <span class="sr-only">40% Complete</span>
                        </div>
                    </div>
                </div>
                <div class="prog-row">
                    <p>
                        Full sleeve baby wear (SL: 17665)
                    </p>
                    <div class="progress progress-xs mtop10">
                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                            <span class="sr-only">70% Completed</span>
                        </div>
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
<script src="bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
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

<script>
    $(document).on('click', '.view_data', function(){  
           var stud_id = $(this).attr("STUD_ID");  
           if(stud_id != '')  
           {  
                $.ajax({  
                     url:"viewprofile.php",  
                     method:"POST",  
                     data:{stud_id:stud_id},  
                     success:function(data){  
                          $('#employee_detail').html(data);  
                          $('#dataModal').modal('show');  
                     }  
                });  
           }            
      });  
 });  
</script>
<script>
    function showDetails(button){
        var stud_id = button.id;
        $.ajax ({
            url:"viewprofile.php",
            method:"GET",
            data: ("stud_id":stud_id),
            success:function(response){
                var student = JSON.parse(response);
                $("#FULLNAME").text(student.STUD_FNAME);
                $("#title").text(student.STUD_FNAME);
            }
        });
    }
</script>


</body>
</html>
