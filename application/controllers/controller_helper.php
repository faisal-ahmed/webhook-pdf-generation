<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:49 AM
 * To change this template use File | Settings | File Templates.
 */

require_once (BASEPATH . '../application/libraries/Utilities.php');

class controller_helper extends CI_Controller{

    private $viewData;

    function __construct() {
        parent::__construct();
        $this->viewData = array(
            'active_menu' => 'dashboard'
        );
        $this->addViewData('username', $this->getSessionAttr('username'));
        $this->addViewData('user_id', $this->getSessionAttr('user_id'));
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
    }

    function loadview($content_template) {
        $login = $this->getSessionAttr('login');
        $this->load->view("header", $this->viewData);
        if ($login) {
            $this->load->view("main_menu", $this->viewData);
            $this->load->view("content_header", $this->viewData);
            $this->load->view("left_sidebar", $this->viewData);
        }
        $this->load->view($content_template, $this->viewData);
        if ($login) {
            $this->load->view("content_footer", $this->viewData);
        }
        $this->load->view("footer", $this->viewData);
    }

    function debug($debugArray){
        echo "<pre>";
        print_r($debugArray);
        echo "</pre>";
    }

    function addViewData($key, $value){
        $this->viewData[$key] = $value;
    }

    function getViewData($key){
        if (isset($this->viewData[$key])) {
            return $this->viewData[$key];
        }
        return false;
    }

    function checkLogin(){
        $login = $this->getSessionAttr('login');
        if ($login !== true) {
            redirect('user', 'refresh');
        }
    }

    function alreadyLoggedIn(){
        $login = $this->getSessionAttr('login');
        if ($login === true) {
            redirect('dashboard', 'refresh');
        }
    }

    function getSessionAttr($attr) {
        if ($this->session->userdata("$attr") ) {
            return $this->session->userdata("$attr");
        }
        return false;
    }

    function getErrors($errorString){
        $return = array();
        $errors = explode('</p>', $errorString);
        foreach ($errors as $key => $value) {
            $error = substr($value, strpos($value, '<p>') + 3);
            if ($error == '') {
                continue;
            }
            $return[] = $error;
        }
        return $return;
    }

    function loadPagination($controllerFunction, $totalRows = 200, $perPage = 50) {
        $this->load->library('pagination');

        $config['base_url'] = base_url() . "index.php/$controllerFunction/";
        $config['total_rows'] = $totalRows;
        $config['per_page'] = $perPage;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<div class="pagination right">';
        $config['full_tag_close'] = '</div>';
        $config['next_link'] = '&raquo;';
        $config['prev_link'] = '&laquo;';
        $config['cur_tag_open'] = '<a href="#" class="active">';
        $config['cur_tag_close'] = '</a>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}