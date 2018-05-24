<!DOCTYPE html>
<?php
    session_start();
    if(!$_SESSION['Logged_In'])
    {
        header('Location:LogIn.php');
        exit;
    }
    include('config.php')
?>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>G&CSMS-Files and Documents</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" href="css/jquery.steps.css?1">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>

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
$currentPage ='G&CSMS-Files';
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
                            <a href="#"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-book"></i> Files and Documents</a>
                        </li>
                    </ul>
                </div>
            </div>
        <!-- page start-->

        <div class="row">
            <div class="col-sm-12">
                <div  style="padding:10px; padding-left:0px;">
                    <button data-toggle="modal" href="#AddModal" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add File</button>
                    </div>
                    <!-- <button class="btn btn-primary" name="view" value="View" id="" data-toggle="modal" href="#View"><i class="fa fa-eye"></i></button>
                    <div class="modal fade" id="View" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog" style="width:1000px">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="modal-footer">
                                        <button data-dismiss="modal" class="btn btn-primary" type="button">OK</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                <section class="panel">
                    <header class="panel-heading">
                        Records
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    
                    <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Category</th>
                        <th>Date Uploaded</th>
                        <th class="hidden-phone">Download</th>
                    </tr>
                    </thead>
                    <?php

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql= "SELECT Upload_DATE,Upload_FILENAME,Upload_CATEGORY,Upload_TYPE,Upload_FILETYPE,Upload_FILEPATH from t_upload WHERE Upload_CATEGORY = 'Records'";

$query = mysqli_query($db, $sql);
    
if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_array($query)) 
        {
                   echo'
                    <tr>
                    <td>'.$row[1].'</td>
                    <td>'.$row[2].'</td>
                    <td>'.$row[3].'</td>
                    <td>
                        <a href="'.$row['UPLOAD_FILEPATH'].'" class="confirmation text-primary" >Download</a>
                    </td>
                </tr>';
        }
?> 
<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
                </table>

                    </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Printables
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                         </span>
                    </header>
                    <div class="panel-body">
                    <div  style="padding:10px; padding-left:0px;">
                    </div>
                    <div class="adv-table">
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Category</th>
                        <th>Date Uploaded</th>
                        <th class="hidden-phone">Download</th>
                    </tr>
                    </thead>
                    <?php

// Check connection
if (mysqli_connect_errno())
{
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
  $sql= "SELECT Upload_DATE,Upload_FILENAME,Upload_CATEGORY,Upload_TYPE,Upload_FILETYPE,Upload_FILEPATH from t_upload WHERE Upload_CATEGORY = 'Printables'";

$query = mysqli_query($db, $sql);
    
if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_array($query)) 
        {
                   echo'
                    <tr>
                    <td>'.$row[1].'</td>
                    <td>'.$row[2].'</td>
                    <td>'.$row[3].'</td>
                    <td>
                        <a href="'.$row['UPLOAD_FILEPATH'].'" class="confirmation text-primary" >Download</a>
                    </td>
                </tr>';
        }
?> 
<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>
                </table>

                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>



    
    <!-- Modal -->
    
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="AddModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Upload a File</h4>
                </div>
                <div class="modal-body">
                    <br>
                    <p>You are now uploading a file</p><br>
                    <form action="TypeA_UploadSession.php" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            *File Name <input name="UPLOAD_FILENAME" type="text" class="form-control" placeholder="ex. Request Form" required/>
                        </div>
                        <div class="col-md-4 form-group">
                            *Category
                            <select name="UPLOAD_CATEGORY" type="text" class="form-control" required>
                                <?php
                                    $query = "SELECT Upload_FILE_CATEGORY FROM r_upload_category WHERE Upload_CATEGORY_STAT = 'Active'";
                                    $category = mysqli_query($db,$query);
                                    while ($row = mysqli_fetch_assoc($category)) {
                                        echo '<option value="'.$row["Upload_FILE_CATEGORY"].'">'.$row["Upload_FILE_CATEGORY"].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            *Type
                            <select name="UPLOAD_FILETYPE" type="text" class="form-control" required>
                                <option value="Records">Records</option>
                                <option value="Printables">Printables</option>
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            *File<input accept=".pdf, .doc, .docx, .xls, .xlsx" name="file" type="file" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="save" value="Upload" class="btn btn-success">Upload</button>
                        <button data-dismiss="modal" class="btn btn-cancel" type="button">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--MODAL-->
    <!-- modal -->
    <!--main content end-->

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
