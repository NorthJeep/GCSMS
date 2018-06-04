<!DOCTYPE html>

<?php
    session_start();
    if(!$_SESSION['Logged_In'])
    {
        header('Location:LogIn.php');
        exit;
    }
    include ("config.php");

if (mysqli_connect_errno())
{
printf("Connect failed: %s\n", mysqli_connect_error());
exit();
}

if(isset($_POST['IndivFilter']))
{
    $acadOpt = $_POST['acadOpt'];
    $semOpt = $_POST['semOpt'];
    $monthOpt = $_POST['monthOpt'];
    $dayOpt = $_POST['dayOpt'];
    $courseOpt = $_POST['courseOpt'];

        $actualQuery = "SELECT
        `c`.`Couns_CODE` AS `COUNSELING_CODE`,
        DATE_FORMAT(`c`.`Couns_DATE`, '%W %M %d %Y') AS `COUNSELING_DATE`,
        `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
        `c`.`Couns_APPOINTMENT_TYPE` AS `APPOINTMENT_TYPE`,
        `s`.`Stud_NO` AS `STUD_NO`,
        CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
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
        ) ";

        $conditions = array();

        if($acadOpt != 'All'){
            $conditions[] = "";
        }

        if($semOpt != 'All'){
            $conditions[] = ""; 
        }

        if($monthOpt != 'All'){
            $conditions[] = "";
        }

        if($dayOpt != 'All'){
            $conditions[] = "";
        }

        if($courseOpt != 'All'){
            $conditions[] = "";
        }

        $query = $actualQuery;
        if(count($conditions)>0){
            $query .= " WHERE ". implode(' AND ', $conditions);
        }

        $result = mysqli_query($db,$query);
} else {
    $actualQuery = "SELECT
        `c`.`Couns_CODE` AS `COUNSELING_CODE`,
        DATE_FORMAT(`c`.`Couns_DATE`, '%W %M %d %Y') AS `COUNSELING_DATE`,
        `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
        `c`.`Couns_APPOINTMENT_TYPE` AS `APPOINTMENT_TYPE`,
        `s`.`Stud_NO` AS `STUD_NO`,
        CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
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
        ) ";

    $result = mysqli_query($db,$actualQuery);
}

$sql =  mysqli_query ($db," SELECT
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
) ");
//Academic year
$sqlAY = mysqli_query($db, "SELECT Batch_ID,Batch_YEAR FROM `pupqcdb`.`r_batch_details` ");
$optionAY = '';
while ($row = mysqli_fetch_assoc($sqlAY))
{
    $optionAY .='<option value = "'.$row['Batch_ID'].'">'.$row['Batch_YEAR'].'</option>';
}

$sqlSem = mysqli_query($db, "SELECT Semestral_ID, Semestral_NAME FROM `r_semester`  ");
$optionSem = '';
while ($row = mysqli_fetch_assoc($sqlSem))
{
    $optionSem .='<option value = "'.$row['Semestral_ID'].'">'.$row['Semestral_NAME'].'</option>';
}

$sqlCourse = mysqli_query($db, "SELECT Course_ID, Course_CODE FROM `r_courses`  ");
$optionCourse = '';
while ($row = mysqli_fetch_assoc($sqlCourse))
{
    $optionCourse .='<option value = "'.$row['Course_ID'].'">'.$row['Course_CODE'].'</option>';
}
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
                        <div class="col-lg-12">
                            <section class="panel">
                                <header class="panel-heading tab-bg-dark-navy-blue ">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                            <a data-toggle="tab" href="#Indiv">Individual Counsel</a>
                                        </li>
                                        <li class="">
                                            <a data-toggle="tab" href="#Grouped">Group Counsel</a>
                                        </li>
                                        <li class="">
                                            <a data-toggle="tab" href="#Visits">Visit Reports</a>
                                        </li>
                                    </ul>
                                </header>
                                <div class="panel-body">
                                    <div class="tab-content">
                                        <div id="Indiv" class="tab-pane active">
                                            <div class="col-lg-12" style="padding-left:0px">
                                                <form action="counselingreport.php" method="POST">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <select name="acadOpt" class="form-control input-sm m-bot4" placeholder="Academic Year">
                                                                <option value="All">--Select Academic Year--</option>
                                                                <?php echo $optionAY; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="semOpt" class="form-control input-sm m-bot4" placeholder="Semester">
                                                                <option value="All">--Select Semester--</option>
                                                                <?php echo $optionSem; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="monthOpt" class="form-control input-sm m-bot4" placeholder="Month">
                                                                <option value="All">--Select Month--</option>
                                                                <?php for( $m=1; $m<=12; ++$m ) { 
          $month_label = date('F', mktime(0, 0, 0, $m, 1));
        ?>
                                                                <option value="<?php echo $m; ?>">
                                                                    <?php echo $month_label; ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="dayOpt" class="form-control input-sm m-bot4" placeholder="Day">
                                                                <option value="All">--Select Date--</option>
                                                                <?php 
          $start_date = 1;
          $end_date   = 31;
          for( $j=$start_date; $j<=$end_date; $j++ ) {
            echo '<option value='.$j.'>'.$j.'</option>';
          }
        ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <select name="courseOpt" class="form-control input-sm m-bot4" placeholder="Programme/Course">
                                                                <option value="All">--Select Programme/Course--</option>
                                                                <?php echo $optionCourse; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <br>
                                            </br>
                                            <button class="btn btn-info btn-sm" type="submit" name="IndivFilter">
                                                <i class="fa fa-search"></i>
                                                Search
                                            </button>
                                            </form>
                                            &nbsp
                                            <a href="print_record_all.php" type="button" class="btn btn-success">
                                                <i class="fa fa-print"></i> Print</a>
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
                                                    <?php while ($row = mysqli_fetch_array($result)) { ?>
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
                                                                <a href="counseling_report_review.php?view=<?php echo $row['COUNSELING_CODE']; ?>" id="viewbutton" type="button" class="btn btn-success">
                                                                    <i class="fa fa-eye"></i> View</a>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                                <!-- page end-->
                                            </section>
                                        </div>
                                        <div id="Grouped" class="tab-pane">
                                            <div class="col-lg-12" style="padding-left:0px">
                                                <form action="counselingreport.php" method="POST">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <select name="AOption" class="form-control input-sm m-bot4" placeholder="Academic Year">
                                                                <option value="">--Select Academic Year--</option>
                                                                <?php echo $optionAY; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="SemOption" class="form-control input-sm m-bot4" placeholder="Semester">
                                                                <option value="">--Select Semester--</option>
                                                                <?php echo $optionSem; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="MonthOpt" class="form-control input-sm m-bot4" placeholder="Month">
                                                                <option value="">--Select Month--</option>
                                                                <?php for( $m=1; $m<=12; ++$m ) { 
          $month_label = date('F', mktime(0, 0, 0, $m, 1));
        ?>
                                                                <option value="<?php echo $m; ?>">
                                                                    <?php echo $month_label; ?>
                                                                </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select name="DayOpt" class="form-control input-sm m-bot4" placeholder="Day">
                                                                <option value="">--Select Date--</option>
                                                                <?php 
          $start_date = 1;
          $end_date   = 31;
          for( $j=$start_date; $j<=$end_date; $j++ ) {
            echo '<option value='.$j.'>'.$j.'</option>';
          }
        ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                            </div>
                                            </br>
                                            </br>
                                            <button class="btn btn-info btn-sm" name="reportSearch">
                                                <i class="fa fa-search"></i> Search</button>
                                            </form>
                                            &nbsp
                                            <a href="print_record_all.php" type="button" class="btn btn-success">
                                                <i class="fa fa-print"></i> Print</a>
                                            </br>
                                            </br>
                                            <section id="unseen">
                                                <table class=" display table table-bordered table-striped table-condensed" id="dynamic-table">
                                                    <thead>
                                                        <tr>
                                                            <th hidden>Couseling ID</th>
                                                            <th>Student Number</th>
                                                            <th>Name of Students</th>
                                                            <th>Date</th>
                                                            <th id="thview">View</th>
                                                        </tr>
                                                    </thead>
                                                    <!-- page start-->

                                                    <?php while ($row = mysqli_fetch_array($result)) { ?>
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
                                                                <a href="counseling_report_review.php?view=<?php echo $row['COUNSELING_CODE']; ?>" id="viewbutton" type="button" class="btn btn-success">
                                                                    <i class="fa fa-eye"></i> View</a>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                    </tbody>
                                                </table>
                                                <!-- page end-->
                                            </section>
                                        </div>
                                        <div id="Visits" class="tab-pane">Profile</div>
                                    </div>
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