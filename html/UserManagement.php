<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    

    <title>GSMS</title>

    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link rel="icon" href="images/PUPlogo.png">
    <link rel="stylesheet" type="text/css" href="js/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="js/bootstrap-datetimepicker/css/datetimepicker.css" />

    

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
    <section id="container">
        <!--header start-->
        <header class="header fixed-top clearfix">
            <!--logo start-->
            <div class="brand">
                <a href="Home.php" class="logo">
                    <img src="images/pup200.png" alt="">
                </a>
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars"></div>
                </div>
            </div>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- settings start -->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-tasks"></i>
                            <span class="badge bg-success">8</span>
                        </a>
                        <ul class="dropdown-menu extended tasks-bar">
                            <li>
                                <p class="">You have 8 pending tasks</p>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info clearfix">
                                        <div class="desc pull-left">
                                            <h5>Target Sell</h5>
                                            <p>25% , Deadline  12 June’13</p>
                                        </div>
                                        <span class="notification-pie-chart pull-right" data-percent="45">
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info clearfix">
                                        <div class="desc pull-left">
                                            <h5>Product Delivery</h5>
                                            <p>45% , Deadline  12 June’13</p>
                                        </div>
                                        <span class="notification-pie-chart pull-right" data-percent="78">
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info clearfix">
                                        <div class="desc pull-left">
                                            <h5>Payment collection</h5>
                                            <p>87% , Deadline  12 June’13</p>
                                        </div>
                                        <span class="notification-pie-chart pull-right" data-percent="60">
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="task-info clearfix">
                                        <div class="desc pull-left">
                                            <h5>Target Sell</h5>
                                            <p>33% , Deadline  12 June’13</p>
                                        </div>
                                        <span class="notification-pie-chart pull-right" data-percent="90">
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li class="external">
                                <a href="#">See All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- settings end -->
                    <!-- inbox dropdown start-->
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-important">4</span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <li>
                                <p class="red">You have 4 Mails</p>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar" src="images/avatar-mini.jpg"></span>
                                    <span class="subject">
                                        <span class="from">Jonathan Smith</span>
                                        <span class="time">Just now</span>
                                    </span>
                                    <span class="message">
                                        Hello, this is an example msg.
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar" src="images/avatar-mini-2.jpg"></span>
                                    <span class="subject">
                                        <span class="from">Jane Doe</span>
                                        <span class="time">2 min ago</span>
                                    </span>
                                    <span class="message">
                                        Nice admin template
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar" src="images/avatar-mini-3.jpg"></span>
                                    <span class="subject">
                                        <span class="from">Tasi sam</span>
                                        <span class="time">2 days ago</span>
                                    </span>
                                    <span class="message">
                                        This is an example msg.
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar" src="images/avatar-mini.jpg"></span>
                                    <span class="subject">
                                        <span class="from">Mr. Perfect</span>
                                        <span class="time">2 hour ago</span>
                                    </span>
                                    <span class="message">
                                        Hi there, its a test
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#">See all messages</a>
                            </li>
                        </ul>
                    </li>
                    <!-- inbox dropdown end -->
                    <!-- notification dropdown start-->
                    <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge bg-warning">3</span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <li>
                                <p>Notifications</p>
                            </li>
                            <li>
                                <div class="alert alert-info clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #1 overloaded.</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="alert alert-danger clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #2 overloaded.</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="alert alert-success clearfix">
                                    <span class="alert-icon"><i class="fa fa-bolt"></i></span>
                                    <div class="noti-info">
                                        <a href="#"> Server #3 overloaded.</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- notification dropdown end -->
                </ul>
                <!--  notification end -->
            </div>
            <div class="top-nav clearfix">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <li>
                        <input type="text" class="form-control search" placeholder=" Search">
                    </li>
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="images/Tearsontape.jpg">
                            <span class="username">Vincent Ian Montes</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                            <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                            <li><a href="login.html"><i class="fa fa-key"></i> Log Out</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                    <li>
                        <div class="toggle-right-box">
                            <div class="fa fa-bars"></div>
                        </div>
                    </li>
                </ul>
                <!--search & user info end-->
            </div>
        </header>
        <!--header end-->
        <aside>
            <div id="sidebar" class="nav-collapse">
                <!-- sidebar menu start-->
                <div class="leftside-navigation">
                    <ul class="sidebar-menu" id="nav-accordion">
                        <li>
                            <a href="Home.php">
                                <i class="fa fa-home"></i>
                                <span>Home</span>
                            </a>
                        </li>

                        <li class="sub-menu">
                            <a href="javascript:;">
                                <i class="fa fa-clipboard"></i>
                                <span>Guidance Counseling</span>
                            </a>
                            <ul class="sub">
                                <li><a href="#">Counceling</a></li>
                                <li><a href="#">Cases</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="active" href="StudentProfile.php">
                                <i class="fa fa-smile-o"></i>
                                <span>Student Profling</span>
                            </a>
                        </li>
                        <li>
                            <a class="sub-menu" href="#">
                                <i class="fa fa-book"></i>
                                <span>Documents and Policies</span>
                            </a>
                        <li>
                            <a href="#">
                                <i class="fa fa-print"></i>
                                <span>Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="UserManagement.php">
                                <i class="fa fa-users"></i>
                                <span>User Management</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- sidebar menu end-->
            </div>
        </aside>
        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <!--Start of  Add Modal-->
                <div class="panel-body">
                    <div class="JustifyLeft">
                        <div class="JustifyLeft">
                            <a href="#myModal" data-toggle="modal" class="btn btn-success">add user</a>
                        </div>
                    </div>
                </div>
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade" method="Submit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                                <h4 class="modal-title">Add student</h4>
                            </div>
                            <div class="modal-body">
                                <form role="form" action="insertmodal2.php" method="post">
                                    <div class="form-group">
                                        <label for="First_Name">First Name</label>
                                        <input type="text" class="form-control" placeholder="First Name" name="First_Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="Middle_Name">Middle Name</label>
                                        <input type="text" class="form-control" placeholder="Middle Name" name="Middle_Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="Last_Name">Last Name</label>
                                        <input type="text" class="form-control" placeholder="Last Name" name="Last_Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="User_name">User Name</label>
                                        <input type="text" class="form-control" placeholder="User Name" name="User_name">
                                    </div>
                                <div class="form-group">
                                    <label for="Input_Password">Password</label>
                                    <input type="password" class="form-control" placeholder="Password" name="Input_Password">
                                </div>
                                    <div class="form-group">
                                        <label for="User_level" class="col-lg-2 col-sm-2 control-label "placeholder="Select" >User Level</label>
                                            <select class="populate" name="User_level" style="width: 150px">
                                                    <option value="SchoolAdministrator">School Administrator</option>
                                                    <option value="SystemAdministrator">System Administrator</option>
                                                    <option value=" GuidanceCounselor">Counselor</option>
                                                    <option value="StudentAssistant">Student Assistant</option>
                                            </select>                   
                                    </div>
                                    <div class="form-group">
                                    <label for="Input_File">File input</label>
                                    <input type="file" name="Input_File" id="exampleInputFile">
                                    <p class="help-block">insert picture</p>
                                </div>
                      <button type="submit" class="btn btn-default" name="submit">Submit</button>
					 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end of  add modal-->
                 <!-- page start-->
                <div class="row">
                    <div class="col-sm-6">
                        <header class="panel-heading">
                            Student Information
                            <span class="tools pull-right">
                                <a href="javascript:;" class="fa fa-chevron-down"></a>
                                <a href="javascript:;" class="fa fa-cog"></a>
                                <a href="javascript:;" class="fa fa-times"></a>
                            </span>
                        </header>
                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Username</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Mark</td>
                                        <td>Otto</td>
                                        <td>@mdo</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Jacob</td>
                                        <td>Thornton</td>
                                        <td>@fat</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Larry</td>
                                        <td>the Bird</td>
                                        <td>@twitter</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- page end-->
            </section>
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
                            <div class="prog-row side-mini-stat">
                                <div class="side-graph-info">
                                    <h4>product delivery</h4>
                                    <p>
                                        55%, Deadline 12 june 13
                                    </p>
                                </div>
                                <div class="side-mini-graph">
                                    <div class="p-delivery">
                                        <div class="sparkline" data-type="bar" data-resize="true" data-height="30" data-width="90%" data-bar-color="#39b7ab" data-bar-width="5" data-data="[200,135,667,333,526,996,564,123,890,564,455]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="prog-row side-mini-stat">
                                <div class="side-graph-info payment-info">
                                    <h4>payment collection</h4>
                                    <p>
                                        25%, Deadline 12 june 13
                                    </p>
                                </div>
                                <div class="side-mini-graph">
                                    <div class="p-collection">
                                        <span class="pc-epie-chart" data-percent="45">
                                            <span class="percent"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="prog-row side-mini-stat">
                                <div class="side-graph-info">
                                    <h4>delivery pending</h4>
                                    <p>
                                        44%, Deadline 12 june 13
                                    </p>
                                </div>
                                <div class="side-mini-graph">
                                    <div class="d-pending">
                                    </div>
                                </div>
                            </div>
                            <div class="prog-row side-mini-stat">
                                <div class="col-md-12">
                                    <h4>total progress</h4>
                                    <p>
                                        50%, Deadline 12 june 13
                                    </p>
                                    <div class="progress progress-xs mtop10">
                                        <div style="width: 50%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="20" role="progressbar" class="progress-bar progress-bar-info">
                                            <span class="sr-only">50% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="widget-collapsible">
                    <a href="#" class="head widget-head terques-bg active clearfix">
                        <span class="pull-left">contact online (5)</span>
                        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
                    </a>
                    <ul class="widget-container">
                        <li>
                            <div class="prog-row">
                                <div class="user-thumb">
                                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                                </div>
                                <div class="user-details">
                                    <h4><a href="#">Jonathan Smith</a></h4>
                                    <p>
                                        Work for fun
                                    </p>
                                </div>
                                <div class="user-status text-danger">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb">
                                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                                </div>
                                <div class="user-details">
                                    <h4><a href="#">Anjelina Joe</a></h4>
                                    <p>
                                        Available
                                    </p>
                                </div>
                                <div class="user-status text-success">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb">
                                    <a href="#"><img src="images/chat-avatar2.jpg" alt=""></a>
                                </div>
                                <div class="user-details">
                                    <h4><a href="#">John Doe</a></h4>
                                    <p>
                                        Away from Desk
                                    </p>
                                </div>
                                <div class="user-status text-warning">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb">
                                    <a href="#"><img src="images/avatar1_small.jpg" alt=""></a>
                                </div>
                                <div class="user-details">
                                    <h4><a href="#">Mark Henry</a></h4>
                                    <p>
                                        working
                                    </p>
                                </div>
                                <div class="user-status text-info">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb">
                                    <a href="#"><img src="images/avatar1.jpg" alt=""></a>
                                </div>
                                <div class="user-details">
                                    <h4><a href="#">Shila Jones</a></h4>
                                    <p>
                                        Work for fun
                                    </p>
                                </div>
                                <div class="user-status text-danger">
                                    <i class="fa fa-comments-o"></i>
                                </div>
                            </div>
                            <p class="text-center">
                                <a href="#" class="view-btn">View all Contacts</a>
                            </p>
                        </li>
                    </ul>
                </li>
                <li class="widget-collapsible">
                    <a href="#" class="head widget-head purple-bg active">
                        <span class="pull-left"> recent activity (3)</span>
                        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
                    </a>
                    <ul class="widget-container">
                        <li>
                            <div class="prog-row">
                                <div class="user-thumb rsn-activity">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="rsn-details ">
                                    <p class="text-muted">
                                        just now
                                    </p>
                                    <p>
                                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                                    </p>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb rsn-activity">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="rsn-details ">
                                    <p class="text-muted">
                                        2 min ago
                                    </p>
                                    <p>
                                        <a href="#">Jane Doe </a>Purchased new equipments for zonal office setup
                                    </p>
                                </div>
                            </div>
                            <div class="prog-row">
                                <div class="user-thumb rsn-activity">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <div class="rsn-details ">
                                    <p class="text-muted">
                                        1 day ago
                                    </p>
                                    <p>
                                        <a href="#">Jim Doe </a>Purchased new equipments for zonal office setup
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="widget-collapsible">
                    <a href="#" class="head widget-head yellow-bg active">
                        <span class="pull-left"> shipment status</span>
                        <span class="pull-right widget-collapse"><i class="ico-minus"></i></span>
                    </a>
                    <ul class="widget-container">
                        <li>
                            <div class="col-md-12">
                                <div class="prog-row">
                                    <p>
                                        Full sleeve baby wear (SL: 17665)
                                    </p>
                                    <div class="progress progress-xs mtop10">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="prog-row">
                                    <p>
                                        Full sleeve baby wear (SL: 17665)
                                    </p>
                                    <div class="progress progress-xs mtop10">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                            <span class="sr-only">70% Completed</span>
                                        </div>
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
    <!-- Placed js at the end of the document so the pages load faster -->
    <!--Core js-->
    <script src="js/jquery.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="bs3/js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/easypiechart/jquery.easypiechart.js"></script>
    <script src="js/jQuery-slimScroll-1.3.0/jquery.slimscroll.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/jquery.nicescroll.js"></script>

    

    <script type="text/javascript" src="js/fuelux/js/spinner.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-fileupload/bootstrap-fileupload.js"></script>
    <script type="text/javascript" src="js/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
    <script type="text/javascript" src="js/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script type="text/javascript" src="js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
    <script type="text/javascript" src="js/bootstrap-daterangepicker/moment.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script type="text/javascript" src="js/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
    <script type="text/javascript" src="js/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
    <script type="text/javascript" src="js/jquery-multi-select/js/jquery.multi-select.js"></script>
    <script type="text/javascript" src="js/jquery-multi-select/js/jquery.quicksearch.js"></script>

    <script type="text/javascript" src="js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>

    <script src="js/jquery-tags-input/jquery.tagsinput.js"></script>

    <script src="js/select2/select2.js"></script>
    <script src="js/select-init.js"></script>


    <!--common script init for all pages-->
    <script src="js/scripts.js"></script>

    <script src="js/toggle-init.js"></script>

    <script src="js/advanced-form.js"></script>
    <!--Easy Pie Chart-->
    <script src="js/easypiechart/jquery.easypiechart.js"></script>
    <!--Sparkline Chart-->
    <script src="js/sparkline/jquery.sparkline.js"></script>
    <!--jQuery Flot Chart-->
    <script src="js/flot-chart/jquery.flot.js"></script>
    <script src="js/flot-chart/jquery.flot.tooltip.min.js"></script>
    <script src="js/flot-chart/jquery.flot.resize.js"></script>
    <script src="js/flot-chart/jquery.flot.pie.resize.js"></script>

</body>
</html>
