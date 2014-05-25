<div class="sidebar_content">
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
        <?php foreach ($lists as $key => $value) { ?>
            <tr>
                <td style="display: none"><input type="checkbox" /></td>
                <td style="border-left: 1px solid #ddd;"><a href="#"><?php echo $value['template_name'] ?></a></td>
                <td><?php echo date("m/d/Y", $value['created']) ?></td>
                <td><?php echo ($value['updated'] != '') ? date("m/d/Y", $value['updated']) : "Not Updated Yet"; ?></td>
                <td class="delete" style="text-decoration: none;"><a href="#">View PDF</a> | <a href="#">Update</a> | <a href="#">Delete</a></td>
            </tr>
        <?php } ?>
        </tbody>

    </table>
</div>        <!-- .sidebar_content ends -->
