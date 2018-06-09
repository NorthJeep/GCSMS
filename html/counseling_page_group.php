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

    <!--Intellisence-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           

</head>
<body>

<?php 
$currentPage ='G&CSMS-Counseling Services';
include('header.php');
include('sidebarnav.php');


if(isset($_POST['search']))
{
    $valueToSearch = $_POST['filter'];
    // search in all table columns
    // using concat mysql function
    $query = "SELECT `BATCH_ID`,`BATCH_APPROACH`,`BATCH_bg`,`BATCH_goals`,`BATCH_comments`,BATCH_DATE,`BATCH_recomm`
FROM t_batch_group 
WHERE BATCH_APPROACH = '$valueToSearch'
ORDER BY `BATCH_DATE` DESC";
    $search_result = filterTable($query);
    
}
 else {
    $query = "SELECT `BATCH_ID`,`BATCH_APPROACH`,`BATCH_bg`,`BATCH_goals`,`BATCH_comments`,BATCH_DATE,`BATCH_recomm`
FROM `t_batch_group` 
ORDER BY `BATCH_DATE` DESC";
    $search_result = filterTable($query);
} 
function filterTable($query)
{
    $db = mysqli_connect('localhost','root','','g&csms_db');
    $filter_Result = mysqli_query($db, $query);
    return $filter_Result;
}
?>

    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
        <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-edit"></i> Counseling Services</a>
                        </li>
                        <li>
                            <a class="current" href="counseling_page_group.php"><i class="fa fa-users"></i> Group Counseling Records</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Group Counseling Records
                    </header>
                    <div class="panel-body">
                   <div>
                        <a href="#modal" data-toggle="modal" class="btn btn-primary"> Start Counseling</a>
                    </div><br>
                    <div class="col-md-6" style="padding-left:0px">
                    <form action="counseling_page_group.php" method="POST">
                        <div class="row">
                        <div class="col-md-6">
                        <select name="filter" class="form-control input-sm m-bot4">
                            <option value="Behavior Therapy">Behavior Therapy</option>
                                        <option value="Cognitive Therapy">Cognitive Therapy</option>
                                        <option value="Educational Counseling">Educational Counseling</option>
                                        <option value="Holistic Therapy">Holistic Therapy</option>
                                        <option value="Mental Health Counseling">Mental Health Counseling</option>
                        </select>
                        </div>
                        <button class="btn btn-info btn-sm" name="search"><i class="fa fa-mail-forward"></i></button>
                        </div>
                    </form></div>
                    <div class="adv-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">
                    <!--<table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">-->
                    <thead>
                    <tr>
                        <th>Group Number</th>
                        <th class="hidden-phone">Counseling Approach</th>
                        <th class="hidden-phone">Counseling Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
include('config.php');

  /*$sql= "SELECT `BATCH_ID`,`BATCH_APPROACH`,`BATCH_bg`,`BATCH_goals`,`BATCH_comments`,`BATCH_DATE`,`BATCH_recomm`,`grp_COUNSELING_ID`,
  `grp_STUD_NO`,`grp_STUD_NAME`,`grp_id`
FROM `t_batch_group` 
INNER JOIN t_counseling_group On grp_id = BATCH_ID
ORDER BY `BATCH_DATE` DESC";*/
$sql= "SELECT `BATCH_ID`,`BATCH_APPROACH`,`BATCH_bg`,`BATCH_goals`,`BATCH_comments`,`BATCH_DATE`,`BATCH_recomm`
FROM `t_batch_group` 
ORDER BY `BATCH_DATE` DESC";

$query = mysqli_query($db, $sql);

if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_assoc($search_result)) 
        {       $batch_ID=$row['BATCH_ID']; 
                $app=$row['BATCH_APPROACH'];
                $bg=$row['BATCH_bg'];
                $goals=$row['BATCH_goals'];
                $comments=$row['BATCH_comments'];
                $recomm=$row['BATCH_recomm'];
                $date=$row['BATCH_DATE'];
               // $name=$row["grp_STUD_NAME"];
    ?>
                    <tr>
                    <td><?php echo $batch_ID; ?></td>
                    <td><?php echo $app; ?></td>
                    <td><?php echo $date; ?></td>
                    <td><button class="btn btn-primary" name="view" value="View" id="" data-toggle="modal" href="#Viewmodal<?php echo $batch_ID; ?>"
                    <i class="fa fa-eye"> View</i></button></td>
               </tr>
                    </tfoot>
                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!--Second-->
        
        <!-- page end-->
        </section>
    </section>
                <!--MODALSSSS-->
                            <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modal" class="modal fade" method="submit">
                            <div class="modal-dialog">
                                <div class="modal-content" >
                                    <div class="modal-header">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4>Type of Counseling:</h4>
                                    </div>
                                    <div class="modal-body">
                                    <div class="form-group" id="selectType" style="text-align:center; padding:20px 0px">
                                            <button type="" class="btn btn-primary" onclick="ShowInput()">
                                            <i class="fa fa-user"></i> Individual Counseling</button>
                                            <button type="" class="btn btn-primary" onclick="document.location.href='counseling_services_group.php'">
                                            <i class="fa fa-users"></i> Group Counseling</button>
                                    </div>
                                    <form method="POST" action="counseling_services.php">
                                    <div id="input"style="display:none">
                                    <p>Input student's number and student's name before proceeding</p><br/>
                                        <form role="form" action="">
                                            <div class="form-group">
                                                <label for="studentnumber">Student Number</label><br/><br/>
                                                <input type="text" class="form-control" name="student_no" placeholder="20XX-XXXXX-CM-0">
                                            <div id="studnt_no_list"></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="studentname">Name</label><br/><br/>
                                                <input type="text" class="form-control" name="student_name" placeholder="Fullname">
                                            <div id="studnt_no_list"></div>
                                            </div>
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </form>
                                            <button type="submit" onclick="ShowPrev()" class="btn btn-cancel" >Cancel</button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                <!--MODALSSSS-->
                            <div class="modal fade" id="Viewmodal<?php echo $batch_ID; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#07847d; color:white">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-user"></i>&nbsp Batch: 
                                            <?php 
                                            include("config.php");

                                            $sql= "SELECT `BATCH_ID`,`BATCH_APPROACH`,`BATCH_bg`,`BATCH_goals`,`BATCH_comments`,`BATCH_DATE`,`BATCH_recomm`,`grp_COUNSELING_ID`,
                                                  `grp_STUD_NO`,`grp_STUD_NAME`,`grp_id`
                                                FROM `t_batch_group` 
                                                INNER JOIN t_counseling_group On grp_id = BATCH_ID
                                                WHERE BATCH_ID='$batch_ID'
                                                ORDER BY `BATCH_DATE` DESC";

                                            $queryy = mysqli_query($db, $sql);
                                            if (!$queryy) {
                                                    die ('SQL Error: ' . mysqli_error($db));
                                                }
                                                while ($row = mysqli_fetch_assoc($queryy)) 
                                            { 
                                                echo $batch_ID;

                                                $name=$row['grp_STUD_NAME'];

                                             ?></h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="col-md-12 well">
                                            <h4 class="col-md-8"style="padding-left:0px"><i class="fa fa-pencil"></i>&nbsp <?php echo $name; ?></h4>
                                            <h6 style="text-align:right"><i class="fa fa-calendar"></i>&nbsp <?php echo $date; ?></h6>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="well">
                                                <h4>Background of the Case:</h4>
                                                    <?php echo $bg; ?>
                                             </div>
                                             <div class="well">
                                                <h4>Counseling Goals:</h4>
                                                    <?php echo $goals; ?>
                                             </div>
                                             <div class="well">
                                                <h4>Comments:</h4>
                                                    <?php echo $comments; ?>
                                             </div>
                                             <div class="well">
                                                <h4>Recommendations:</h4>
                                                    <?php echo $recomm; ?>
                                             </div>

                <?php }  ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="button">OK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                </div>
                </section>
                </section>
                <?php }  ?>
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

<?php include('footer.php'); ?>
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
<script src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>

<script src="js/iCheck/jquery.icheck.js"></script>

<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>

<!--common script init for all pages-->
<!-- <script src="js/scripts.js"></script> -->

<!--icheck init -->
<script src="js/icheck-init.js"></script>

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>

<!--dynamic table initialization -->
<script src="js/dynamic_table_init1.js"></script>

<script>
    function ShowInput(){

        document.getElementById("selectType").style.display="none";
        document.getElementById("input").style.display="";
    }
</script>
<script>
    function ShowPrev(){

        document.getElementById("selectType").style.display="";
        document.getElementById("input").style.display="none";
    }
</script>

<script> 
 $(document).ready(function(){  

      $('#student_name').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"search.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#studnt_no_list').fadeIn();  
                          $('#studnt_no_list').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#student_name').val($(this).text());  
           $('#studnt_no_list').fadeOut();  
      });  
 });  
 </script>  

</body>
</html>
