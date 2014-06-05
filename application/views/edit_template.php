<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <?php echo form_open_multipart('template/editTemplate', array('id' => 'updateTemplate')); ?>
        <p><label>Select Template:</label> <br />

            <select name="template_list" id="template_list" onchange="loadTemplate();" class="styled">
                <option value="none">None</option>
            <?php foreach ($template_lists as $key => $value) { ?>
                <option value="<?php echo $value['template_id'] ?>" <?php echo (isset($template['template_id']) && $template['template_id'] == $value['template_id']) ? "selected=\"selected\"" : "" ?>><?php echo $value['template_name'] ?></option>
            <?php } ?>
            </select>
        </p>

        <input type="hidden" id="template_id" name="template_id" value="<?php echo (isset($template['template_id']) ? $template['template_id'] : ""); ?>" />
        <div id="templateContent" style="<?php echo (!isset($template['template_id'])) ? 'display:none;' : ""; ?>">
            <p>
                <label>HTML Content:</label><br />
                <textarea cols="80" id="editor1" name="html" rows="10"><?php if (isset($template['html'])) { echo $template['html']; } ?></textarea>
            </p>

            <hr/>

            <p>
                <input type="submit" class="submit long" value="Update Template" />
            </p>
        </div>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->

<script src="<?php echo base_url() ?>ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    function loadTemplate() {
        $('#templateContent').hide();
        var e = document.getElementById("template_list");
        var template_id = e.options[e.selectedIndex].value;
        if (template_id != 'none') {
            $('#template_id').val(template_id);
            $('#loadingDiv').slideToggle();
            var url = "<?php echo base_url() ?>index.php/template/getTemplateContentAjax";
            $.post(url, {template_id: template_id}, function (data) {
                CKEDITOR.instances.editor1.setData(data.html);
                $('#loadingDiv').slideToggle();
                $('#templateContent').slideToggle();
            }, "json");
        }
    }

    CKEDITOR.replace( 'editor1', {
        fullPage: true,
        allowedContent: true,
        extraPlugins: 'wysiwygarea',
        height: '800px'
    });

</script>