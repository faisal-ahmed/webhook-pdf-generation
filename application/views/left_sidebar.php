<div class="sidebar">
    <ul class="sidemenu">
        <li class="<?php if ($active_menu == 'dashboard') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/dashboard">Dashboard</a></li>
        <li class="<?php if ($active_menu == 'list_template') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/template/listTemplate">List Template</a></li>
        <li class="<?php if ($active_menu == 'add_template') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/template/addTemplate">Add Template</a></li>
        <li class="<?php if ($active_menu == 'update_template') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/template/editTemplate">Update Template</a></li>
        <li class="<?php if ($active_menu == 'update_profile') echo 'active'; ?>"><a href="<?php echo base_url() ?>index.php/user/updateProfile">Update Profile</a></li>
    </ul>
</div>        <!-- .sidebar ends -->
