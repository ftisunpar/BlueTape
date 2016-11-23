<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PerubahanKuliah_Initial extends CI_Migration {

    public function up() {
        $this->dbforge->add_field('id');
        $fields = array(
            'requestByEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'requestDateTime' => array(
                'type' => 'DATETIME'
            ),
            'mataKuliahName' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'mataKuliahCode' => array(
                'type' => 'VARCHAR',
                'constraint' => '6'
            ),
            'class' => array(
                'type' => 'VARCHAR',
                'constraint' => '1',
            ),
            'changeType' => array(
                'type' => 'VARCHAR',
                'constraint' => '1'
            ),
            'fromDateTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'fromRoom' => array(
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => TRUE
            ),
            'toDateTime' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'toRoom' => array(
                'type' => 'VARCHAR',
                'constraint' => '16',
                'null' => TRUE
            ),
            'remarks' => array(
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
        $this->dbforge->create_table('PerubahanKuliah');
    }

    public function down() { }

}