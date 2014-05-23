<?php

require_once 'model_helper.php';

class User_persistence extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function user_has_identity()
    {
        $username = $this->getPost('username');
        $password = $this->getPost('password');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $res = $this->db->get('users');
        foreach ($res->result() as $user) {
            $this->session->set_userdata('login', true);
            $this->session->set_userdata('username', $user->username);
            $this->session->set_userdata('user_id', $user->user_id);
            return true;
        }
        return false;
    }

    function update_profile($user_id){
        $current_password = $this->getPost('current_password');
        $data = array();
        $data['username'] = $this->getPost('username');
        $data['password'] = md5($this->getPost('new_password'));
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_id', $user_id);
        $this->db->where('password', md5($current_password));
        if ($this->db->count_all_results() == 0) {
            return 'Incorrect user or password!';
        }

        $this->db->where('user_id', $user_id);
        $this->db->update('users', $data);
        $this->session->unset_userdata('username');
        $this->session->set_userdata('username', $data['username']);

        return true;
    }
}

?>