<div class="sidebar">
    <ul class="sidemenu">
        <li class="<?php if ($active_menu == 'dashboard') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/dashboard">Dashboard</a></li>
        <li class="<?php if ($active_menu == 'class') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/classManagement">Class Management</a></li>
        <li class="<?php if ($active_menu == 'student') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/studentManagement">Student Management</a></li>
        <li class="<?php if ($active_menu == 'result') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/resultManagement">Result Management</a></li>
        <!--<li class="<?php /*if ($active_menu == 'notification') echo 'active'; */?>"><a href="<?php /*echo base_url() */?>index.php/notificationManagement">Notification Management</a></li>-->
    </ul>
</div>        <!-- .sidebar ends -->
