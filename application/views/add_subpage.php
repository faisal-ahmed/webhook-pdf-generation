<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>"/>
    <?php echo form_open_multipart('template/addSubPage', array('id' => 'addSubpage', 'onsubmit' => 'return validateSubpage();')); ?>
        <p><label>Select Template:</label> <br />

            <select name="template_list" id="template_list" onchange="loadTemplate();" class="styled">
                <option value="none" selected="selected">None</option>
            <?php foreach ($template_lists as $key => $value) { ?>
                <option value="<?php echo $value['template_id'] . "___" . $value['template_name'] . count(explode(';', $value['uploaded_html_file_name'])) ?>"><?php echo $value['template_name'] ?></option>
            <?php } ?>
            </select>
        </p>

        <input type="hidden" id="template_name" name="template_name" value="" />
        <input type="hidden" id="template_id" name="template_id" value="" />
        <div id="templateContent" style="display:none;">
            <p class="fileupload htmlupload">
                <label>HTML File<span class="required">*</span>:</label> <br/>
                <input type="hidden" value="" name="uploaded_html_file_name" id="uploaded_html_file_name"/>
                <input type="file" id="htmlupload"/><span id="uploadmsg">Upload HTML type file only.</span>
            </p>

            <hr/>

            <p>
                <input type="submit" class="submit long" value="Add Subpage" />
            </p>
        </div>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->
<script type="text/javascript">
    function loadTemplate() {
        var e = document.getElementById("template_list");
        var template_detail = e.options[e.selectedIndex].value;
        if (template_detail === 'none') {
            $('#template_id').val('');
            $('#templateContent').slideUp();
        } else {
            var template = template_detail.split("___");
            $('#template_id').val(template[0]);
            $('#template_name').val(template[1]);
            $('#templateContent').slideDown();
        }
    }

    function validateSubpage(){
        return ($('#template_id').val() !== '');
    }
</script>