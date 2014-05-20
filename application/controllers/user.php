<?phprequire_once 'controller_helper.php';class User extends controller_helper {    function __construct() {        parent::__construct();        $this->load->model('user_persistance');    }    function index() {        $this->alreadyLoggedIn();        $this->addViewData('info', array('Please login to continue'));        if ($this->input->server('REQUEST_METHOD') === 'POST') {            $this->form_validation->set_rules('username', 'Username', 'required');            $this->form_validation->set_rules('password', 'Password', 'required');            if ($this->form_validation->run() == FALSE) {                $this->addViewData('error', $this->getErrors(validation_errors()));            } else if ($this->user_persistance->user_has_identity() == FALSE) {                $this->addViewData('error', array('Incorrect username or password'));            } else {                redirect('dashboard', 'refresh');                exit();            }        }        $this->loadview('login');    }    function logout() {        $this->session->sess_destroy();        redirect('user', 'refresh');    }    function updateProfile(){        $this->checkLogin();        $this->addViewData('active_menu', 'update_profile');        if ($this->input->server('REQUEST_METHOD') === 'POST') {            $this->form_validation->set_rules('username', 'Username', 'required');            $this->form_validation->set_rules('current_password', 'Current Password', 'required');            $this->form_validation->set_rules('new_password', 'New Password', 'required');            if ($this->form_validation->run() == FALSE) {                $this->addViewData('error', $this->getErrors(validation_errors()));            } else if (($result = $this->user_persistance->update_profile()) !== true) {                $this->addViewData('error', array($result));            } else {                $this->addViewData('username', $this->getSessionAttr('username'));                $this->addViewData('success', array('Your profile has been updated successfully!'));            }        }        $this->loadview('update_profile');    }}?>