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
        //$this->template_persistence->createTables();
        redirect('template/addTemplate', 'refresh');
    }

    function addTemplate(){
        $this->addViewData('active_menu', 'add_template');
        $this->loadview('add_template');
    }

    function editTemplate(){
        $this->addViewData('active_menu', 'edit_template');
        $this->loadview('edit_template');
    }

    function listTemplate(){
        $this->addViewData('active_menu', 'list_template');
        $this->loadview('list_template');
    }
}