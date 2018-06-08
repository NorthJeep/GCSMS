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
    $query = "SELECT t.COUNSELING_ID, t.COUNSELING_TYPE_CODE, t.STUD_ID, t.STUD_NO, t.STUD_NAME, t.STUD_COURSE, t.STUD_YR, t.STUD_SECTION, 
              t.STUD_CONTACT, t.STUD_EMAIL, t.STUD_ADDRESS, t.COUNS_APPROACH, t.COUNS_BG, t.COUNS_GOALS, t.COUNS_PREV_TEST, t.COUNS_PREV_PERSON, 
              t.COUNS_COMMENTS, t.COUNS_RECOMM, t.COUNS_APPOINTMENT_TYPE, t.COUNS_DATE, r.COUNS_APPROACH_CODE, r.COUNS_APPROACH_NAME
FROM `t_counseling` as t
INNER JOIN `r_couns_approach` as r ON t.COUNS_APPROACH = r.COUNS_APPROACH_CODE WHERE `COUNS_APPROACH`='$valueToSearch'
ORDER BY t.COUNS_DATE DESC";
    $search_result = filterTable($query);
    
}
 else {
    $query = "SELECT  t.COUNSELING_ID, t.COUNSELING_TYPE_CODE, t.STUD_ID, t.STUD_NO, t.STUD_NAME, t.STUD_COURSE, t.STUD_YR, t.STUD_SECTION, 
              t.STUD_CONTACT, t.STUD_EMAIL, t.STUD_ADDRESS, t.COUNS_APPROACH, t.COUNS_BG, t.COUNS_GOALS, t.COUNS_PREV_TEST, t.COUNS_PREV_PERSON, 
              t.COUNS_COMMENTS, t.COUNS_RECOMM, t.COUNS_APPOINTMENT_TYPE, t.COUNS_DATE, r.COUNS_APPROACH_CODE, r.COUNS_APPROACH_NAME
FROM `t_counseling` as t
INNER JOIN `r_couns_approach` as r ON t.COUNS_APPROACH = r.COUNS_APPROACH_CODE
ORDER BY t.COUNS_DATE DESC";
    $search_result = filterTable($query);
} 
function filterTable($query)
{
    $db = mysqli_connect('localhost','root','oliver','pupqcdb');
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
                            <a class="current" href="counseling_page.php"><i class="fa fa-user"></i> Individual Counseling Records</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Individual Counseling Records
                    </header>
                    <div class="panel-body">
                   <div style="padding-left:0px; padding-top:0px">
                        <a href="#modal" data-toggle="modal" class="btn btn-primary"> Start Counseling</a>
                    </div><br>
                    <div class="col-md-6" style="padding-left:0px">
                    <form action="counseling_page.php" method="POST">
                        <div class="row">
                        <div class="col-md-6">
                        <select name="filter" class="form-control input-sm m-bot4" placeholder="Counseling Approaches">
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
                    <table  class="display table table-bordered table-striped" id="dynamic-table">
                    <!--<table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="hidden-table-info">-->
                    <thead>
                    <tr>
                        <th>Student Number</th>
                        <th class="hidden-phone">Student Name</th>
                        <th class="hidden-phone">Counseling Type</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                    <?php
include('config.php');

 /* $sql= "SELECT  t.COUNSELING_ID, t.COUNSELING_TYPE_CODE, t.STUD_ID, t.STUD_NO, t.STUD_NAME, t.STUD_COURSE, t.STUD_YR, t.STUD_SECTION, 
              t.STUD_CONTACT, t.STUD_EMAIL, t.STUD_ADDRESS, t.COUNS_APPROACH, t.COUNS_BG, t.COUNS_GOALS, t.COUNS_PREV_TEST, t.COUNS_PREV_PERSON, 
              t.COUNS_COMMENTS, t.COUNS_RECOMM, t.COUNS_APPOINTMENT_TYPE, DATE_FORMAT(t.COUNS_DATE,'%W %M %e %Y'), r.COUNS_APPROACH_CODE, r.COUNS_APPROACH_NAME
FROM `t_counseling` as t
INNER JOIN `r_couns_approach` as r ON t.COUNS_APPROACH = r.COUNS_APPROACH_CODE
ORDER BY t.COUNS_DATE DESC";*/

$sql = "select * from student_counseling where COUNSELING_TYPE = 'Individual Counseling' order by COUNSELING_DATE desc";
$search_result = mysqli_query($db, $sql);

if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_assoc($search_result)) 
        {       //$ID=$row['COUNSELING_ID']; 
                $no=$row['STUD_NO'];
                $name=$row['STUD_NAME'];
                $app=$row['COUNSELING_APPROACH'];
                $type=$row['COUNSELING_TYPE'];
                $bg=$row['COUNSELING_BG'];
                $goals=$row['GOALS'];
                $comments=$row['COUNS_COMMENT'];
                $recomm=$row['RECOMMENDATION'];
                $apptype=$row['APPOINTMENT_TYPE'];
                $date=$row['COUNSELING_DATE'];
    ?>
                    <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $type; ?></td>
                    <td><button class="btn btn-primary" name="view" value="View" id="" data-toggle="modal" href="#Viewmodal<?php echo $no; ?>"
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
                                    <!--<div class="form-group" id="selectType" style="text-align:center; padding:20px 0px">
                                            <button type="" class="btn btn-primary" onclick="ShowInput()">
                                            <i class="fa fa-user"></i> Individual Counseling</button>
                                            <button type="" class="btn btn-primary" onclick="document.location.href='counseling_services_group.php'">
                                            <i class="fa fa-users"></i> Group Counseling</button>
                                    </div>-->
                                    <form method="POST" action="counseling_services.php">
                                    <div id="input"style="">
                                    <p>Input student's number and student's name before proceeding</p><br/>
                                        <form role="form" action="">
                                            <div class="form-group col-md-4">
                                                <label for="studentnumber">Student Number</label><br/><br/>
                                                <input id="student_no" type="text" class="form-control" name="student_no" placeholder="20XX-XXXXX-CM-0" required>
                                            <div class="studnt_no_list1"></div>
                                            </div>
                                            <div class="form-group col-md-8">
                                                <label for="studentname">Name</label><br/><br/>
                                                <input id="student_name" type="text" class="form-control" name="student_name" placeholder="Fullname">
                                            <div class="studnt_no_list2"></div>
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
                            <div class="modal fade" id="Viewmodal<?php echo $no; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog" style="width:1000px">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#07847d; color:white">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title"><i class="fa fa-user"></i>&nbsp <?php echo $name; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="col-md-12 well">
                                            <h4 class="col-md-8"style="padding-left:0px"><i class="fa fa-pencil"></i>&nbsp <?php echo $app; ?></h4>
                                            <h5 class="col-md-4" style="text-align:right"><i class="fa fa-thumb-tack"></i>&nbsp <?php echo $apptype; ?></h5>
                                            <h6 style="text-align:right"><i class="fa fa-calendar"></i>&nbsp <?php echo $date; ?></h6>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="well">
                                                <h4>Background of the Case:</h4>
                                                   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo $bg; ?>
                                             
                                                <h4>Counseling Goals:</h4>
                                                   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo $goals; ?>
                                             
                                                <h4>Comments:</h4>
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo $comments; ?>
                                             
                                                <h4>Recommendations:</h4>
                                                    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp <?php echo $recomm; ?>
                                             </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-primary" type="button">OK</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </div>
                </div>
                </section>
                </section>
                <?php } ?>
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

<!--<script>
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
</script>-->
    <script>  
     $(document).ready(function(){  
          $('#student_name').keyup(function(){  
               var query = $(this).val();  
               if(query != '')  
               {  
                    $.ajax({  
                         url:"search.php",  
                         type:"POST",  
                         data:{query:query},  
                         success:function(data)  
                         {  
                              $('.studnt_no_list2').fadeIn();  
                              $('.studnt_no_list2').html(data);  
                         }  
                    });
                    $.ajax({  
                         url:"search2.php",  
                         type:"POST",  
                         data:{query:query},  
                         success:function(data)  
                         {  
                              $('.studnt_no_list1').fadeIn();  
                              $('.studnt_no_list1').html(data);  
                         }  
                    });
               }
               else
               {
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               }
          });  

          $('#student_no').keyup(function(){  
               var query = $(this).val();  
               if(query != '')  
               {  
                    $.ajax({  
                         url:"search3.php",  
                         type:"POST",  
                         data:{query:query},  
                         success:function(data)  
                         {  
                              $('.studnt_no_list2').fadeIn();  
                              $('.studnt_no_list2').html(data);  
                         }  
                    });
                    $.ajax({  
                         url:"search4.php",  
                         type:"POST",  
                         data:{query:query},  
                         success:function(data)  
                         {  
                              $('.studnt_no_list1').fadeIn();  
                              $('.studnt_no_list1').html(data);  
                         }  
                    });
               }
               else
               {
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               }
          });  
          $(document).on('click', 'li', function(){  
                $('#student_name').val($(this).text());
                $('#student_no').val($(this).text());
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               // $('.studnt_no_list').fadeOut();  
              

          });
          $(document).on('click', '.search-item-name', function(){  
                
                $('#student_no').val($(this).find(".student-no-hide").text());
                $(this).find(".student-no-hide").empty();
                $('#student_name').val($(this).text());
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               // $('.studnt_no_list').fadeOut();  
              

          });
          $(document).on('click', '.search-item-no', function(){  
                
                $('#student_name').val($(this).find(".student-name-hide").text());
                $(this).find(".student-name-hide").empty();
                $('#student_no').val($(this).text());
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               // $('.studnt_no_list').fadeOut();  
              

          });

          
     });  
     </script>  

</body>
</html>
