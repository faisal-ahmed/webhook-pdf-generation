<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>"/>

    <form action="" method="post">
        <p>
            <label>Uploaded Image Url:</label> <br/>
            <input type="text" disabled="disabled" id="uploaded_image_file_name" maxlength="255" value="Copy this URL and use for the template when you upload the image below."
                   style="width: 98%; background: #96f7ca; border: 1px solid #bbb; font-size: 14px; color: #333; padding: 7px; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; outline: none; vertical-align: middle;"/>
        </p>

        <p><label>Select Template:</label> <br />

            <select name="template_list" id="template_list" onchange="loadTemplate();" class="styled">
                <option value="none">None</option>
                <?php foreach ($template_lists as $key => $value) { ?>
                    <option value="<?php echo $value['template_id'] ?>" <?php echo (isset($template['template_id']) && $template['template_id'] == $value['template_id']) ? "selected=\"selected\"" : "" ?>><?php echo $value['template_name'] ?></option>
                <?php } ?>
            </select>
        </p>

        <input type="hidden" id="template_id" name="template_id"/>
        <div id="imageContent" style="display:none">
            <p class="fileupload imageupload">
                <label>Upload Image:</label> <br/>
                <input type="file" id="imageupload"/><span id="uploadmsg">Upload JPEG/JPG/PNG/GIF type file as many as you wish.</span>
            </p>
        </div>
    </form>
    <hr/>
</div>        <!-- .sidebar_content ends -->

<script type="text/javascript">
    function loadTemplate() {
        var e = document.getElementById("template_list");
        var template_id = e.options[e.selectedIndex].value;
        if (template_id != 'none') {
            $('#template_id').val(template_id);
            $('#imageContent').slideDown();
        } else {
            $('#uploaded_image_file_name').val('Copy this URL and use for the template when you upload the image below.');
            $('#imageContent').slideUp();
        }
    }
</script>