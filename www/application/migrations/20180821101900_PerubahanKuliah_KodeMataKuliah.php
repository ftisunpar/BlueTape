<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PerubahanKuliah_KodeMataKuliah extends CI_Migration {

    public function up() {
        $modifyfields = array(
            'mataKuliahCode' => array(
                'type' => 'VARCHAR',
                'constraint' => '9'
            ),
        );
        $this->dbforge->modify_column('PerubahanKuliah', $modifyfields);
    }

    public function down() {
        
    }

}
