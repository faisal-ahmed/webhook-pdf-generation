<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';

class pdf extends controller_helper{
    function __construct() {
        parent::__construct();
        $this->checkLogin();
    }

    function index(){
        $this->addViewData('active_menu', 'pdf');
    }

    function view(){
        $id = $this->uri->segment(3);
        $data = $this->template_persistence->getTemplateByID($id);
        $data['template_name'] = $this->template_persistence->createFolderAliasFromName($data['template_name']);
        $pdfUrl['base_url'] = base_url() . STATIC_DIRECTORY_NAME . "/{$data['template_name']}";
        $pdfUrl['files_name'] = $data['uploaded_html_file_name'];
        $pdfUrl['template_name'] = $data['template_name'];
        $viewData = array('pdf' => $pdfUrl);
        $this->load->view("view_pdf", $viewData);
/*        $this->addViewData('pdf', $pdfUrl);
        $this->loadview("view_pdf");*/
    }

    function pdfShortcodes(){
        global $zohoModules;
        $this->addViewData('active_menu', 'shortcodes');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $shortCodeAdditionResult = $this->shortcode->addShortcode();
            if ($shortCodeAdditionResult === true) {
                $this->addViewData('success', array("ShortCode has been added successfully."));
            } else {
                $this->addViewData('error', array($shortCodeAdditionResult));
            }
        }
        $this->addViewData('shortcodes', $this->shortcode->getAllShortcodes());
        $this->addViewData('zoho_modules', $zohoModules);
        $this->loadview('pdf_shortcodes');
    }

    function uploadImages(){
        $this->addViewData('active_menu', 'upload_images');
        $this->addViewData('template_lists', $this->template_persistence->getTemplateLists());
        $this->loadview('upload_images');
    }
}