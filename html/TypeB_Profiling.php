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

    <title>G&CSMS-Students Profiles</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    
<link href="css/reset.css" rel="stylesheet" type="text/css">
<link href="css/style.css" rel="stylesheet" type="text/css">
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

<!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />
    <link rel="stylesheet" href="css/jquery.steps.css?1">

    <!-- Custom styles for this template -->
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!--Intellisence-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
           

</head>
<body>
<?php 
$currentPage ='G&CSMS-Profiling';
include('TypeB_Header.php');
include('TypeB_SideBar.php');
?>
<!--sidebar end-->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
        <!-- page start-->
        <!-- page start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumbs-alt">
                        <li>
                            <a href="#"><i class="fa fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-user"></i> Profiling</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                        Students Profile
                    </header>
                    <div class="panel-body">
                    <!-- <div>
                    <button data-toggle="modal" href="#Add" class="btn btn-primary">
                                <i class="fa fa-plus"></i>   Add</button>
                    <button href="#ImportModal" data-toggle="modal" class="btn btn-warning">
                                <i class="fa fa-plus"></i> Import</button>
                    </div> -->
                    <div class="adv-table">
                    <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered" id="dynamic-table">
                    <thead>
                    <tr>
                        <th>Student Number</th>
                        <th class="hidden-phone">Student Name</th>
                        <th class="hidden-phone">Course/Year/Section</th>
                        <th class="hidden-phone">Status</th>
                        <th>Action</th>
                    </tr>
                    <tbody>
                    <?php
include('config.php');

  $sql= "SELECT * FROM student_profiling";

$query = mysqli_query($db, $sql);

if (!$query) {
    die ('SQL Error: ' . mysqli_error($db));
}

    /* fetch object array */
    while ($row = mysqli_fetch_assoc($query)) 
    {
                // $ID =$row['ID'];
                $NO =$row['STUD_NO'];
                $FULLNAME=$row['FULLNAME'];
                $COURSE=$row['COURSE'];
                $STATUS=$row['STUD_STATUS'];

                  ?>

                    <tr>
                    <td><?php echo $NO; ?></td>
                    <td><?php echo $FULLNAME; ?></td>
                    <td><?php echo $COURSE; ?></td>
                    <td><?php echo $STATUS; ?></td>
                    <td><button class="btn btn-primary action-button stud_id" name="view" value="View" data-toggle="modal" href="#myModal<?php echo $NO; ?>" />
                    <i class="fa fa-eye"> View</i></button></td>  
               </tr>
                    </tfoot>
                    </div>
                    </div>
                </section>
            </div>
        </div>
        <!-- page end-->
        </section>
    </section>
                <!-- Modal -->
                            <div class="modal fade" id="myModal<?php echo $NO; ?>" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color:#07847d; color:#fff">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title">Student Details</h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class='twt-feed' style="background-color:#07847d; padding:15px;">
                                        <div class="row">
                                            <div class="col-md-4 modal-con">

                                                <input type="text" class="imgText" id="stud-img-id<?php echo $NO; ?>" name="stud_img_id" style="display:none" value="<?php echo $NO;?>">
                                                <input type="file" class="imgupload" style="display:none" accept="image/*" onchange="showMyImage(this)" name="stud_img"/> 
                                                <?php 
                                                    if(file_exists("images/".$NO.".png"))
                                                    {
                                                        echo ' <img src="images/'.$NO.'.png" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                    elseif(file_exists("images/".$NO.".jpg"))
                                                    {
                                                        echo ' <img src="images/'.$NO.'.jpg" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                    else
                                                    {
                                                        echo ' <img src="images\user.ico" alt="images\user.ico" id="img'.$NO.'" style=" height:140px;padding-left:10px; padding-top:10px;" class="OpenImg"></img>';
                                                    }
                                                ?>

                                               


                                                <h3><span id="FULLNAME">  <?php echo $FULLNAME?></span></h3>
                                                <h5> <?php echo $NO; ?> </h5>
                                                <h5> <?php echo $COURSE; ?> </h5>
                                                Status: 
                                                <form class="form-inline" method="POST" action="update_status.php">
                                                <div>
                                                <input type="text" id="status_id" name="status_id" style="display:none" value="<?php echo $NO;?>">
                                                <input type="text" id="stud_status" name="stud_status" 
                                                class="form-control" style="width:80px" placeholder="<?php echo $STATUS; ?>" disabled> 
                                                <button type="submit" class="btn btn-success" name="ok_status" id="ok_status" style="display:none"><i class="fa fa-check"></i>
                                                </form>
                                                <!-- <button class="btn btn-warning" id="edit_status"onclick="EditStatus()"><i class="fa fa-pencil"></i></button> -->
                                                </div>
                                            </div>
                                        <div class="col-md-8">
                                            <blockquote style="background-color:#03605b; height:100px;">
                                                <h4>Sanction:</h4>
                                                <span class="label label-warning"><i class="fa fa-exclamation"></i> Warning: 18hrs</span>
                                            </blockquote>
                                            <!-- <blockquote style="background-color:#03605b; height:150px">
                                                <h4>Counseling Remarks:</h4>
                                                <h5 id="remarkstxt" name="remarkstxt">Follow Up</h5>
                                                <br/>
                                                <form method="POST" action="counseling_services.php">
                                                  <input type="text" id="student_no" name="student_no" style="display:none;" value="<?php echo $NO; ?>"></p>
                                                  <input type="text" id="student_name" name="student_name" style="display:none;" value="<?php echo $FULLNAME; ?>"></p>
                                            <button type="submit" class="btn btn-success" href="counseling_services.php"><i class="fa fa-edit"></i> Start Counseling</button>
                                            </form>
                                            <button class="btn btn-info" id="viewCouns" href="visit_logs.php"><i class="fa fa-eye"></i> View History</button>
                                            </blockquote> -->
                                            </div>
                                            </div>
                                        </div>
                                        <div class="panel-group" id="accordion">
                                          <div class="panel panel-default" style=" padding-top:5px;">
                                            <div class="panel-heading"  style="background-color:#07847d">
                                              <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="color:#FFF">
                                                Visits</a>
                                              </h4>
                                            </div>
                                            <div id="collapse1" class="panel-collapse collapse in">
                                              <div class="panel-body">
                                              <form action="addVisit.php" method="POST">
                                                  <input type="text" id="V_s_ID" name="V_s_ID" style="display:none;" value="<?php echo $ID; ?>"></p>
                                                  <input type="text" id="V_s_name" name="V_s_name" style="display:none;" value="<?php echo $FULLNAME; ?>"></p>
                                                  <input type="text" id="V_s_no" name="V_s_no" style="display:none;" value="<?php echo $NO; ?>"></p>
                                                  <input type="text" id="V_s_course" name="V_s_course" style="display:none;"value="<?php echo $COURSE; ?>"></p>
                                                    <div class="col-lg-5">
                                                    <select style="width:180px" name="txtcode" id="e9" class="populate" required="">
                                    
                                        <option value="Excuse">Excuse Letter</option>
                                        <option value="CoC">Cert of Candidacy</option>
                                        <option value="Clearance">Clearance</option>
                                    </select>    
                                                    </div>
                                                    <div class="col-lg-5">
                                                        <input type="text" id="txtdetails" name="txtdetails" class="form-control col-lg-2" placeholder="Other Details...">
                                                    </div>
                                                    <div class="col-lg-1">
                                                        <button type="submit" class="btn btn-success" value=""><i class="fa fa-plus"></button></i>
                                                    </div>
                                              </form>
                                                    <div class="col-lg-1">
                                                        <button type="" class="btn btn-primary" id="viewVisit"><i class="fa fa-eye"></button></i>
                                                    </div>
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
                                        <div class="modal-footer">
                                            <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                                            <button class="btn btn-success" type="button">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
    
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
             <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="ImportModal" class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:#07847d; color:#fff">
                                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                        <h4 class="modal-title">Import Students</h4>
                                    </div>
                                    <div class="modal-body">
                                         <form method="post" action="profiling\import_excel.php" enctype="multipart/form-data">

                                                 <div class="form-group">
                                                <br/><input type="file" name="excelfile" id="excelfile"><br/>
                                                </div>
                                                 <div class="modal-footer">
                                               <button class="btn btn-primary"><i class="fa fa-check"></i> Upload</button>
                                                 </div>

                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
    <!-- modal -->
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

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<script src="js/jquery.js"></script>
<!-- <script src="bs3/js/bootstrap.min.js"></script> -->
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>


<script src="js/iCheck/jquery.icheck.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>

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

<script>
    var global_href = '';
    var global_img_id = '';
    var global_txt = '';

    $(".stud_id").on('click', function(){  
          
           console.log($(this).attr("href"));
           var modalhref = $(this).attr("href");
           global_href = $(this).attr("href");
           $(modalhref).find('.OpenImg').click(function(){ $(modalhref).find('.imgupload').trigger('click'); });
           global_img_id =  $(modalhref).find('.OpenImg').attr('id');
           global_txt =  $(modalhref).find('.imgText').attr('id');
           // if(stud_id != '')  
           // {  
           //      $.ajax({  
           //           url:"viewprofile.php",  
           //           method:"POST",  
           //           data:{stud_id:stud_id},  
           //           success:function(data){  
           //                $('#employee_detail').html(data);  
           //                $('#dataModal').modal('show');  
           //           }  
           //      });  
           // }            
      });  
</script>
<script>
    function EditStatus(){
        document.getElementById("stud_status").removeAttribute("disabled");
        document.getElementById("ok_status").style.display="";
        document.getElementById("edit_status").style.display="none";
    }
</script>
<script>
    // function showDetails(button){
    //     var stud_id = button.id;
    //     $.ajax ({
    //         url:"viewprofile.php",
    //         method:"GET",
    //         data: ("stud_id":stud_id),
    //         success:function(response){
    //             var student = JSON.parse(response);
    //             $("#FULLNAME").text(student.STUD_FNAME);
    //             $("#title").text(student.STUD_FNAME);
    //         }
    //     });
    // }
</script>
<script>
    var btn = document.getElementById('viewVisit');
btn.addEventListener('click', function() {
  document.location.href = 'visit_logs.php';
});
</script>
<script>
    var btn = document.getElementById('viewCouns');
btn.addEventListener('click', function() {
  document.location.href = 'counseling_page.php';
});
</script>

<script>
    // 
    // $('.OpenImg').click(function(){ $('.imgupload').trigger('click'); });
</script>

<script>
$(document).ready(function()
    {
        $(".action-button").click(function()
        {
            // $("#editOrdID").val($(this).closest("tbody tr").find("td:eq(0)").html());
            // $("#editOrdTitle").val($(this).closest("tbody tr").find("td:eq(1)").html());

        });
    });



function showMyImage(fileInput)
{
    var files = fileInput.files;

    var FileName = document.getElementById(global_txt).value;

    var file = fileInput.files[0];
    var fd = new FormData();
    fd.append('imageFile', file);
    fd.append('filename', FileName);

    $.ajax({
            type: 'POST',
            url: 'stud_img.php',
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) 
            {
                
            }
        });

    for (var i = 0; i < files.length; i++) 
    { 
        var file = files[i];
        var imageType = /image.*/; 
        if (!file.type.match(imageType)) 
        {
            continue;
        }
        
        
        // var img = document.getElementsByClassName('OpenImg'); 
        var img = document.getElementById(global_img_id);
        img.file = file; 
        var reader = new FileReader();
        reader.onload = (function(aImg) 
        { 
            return function(e) 
                { 
                
                    aImg.src = e.target.result; 


                }; 
        })(img);
        reader.readAsDataURL(file);
    } 
}
</script>
            
</script>
</body>
</html>
