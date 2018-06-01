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

        <title>G&CSMS-Reports</title>

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
$currentPage ='G&CSMS-Reports';
include('header.php');
include('sidebarnav.php');
?>
        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="breadcrumbs-alt">
                            <li>
                                <a href="#">
                                    <i class="fa fa-home"></i> Home</a>
                            </li>
                            <li>
                                <a class="current" href="#">
                                    <i class="fa fa-bar-chart-o"></i> Reports</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <section class="panel">
                            <header class="panel-heading">
                                Guidance Record
                            </header>
                            <div class="panel-body">
                                <div class="col-md-12" style="padding-left:0px">
                                    <form action="counselingreport.php" method="POST">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <select name="AOption" class="form-control input-sm m-bot4" placeholder="Academic Year">
                                                    <option value="">--Select Academic Year--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="SemOption" class="form-control input-sm m-bot4" placeholder="Semester">
                                                    <option value="">--Select Semester--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="MonthOpt" class="form-control input-sm m-bot4" placeholder="Month">
                                                    <option value="">--Select Month--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <select name="DayOpt" class="form-control input-sm m-bot4" placeholder="Day">
                                                    <option value="">--Select Date--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <select name="CourseOption" class="form-control input-sm m-bot4" placeholder="Programme/Course">
                                                    <option value="">--Select Programme/Course--</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                </div>
                                <br>
                                <br>
                                <button class="btn btn-info btn-sm" name="reportSearch">
                                                <i class="fa fa-search"> Search</i>
                                            </button>
                                </form>
                            </div>
                            <a href="print_record_all.php" type="button" class="btn btn-success">Print</a>
                            <section id="unseen">
                                <table class=" display table table-bordered table-striped table-condensed" id="dynamic-table">
                                    <thead>
                                        <tr>
                                            <th hidden>Couseling ID</th>
                                            <th>Student Number</th>
                                            <th>Student Name</th>
                                            <th>Date</th>
                                            <th id="thview">View</th>
                                        </tr>
                                    </thead>
                                    <!-- page start-->
                                    <?php

$conn = mysqli_connect("localhost","root","","pupqcdb");

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql =  mysqli_query ($conn," SELECT
  `c`.`Couns_CODE` AS `COUNSELING_CODE`,
  CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
  `s`.`Stud_NO` AS `STUD_NO`,
  DATE_FORMAT(`c`.`Couns_DATE`, '%W %M %d %Y') AS `COUNSELING_DATE`,
  `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  `c`.`Couns_APPOINTMENT_TYPE` AS `APPOINTMENT_TYPE`,
  CONCAT(
      `s`.`Stud_COURSE`,
      ' ',
      `s`.`Stud_YEAR_LEVEL`,
      ' - ',
      `s`.`Stud_SECTION`
  ) AS `COURSE`,
  (
  SELECT
      GROUP_CONCAT(`a`.`Couns_APPROACH` SEPARATOR ', ')
  FROM
      `t_couns_approach` `a`
  WHERE
      (
          `a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`
      )
) AS `COUNSELING_APPROACH`,
`c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,
`c`.`Couns_GOALS` AS `GOALS`,
`c`.`Couns_COMMENT` AS `COUNS_COMMENT`,
`c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION`
FROM
  (
      (
          `t_counseling` `c`
      JOIN `t_couns_details` `cd` ON
          (
              (
                  `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
              )
          )
      )
  JOIN `r_stud_profile` `s` ON
      ((`s`.`Stud_NO` = `cd`.`Stud_NO`))
  ) ");?>

                                        <?php while ($row = mysqli_fetch_array($sql)) { ?>
                                        <tbody>
                                            <tr>
                                                <td hidden>
                                                    <?php echo $row['COUNSELING_CODE']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['STUD_NO']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['STUD_NAME']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $row['COUNSELING_DATE']; ?>
                                                </td>

                                                <td>
                                                    <a href="counseling_report_review.php?view=<?php echo $row['COUNSELING_CODE']; ?>" id="viewbutton" type="button" class="btn btn-success">View</a>
                                                </td>
                                            </tr>
                                            <?php }?>
                                        </tbody>
                                </table>
                                <!-- page end-->
                            </section>
                    </div>
                    </section>
                    <!-- page start-->

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

        <script src="js/jquery-steps/jquery.steps.js"></script>
        <!--Easy Pie Chart-->
        <script src="js/easypiechart/jquery.easypiechart.js"></script>
        <!--Sparkline Chart-->
        <script src="js/sparkline/jquery.sparkline.js"></script>
        <!--jQuery Flot Chart-->
        <!-- <script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script> -->

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