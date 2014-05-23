<?php

require_once 'model_helper.php';

class User_persistence extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function createTables(){
        $this->load->dbforge();

        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
        ));
        $this->dbforge->add_key('user_id');

        $query = $this->dbforge->create_table('users');

        var_dump($query);

        $data = array(
            'username' => 'faisal' ,
            'password' => '81dc9bdb52d04dc20036dbd8313ed055' ,
        );

        $query = $this->db->insert('users', $data);

        var_dump($query);
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