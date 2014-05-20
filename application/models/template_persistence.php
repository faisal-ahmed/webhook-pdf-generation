<?php

require_once 'model_helper.php';

class Template_persistence extends model_helper
{
    function __construct()
    {
        parent::__construct();
    }

    function createTables(){
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
}

?>