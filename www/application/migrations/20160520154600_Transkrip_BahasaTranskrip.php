<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Transkrip_BahasaTranskrip extends CI_Migration {

    public function up() {
        $modifyfields = array(
            'requestType' => array(
                'type' => 'VARCHAR',
                'constraint' => '8'
            ),
        );
        $this->dbforge->modify_column('Transkrip', $modifyfields);
    }

    public function down() {
        
    }

}
