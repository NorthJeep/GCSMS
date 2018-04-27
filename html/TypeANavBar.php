<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a <?php if($currentPage=='G&CSMS-Dasboard') { echo 'class="active"';} ?>
                     href="index.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-Profiling') { echo 'class="active"';} ?> href="profiling.php">
                        <i class="fa fa-tasks"></i>
                        <span>Profiling</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-Counseling Services') { echo 'class="active"';} ?> href="counseling_page.php">
                        <i class="fa fa-suitcase"></i>
                        <span>Counseling Services</span>
                    </a>
                   
                </li>
                <li>
                    <a <?php if($currentPage=='G&CSMS-Files') { echo 'class="active"';} ?> href="file_upload.html">
                        <i class="fa fa-book"></i>
                        <span>Files and Documents </span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-Reports') { echo 'class="active"';} ?> href="couselingreport.php">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>            </div>
        <!-- sidebar menu end-->
    </div>
</aside>