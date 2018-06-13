<?php
    
    include ('config.php');

    if(isset($_POST['insert']))
    {
        
        $name = $_POST['approachName'];
        $desc = $_POST['approachDesc'];

        $query = "INSERT INTO `r_couns_approach`(`Couns_APPROACH`, `Couns_APPROACH_DESC`) VALUES ('$name','$desc')";

        $result = mysqli_query($db, $query);

        if ($result) 
        {
            echo ' <script>alert("Congratulations! Your data has been inserted successfully");</script> ';
        }
        else 
        {
            echo ' <script>alert("Sorry! Your data was not inserted");</script> ';
        }

        mysqli_close($db);
    }

    if (isset($_POST['edit'])) 
    {
        $id = $_POST['Id'];
        $nature = $_POST['nature'];
        $desc = $_POST['desc'];

        $updatequery = "UPDATE r_couns_approach SET Couns_APPROACH = '$nature', Couns_APPROACH_DESC = '$desc' WHERE Couns_APPROACH_ID = $id";
                                        
        mysqli_query($db, $updatequery);
    }

    if (isset($_POST['deactivate'])) 
    {
        $id = $_POST['caId'];
        $updatequery = "";

        if ($_POST['stat'] == 'Active') {
            $updatequery = "UPDATE r_couns_approach SET Couns_APPROACH_STAT = 'Inactive' WHERE Couns_APPROACH_ID = $id";
        } else {
            $updatequery = "UPDATE r_couns_approach SET Couns_APPROACH_STAT = 'Active' WHERE Couns_APPROACH_ID = $id";
        }
                                        
        mysqli_query($db, $updatequery);
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

    <title>G&CSMS-Counseling Approach</title>

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
<?php 
$currentPage ='G&CSMS-System Configurations';
include('TypeS_Header.php');
include('TypeS_Sidebar.php');
?>

<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="#"><i class="fa fa-gears"></i> System Configurations</a>
                        </li>
                        <li>
                            <a class="current" href="#"> Counseling Approach</a>
                        </li>
                    </ul>
                </div>
            </div>
        <!-- page start-->

        <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Counseling Approach
                        </header>
                        <div class="panel-body">
                            <div class="position-center">
                                <form action="TypeS_CounselingApproach.php" method="POST">
                                    <div class="form-group">
                                        <br>
                                        <label for="approachName">Approach Name</label>
                                        <input type="text" class="form-control" name="approachName" required>
                                        <label for="approachDesc">Description</label>
                                        <input type="text" class="form-control" name="approachDesc" required>
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
                        
                        <thead>
                            <tr>
                                <th class="hidden">ID</th>
                                <th>Counseling Approach</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include ('config.php');

                                $sql = "SELECT * FROM `r_couns_approach`";
                                $records = mysqli_query($db,$sql);

                                while ($approach = mysqli_fetch_assoc($records)) 
                                {
                                    $Id = $approach['Couns_APPROACH_ID'];
                                    $Capproach = $approach['Couns_APPROACH'];
                                    $apDesc = $approach['Couns_APPROACH_DESC'];
                                    $apStat = $approach['Couns_APPROACH_STAT'];
                            ?>

                            <tr>
                                <td class="hidden"> <?php echo $Id; ?> </td>
                                <td> <?php echo $Capproach; ?> </td>
                                <td> <?php echo $apDesc; ?> </td>
                                <td> <?php echo $apStat; ?> </td>
                                <td class="center">
                                    <a data-toggle="modal" class="btn btn-info" href="#UpdateModal<?php echo $Id; ?>"><i class="fa fa-pencil-square-o" name="edit"></i></a>
                                    <a data-toggle="modal" class="btn btn-danger delete" href="#deactivateModal<?php echo $Id; ?>"><i class="fa fa-check"></i></a>
                                </td>
                            </tr>

                            <!--Update Modal-->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="UpdateModal<?php echo $Id; ?>" class="modal fade">
                                <div class="modal-dialog" style="width: 40%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                            <h4 class="modal-title">Edit!</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" method="POST">
                                                <div class="hidden">
                                                    <label>ID</label>
                                                    <input class="form-control" name="Id" value="<?php echo $Id ?>">                           
                                                </div>
                                                <div class="form-group">
                                                    <label>Nature of the Case</label>
                                                    <input class="form-control" name="nature" value="<?php echo $Capproach ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Description</label>
                                                    <input class="form-control" name="desc" value="<?php echo $apDesc ?>">
                                                </div>
                                                <br><br>
                                                <div style="margin-left: 70%">
                                                    <button type="submit" class="btn btn-cancel" data-dismiss="modal">CANCEL</button>
                                                    <button type="submit" class="btn btn-info" name="edit">EDIT</button>
                                                </div>     
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end of update-->

                            <!--deactivate/activate modal-->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="deactivateModal<?php echo $Id; ?>" class="modal fade">
                                <div class="modal-dialog" style="width: 30%;">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                            <h4 class="modal-title">Warning!</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form role="form" method="POST">
                                                <div class="hidden">
                                                    <input name="caId" value="<?php echo $Id ?>">
                                                    <input name="stat" value="<?php echo $apStat ?>">
                                                </div>
                                                <br>  
                                                <label style="margin-left: 15%;">You are about to deactivate / activate</label><br>
                                                <label style="margin-left: 15%;"> a data, continue?</label>
                                                <br><br>
                                                <div style="margin-left: 60%">
                                                    <button type="submit" class="btn btn-cancel" data-dismiss="modal">CANCEL</button>
                                                    <button type="submit" class="btn btn-info" name="deactivate">OK</button>
                                                </div>     
                                            </form>    
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
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
