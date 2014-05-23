<?php
/**
 * Created by PhpStorm.
 * User: victoryland
 * Date: 5/24/14
 * Time: 3:10 AM
 */

class Db_table_creator extends model_helper{
    function __construct()
    {
        parent::__construct();
    }

    function createUserTable(){
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
    }

    function insertUser() {
        $this->load->dbforge();

        $data = array(
            'username' => 'faisal' ,
            'password' => '81dc9bdb52d04dc20036dbd8313ed055' ,
        );

        $query = $this->db->insert('users', $data);

        var_dump($query);
    }

    function createTemplateTable(){
        $this->load->dbforge();

        $this->dbforge->add_field(array(
            'template_id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'template_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'uploaded_html_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'uploaded_css_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'uploaded_pdf_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'created' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'updated' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,
            ),
        ));
        $this->dbforge->add_key('template_id');

        $query = $this->dbforge->create_table('template');

        var_dump($query);
    }

    function updateTemplateTableOn24TH_May(){
        $this->load->dbforge();

        $fields = array(
            'junk' => array(
                'type' => 'INT',
                'constraint' => 5,
                'null' => TRUE,
                'default' => 0,
            )
        );
        $addColumn = $this->dbforge->add_column('template', $fields);

        var_dump($addColumn);

        $fields = array(
            'uploaded_pdf_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'uploaded_css_file_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
        );
        $columnUpdate = $this->dbforge->modify_column('template', $fields);

        var_dump($columnUpdate);
    }
}