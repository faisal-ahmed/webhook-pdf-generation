<div class="block withsidebar">

    <div class="block_head">
        <div class="bheadl"></div>
        <div class="bheadr"></div>

        <h2><a href="#" onclick="javascript:window.history.back(-1);return false;">Back</a>&nbsp;|&nbsp;<a href="#"><?php echo $active_menu ?> Management</a></h2>

        <?php if ($active_menu == 'class') { ?>
            <ul>
                <li class="<?php if ($tab_menu == 'addClass') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/classManagement/addClass">Add Class</a></li>
                <li class="<?php if ($tab_menu == 'classList') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/classManagement/classList">Class List</a></li>
            </ul>
        <?php } else if ($active_menu == 'student') { ?>
            <ul>
                <li class="<?php if ($tab_menu == 'addStudent') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/studentManagement/addStudent">Add Student</a></li>
                <li class="<?php if ($tab_menu == 'studentList') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/studentManagement/studentList">Student List</a></li>
            </ul>
        <?php } else if ($active_menu == 'result') { ?>
            <ul>
                <li class="<?php if ($tab_menu == 'addResult') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/resultManagement/addResult">Add Result</a></li>
                <!--<li class="<?php /*if ($tab_menu == 'uploadReport') echo 'active'; */?>"><a href="<?php /*echo base_url() */?>index.php/resultManagement/uploadReport">Last Uploaded Result's Report</a></li>-->
                <li class="<?php if ($tab_menu == 'publishResult') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/resultManagement/publishResult">Publish Result (Via SMS)</a></li>
            </ul>
        <?php } /*else if ($active_menu == 'notification') { ?>
            <ul>
                <li class="<?php if ($tab_menu == 'notifyClass') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/notificationManagement/notifyClass">Notify Class</a></li>
                <li class="<?php if ($tab_menu == 'notifyStudent') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/notificationManagement/notifyStudent">Notify Student</a></li>
            </ul>
        <?php } */?>

    </div>
    <!-- .block_head ends -->

    <div class="block_content">