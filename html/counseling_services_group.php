<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Counseling Services</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
   
    <link rel="stylesheet" href="css/bootstrap-switch.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-multi-select/css/multi-select.css" />
    <link rel="stylesheet" type="text/css" href="js/jquery-tags-input/jquery.tagsinput.css" />

    <link rel="stylesheet" type="text/css" href="js/select2/select2.css" />

    <!--icheck-->
    <link href="js/iCheck/skins/minimal/minimal.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/red.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/green.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/blue.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/yellow.css" rel="stylesheet">
    <link href="js/iCheck/skins/minimal/purple.css" rel="stylesheet">

    <!--dynamic table-->
    <link href="js/advanced-datatable/css/demo_page.css" rel="stylesheet" />
    <link href="js/advanced-datatable/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/data-tables/DT_bootstrap.css" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- <link href="data-tables/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection">   -->

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
$currentPage ='G&CSMS-Counseling Services';
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
                            <a href="index.php"><i class="fa fa-home"></i> Home</a>
                        </li><li>
                            <a href="counseling_page.php"><i class="fa fa-edit"></i> Counseling Services</a>
                        </li>
                        <li>
                            <a class="current" href="#"><i class="fa fa-user"></i> Group Counseling</a>
                        </li>
                    </ul>
                </div>
            </div>
<div class="row">
            <div class="col-sm-12">
                <section class="panel">
                    <header class="panel-heading">
                       Group Counseling
                        <span class="tools pull-right">
                            <a href="javascript:;" class="fa fa-chevron-down"></a>
                            <a href="javascript:;" class="fa fa-cog"></a>
                            <a href="javascript:;" class="fa fa-times"></a>
                         </span>
                    </header>
            <section class="panel">
                    <div class="panel-body">
                          
                                        <form action="addtrytry.php" method="POST">

                                            <div class="form-content">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <p><button type="button" id="btnAdd" class="btn btn-primary" ><i class="fa fa-plus"></i> Add Student(s)</button></p>
                                                    </div>
                                                </div>
                                                <?php  

                                                     include('config.php');

                                                    {
                                                        
                                                    $result = mysqli_query($db, "SELECT MAX(Batch_ID) FROM t_batch_group");
                                                    $row = mysqli_fetch_array($result);
                                                    $last = $row[0];
                                                    $finalid = $last + 1;

                                                ?>

                                                 <div class="form-group">
                                                    <input type="hidden" name="batch_id" value="<?php echo $finalid; ?>">
                                                </div> <?php } ?>
                                                 
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div style="padding: 1px; margin-bottom: 10px; background-color: #E0E1E7;">                                                             
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row group">                                                        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Student Number</label>
                                                            <input maxlength="150" type="text" name="stud_no[]" class="form-control student_no2" required="" style="color: black;" placeholder="Input student number..." />

                                                            <div class="studnt_no_list1"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Student Name</label>
                                                            <input style="color: black; padding-right: 2px;" type="text" name="stud_name[]" class="form-control student_name2" required="" max="150" placeholder="Input student name..."/>
                                                            
                                                            <div class="studnt_no_list2"></div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            <button type="button" class="btn btn-danger btnRemove" style="margin-top: 23px;">Remove</button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div style="padding: 1px; margin-bottom: 10px; background-color: #E0E1E7;">                                                 
                                                        </div>
                                                    </div>

                                                </div>  
                                            </div>

                    <div class="col-md-12">
                    <div id="wysiwyg" name="wysiwyg" style="padding-left:3px">
                            <div class="form-group">
                                <BR/>
                                <h5 style=" padding-left:17px"><strong>I.&nbsp&nbsp&nbsp&nbspBackground of the Case:</strong></h5>
                                <div class="col-md-10">
                                    <textarea name="C_couns_bg" id="C_couns_bg" class="form-control" rows="9"></textarea>
                                </div>
                            </div>
                    </div>
                    </div>
                    <!--MULTISELECT-->
                    <div class="form-group col-md-12" style="padding-top:20px">
                    <h5 style=" padding-left:20px"><strong>II.&nbsp&nbsp&nbsp&nbspCounseling Plan:</strong></h5><br/>
                                <label class="col-lg-3 control-label" style="font-size:14px">&nbsp&nbsp&nbsp&nbsp&nbspa.&nbsp&nbsp&nbspCounseling Approach(es):</label>
                                <div class="col-lg-6">
                                    <select multiple name="C_approach" id="e9" style="width:400px" class="populate">
                                        <option value="Behavior Therapy">Behavior Therapy</option>
                                        <option value="Cognitive Therapy">Cognitive Therapy</option>
                                        <option value="Educational Counseling">Educational Counseling</option>
                                        <option value="Holistic Therapy">Holistic Therapy</option>
                                        <option value="Mental Health Counseling">Mental Health Counseling</option>
                                    </select>
                                </div>
                                <br/><br/><br/><br/>
                                <div class="col-md-10">
                                <h5><strong>&nbsp&nbsp&nbsp&nbsp&nbspb.&nbsp&nbsp&nbspCounseling Goals:</strong></h5>
                                    <textarea name="C_goals" id="C_goals" class="form-control" rows="9"></textarea>
                                <br/><br/>
                                </div>
                                <div class="col-md-10">
                                <h5><strong>IV.&nbsp&nbsp&nbsp&nbspComments:</strong></h5>
                                    <textarea name="C_comments" id="C_comments" class="form-control" rows="9"></textarea>
                                <br/><br/>
                                </div>
                                <div class="col-md-10">
                                <h5><strong>V.&nbsp&nbsp&nbsp&nbspRecommendations:</strong></h5>
                                    <textarea name="C_recomm" id="C_recomm" class="form-control" rows="9"></textarea>
                                <br/><br/>
                                </div>
                                </div>
                                </div>
                                <div style="text-align:center">
                                <a data-toggle="modal" name="insert" class="btnInsert btn btn-primary" href="#Continue" type="submit">Save</a>
                                <button data-dismiss="modal" class="btn btn-cancel" type="button">Cancel</button>
                                </div>
                                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="Continue" class="modal fade">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header"style="background-color:#07847d; color:white">
                                                                 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                 <h5>Save Group Counseling</h5>
                                                            </div>
                                                            <h5 style="padding-top:20px; text-align: center;">You won't be able to edit the following informations. Do you still want to proceed?</h5>
                                                            <br>
                                                            <div class="panel" style="height: 50%; width: 100%; text-align: center;">
                                                                <br>
                                                                <button type="submit" class="btn btn-primary btn-lg" name="insertonly">
                                                                <i class="fa fa-check"></i>   Yes   </button> &nbsp&nbsp&nbsp&nbsp
                                                                <button data-dismiss="modal" class="btn btn-error btn-lg">
                                                                <i class="fa fa-ban"></i>   No</button>
                                                            </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                            </div>

                                </form>
                                </div>
                                </div>
                            <!--END-->
                    </form>
                                    </td> 
                                </tr>
                            </table>
                            </div>
                    </div>
                </section>
            </div>
</div>
<!--right sidebar start-->
<!--right sidebar end-->

</section>

<!-- Placed js at the end of the document so the pages load faster -->

<!--Core js-->
<!-- <script src="js/jquery.js"></script> -->

<script src="js/jquery-1.8.3.min.js"></script>
<script src="bs3/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
<script src="js/jquery.scrollTo.min.js"></script>
<script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
<script src="js/jquery.nicescroll.js"></script>
<script src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="js/easypiechart/jquery.easypiechart.js"></script>

<script src="js/bootstrap-switch.js"></script>

<script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script><!-- 
<script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script> -->
<script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
<script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

<script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

<script src="js/iCheck/jquery.icheck.js"></script>


<script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

<script src="js/select2/select2.js"></script>
<script src="js/select-init.js"></script>
<!--Easy Pie Chart-->
<script src="js/easypiechart/jquery.easypiechart.js"></script>
<!--Sparkline Chart-->
<script src="js/sparkline/jquery.sparkline.js"></script>
<!--jQuery Flot Chart-->
<!-- <script src="js/flot-chart/jquery.flot.js"></script>
<script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
<script src="js/flot-chart/jquery.flot.resize.js"></script>
<script src="js/flot-chart/jquery.flot.pie.resize.js"></script> -->

<!--dynamic table-->
<script type="text/javascript" language="javascript" src="js/advanced-datatable/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/data-tables/DT_bootstrap.js"></script>
<!--common script init for all pages-->
<!-- <script src="js/scripts.js"></script> -->

<!--dynamic table initialization -->
<script src="js/dynamic_table_init.js"></script>

<!-- <script src="js/advanced-form.js"></script> -->
<script src="js/toggle-init.js"></script>
<!--icheck init -->
<script src="js/icheck-init.js"></script>
<!-- <script src="js/advanced-form.js"></script> -->

<script> 
$(document).ready(function(){  
            addButtonEvent();
          $('.student_name2').on("keyup",function(){   
                // console.log("WTF");
               var query = $(this).val();
               
               if(query != '')  
               {  
                    $.ajax({  
                         url:"search.php",  
                         type: "POST",  
                         data:{query:query},  
                         success:function(data)  
                         {  
                            // console.log(data);
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

          $('.student_no2').on("keyup",function(){  
            // console.log("WTF");
               var query = $(this).val();
               // console.log("QUERY: "+query);  
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
          // $(document).on('click', 'li', function(){  
          //       // $('.student_name').val($(this).text());
          //       // $('.student_no').val($(this).text());
          //       // $(".studnt_no_list1").empty();
          //       // $(".studnt_no_list2").empty();
          //      // $('.studnt_no_list').fadeOut();  
              

          // });

        $("#btnAdd").on("click",function(){
            addButtonEvent();
        });
       


        function addButtonEvent(){
             $(".form-content").on('click', '.search-item-name', function(){  
                
                // $(this).parent().find('.student_no2').val($(this).find(".student-no-hide").text());
                $('.student_no2').val($(this).find(".student-no-hide").text());
                 // $(this).parent().find(".student-no-hide").empty();
                $(this).find(".student-no-hide").empty();
                // $(this).parent().find('.student_name2').val($(this).text());
                $('.student_name2').val($(this).text());
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               // $('.studnt_no_list').fadeOut();  
              

          });
           $(".form-content").on('click', '.search-item-no', function(){  
            console.log("reached");
                // $(this).closest('div').find('.student_name2').val($(this).find(".student-name-hide").text());
                $('.student_name2').val($(this).find(".student-name-hide").text());
                $(this).find(".student-name-hide").empty();
                $('.student_no2').val($(this).text());
                $(".studnt_no_list1").empty();
                $(".studnt_no_list2").empty();
               // $('.studnt_no_list').fadeOut();  
              

          });
        }
         
    });  
</script>  

<!-- <script type="text/javascript">
$(document).ready(function(){ 


        "use strict";
        
        $('.btn-message').click(function(){
            swal("Here's a message!");
        });
        
        $('.btn-title-text').click(function(){
            swal("Here's a message!", "It's pretty, isn't it?")
        });

        $('.btn-timer').click(function(){
            swal({
                title: "Auto close alert!",
                text: "I will close in 2 seconds.",
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        $('.btn-successs').click(function(){
            swal("Good job!", "You clicked the button!", "success");
        });
        
        $('.btn-warning-confirm').click(function(){
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false
            },
            function(){
                swal("Deleted!", "Your imaginary file has been deleted!", "success");
            });
        });
        
        $('.btn-warning-cancel').click(function(){
            swal({
                title: "Are you sure?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: "No, cancel plx!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm){
            if (isConfirm){
              swal("Deleted!", "Your imaginary file has been deleted!", "success");
            } else {
              swal("Cancelled", "Your imaginary file is safe :)", "error");
            }
            });
        });
        
        $('.btn-custom-icon').click(function(){
            swal({
                title: "Sweet!",
                text: "Here's a custom image.",
                imageUrl: 'images/favicon/apple-touch-icon-152x152.png'
            });
        });
        
        $('.btn-message-html').click(function(){
            swal({
                title: "HTML <small>Title</small>!",
                text: 'A custom <span style="color:#F8BB86">html<span> message.',
                html: true
            });
        });
        
        $('.btn-input').click(function(){
            swal({
                title: "An input!",
                text: 'Write something interesting:',
                type: 'input',
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something",
            },
            function(inputValue){
                if (inputValue === false) return false;
        
                if (inputValue === "") {
                    swal.showInputError("You need to write something!");
                    return false;
                }
            
                swal("Nice!", 'You wrote: ' + inputValue, "success");
        
            });
        });
        
        $('.btn-theme').click(function(){
            swal({
                title: "Themes!",
                text: "Here's the Twitter theme for SweetAlert!",
                confirmButtonText: "Cool!",
                customClass: 'twitter'
            });
        });
        
        $('.btn-ajax').click(function(){
          swal({
            title: 'Ajax request example',
            text: 'Submit to run ajax request',
            type: 'info',
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
          }, function(){
            setTimeout(function() {
              swal('Ajax request finished!');
            }, 2000);
          });
        });
        
    });
    </script> -->

    <script type="text/javascript" src="jquery.multifield.min.js"></script>
    <script>

        $('.form-content').multifield({
            section: '.group',
            btnAdd:'#btnAdd',
            btnRemove:'.btnRemove',
        });


        // $(function(){
        //     $('select').on('change',function(){                        
        //         $('input[name=place]').val($(this).val());            
        //     });
        // });

        // $(function(){
        //     $('select').on('change',function(){                        
        //         $('input[name=reqperson]').val($(this).val());            
        //     });
        // });

        // $(function(){
        //     $('select').on('change',function(){                        
        //         $('input[name=asttypesss]').val($(this).val());            
        //     });
        // });

    </script>
    <!-- <script id="header_notification_bar">
$(document).ready(function(){
 
 function load_unseen_notification(view = '')
 {
  $.ajax({
   url:"fetch.php",
   method:"POST",
   data:{view:view},
   dataType:"json",
   success:function(data)
   {
    $('.dispnotif').html(data.notification);
    if(data.unseen_notification > 0)
    {
     $('.count').html(data.unseen_notification);
    }
   }
  });
 }
 
 load_unseen_notification();
 
 $('#comment_form').on('submit', function(event){
  event.preventDefault();
  if($('#subject').val() != '' && $('#comment').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     $('#comment_form')[0].reset();
     load_unseen_notification();
    }
   });
  }
  else
  {
   alert("Both Fields are Required");
  }
 });
 
 $(document).on('click', '.dropdown-toggle', function(){
  $('.count').html('');
  load_unseen_notification('yes');
 });
 
 setInterval(function(){ 
  load_unseen_notification();; 
 }, 2500);
 
});
</script> -->


</body>
</html>
