<div class="sidebar_content">
    <?php include_once 'error.php' ?>
    <input type="hidden" id="base_url" value="<?php echo base_url(); ?>"/>
    <?php echo form_open_multipart('template/addTemplate', array('id' => 'addTemplate', 'onsubmit' => 'return validateForm();')); ?>
    <h3><span class="required">*</span> Denotes the mandatory field.</h3>

    <div id="emptyTemplate" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot be empty.</p></div>
    </div>

    <div id="invalidCharError" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot contain "[", "]", "=", "%", "$", "+", ";", "<", ">", ":", "\", "/", "|", "*", "?".</p></div>
    </div>

    <div id="invalidStringError" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot be "~", "CON", "PRN", "AUX", "NUL", "COM1", "COM2", "COM3", "COM4", "COM5", "COM6", "COM7", "COM8", "COM9", "LPT1", "LPT2", "LPT3", "LPT4", "LPT5", "LPT6", "LPT7", "LPT8", "LPT9", "CLOCK$".</p></div>
    </div>

    <div id="invalidStartingError" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot start with ".", "-", "~".</p></div>
    </div>

    <div id="invalidEndingError" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot end with ".", "~".</p></div>
    </div>

    <div id="invalidConsecutiveDotsError" style="display: none;">
        <div class="message errormsg"><p>Template Name cannot contain consecutive dots (e.g. "..").</p></div>
    </div>

    <p>
        <label>Template Name<span class="required">*</span>:</label> <br/>
        <input type="text" onchange="resetTemplateUpload('0');" id="template_name" name="template_name" maxlength="255"
               class="text" required="required"/>
    </p>

    <p id="next" onclick="next();">Next</p>

    <div id="uploadTemplate">
        <p class="fileupload htmlupload">
            <label>HTML File<span class="required">*</span>:</label> <br/>
            <input type="hidden" value="" name="uploaded_html_file_name" id="uploaded_html_file_name"/>
            <input type="file" id="htmlupload"/><span id="uploadmsg">Upload HTML type file only.</span>
        </p>

        <p class="fileupload cssupload">
            <label>CSS File:</label> <br/>
            <input type="hidden" value="" name="uploaded_css_file_name" id="uploaded_css_file_name"/>
            <input type="file" id="cssupload"/><span id="uploadmsg">Upload CSS type file only.</span>
        </p>

        <p>
            <input type="submit" class="submit long" value="Create Template"/>
            <input type="reset" onclick="resetTemplateUpload('1');" class="submit long" value="Reset Form"/>
        </p>
    </div>
    <?php echo form_close() ?>
</div>        <!-- .sidebar_content ends -->

<script type="text/javascript">
    var validated = 0;
    function next() {
        if ($('#template_name').val().trim() == '') {
            $('#emptyTemplate').slideDown();
            $('#template_name').addClass('redBorder');
        } else if (!invalidTemplateName()) {
            var template_name = $('#template_name').val().trim();
            var url = "<?php echo base_url() ?>index.php/template/checkTemplateNameForAjax";
            $.post(url, {template_name: template_name}, function (data) {
                if (data.status == false) {
                    $('.htmlupload .file').removeClass('redBorder');
                    $('.cssupload .file').removeClass('redBorder');
                    $('.htmlupload #uploadmsg').text("Upload HTML type file only.");
                    $('.cssupload #uploadmsg').text("Upload CSS type file only.");
                    $('#uploadTemplate').slideToggle();
                    $('#next').slideToggle();
                    validated = 1;
                } else {
                    $('#template_name').addClass('redBorder');
                    alert("Template name already exists! Please try with a different name.");
                    $('#template_name').val('');
                }
            }, "json");
        }
    }

    function resetTemplateUpload(reset) {
        if (invalidTemplateName()) {
            return;
        }
        if ($('#template_name').val().trim() != '' && reset != 1) {
            $('#emptyTemplate').hide();
            $('#template_name').removeClass('redBorder');
        } else if (validated || reset == '1') {
            validated = 0;
            $('#uploadTemplate').slideToggle();
            $('#next').slideToggle();
            $('#uploaded_html_file_name').val('');
            $('#uploaded_css_file_name').val('');
            $('#template_name').addClass('redBorder');
            $('#emptyTemplate').slideDown();
        } else {
            $('#emptyTemplate').slideDown();
            $('#template_name').addClass('redBorder');
        }
    }

    function validateForm() {
        if ($('#uploaded_html_file_name').val().trim() == '') {
            alert("Please upload your HTML file.");
            $('.htmlupload .file').addClass('redBorder');
            return false;
        } else if ($('#template_name').val().trim() == '') {
            alert("Please enter a template name.");
            $('#template_name').addClass('redBorder');
            return false;
        }

        return true;
    }

    function invalidTemplateName() {
        var invalidCharList = ["[", "]", "=", "%", "$", "+", ";", "<", ">", ":", "\\", "/", "|", "*", "?"];
        var invalidStrings = ["~", "CON", "PRN", "AUX", "NUL", "COM1", "COM2", "COM3", "COM4", "COM5", "COM6", "COM7", "COM8", "COM9", "LPT1", "LPT2", "LPT3", "LPT4", "LPT5", "LPT6", "LPT7", "LPT8", "LPT9", "CLOCK$"];
        var startInvalid = [".", "-", "~"];
        var endInvalid = [".", "~"];
        var midInvalid = [".."];

        var toCheck = $('#template_name').val(), error = false;

        $('#invalidCharError').hide();
        $('#invalidStringError').hide();
        $('#invalidStartingError').hide();
        $('#invalidEndingError').hide();
        $('#invalidConsecutiveDotsError').hide();

        for (var i = 0; i < invalidCharList.length; i++){
            if (toCheck.indexOf(invalidCharList[i]) != -1) {
                $('#invalidCharError').slideDown();
                error = true;
                break;
            }
        }

        for (var i = 0; i < invalidStrings.length; i++){
            if (toCheck.toLowerCase() == invalidStrings[i].toLowerCase()) {
                $('#invalidStringError').slideDown();
                error = true;
                break;
            }
        }

        for (var i = 0; i < startInvalid.length; i++){
            if (toCheck[0] == startInvalid[i]) {
                $('#invalidStartingError').slideDown();
                error = true;
                break;
            }
        }

        for (var i = 0; i < endInvalid.length; i++){
            if (toCheck[toCheck.length - 1] == endInvalid[i]) {
                $('#invalidEndingError').slideDown();
                error = true;
                break;
            }
        }

        for (var i = 0; i < midInvalid.length; i++){
            if (toCheck.indexOf(midInvalid[i]) != -1) {
                $('#invalidConsecutiveDotsError').slideDown();
                error = true;
                break;
            }
        }

        if (error) {
            if (validated) {
                validated = 0;
                $('#uploadTemplate').slideToggle();
                $('#next').slideToggle();
                $('#uploaded_html_file_name').val('');
                $('#uploaded_css_file_name').val('');
            }
            $('#template_name').addClass('redBorder');
        }
        return error;
    }

</script>

<style type="text/css">
    #emptyTemplate div span.close{
        display: none;
    }
</style>
