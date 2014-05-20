<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Victoryland
 * Date: 3/22/14
 * Time: 1:45 AM
 * To change this template use File | Settings | File Templates.
 */

require_once 'controller_helper.php';

class dashboard extends controller_helper{
    function __construct() {
        parent::__construct();
        $this->checkLogin();
        $this->addViewData('active_menu', 'dashboard');
    }

    function index(){
        $this->loadview('dashboard');
    }
}