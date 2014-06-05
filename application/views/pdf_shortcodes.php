<div class="sidebar_content">
    <h2>Available Zoho ShortCodes</h2>
    <h3>To use the ShortCodes just copy the code and use it in your template</h3>
    <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
        <thead>
        <tr>
            <th width="10" style="display: none"><input type="checkbox" class="check_all" /></th>
            <th style="border-left: 1px solid #ddd;">Zoho Module Name -- Field Name</th>
            <th>ShortCode</th>
            <th style="border-left: 1px solid #ddd;">Zoho Module Name -- Field Name</th>
            <th>ShortCode</th>
        </tr>
        </thead>

        <tbody>
        <?php if (count($shortcodes) > 0) { foreach ($shortcodes as $key => $value) { if (!$key) { continue; } ?>
            <?php if ($key % 2) { ?><tr><td style="display: none"><input type="checkbox" /></td><?php } ?>
                <td style="border-left: 1px solid #ddd;"><?php echo $value[0] . " -- " . $value[1] ?></td>
                <td><?php echo $value[2] ?></td>
            <?php if (count($shortcodes) == ($key + 1) && $key % 2) { ?> <td style="border-left: 1px solid #ddd;">&nbsp;</td><td>&nbsp;</td> <?php } ?>
            <?php if (!($key % 2)) { ?></tr><?php } ?>
        <?php } } else {
            echo "<tr><td style=\"border-left: 1px solid #ddd; text-align: center; font-weight: bold;\" colspan=\"5\">There is no ShortCode to display. Please add a ShortCode to use.</td></tr>";
        } ?>
        </tbody>

    </table>

    <h2>Add Zoho ShortCodes</h2>
    <?php include_once 'error.php' ?>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>"/>
    <?php echo form_open_multipart('pdf/pdfShortcodes', array('id' => 'addShortCode')); ?>

    <p><label>Select Zoho Module:</label> <br />

        <select name="zoho_modules" id="zoho_modules" class="styled">
            <?php foreach ($zoho_modules as $key => $value) { ?>
                <option value="<?php echo $key ?>"><?php echo $value ?></option>
            <?php } ?>
        </select>
    </p>

    <p>
        <label>Field Name<span class="required">*</span>:</label> <br/>
        <input type="text" id="field_name" name="field_name" maxlength="255"
               class="text" required="required"/>
    </p>

    <p>
        <input type="submit" class="submit long" value="Add Shortcode"/>
    </p>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->