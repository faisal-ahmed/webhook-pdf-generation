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
        $this->load->model('template_persistence');
    }

    function index(){
        $this->addViewData('active_menu', 'pdf');
    }

    function view(){
        $id = $this->uri->segment(3);
        $data = $this->template_persistence->getHTML($id);
        $this->load->view("view_pdf", $data);
    }
}