<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a <?php if($currentPage=='G&CSMS-Dasboard') { echo 'class="active"';} ?> href="TypeBIndex.php">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-Profiling') { echo 'class="active"';} ?> href="TypeBProfiling.php">
                        <i class="fa fa-tasks"></i>
                        <span>Profiling</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-Visits') { echo 'class="active"';} ?> href="TypeBVisitLogs.php">
                        <i class="fa fa-sign-in"></i>
                        <span>Visit Logs</span>
                    </a>
                </li>
                <li>
                    <a <?php if($currentPage=='G&CSMS-Files') { echo 'class="active"';} ?> href="TypeBFilesAndDocuments.php">
                        <i class="fa fa-book"></i>
                        <span>Files and Documents </span>
                    </a>
                </li>
                
            </ul>            </div>
        <!-- sidebar menu end-->
    </div>
</aside>