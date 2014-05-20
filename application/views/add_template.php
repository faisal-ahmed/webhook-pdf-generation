<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>"/>
    <?php echo form_open_multipart('template/addTemplate', array('id' => 'addTemplate', 'onsubmit' => 'validateForm();')); ?>
    <h3>* Denotes the mandatory field.</h3>
    <div id="emptyTemplate" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot be empty.</p></div>
    </div>
    <p>
        <label>Template Name<span class="required">*</span>:</label> <br/>
        <input type="text" onchange="resetTemplateUpload();" id="template_name" name="template_name" maxlength="255" class="text" required="required"/>
    </p>

    <p id="next" onclick="next();">Next</p>

    <div id="uploadTemplate">
        <p class="fileupload">
            <label>HTML File<span class="required">*</span>:</label> <br/>
            <input type="hidden" value="" name="uploaded_html_file_name" id="uploaded_html_file_name"/>
            <input type="file" id="fileupload" /><span id="uploadmsg"></span>
        </p>

        <p class="fileupload">
            <label>CSS File:</label> <br/>
            <input type="hidden" value="" name="uploaded_css_file_name" id="uploaded_css_file_name"/>
            <input type="file" id="fileupload" /><span id="uploadmsg"></span>
        </p>

        <p>
            <input type="submit" class="submit long" value="Create Template"/>
            <input type="reset" onclick="resetTemplateUpload();" class="submit long" value="Reset Form"/>
        </p>
    </div>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->

<script type="text/javascript">
    var validated = 0;
    function next(){
        if ($('#template_name').val().trim() == '') {
            $('#emptyTemplate').slideToggle();
        } else {
            //Validate data here
            $('#uploadTemplate').slideToggle();
            $('#next').slideToggle();
            validated = 1;
        }
    }

    function resetTemplateUpload(){
        if ($('#template_name').val().trim() != '') {
            $('#emptyTemplate').hide();
        }
        if (validated) {
            validated = 0;
            $('#uploadTemplate').slideToggle();
            $('#next').slideToggle();
            $('#uploaded_html_file_name').val('');
            $('#uploaded_css_file_name').val('');
        }
    }

    function validateForm(){
        alert('here');
        return false;
    }

</script>
