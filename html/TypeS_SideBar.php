<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-UserManagement') { echo 'class="active"';} ?> href="TypeS_UserManagement.php">
                        <i class="fa fa-users"></i>
                        <span>User Management</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a <?php if($currentPage=='G&CSMS-System Configurations') { echo 'class="active"';} ?> >
                        <i class="fa fa-gears"></i>
                        <span>System Configurations</span>
                    </a>
                   <ul class="sub">
                    <li><a href="TypeS_VisitType.php">Visit Type</a></li>
                    <li><a href="TypeS_AppointmentType.php">Appointment Type</a></li>
                    <li><a href="TypeS_CounselingType.php">Counseling Type</a></li>
                    <li><a href="TypeS_CounselingApproach.php">Counseling Approach</a></li>
                    <li><a href="TypeS_Remarks.php">Remarks</a></li>
                    <li><a href="TypeS_CivilStatus.php">Civil Status</a></li>
                    <li><a href="TypeS_UploadCategory.php">Upload Category</a></li>
                    <li><a href="TypeS_UserRoles.php">User Roles</a></li>
                    </ul>
                </li>
                
            </ul>            
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>