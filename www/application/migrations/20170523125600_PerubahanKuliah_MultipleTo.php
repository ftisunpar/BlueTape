<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_PerubahanKuliah_MultipleTo extends CI_Migration {

    public function up() {
        $fields = array(
            'to' => array(
                'type' => 'VARCHAR',
                'constraint' => '1024',
                'null' => TRUE,
                'after' => 'toRoom'
            )            
        );
        $this->dbforge->add_column('PerubahanKuliah', $fields);

        $this->db->select('id, toDateTime, toRoom');
        $query = $this->db->get('PerubahanKuliah');
        foreach ($query->result() as $row) {
            $this->db->set('to', json_encode([[
                'dateTime' => $row->toDateTime,
                'room' => $row->toRoom
            ]]));
            $this->db->where('id', $row->id);
            $this->db->update('PerubahanKuliah');
        }

        $this->dbforge->drop_column('PerubahanKuliah', 'toDateTime');
        $this->dbforge->drop_column('PerubahanKuliah', 'toRoom');
    }

    public function down() { }

}