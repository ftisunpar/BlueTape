<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_EntriJadwalDosen_Jadwal extends CI_Migration {

    public function up() {
        $fields = array(
            'id' => array(
                'type' => 'int',
				 'auto_increment' => TRUE
            ),
            'user' => array(				// Seharusnya mereferensi ke kolom email pada table bluetape_userinfo
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
            'hari' => array(
                'type' => 'int'
            ),
			'jam_mulai' => array(
                'type' => 'int'
            ),
			'durasi' => array(
                'type' => 'int'
            ),
			'jenis' => array(
                'type' => 'VARCHAR',
                'constraint' => '256'
            ),
			'label' => array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
        );
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('Jadwal_dosen');

        
    }

    public function down() { 
		
	}

}
