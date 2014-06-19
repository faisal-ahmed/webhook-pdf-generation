$(function () {

    // Preload images
    $.preloadCssImages();


    // CSS tweaks
    $('#header #nav li:last').addClass('nobg');
    $('.block_head ul').each(function () {
        $('li:first', this).addClass('nobg');
    });
    $('.block form input[type=file]').addClass('file');


    // Web stats
    $('table.stats').each(function () {

        if ($(this).attr('rel')) {
            var statsType = $(this).attr('rel');
        } else {
            var statsType = 'area';
        }

        var chart_width = ($(this).parent('div').width()) - 60;


        if (statsType == 'line' || statsType == 'pie') {
            $(this).hide().visualize({
                type: statsType,	// 'bar', 'area', 'pie', 'line'
                width: chart_width,
                height: '240px',
                colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c'],

                lineDots: 'double',
                interaction: true,
                multiHover: 5,
                tooltip: true,
                tooltiphtml: function (data) {
                    var html = '';
                    for (var i = 0; i < data.point.length; i++) {
                        html += '<p class="chart_tooltip"><strong>' + data.point[i].value + '</strong> ' + data.point[i].yLabels[0] + '</p>';
                    }
                    return html;
                }
            });
        } else {
            $(this).hide().visualize({
                type: statsType,	// 'bar', 'area', 'pie', 'line'
                width: chart_width,
                height: '240px',
                colors: ['#6fb9e8', '#ec8526', '#9dc453', '#ddd74c']
            });
        }
    });


    // Sort table
    $("table.sortable").tablesorter({
        headers: { 0: { sorter: false}, 5: {sorter: false} },		// Disabled on the 1st and 6th columns
        widgets: ['zebra']
    });

    $('.block table tr th.header').css('cursor', 'pointer');


    // Check / uncheck all checkboxes
    $('.check_all').click(function () {
        $(this).parents('form').find('input:checkbox').attr('checked', $(this).is(':checked'));
    });


    // Set WYSIWYG editor
    $('.wysiwyg').wysiwyg({css: "css/wysiwyg.css", brIE: false });


    // Modal boxes - to all links with rel="facebox"
//    $('a[rel*=facebox]').facebox()


    // Messages
    $('.block .message').hide().append('<span class="close" title="Dismiss"></span>').fadeIn('slow');
    $('.block .message .close').hover(
        function () {
            $(this).addClass('hover');
        },
        function () {
            $(this).removeClass('hover');
        }
    );

    $('.block .message .close').click(function () {
        $(this).parent().fadeOut('slow', function () {
            $(this).remove();
        });
    });


    // Form select styling
    $("form select.styled").select_skin();


    // Tabs
    //$(".tab_content").hide();
    //$("ul.tabs li:first-child").addClass("active").show();
    //$(".block").find(".tab_content:first").show();
//
    //$("ul.tabs li").click(function() {
    //	$(this).parent().find('li').removeClass("active");
    //	$(this).addClass("active");
    //	$(this).parents('.block').find(".tab_content").hide();
    //
    //	var activeTab = $(this).find("a").attr("href");
    //	$(activeTab).show();
    //
    //	// refresh visualize for IE
    //	$(activeTab).find('.visualize').trigger('visualizeRefresh');
    //
    //	return false;
    //});


    // Sidebar Tabs
    //$(".sidebar_content").hide();
    //
    //if(window.location.hash && window.location.hash.match('sb')) {
    //
    //	$("ul.sidemenu li a[href="+window.location.hash+"]").parent().addClass("active").show();
    //	$(".block .sidebar_content#"+window.location.hash).show();
    //} else {
    //
    //	$("ul.sidemenu li:first-child").addClass("active").show();
    //	$(".block .sidebar_content:first").show();
    //}
//
    //$("ul.sidemenu li").click(function() {
    //
    //	var activeTab = $(this).find("a").attr("href");
    //	window.location.hash = activeTab;
    //
    //	$(this).parent().find('li').removeClass("active");
    //	$(this).addClass("active");
    //	$(this).parents('.block').find(".sidebar_content").hide();
    //	$(activeTab).show();
    //	return false;
    //});


    // Block search
    $('.block .block_head form .text').bind('click', function () {
        $(this).attr('value', '');
    });


    // Image actions menu
    $('ul.imglist li').hover(
        function () {
            $(this).find('ul').css('display', 'none').fadeIn('fast').css('display', 'block');
        },
        function () {
            $(this).find('ul').fadeOut(100);
        }
    );


    // Image delete confirmation
    $('ul.imglist .delete a').click(function () {
        if (confirm("Are you sure you want to delete this image?")) {
            return true;
        } else {
            return false;
        }
    });

    var base_url = $('#base_url').val();
    // Style file input
    $("input[type=file]").filestyle({
        image: base_url + "images/upload.gif",
        imageheight: 30,
        imagewidth: 80,
        width: 250
    });

    // File upload
    if ($('#htmlupload').length) {
        new AjaxUpload('htmlupload', {
            action: base_url + 'upload-handler.php',
            autoSubmit: true,
            name: 'userfile',
            responseType: 'text/html',
            onSubmit: function (file, ext) {
                var template_name = $('#template_name').val();
                if (validateHTMLFileExt(ext) != true) {
                    $('.htmlupload #uploadmsg').text("File type doesn't match. Please select html files only.");
                    $('.htmlupload .file').addClass('redBorder');
                    return false;
                }
                this.setData({template_name: template_name});
                $('.submit').addClass('submitActionLoading');
                $('.submit').attr('disabled', 'true');
                $('.htmlupload #uploadmsg').addClass('loading').text('Uploading...');
                this.disable();
            },
            onComplete: function (file, response) {
                var fileName = response.substr(0, response.indexOf("___"));
                var message = response.substr((response.indexOf("___") + 3));
                $('.submit').removeClass('submitActionLoading');
                $('.submit').removeAttr('disabled');
                $('#uploaded_html_file_name').attr('value', fileName);
                $('.htmlupload #uploadmsg').removeClass('loading').text(message);
                $('.htmlupload .file').removeClass('redBorder');
                this.enable();
            }
        });
    }

    // File upload
    if ($('#cssupload').length) {
        new AjaxUpload('cssupload', {
            action: base_url + 'upload-handler.php',
            autoSubmit: true,
            name: 'userfile',
            responseType: 'text/html',
            onSubmit: function (file, ext) {
                var template_name = $('#template_name').val();
                if (validateCSSFileExt(ext) != true) {
                    $('.cssupload #uploadmsg').text("File type doesn't match. Please select css files only.");
                    $('.cssupload .file').addClass('redBorder');
                    return false;
                }
                this.setData({template_name: template_name});
                $('.submit').addClass('submitActionLoading');
                $('.submit').attr('disabled', 'true');
                $('.cssupload #uploadmsg').addClass('loading').text('Uploading...');
                this.disable();
            },
            onComplete: function (file, response) {
                var fileName = response.substr(0, response.indexOf("___"));
                var message = response.substr((response.indexOf("___") + 3));
                $('.submit').removeClass('submitActionLoading');
                $('.submit').removeAttr('disabled');
                $('#uploaded_css_file_name').attr('value', fileName);
                $('.cssupload #uploadmsg').removeClass('loading').text(message);
                $('.cssupload .file').removeClass('redBorder');
                this.enable();
            }
        });
    }

    // File upload
    if ($('#imageupload').length) {
        new AjaxUpload('imageupload', {
            action: base_url + 'upload-handler.php',
            autoSubmit: true,
            name: 'userfile',
            responseType: 'text/html',
            onSubmit: function (file, ext) {
                var template_id = $('#template_id').val();
                if (validateImageFileExt(ext) != true) {
                    $('.imageupload #uploadmsg').text("File type doesn't match. Allowed types are JPEG/JPG/PNG/GIF.");
                    $('.imageupload .file').addClass('redBorder');
                    return false;
                }
                this.setData({template_id: template_id, image: true, base_url: base_url});
                $('.imageupload #uploadmsg').addClass('loading').text('Uploading...');
                this.disable();
            },
            onComplete: function (file, response) {
                var fileName = response.substr(0, response.indexOf("___"));
                var message = response.substr((response.indexOf("___") + 3));
                $('#uploaded_image_file_name').attr('value', fileName);
                $('.imageupload #uploadmsg').removeClass('loading').text(message);
                $('.imageupload .file').removeClass('redBorder');
                this.enable();
            }
        });
    }

    function validateImageFileExt(ext) {
        var imageType = ['png', 'jpg', 'jpeg', 'gif'], i;
        for ( i = 0; i < imageType.length; i++) {
            if (imageType[i] == ext) {
                return true;
            }
        }

        return false;
    }

    function validateHTMLFileExt(ext) {
        if (ext == 'html' || ext == 'htm') return true;
        else return false;
    }

    function validateCSSFileExt(ext) {
        if (ext == 'css') return true;
        else return false;
    }

    // Date picker
    $('input.date_picker').date_input();

    // Navigation dropdown fix for IE6
    if (jQuery.browser.version.substr(0, 1) < 7) {
        $('#header #nav li').hover(
            function () {
                $(this).addClass('iehover');
            },
            function () {
                $(this).removeClass('iehover');
            }
        );
    }


    // IE6 PNG fix
    $(document).pngFix();

});