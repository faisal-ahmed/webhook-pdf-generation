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

    /*FCK Editor configuration*/
    CKEDITOR.replace( 'editor1', {
        /*
         * Ensure that htmlwriter plugin, which is required for this sample, is loaded.
         */
        extraPlugins: 'htmlwriter',

        /*
         * Style sheet for the contents
         */
        contentsCss: 'body {color:#000; background-color#:FFF;}',

        /*
         * Simple HTML5 doctype
         */
        docType: '<!DOCTYPE HTML>',

        /*
         * Allowed content rules which beside limiting allowed HTML
         * will also take care of transforming styles to attributes
         * (currently only for img - see transformation rules defined below).
         *
         * Read more: http://docs.ckeditor.com/#!/guide/dev_advanced_content_filter
         */
        allowedContent:
            'h1 h2 h3 p pre[align]; ' +
                'blockquote code kbd samp var del ins cite q b i u strike ul ol li hr table tbody tr td th caption; ' +
                'img[!src,alt,align,width,height]; font[!face]; font[!family]; font[!color]; font[!size]; font{!background-color}; a[!href]; a[!name]',

        /*
         * Core styles.
         */
        coreStyles_bold: { element: 'b' },
        coreStyles_italic: { element: 'i' },
        coreStyles_underline: { element: 'u' },
        coreStyles_strike: { element: 'strike' },

        /*
         * Font face.
         */

        // Define the way font elements will be applied to the document.
        // The "font" element will be used.
        font_style: {
            element: 'font',
            attributes: { 'face': '#(family)' }
        },

        /*
         * Font sizes.
         */
        fontSize_sizes: 'xx-small/1;x-small/2;small/3;medium/4;large/5;x-large/6;xx-large/7',
        fontSize_style: {
            element: 'font',
            attributes: { 'size': '#(size)' }
        },

        /*
         * Font colors.
         */

        colorButton_foreStyle: {
            element: 'font',
            attributes: { 'color': '#(color)' }
        },

        colorButton_backStyle: {
            element: 'font',
            styles: { 'background-color': '#(color)' }
        },

        /*
         * Styles combo.
         */
        stylesSet: [
            { name: 'Computer Code', element: 'code' },
            { name: 'Keyboard Phrase', element: 'kbd' },
            { name: 'Sample Text', element: 'samp' },
            { name: 'Variable', element: 'var' },
            { name: 'Deleted Text', element: 'del' },
            { name: 'Inserted Text', element: 'ins' },
            { name: 'Cited Work', element: 'cite' },
            { name: 'Inline Quotation', element: 'q' }
        ],

        on: {
            pluginsLoaded: configureTransformations,
            loaded: configureHtmlWriter
        }
    });

    /*
     * Add missing content transformations.
     */
    function configureTransformations( evt ) {
        var editor = evt.editor;

        editor.dataProcessor.htmlFilter.addRules( {
            attributes: {
                style: function( value, element ) {
                    // Return #RGB for background and border colors
                    return CKEDITOR.tools.convertRgbToHex( value );
                }
            }
        } );

        // Default automatic content transformations do not yet take care of
        // align attributes on blocks, so we need to add our own transformation rules.
        function alignToAttribute( element ) {
            if ( element.styles[ 'text-align' ] ) {
                element.attributes.align = element.styles[ 'text-align' ];
                delete element.styles[ 'text-align' ];
            }
        }
        editor.filter.addTransformations( [
            [ { element: 'p',	right: alignToAttribute } ],
            [ { element: 'h1',	right: alignToAttribute } ],
            [ { element: 'h2',	right: alignToAttribute } ],
            [ { element: 'h3',	right: alignToAttribute } ],
            [ { element: 'pre',	right: alignToAttribute } ]
        ] );
    }

    /*
     * Adjust the behavior of htmlWriter to make it output HTML like FCKeditor.
     */
    function configureHtmlWriter( evt ) {
        var editor = evt.editor,
            dataProcessor = editor.dataProcessor;

        // Out self closing tags the HTML4 way, like <br>.
        dataProcessor.writer.selfClosingEnd = '>';

        // Make output formatting behave similar to FCKeditor.
        var dtd = CKEDITOR.dtd;
        for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) ) {
            dataProcessor.writer.setRules( e, {
                indent: true,
                breakBeforeOpen: true,
                breakAfterOpen: false,
                breakBeforeClose: !dtd[ e ][ '#' ],
                breakAfterClose: true
            });
        }
    }

</script>