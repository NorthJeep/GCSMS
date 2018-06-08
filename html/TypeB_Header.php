<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="ThemeBucket">
    <link rel="shortcut icon" href="images/favicon.png">
    <title>G&CSMS-Dasboard</title>
    <!--Core CSS -->
    <link href="bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="js/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="js/jvector-map/jquery-jvectormap-1.2.2.css" rel="stylesheet">
    <link href="css/clndr.css" rel="stylesheet">
    <!--clock css-->
    <link href="js/css3clock/css/style.css" rel="stylesheet">
    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="js/morris-chart/morris.css">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet"/>
    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

<section id="container">
<!--header start-->
<header class="header fixed-top clearfix">
<!--logo start-->
<div class="brand">

    <a href="index.php" class="logo">
        <img src="images/logogcsms.png" alt="">
    </a>
</div>
<!--logo end-->

<div class="top-nav clearfix">

    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
    <a href="Logout.php"><i class="fa fa-sign-out"></i> Log Out</a>

    <li id="header_inbox_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <i class="fa fa-plus"></i>
                    <i class="fa fa-comment-o"></i><!-- 
                    <span class="badge bg-important">4</span> -->
                </a>
                <ul class="dropdown-menu extended inbox">
                    <li>
                        <p class="red">Write Message</p>
                    </li>
                    <li>
                        <form id="NotifSend" Method="POST">
                        <a href="#">
                            <span class="subject">
                                <div class="input-group m-bot15">
                                    <input id="NotifDetail" type="text" name="NotifDetail" placeholder="Your message here." class="form-control">
                                        <span class="input-group-btn">
                                            <button id="NotifPost" class="btn btn-success" type="submit"><i class="fa fa-reply"></i></button>
                                        </span>
                                </div>
                                <span class="from">Message the Counselor</span>
                            </span>
                            <span class="message">
                                Max 100 char.
                            </span>
                        </a>
                        </form>
                    </li>
                </ul>
        </li>
    </ul>
    <!--search & user info end-->

</div>
  
</header>