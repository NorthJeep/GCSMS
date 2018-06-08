<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>G&CSMS-Visit Logs</title>

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
<body>
<?php 
$currentPage ='G&CSMS-Visits';
include('TypeB_Header.php');
include('TypeB_SideBar.php');
include 'config.php';
        
    // Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
if(isset($_POST['search']) && $_POST['filter'] != 'All')
{
    $valueToSearch = $_POST['filter'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT * FROM visit_record WHERE Visit_PURPOSE ='$valueToSearch' ORDER BY Visit_DATE DESC";
    $search_result = mysqli_query($db,$query);
    
} else {
    $query = "SELECT * FROM visit_record ORDER BY Visit_DATE DESC";
    $search_result = mysqli_query($db,$query);
}
?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="#"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-sign-in"></i> Visit-Logs</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Visit Logs
                    </header>
                    <div class="panel-body">
                    <div class="col-md-6" style="padding-left:0px">
                        <form action="TypeB_VisitLogs.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <select name="filter" class="form-control input-sm m-bot4">
                                        <option value="All">All Records</option>
                                        <?php
                                            $query = "SELECT Visit_TYPE FROM r_visit WHERE Visit_TYPE_STAT = 'Active'";
                                            $purpose = mysqli_query($db,$query);
                                            while ($row = mysqli_fetch_assoc($purpose)) {
                                                echo '
                                                    <option value="'.$row['Visit_TYPE'].'">'.$row['Visit_TYPE'].'</option>
                                                ';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <button class="btn btn-info btn-sm" name="search"><i class="fa fa-mail-forward"></i></button>
                            </div>
                        </form>
                    </div>
                    <br><br><br>
                    <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                        <thead>
                            <tr>
                                <th><strong>Visit Purpose</th>
                                <th>Student Number</th>
                                <th>Student Name</th>
                                <th>Course/Year/Section</th>
                                <th>Date and Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($row = mysqli_fetch_assoc($search_result)) { 
                                    $code=$row['Visit_CODE'];
                                    $purpose=$row['Visit_PURPOSE'];
                                    $no=$row['Stud_NO'];
                                    $name=$row['STUDENT'];
                                    $course=$row['COURSE'];
                                    $date=$row['Visit_DATE'];
                            ?>
                            <tr>
                                <td><strong><?php echo $purpose; ?></td>
                                <td><?php echo $no; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $course; ?></td>
                                <td><?php echo $date; ?></td>
                            </tr>
                            <?php } ?>
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

<!-- Placed js at the end of the document so the pages load faster -->
<?php include('footer.php'); ?>
<!--Core js-->
<script src="js/jquery.js"></script>
<!-- <script src="js/jquery-1.8.3.min.js"></script> -->
<!-- <script src="bs3/js/bootstrap.min.js"></script> -->
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script type="text/javascript" src="js/gritter/js/jquery.gritter.js"></script>
<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--common script init for all pages-->
<!-- <script src="js/scripts.js"></script> -->

<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<script>
    $(document).ready(function(){

        $('#NotifSend').on('submit', function(event){
            
            event.preventDefault();
            var form_data = $(this).serialize();

            $.ajax({
                url:'NotifPost.php',
                method:'POST',
                data:form_data,
                error:function(data)
                {
                    console.log("wewewew");
                },
                success:function(data)
                {   
                    console.log(data);
                    $('#NotifSend')[0].reset();
                    console.log("wewewewewe");
                }
            });
        });
    });
</script> 

</body>
</html>
