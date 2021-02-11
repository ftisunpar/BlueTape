<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Transkrip_Initial extends CI_Migration {

    public function up() {
        $this->dbforge->add_field('id');
        $fields = array(
            'requestByEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'requestByName' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'requestDateTime' => array(
                'type' => 'DATETIME'
            ),
            'requestUsage' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'answer' => array(
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => TRUE,
            ),
            'answeredByEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '256',
                'null' => TRUE,
            ),
            'answeredDateTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'answeredMessage' => array(
                'type' => 'VARCHAR',
                'constraint' => '256',
                'null' => TRUE,
            )
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table('Transkrip');
    }

    public function down() { }

}
