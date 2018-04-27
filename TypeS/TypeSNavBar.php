<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-User Management') { echo 'class="active"';} ?> href="TypeSManagement.php">
                        <i class="fa fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-SysConfig') { echo 'class="active"';} ?> href="TypeSConfig.php">
                        <i class="fa fa-cog"></i>
                        <span>System Configuration</span>
                    </a>
                </li>
            </ul>            </div>
        <!-- sidebar menu end-->
    </div>
</aside>