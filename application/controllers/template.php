<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';

class template extends controller_helper{
    function __construct() {
        parent::__construct();
        $this->checkLogin();
        $this->load->model('template_persistence');
    }

    function index(){
        redirect('template/addTemplate', 'refresh');
    }

    function addTemplate(){
        $this->addViewData('active_menu', 'add_template');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            if (($addTemlateStatus = $this->template_persistence->addTemplate()) !== true){
                $this->addViewData('error', array($addTemlateStatus));
            } else {
                $this->addViewData('success', array('The template has been created successfully!'));
            }
        }
        $this->loadview('add_template');
    }

    function editTemplate(){
        $this->addViewData('active_menu', 'update_template');
        $id = $this->uri->segment(3, false);

        if ($id !== false) {
            //Load the content here
            $this->addViewData("template", $this->template_persistence->getHTML($id));
        }

        $this->addViewData('template_lists', $this->template_persistence->getTemplateLists());
        $this->loadview('edit_template');
    }

    function listTemplate(){
        $this->addViewData('active_menu', 'list_template');
        if ($this->input->get_post('status', true) === 'deleteSuccessful') {
            $this->addViewData('success', array("The template has been deleted successfully!"));
        } else if ($this->input->get_post('status', true) === 'deleteFailed') {
            $this->addViewData('error', array("Server Error! Delete failed. Please try again."));
        }
        $this->addViewData('lists', $this->template_persistence->getTemplateLists());
        $this->loadview('list_template');
    }

    function deleteTemplate(){
        $id = $this->uri->segment(3);
        $deleteStatus = $this->template_persistence->deleteTemplate($id);
        if ($deleteStatus) {
            redirect('template/listTemplate/?status=deleteSuccessful', 'refresh');
        } else {
            redirect('template/listTemplate/?status=deleteFailed', 'refresh');
        }
    }

    function getTemplateContentAjax(){
        echo json_encode($this->template_persistence->getHTML());
    }

    function checkTemplateNameForAjax(){
        $status = $this->template_persistence->checkDuplicateTemplate();
        echo json_encode(
            array("status" => $status)
        );
    }
}