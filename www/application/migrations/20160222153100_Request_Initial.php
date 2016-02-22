<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Request_Initial extends CI_Migration {

    public function up() {
        $this->dbforge->add_field('id');
        $fields = array(
            'requestEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'requestDateTime' => array(
                'type' => 'DATETIME'
            ),
            'requestMessage' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'approvalEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '256',
                'null' => TRUE,
            ),
            'approvalDateTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'approvalMessage' => array(
                'type' => 'VARCHAR',
                'constraint' => '256',
                'null' => TRUE,
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Request');
    }

    public function down() {
        $this->dbforge->drop_table('Request', TRUE);
    }

}
