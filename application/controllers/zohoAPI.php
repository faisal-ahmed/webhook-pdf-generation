<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';
require_once (BASEPATH . "../zoho_library/utils_zoho_request.php");

class zohoapi extends controller_helper{
    function __construct() {
        parent::__construct();
        $this->load->model('template_persistence');
    }

    function index(){
        echo "Hello World";
    }
}