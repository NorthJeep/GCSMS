<?php
    
    include ('GCSMS_connection.php');

    if(isset($_POST['insert']))
    {
        
        $civil = $_POST['civil'];

        $query = "INSERT INTO `r_civil_stat`(`Stud_CIVIL_STATUS`) VALUES ('$civil')";

        $result = mysqli_query($connect, $query);

        if ($result) 
        {
            echo ' <script>alert("Congratulations! Your data has been inserted successfully");</script> ';
        }
        else 
        {
            echo ' <script>alert("Sorry! Your data was not inserted");</script> ';
        }

        mysqli_close($connect);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Civil Status</title>

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

    <style type="text/css">
        th
        {
            text-align: center;
        }

    </style>
</head>

<body>
<?php include 'GCSMS_modals.php'?>

<section id="container" >
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">

    <a href="index.html" class="logo">
        <img src="images/logogcsms.png" alt="">
    </a>
</div>
<!--logo end-->


<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <li>
            <input type="text" class="form-control search" placeholder=" Search">
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="images/avatar1_small.jpg">
                <span class="username">John Doe</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="login.html"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->            <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
            <li class="sub-menu">
                <a href="javascript:;">
                    <i class="fa fa-laptop"></i>
                    <span>System Configurations</span>
                </a>
                <ul class="sub">
                    <li><a href="GCSMS_VisitType.php">Visit Type</a></li>
                    <li><a href="GCSMS_AppointmentType.php">Appointment Type</a></li>
                    <li><a href="GCSMS_CounselingType.php">Counseling Type</a></li>
                    <li><a href="GCSMS_CounselingApproach.php">Counseling Approach</a></li>
                    <li><a href="GCSMS_Remarks.php">Remarks</a></li>
                    <li><a href="GCSMS_CivilStatus.php">Civil Status</a></li>
                </ul>
            </li>
        </ul></div>        
<!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->

        <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Civil Status
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
                                <form action="GCSMS_CivilStatus.php" method="POST">
                                    <div class="form-group">
                                        <br>
                                        <label for="civil">Civil Status</label>
                                        <input type="text" class="form-control" name="civil" required>
                                    </div>
                                    <button type="submit" class="btn btn-info" name="insert">Save</button>
                                </form>
                            </div>

                        </div>
                    </section>

            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <div class="panel-body">
                    <div class="adv-table">                    
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                        <?php 
                           
                            include ('GCSMS_connection.php');

                            $sql = "SELECT * FROM `r_civil_stat`";
                            $records = mysqli_query($connect,$sql);
                        ?>
                        <thead>
                            <tr>
                                <th>Civil Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($civil = mysqli_fetch_assoc($records)) 
                                {
                                    echo "<tr>";
                                        echo "<td>".$civil['Stud_CIVIL_STATUS']."</td>";
                                        echo "<td>".$civil['Stud_CS_STAT']."</td>";
                                        echo "<td class='center hidden-phone'>".
                                            "<button type='submit' class='btn btn-info' ><i class='fa fa-pencil-square-o' name='update'></i></button>
                                            <a href='#deleteModal' data-toggle=modal class='btn btn-danger delete'><i class='fa fa-trash-o'name='delete'></i></a>"
                                        ."</td>";
                                    echo "</tr>";
                                }
                            ?>

                        </tbody>
                    </table>
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



</body>
</html>
