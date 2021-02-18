<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_EntriJadwalDosen_Jadwal_addLastUpdate extends CI_Migration {

    public function up() {
        $newFields = array(
			'lastUpdate' => array('type' => 'DATETIME',
								  'default'=> date('Y-m-d H:i:s')		
			)
		);
		$this->dbforge->add_column('Jadwal_dosen', $newFields);
		// Artinya: ALTER TABLE Jadwal_dosen ADD lastUpdate DATETIME
    }

    public function down() { 
		
	}

}
