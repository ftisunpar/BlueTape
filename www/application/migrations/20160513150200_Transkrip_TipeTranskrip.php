<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Transkrip_TipeTranskrip extends CI_Migration {

    public function up() {
        $fields = array(
            'requestType' => array(
                'type' => 'VARCHAR',
                'constraint' => '3',
                'after' => 'requestDateTime'
            )
        );
        $this->dbforge->add_column('Transkrip', $fields);
    }

    public function down() {
        
    }

}
