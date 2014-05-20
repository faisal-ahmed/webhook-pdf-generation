<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <?php echo form_open_multipart('user/updateProfile'); ?>
    <p>
        <label>Username:</label> <br/>
        <input type="text" name="username" class="text" required="required"/>
    </p>

    <p>
        <label>Current Password:</label> <br/>
        <input type="password" name="current_password" class="text" required="required"/>
    </p>

    <p>
        <label>New Password:</label> <br/>
        <input type="password" name="new_password" class="text" required="required"/>
    </p>

    <p>
        <input type="submit" class="submit long" value="Update Password"/>
    </p>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->
