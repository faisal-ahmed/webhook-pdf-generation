<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <?php echo form_open_multipart('template/editTemplate', array('id' => 'updateTemplate')); ?>
        <p><label>Select Template:</label> <br />

            <select name="template_list" id="template_list" onchange="loadSubPage();" class="styled">
                <option value="none">None</option>
            <?php foreach ($template_lists as $key => $value) { ?>
                <option value="<?php echo $value['template_id'] ?>" <?php echo (isset($template_id) && $template_id == $value['template_id']) ? "selected=\"selected\"" : "" ?>><?php echo $value['template_name'] ?></option>
            <?php } ?>
            </select>
        </p>

        <p id="selectSubPage" style="<?php echo (!isset($template_id)) ? 'display:none;' : ""; ?>"><label>Select Sub Page:</label> <br />

            <select name="html_file_name" id="html_file_name" onchange="loadTemplate();" class="styled">
                <option value="none">None</option>
            <?php if (isset($template_id)) { foreach ($html_page_lists as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } } ?>
            </select>
        </p>

        <input type="hidden" id="template_id" name="template_id" value="<?php echo (isset($template_id) ? $template_id : ""); ?>" />
        <div id="templateContent" style="display: none">
            <p>
                <label>HTML Content:</label><br />
                <textarea cols="80" id="editor1" name="html" rows="10"></textarea>
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
        var e2 = document.getElementById("html_file_name");
        var html_file_name = e2.options[e2.selectedIndex].value;
        if (template_id != 'none' && html_file_name != 'none') {
            $('#template_id').val(template_id);
            $('#loadingDiv').slideToggle();
            var url = "<?php echo base_url() ?>index.php/template/getTemplateContentAjax";
            $.post(url, {template_id: template_id, html_file_name: html_file_name}, function (data) {
                CKEDITOR.instances.editor1.setData(data.html);
                $('#loadingDiv').slideToggle();
                $('#templateContent').slideToggle();
            }, "json");
        }
    }

    function loadSubPage() {
        $('#selectSubPage').hide();
        $('#templateContent').hide();
        var e = document.getElementById("template_list");
        var template_id = e.options[e.selectedIndex].value;
        if (template_id != 'none') {
            $('#template_id').val(template_id);
            $('#loadingDiv').slideToggle();
            var url = "<?php echo base_url() ?>index.php/template/getTemplateSubPageAjax";
            $.post(url, {template_id: template_id}, function (data) {
                var i, length = e.options.length;
                $('#html_file_name')
                    .find('option')
                    .remove()
                    .end()
                    .append(data.lists);
                $('#loadingDiv').slideToggle();
                $('#selectSubPage').slideToggle();
                $('#selectSubPage .cmf-skinned-text').text("None");
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