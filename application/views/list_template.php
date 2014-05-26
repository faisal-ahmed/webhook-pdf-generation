<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
        <thead>
        <tr>
            <th width="10" style="display: none"><input type="checkbox" class="check_all" /></th>
            <th style="border-left: 1px solid #ddd;">Template Name</th>
            <th>Date Created</th>
            <th>Date Updated</th>
            <th style="border-top: 1px solid #ddd; text-align: center;">Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php if (count($lists) > 0) { foreach ($lists as $key => $value) { ?>
            <tr>
                <td style="display: none"><input type="checkbox" /></td>
                <td style="border-left: 1px solid #ddd;"><a href="#"><?php echo $value['template_name'] ?></a></td>
                <td><?php echo date("m/d/Y", $value['created']) ?></td>
                <td><?php echo ($value['updated'] != '') ? date("m/d/Y", $value['updated']) : "Not Updated Yet"; ?></td>
                <td class="delete" style="text-decoration: none;"><a href="<?php echo base_url() ?>index.php/pdf/view/<?php echo $value['template_id']; ?>" target="_blank">View PDF</a> | <a href="#">Update</a> | <a href="#" onclick="confirmDelete(<?php echo $value['template_id'] ?>)">Delete</a></td>
            </tr>
        <?php } } else {
            echo "<tr><td style=\"border-left: 1px solid #ddd; text-align: center; font-weight: bold;\" colspan=\"5\">There is no template to display. Please create a template first.</td></tr>";
        } ?>
        </tbody>

    </table>
</div>        <!-- .sidebar_content ends -->

<script type="text/javascript">
    function confirmDelete(id){
        var response = confirm("Are you sure, you want to delete the template?");
        if (response) {
            window.location.assign("<?php echo base_url() ?>index.php/template/deleteTemplate/" + id);
        }
    }
</script>